<?php
/**
 * 
 */
class Model_DbTable_Task extends Zend_Db_Table
{
    protected $_name = 'task';

    public function getTask($id)
    {
        $select  = $this->select()->where('id = ?', (int)$id);
        $user = $this->fetchRow($select);
        if (!$user) {
            throw new Exception("task [$id] not found!");
        }
        return $user->toArray();
    }

    public function getTasks($list_id, $complete=null)
    {
        $where = array('list_id = ?' => (int)$list_id);

        // the complete flag condition is optional (if null, all tasks for the list
        // will be returned)
        if ($complete !== null){
            $where['complete = ?'] = (int)$complete;
        }

        return $this->fetchAll($where, array('complete ASC', 'created_at ASC'));
    }

    public function getTotalTaskCount($complete)
    {
        $sql = 'select count(1) cnt from task where complete = ?';
        $stmt = $this->_db->query($sql, $complete);
        $results = $stmt->fetchAll();
        if ((sizeof($results) > 0) && (isset($results[0]['cnt']))){
            return $results[0]['cnt'];
        }
        throw new Exception("error getting total task count!");
    }

    public function addTask($list_id, $description)
    {
        $data = array(
            'list_id' => $list_id,
            'description' => $description,
            'created_at' => null
        );

        return $this->insert($data);
    }

    function updateTask($id, $description, $complete)
    {
        $where = array('id = ?' => (int)$id);

        $data = array(
            'description' => $description,
            'complete' => $complete,
        );
        $this->update($data, $where);
        
    }

    function deleteTask($id)
    {
        $this->delete(array('id = ?' => (int)$id));
    }


}