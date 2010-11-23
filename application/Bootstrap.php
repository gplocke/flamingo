<?php
/**
 * 
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    public function __construct($application)
    {
        parent::__construct($application);        
    }

    public function run()
    {
        // make the config available to everyone
        Zend_Registry::set('config', new Zend_Config($this->getOptions()));

        parent::run();
    }

    protected function _initAutoload()
    {
        $loader = new Zend_Application_Module_Autoloader(array(
            'namespace' => '',
            'basePath'  => APPLICATION_PATH));
        
        return $loader;
    }

    protected function _initUrl()
    {
        $baseUrl = substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'], '/public/index.php'));

        $zcf = Zend_Controller_Front::getInstance();

        $zcf->setBaseUrl($baseUrl);

        $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
        $protocol = substr(
            strtolower($_SERVER["SERVER_PROTOCOL"]),
            0,
            strpos(strtolower($_SERVER["SERVER_PROTOCOL"]), "/")
        ) . $s;
        $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
        $url = $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $baseUrl;

        Zend_Registry::set('siteUrl', $url);
    }

    protected function _initSession()
    {
        // should probably only do this for modules other than API? -- GAW
        $session = new Zend_Session_Namespace('dodo', true);
        
        return $session;        
    }

    protected function _initView()
    {
        $view = new Zend_View();
        $view->doctype('XHTML1_STRICT');

        $view->addHelperPath(APPLICATION_PATH . '/views/helpers');

        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
        $viewRenderer->setView($view);

        $acl = App_AccessList::getAcl();
        $pages = new Zend_Config(require APPLICATION_PATH . '/configs/nav.php');
        $container = new Zend_Navigation($pages);
        $view->getHelper('navigation')->setContainer($container);
        $view->getHelper('navigation')->setAcl($acl);

        // grab the session and see if we have a logged in user
        // if so let the view know so we can update the customer center (ie. top of page)
        // do it here in bootstrap so it is always available to both back and frontend
        $session = $this->getResource('session');
        $storage = new App_Auth_Storage_Session($session);
        if (!$storage->isEmpty()){
            $view->username = $storage->getUserName();
            $view->getHelper('navigation')->setRole($storage->getUserRole());
        }

        return $view;
    }

}

