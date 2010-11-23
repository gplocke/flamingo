<?php
/**
 * 
 */
class App_Translate
{
	public static function translate($message) {
        $translate = Zend_Registry::get('Zend_Translate');
        $translate->setOptions(array('disableNotices' => 'true'));
        return $translate->_($message);
	}
}
