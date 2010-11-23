<?php
/**
 * Dodo - To-do list application
 *
 * License
 *
 * Simply put:
 * You can use or modify this software for any personal or commercial
 * applications with the following exception:
 *   - You cannot host this software using the Dodo name or any
 *      images from the Dodo website including any logos.
 *
 * @author    Greg Wessels (greg@threadaffinity.com)
 *
 * www.threadaffinity.com
 */
class App_Auth_Storage_Session implements Zend_Auth_Storage_Interface
{
    protected $_session;

    public function __construct($session)
    {
        $this->_session  = $session;
    }

    /**
     * Defined by Zend_Auth_Storage_Interface
     *
     * @return boolean
     */
    public function isEmpty()
    {
        return !isset($this->_session->{'user_id'});
    }

    /**
     * Defined by Zend_Auth_Storage_Interface
     *
     * @return mixed
     */
    public function read()
    {
        return array(
                'user_id'=>$this->_session->{'user_id'},
                'user_name'=>$this->_session->{'user_name'},
                'user_role'=>$this->_session->{'user_role'});
    }

    /**
     * Defined by Zend_Auth_Storage_Interface
     *
     * @param  mixed $contents
     * @return void
     */
    public function write($contents)
    {
        //print_r($contents);
        
        $this->_session->{'user_id'} = $contents['user_id'];
        $this->_session->{'user_name'} = $contents['user_name'];
        $this->_session->{'user_role'} = $contents['user_role'];
    }

    /**
     * Defined by Zend_Auth_Storage_Interface
     *
     * @return void
     */
    public function clear()
    {
        unset($this->_session->{'user_id'});
        unset($this->_session->{'user_name'});
        unset($this->_session->{'user_role'});
    }

    public function getUserName()
    {
        return $this->_session->{'user_name'};
    }

    public function setUserName($username)
    {
        $this->_session->{'user_name'}=$username;
    }

    public function getUserId()
    {
        return $this->_session->{'user_id'};
    }

    public function getUserRole()
    {
        return $this->_session->{'user_role'};
    }
}
