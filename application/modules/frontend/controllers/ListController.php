<?php
/**
 * 
 */
class ListController extends App_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
    }

    public function addAction()
    {
        $request = $this->getRequest();

        $form = new Form_AddList();
        $this->view->form = $form;

        if ($request->isPost()) {

            $formData = $request->getPost();

            if ($form->isValid($formData)) {

                $name = $form->getValue('name');
                $user_id = $this->getUserId();

                // add the list - addTaskList() will return the PK of the new row
                $model = new Model_DbTable_TaskList();
                $list_id = $model->addList($user_id, $name);

                // redirect to tasks/index with our new list selected
                $router = Zend_Controller_Front::getInstance()->getRouter();
                $url = $router->assemble(array('controller'=>'task', 'list'=>$list_id), null, true);
                $this->_redirect($url);

            } else {
                $form->populate($formData);
            }
        }


    }

    public function editAction()
    {
        $request = $this->getRequest();

        $list_id = $this->_getParam('list', null);
        $user_id = $this->getUserId();

        $form = new Form_AddList();
        // change the submit button's label
        $form->getElement('submit')->setLabel('editlist_label_submit');
        
        $this->view->form = $form;

        $model = new Model_DbTable_TaskList();
        $list = $model->getList($list_id, $user_id);

        if ($request->isPost()) {

            $formData = $request->getPost();

            if ($form->isValid($formData)) {

                $name = $form->getValue('name');

                $model->updateList($list_id, $name);

                // redirect to tasks/index with our list selected
                $router = Zend_Controller_Front::getInstance()->getRouter();
                $url = $router->assemble(array('controller'=>'task', 'list'=>$list_id), null, true);
                $this->_redirect($url);

            } else {

                $form->populate($formData);

            }
        }
        else
        {
            // populate the name field
            $form->getElement('name')->setValue($list['name']);


        }


    }

    public function deleteAction()
    {
        $request = $this->getRequest();

        // should come in via a POST
        if (!$request->isPost()) {
            throw new Exception('Request must be a POST!');
        }

        $formData = $request->getPost();

        // the list must come in via the post data (required)
        $list_id = 0;
        if (isset($formData['list'])){
           $list_id = $formData['list'];
        }
        $user_id = $this->getUserId();

        // query for the tasklist (note: we pass in the user id to ensure
        // that for the given user + list_id combination there is a valid
        // list - ie prevents hijacking the request with a random list_id
        // that is - the list must belong to the current user
        $model = new Model_DbTable_TaskList();
        $list = $model->getList($list_id, $user_id);

        // user-interface should already prevent this...but just in case
        if ($model->getListCount($user_id) <= 1){
            throw new Exception('Cannot delete the last task list!');
        }

        // ready to update the database
        $model->deleteList($list_id);

        // redirect to tasks/index (it will auto-magically pick a new list to show)
        $this->_redirect('/task');

    }

}
