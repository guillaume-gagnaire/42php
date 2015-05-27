/**
 * Manages the API calls
 *
 * Ex:
 *
 * api.onError = function(ret) {
 *      console.log(ret.errorMsg);
 * }
 *
 * api.get('me', {}, function(ret){
 *      alert(ret.email);
 * })
 *
 * if (api.logged()) {
 *      // Do fucking stuff
 * }
 *
 * @type {{version: string, token: string, logged: Function, onError: null, call: Function, get: Function, post: Function, delete: Function, put: Function}}
 */
var api = {
    domain: '/api/',
    token: '',
    logged: function(){
        if (this.token == '') {
            return false;
        }
        return true;
    },
    onError: null,
    call: function(type, method, data, callback) {
        if (data == null)
            data = {};

        if (typeof this.token == 'undefined')
            this.token = '';

        var xmlhttp;
        if (window.XMLHttpRequest)
            xmlhttp=new XMLHttpRequest();
        else
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");

        xmlhttp.open(type, this.domain + method, true);
        xmlhttp.setRequestHeader("Content-Type", "application/json");
        xmlhttp.setRequestHeader("Accept", "application/json");
        xmlhttp.setRequestHeader("X-Token", this.token);
        if (typeof lang != 'undefined')
            xmlhttp.setRequestHeader("X-Lang", lang);
        xmlhttp.setRequestHeader("X-App-Key", this.key);
        xmlhttp.setRequestHeader("X-App-Secret", this.secret);
        xmlhttp.send(JSON.stringify(data));

        (function(xmlhttp, t){
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    var ret = eval('('+xmlhttp.responseText+')');

                    if (ret.deleteToken) {
                        this.token = '';
                    }
                    if (ret.storeToken) {
                        this.token = ret.storeToken;
                    }

                    if (ret.error) {
                        if (t.onError)
                            t.onError(ret);
                    } else {
                        if (callback) {
                            callback(ret.data);
                        }
                    }
                }
            }
        })(xmlhttp, this);
        return xmlhttp;
    },

    get: function(method, data, callback) {
        return this.call('GET', method, data, callback);
    },

    post: function(method, data, callback) {
        return this.call('POST', method, data, callback);
    },

    delete: function(method, data, callback) {
        return this.call('DELETE', method, data, callback);
    },

    put: function(method, data, callback) {
        return this.call('PUT', method, data, callback);
    }
};