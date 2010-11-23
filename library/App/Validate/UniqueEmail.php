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
class App_Validate_UniqueEmail extends Zend_Validate_Abstract
{
    const EMAIL_EXISTS = 'emailExists';

    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::EMAIL_EXISTS => "is already in use."
    );

    public function isValid($value)
    {
        $this->_setValue((string) $value);

        $model_user = new Model_DbTable_User();
        try{
            $user = $model_user->getUserByEmail($value);
            $this->_error();
            return false;
        }
        catch (Exception $e){
            // this is a good thing - no user should exist on signup
            // with the given email
        }

        return true;
    }

}

