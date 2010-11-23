<?php
/**
 * 
 */
class IndexController extends App_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // set a body class as home so we can easily change some body styles in CSS
        // such as the bg that runs across the top of the page
        $this->view->bodyClass = 'home';
    }

}

