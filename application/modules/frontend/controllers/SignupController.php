<?php
/**
 * 
 */
class SignupController extends App_Controller_Action{

    public function preDispatch()
	{
        $user = $this->getUserId();
		if (!empty($user)) {
			// If the user is logged in, can't really signup again without logging out
            $this->_redirect('/task');
        }
    }

    public function indexAction()
    {
        $request = $this->getRequest();

        $form = new Form_Signup();
        $this->view->form = $form;        

        if ($this->getRequest()->isPost()) {

            $formData = $this->getRequest()->getPost();

            if ($form->isValid($formData)) {

                $email = $form->getValue('email');
                $password = $form->getValue('password');
                $timezone = $form->getValue('timezone');

                $db = Zend_Db_Table::getDefaultAdapter();
                $db->beginTransaction();

                try {

                    $model_user = new Model_DbTable_User();
                    $model_list = new Model_DbTable_TaskList();
                    $user_id = $model_user->addUser($email, $timezone, $password);

                    // create a default list for the user called 'Personal'
                    $model_list = new Model_DbTable_TaskList();
                    $model_list->addList($user_id, 'Personal');

                    $db->commit();
                }
                catch (Exception $e)
                {
                    $db->rollBack();
                    throw $e;
                }

                // send the user to the login page
                $this->_redirect("/login");

            } else {

                $form->populate($formData);
            }
        }
        else{

            $form->populate(array('timezone'=>'America/New_York'));

        }
        
	}

}


