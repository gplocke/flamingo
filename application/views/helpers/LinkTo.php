<?php
/**
 * 
 */
class Zend_View_Helper_LinkTo
{
	public function linkTo($action='index', $controller=null, $module=null, $params=null)
    {
        return App_Route::buildRoute($action, $controller, $module, $params);
	}
    
    
}
