<?php
/**
 * 
 */
class LoginController extends App_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
    }

    public function preDispatch()
	{
        $user = $this->getUserId();
		if (!empty($user)) {

			// If the user is logged in, we don't want to show the login form;
            // however, the logout action should still be available
            if ('logout' != $this->getRequest()->getActionName()) {
                $this->_redirect('/task');
            }

        } else {

			// If they aren't, they can't logout, so that action should redirect to the login form
            if ('logout' == $this->getRequest()->getActionName()) {
                $this->redirectSimple('/login');
            }
        }
    }

    public function indexAction()
    {
        // this action does not use a layout
        $this->_helper->layout->disableLayout();

        $request = $this->getRequest();

        $form = new Form_Login();
        $this->view->form = $form;        

        if ($this->getRequest()->isPost()) {

            $formData = $this->getRequest()->getPost();

            if ($form->isValid($formData)) {

                $username = $form->getValue('username');
                $password = $form->getValue('password');
                $redirect_url = $form->getValue('_redirect_url');

                $session = $this->getSession();

                // create components for authentication
                $storage = new App_Auth_Storage_Session($session);
                $adapter = new App_Auth_Adapter_Web($username, $password);
                $auth = Zend_Auth::getInstance();
                $auth->setStorage($storage);

                // authenticate the user (note: authorization is in Access Plugin)
                $result = $auth->authenticate($adapter);
                if (!$result->isValid()) {

                    // invalid credentials
                    $form->populate(array('username' => $username, '_redirect_url' => $redirect_url));
                    $this->view->formError = 'Invalid username and/or password.';
                    return; // re-render the login form
                }

                // redirect to the intended destination of the caller
                if (!empty($redirect_url)){
                    $this->_redirect($redirect_url);
                }
                else{
                    // if no redirect url - send the user to the 'my tasks' page
                    $this->_redirect("/task");
                }

            } else {

                $form->populate($formData);
            }
        }
        else{
            
            // set the redirect uri field on the form (original location where the user was going)
            $form->setRedirectUrl($request->getParam('_request_url'));
        }

    }


    // this action destroys all elements stored in the user's session
    // and redirects back to homepage
	public function logoutAction()
    {
        $session = $this->getSession();
        $session->unsetAll();

        $this->_redirect('/');
	}    
    
    



}
