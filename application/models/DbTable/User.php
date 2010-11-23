<?php
/**
 * 
 */
class Model_DbTable_User extends Zend_Db_Table
{
    protected $_name = 'user';

    public function getUser($id)
    {
        $select  = $this->select()->where('id = ?', (int)$id);
        $user = $this->fetchRow($select);
        if (!$user) {
            throw new Exception("user [$id] not found!");
        }
        return $user->toArray();
    }

    public function getUserByEmail($email)
    {
        $select  = $this->select()->where('email = ?', $email);
        $user = $this->fetchRow($select);
        if (!$user) {
            throw new Exception("user with email [$email] does not exist!");
        }
        return $user->toArray();
    }

    public function getUserByApiKey($key)
    {
        $select = $this->select()->where('api_key = ?', $key);
        $user = $this->fetchRow($select);

        if (!$user) {
            throw new Exception("user with api_key [$key] does not exist!");
        }
        return $user->toArray();
    }

    public function getUserCount()
    {
        $sql = 'select count(1) cnt from user';
        $stmt = $this->_db->query($sql);
        $results = $stmt->fetchAll();
        if ((sizeof($results) > 0) && (isset($results[0]['cnt']))){
            return $results[0]['cnt'];
        }
        throw new Exception("error getting user count!");
    }

    public function addUser($email, $timezone, $password)
    {
        // generate unique id for the password salt
        $salt = strtolower(uniqid(rand(), true));

        // generate unique id for the user's api identifier
        $api_key = $this->generateApiKey();

        // and finally...one more for their initial api 'secret' key
        $api_secret_key = strtolower(uniqid(rand(), true));

        // create a password hash to save in the database
        $hashed_pwd = strtolower(md5($password . $salt));

        $data = array(
            'email' => $email,
            'timezone' => $timezone,
            'password' => $hashed_pwd,
            'salt' => $salt,
            'role' => App_AccessList::ROLE_USER,
            'api_key' => $api_key,
            'api_secret_key' => $api_secret_key,
            'created_at' => null // force the created_at to get current timestamp
        );

        return $this->insert($data);
    }

    function updateUser($id, $email, $timezone, $password=null)
    {
        $where = array('id = ?' => (int)$id);

        $data = array('email' => $email);
        $data['timezone']=$timezone;

        if ($password !== null){
            // generate unique id (again) for the password salt
            $salt = strtolower(uniqid(rand(), true));
            $hashed_pwd = strtolower(md5($password . $salt));
            $data['salt']=$salt;
            $data['password']=$hashed_pwd;
        }

        $this->update($data, $where);
    }
    
    /**
     *
     * @return string; a unique API KEY for a new user
     */
    private function generateApiKey()
    {
        // generate unique id for the user's key identifier
        $key = strtolower(uniqid(rand(), true));
        
        // the above code 'should' always create a unique id for the user
        // but...just in case of a collision, we keep trying until there is not one
        // in our database
        $select  = $this->select()->where('api_key = ?', $key);
        while (($user = $this->fetchRow($select)) != null){
            $key = strtolower(uniqid(rand(), true));
        }
        
        return $key;
        
    }

    function deleteUser($id)
    {
        $this->delete(array('id = ?' => (int)$id));
    }


}