<?php
/**
 *
 */
class Api_TaskController extends Zend_Controller_Action
{
    public function init()
    {
    }

    public function indexAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();

        // user param comes in on request (put there by Access Plugin)
        $user_id = $request->getParam('user_id', null);

        // get the tasklists for the user
        $model_list = new Model_DbTable_TaskList();

        // the tasklist should come in as a param (if null, then use default)
        $list_id = $this->_getParam('list', null);
        if ($list_id === null){
            $tasklists = $model_list->getLists($user_id);
            $list_id = $tasklists[0]['id'];
        }

        $this->view->tasklist = $model_list->getList($list_id);

        $model_task = new Model_DbTable_Task();
        $tasks = $model_task->getTasks($list_id);
        $this->view->tasks = $tasks;

        $response->setHeader('Content-Type', 'text/xml', true);
    }
}
