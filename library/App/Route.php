<?php
/**
 * 
 */
class App_Route {
    
    public static function buildRoute($action='index', $controller=null, $module=null, $params=null)
	{
        $opts = array();

		$opts['action'] = $action;
		$opts['controller'] = $controller;
		$opts['module'] = $module;

		if ($params){
			foreach ($params as $key => $value){
				$opts[$key] = $value;
			}
		}

        $router = Zend_Controller_Front::getInstance()->getRouter();
        $url = $router->assemble($opts, null, true);

        return $url;
    }


}