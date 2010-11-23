<?php
/**
 * 
 */
class Model_DbTable_TaskList extends Zend_Db_Table
{
    protected $_name = 'tasklist';

    public function getList($id, $user_id=null)
    {
        $where = array('id = ?' => (int)$id);

        // the user_id is optional (can be used to restrict results)
        if ($user_id !== null){
            $where['user_id = ?'] = (int)$user_id;
        }

        $list = $this->fetchRow($where);
        if (!$list) {
            throw new Exception("tasklist [$id] not found!");
        }
        return $list->toArray();
    }

    public function getLists($user_id)
    {
        $select  = $this->select()->where('user_id = ?', (int)$user_id)
                        ->order('created_at ASC');
        return $this->fetchAll($select);
    }

    public function getListCount($user_id)
    {
        $sql = 'select count(1) cnt from tasklist where user_id = ?';
        $stmt = $this->_db->query($sql, $user_id);
        $results = $stmt->fetchAll();
        if ((sizeof($results) > 0) && (isset($results[0]['cnt']))){
            return $results[0]['cnt'];
        }
        throw new Exception("error getting tasklist count for [$user_id]!");
    }

    public function getTotalListCount()
    {
        $sql = 'select count(1) cnt from tasklist';
        $stmt = $this->_db->query($sql);
        $results = $stmt->fetchAll();
        if ((sizeof($results) > 0) && (isset($results[0]['cnt']))){
            return $results[0]['cnt'];
        }
        throw new Exception("error getting total tasklist count!");
    }

    public function addList($user_id, $name)
    {
        $data = array(
            'user_id' => $user_id,
            'name' => $name
        );

        return $this->insert($data);
    }

    function updateList($id, $name)
    {
        $where = array('id = ?' => (int)$id);
        $data = array(
            'name' => $name
        );
        $this->update($data, $where);
    }

    function deleteList($id)
    {
        $this->delete(array('id = ?' => (int)$id));
    }

}