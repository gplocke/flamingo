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
class App_Auth_Adapter_Api implements Zend_Auth_Adapter_Interface
{
    private $_key;
    private $_signature;

    public function __construct($key, $signature)
    {
        $this->_key = $key;
	    $this->_signature = $signature;
	}

    /**
     * Performs an authentication attempt
     *
     * @throws Zend_Auth_Adapter_Exception If authentication cannot be performed
     * @return Zend_Auth_Result
     */
    public function authenticate()
    {
        $model = new Model_DbTable_User();

        // get the user info from the database (note: we are looking up by api id rather than email)
        $user = null;
        try{
            $user = $model->getUserByApiKey($this->_key);
            
        }catch (Exception $e){
            return new Zend_Auth_Result(Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND,
                array('user_name'=>$this->_key), array(App_Translate::translate('invalid api_key')));
        }

        $signature = strtolower(md5($this->_key . $user['api_secret_key']));

        if ($signature !== $this->_signature){
            return new Zend_Auth_Result(Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID,
                    array('user_name'=>$this->_key),
                    array('invalid credentials'));
        }
        
        return new Zend_Auth_Result(Zend_Auth_Result::SUCCESS,
                        array('user_name'=>$this->_key,
                            'user_id'=>$user['id'],'user_role'=>$user['role']),
                        array(App_Translate::translate('successful login')));

     }

}
