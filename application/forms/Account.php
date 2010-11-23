<?php
/**
 * 
 */
class Form_Account extends App_Form
{
    public function __construct($options = null)
    {
        parent::__construct($options);

        // change the class on the outer <dl> tag
        $this->getDecorator('HtmlTag')->setOption('class', 'form medium');

        $this->setName('account');

        $email = new App_Form_Element_Text('email');
        $email->setLabel('signup_label_email')
            ->setAttrib('size', '32')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');

        $check = new App_Form_Element_Checkbox('change_pwd');
        $check->setLabel('account_label_changepwd');
        
        $password = new App_Form_Element_Password('password');
        $password->setLabel('signup_label_password')
            ->setRequired(true)
            ->setAttrib('size', '32')
            ->addFilter('StripTags')
            ->addFilter('StringTrim');

        $password2 = new App_Form_Element_Password('password2');
        $password2->setLabel('signup_label_password2')
            ->setAttrib('size', '32')
            ->addFilter('StripTags')
            ->addFilter('StringTrim');

        $timezone = new Zend_Form_Element_Select('timezone');
		$timezone->setLabel('signup_label_timezone')
		         ->setMultiOptions(App_Timezone::getTimezones())
		         ->setRequired(true)->addValidator('NotEmpty', true);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton')
            ->setLabel('account_label_submit');

        // create array of elements to add to the form
        $elements = array($email, $timezone, $check, $password, $password2, $submit);

        $this->addElements($elements);

        // add error summary decorator (will list all validation errors at the
        // top of the form - all 'Error' decorators should be disabled since we
        // are not showing the errors next to the input item (just turning the
        // labels red)
        $this->addDecorator(new App_Form_Decorator_FormErrors(
                array('placement'=>Zend_Form_Decorator_Abstract::PREPEND,
                    'message'=>'account_error_summary')));

    }


    public function isValid($data)
    {
        // if checkbox is not set to change password, remove required flags
        if (!isset($data['change_pwd']) || (int)$data['change_pwd'] == 0){
            $this->getElement('password')->setRequired(false);
            $this->getElement('password2')->setRequired(false);
        }
        else{
            // inject another validator on the password element to check
            // password1/password2 equality
            $password = $this->getElement('password');
            $password2= $this->getElement('password2');
            $password->addValidator(new App_Validate_PasswordMatch($data['password2']));
        }

        return parent::isValid($data);
    }


}
