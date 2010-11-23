<?php
/**
 * 
 */
class TaskController extends App_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // the tasklist should come in as a param (if null, then use default)
        $list_id = $this->_getParam('list', null);

        $form = new Form_AddTask();

        $this->view->form = $form;

        // get the user-id from the session
        $user_id = $this->getUserId();

        // get the tasklists for the user
        $model_list = new Model_DbTable_TaskList();
        $tasklists = $model_list->getLists($user_id);
        $this->view->tasklists = $tasklists;

        // if lists exist...get the tasks to show
        if (!empty($tasklists)){

            if ($list_id == null){
                $list_id = $tasklists[0]['id'];
            }

            // current tasklist given to the view
            $this->view->tasklist = $model_list->getList($list_id, $user_id);
            $this->view->tasklist_count = count($tasklists);

            $model_task = new Model_DbTable_Task();
            $tasks = $model_task->getTasks($list_id, 0); // 0 => incomplete
            $this->view->incomplete_tasks = $tasks;

            $tasks = $model_task->getTasks($list_id, 1); // 1 => complete
            $this->view->complete_tasks = $tasks;

            // for demonstration purposes, write out the contents of the tasklists array
            App_Log::debug($tasklists);
            
            // for demonstration purposes, log the total number of tasks
            App_Log::info('tasklist count: ' . $this->view->tasklist_count);
        }
    }

    public function addAction()
    {
        $request = $this->getRequest();

        $results = array();

        try{

            // should come in via jquery.form as POST
            if (!$request->isPost()) {
                throw new Exception('Request must be a POST!');
            }

            // the tasklist must come in as a param (required)
            $list_id = $this->_getParam('list', null);
            $user_id = $this->getUserId();

            // query for the tasklist (note: we pass in the user id to ensure
            // that for the given user + list_id combination there is a valid
            // list - ie prevents hijacking the request with a random list_id
            // that is - the list must belong to the current user
            $model = new Model_DbTable_TaskList();
            $tasklist = $model->getList($list_id, $user_id);

            // use the form to pull the task info - that way we can still
            // use the validators and filters in Zend_Form
            $formData = $request->getPost();
            $form = new Form_AddTask();
            if (!$form->isValid($formData)) {
                throw new Exception('Invalid entry!');
            }
            $description = $form->getValue('description');

            // ready to add to the database
            $model = new Model_DbTable_Task();
            $task_id = $model->addTask($list_id, $description);

            // we send it back - since we will strip tags, etc. so the value
            // may be different than what the client script nows about
            $results['id'] = $task_id;
            $results['description'] = $description;

        }
        catch (Exception $ex){
            $results['error'] = $ex->getMessage();
        }
        
        // send back a JSON response
        $this->_helper->json($results);

    }

    public function deleteAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();

        // disable layouts and views since we are returning JSON directly
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $results = array();

        try{

            // should come in via a POST
            if (!$request->isPost()) {
                throw new Exception('Request must be a POST!');
            }

            // the task must come in as a param (required)
            $task_id = $this->_getParam("task", null);
            $user_id = $this->getUserId();

            $model_task = new Model_DbTable_Task();
            $task = $model_task->getTask($task_id);

            // query for the tasklist (note: we pass in the user id to ensure
            // that for the given user + list_id combination there is a valid
            // list - ie prevents hijacking the request with a random list_id
            // that is - the list must belong to the current user
            $model_list = new Model_DbTable_TaskList();
            $tasklist = $model_list->getList($task['list_id'], $user_id);

            // ready to update the database
            $model_task->deleteTask($task_id);

            // we send the deleted task info back
            $results['id'] = $task_id;
        }
        catch (Exception $ex){
            $results['error'] = $ex->getMessage();
        }

        // send back a JSON response
        $this->_helper->json($results);

    }


    public function completeAction()
    {
        $request = $this->getRequest();

        $results = array();

        try{

            // should come in via a POST
            if (!$request->isPost()) {
                throw new Exception('Request must be a POST!');
            }

            // the task must come in as a param (required)
            $task_id = $this->_getParam("task", null);
            $user_id = $this->getUserId();

            $model_task = new Model_DbTable_Task();
            $task = $model_task->getTask($task_id);

            // query for the tasklist (note: we pass in the user id to ensure
            // that for the given user + list_id combination there is a valid
            // list - ie prevents hijacking the request with a random list_id
            // that is - the list must belong to the current user
            $model_list = new Model_DbTable_TaskList();
            $tasklist = $model_list->getList($task['list_id'], $user_id);

            // pull the status param and default it to 1 (complete)
            // this way to make a task incomplete - this action can be called
            // with ?status=0 to go back to incomplete
            $status = (int)$this->_getParam("status", 1);
            
            // ready to update the database
            $model_task->updateTask($task_id, $task['description'], $status);

            // we send the new task info back
            $results['id'] = $task_id;
            $results['status'] = $status;
            $results['description'] = $task['description'];

        }
        catch (Exception $ex){
            $results['error'] = $ex->getMessage();
        }

        // send back a JSON response
        $this->_helper->json($results);
        
    }

}
