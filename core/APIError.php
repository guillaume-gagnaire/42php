<?php

class                       APIError {
    public function         __construct($errorCode) {
        API::send(null, $errorCode);
    }

    public static function  getMsg($errorCode) {
        switch ($errorCode) {
            case 200:
                return 'OK';
                break;

            // API system errors
            case 1001:
                return "The X-App-Key header is required";
                break;
            case 1002:
                return "The X-App-Secret header is required";
                break;
            case 1003:
                return "The application is not authorized";
                break;
            case 1004:
                return "The input data can't be parsed";
                break;
            case 1005:
                return "The requested method doesn't exists";
                break;
        }
        return 'Unknown';
    }
}

?>