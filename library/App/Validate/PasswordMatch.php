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
class App_Validate_PasswordMatch extends Zend_Validate_Abstract
{
    const PASSWORD_MISMATCH = 'passwordMismatch';

     protected $_compare;

    public function __construct($compare)
    {
        $this->_compare = $compare;
    }

    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::PASSWORD_MISMATCH => "Password doesn't match confirmation"
    );

    public function isValid($value)
    {
        $this->_setValue((string) $value);

        if ($value !== $this->_compare)  {
            $this->_error(self::PASSWORD_MISMATCH);
            return false;
        }

        return true;
    }

}


