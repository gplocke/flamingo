<?php
/**
 * 
 */
class App_AccessList
{
    const ROLE_GUEST = 'guest';
    const ROLE_USER = 'user';
    const ROLE_ADMIN = 'admin';

    protected static $_instance = null;
    protected $_acl = null;

    /**
    * Singleton pattern implementation makes "new" unavailable
    *
    */
    private function __construct()  {
        
        $this->_acl = new Zend_Acl();

        // define our possible user groups
	$this->_acl->addRole(new Zend_Acl_Role(self::ROLE_GUEST));

	// members have at least same access as guest
	$this->_acl->addRole(new Zend_Acl_Role(self::ROLE_USER), array(self::ROLE_GUEST));

        // admins have at least same access as users
        $this->_acl->addRole(new Zend_Acl_Role(self::ROLE_ADMIN), array(self::ROLE_USER));

		// define our restricted controllers as resources.
        $this->_acl->add(new Zend_Acl_Resource('frontend/task'));
        $this->_acl->add(new Zend_Acl_Resource('frontend/list'));
        $this->_acl->add(new Zend_Acl_Resource('frontend/account'));

        // add the backend module resources
        $module = new Zend_Acl_Resource('backend');
		$this->_acl->add($module);

        // add the service module resources
        $module = new Zend_Acl_Resource('api');
		$this->_acl->add($module);

        $this->_acl->allow(self::ROLE_USER, 'frontend/task');
        $this->_acl->allow(self::ROLE_USER, 'frontend/list');
        $this->_acl->allow(self::ROLE_USER, 'frontend/account');
        $this->_acl->allow(self::ROLE_USER, 'api');

        // TODO: change this to admin role
        $this->_acl->allow(self::ROLE_USER, 'backend');

    }

    /**
     * Returns an instance of App_AccessControl
     *
     */
    private static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public static function getAcl()
    {
        $inst = App_AccessList::getInstance();
        return $inst->_acl;
    }

    /**
     * Determines if the given role is allowed access to the resource
     *
     */
    public static function isAllowed($role, $resource, $privilege)
    {
            $inst = App_AccessList::getInstance();

            if (!$inst->_acl->has($resource)) {
                    $resource = null;
            }

            return $inst->_acl->isAllowed($role, $resource, $privilege);
    }

    /**
     * Allows access to the given resource
     *
     */
     public static function getResource($resource)
     {
	$inst = App_AccessList::getInstance();
        if ($inst->_acl->has($resource)){
            return $inst->_acl->get($resource);
        }
        return null; // return null if not found
    }


}