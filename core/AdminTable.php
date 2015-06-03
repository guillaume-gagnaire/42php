<?php

class                   AdminTable {
    private             $params = [];

    public function     __construct($params = []) {
        $this->params = $params;
    }
    
    private function 	auto() {
	    
    }
    
    private function 	standalone() {
	    if (!isset($this->params['handler']))
	    	return _t("Aucune fonction.");
	    return $this->params['handler']();
    }
    
    public function 	render() {
	    $methodName = $this->params['mode'];
	    if (!method_exists($this, $methodName))
	    	return _t("Ce mode n'existe pas.");
	    return $this->$methodName();
    }
}

?>