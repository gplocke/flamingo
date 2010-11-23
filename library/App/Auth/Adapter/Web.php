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
class App_Auth_Adapter_Web implements Zend_Auth_Adapter_Interface
{
	private $_username;
	private $_password;

    /**
     * Sets username and password for authentication
     *
     * @return void
     */
    public function __construct($username, $password)
    {
		$this->_username = $username;
		$this->_password = $password;
	}

	public function getIdentity(){
		return $this->_username;
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

        // get the user info from the database (username is an email address)
        $user = null;
        try{
            $user = $model->getUserByEmail($this->_username);

        }catch (Exception $e){
            return new Zend_Auth_Result(Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND,
                array('user_name'=>$this->_username), array(App_Translate::translate('invalid username')));
        }

        // make a hash using the salt from the database
		$signature = strtolower(md5($this->_password . $user['salt']));

		// validate credentials
		if ($signature !== $user['password']){

			// password is not valid...
			return new Zend_Auth_Result(Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID,
					array('user_name'=>$this->_username), array(App_Translate::translate('invalid credentials')));
		}

		return new Zend_Auth_Result(Zend_Auth_Result::SUCCESS,
                        array('user_name'=>$this->_username,'user_id'=>$user['id'],
                        'user_role'=>$user['role']),
                        array(App_Translate::translate('successful login')));

    }
}







