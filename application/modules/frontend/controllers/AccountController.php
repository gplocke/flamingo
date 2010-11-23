<?php
/**
 * 
 */
class AccountController extends App_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $request = $this->getRequest();

        $form = new Form_Account();
        $this->view->form = $form;

        $user_id = $this->getUserId();
        $model = new Model_DbTable_User();
        $user = $model->getUser($user_id);

        $this->view->api_key = $user['api_key'];

        if ($request->isPost()) {

            $formData = $request->getPost();

            // add the unique email validator if they are changing the email address
            if (isset($formData['email']) && ($user['email'] !== $formData['email'])){
                $form->getElement('email')->addValidator(new App_Validate_UniqueEmail);
            }

            if ($form->isValid($formData, $user['email'])) {

                $password = null;
                $is_pwd_change = $form->getValue('change_pwd');

                if (isset($is_pwd_change) && (int)$is_pwd_change != 0){
                   $password = $form->getValue('password');
                }

                $email = $form->getValue('email');
                $timezone = $form->getValue('timezone');
                $model->updateUser($user_id, $email, $timezone, $password);

                // need to update the username in the session
                $session = $this->getSession();
                $storage = new App_Auth_Storage_Session($session);
                $storage->setUserName($email);

                $this->_redirect('/task');

            } else {
                $form->populate($formData);
            }
        }
        else
        {
            $form->populate(array(
                                'email'=>$user['email'],
                                'timezone'=>$user['timezone']));


        }

    }

    public function deleteAction()
    {
        $request = $this->getRequest();

        // should come in via a POST
        if (!$request->isPost()) {
            throw new Exception('Request must be a POST!');
        }

        $user_id = $this->getUserId();
        $model = new Model_DbTable_User();
        $user = $model->getUser($user_id);

        $model->deleteUser($user_id);

        // dump the session
        $session = $this->getSession();
        $session->unsetAll();

        // pull this from the view since its already set in there (in bootstrap)
        unset($this->view->username);

    }

    public function sendmailAction()
    {
        $request = $this->getRequest();

        // should come in via a POST
        if (!$request->isPost()) {
            throw new Exception('Request must be a POST!');
        }

        $user_id = $this->getUserId();
        $model = new Model_DbTable_User();
        $user = $model->getUser($user_id);

        $email = $user['email'];
        $api_key = $user['api_key'];
        $secret_key = $user['api_secret_key'];

        $subject = 'Flamingo API keys';
        $message = "$email\n\n" .
            "Your Flamingo API Access Key: $api_key\n".
            "Your Flamingo API Secret Key: $secret_key\n\n\n".
            "DO NOT REPLY";
        $headers = 'From: Flamingo Support <support@flamingoapp.com>';

        mail($email, $subject, $message, $headers);

        $this->view->email = $email;
    }

}