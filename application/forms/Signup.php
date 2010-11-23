<?php
/**
 * 
 */
class Form_Signup extends App_Form
{
    public function __construct($options = null)
    {
        parent::__construct($options);

        // change the class on the outer <dl> tag
        $this->getDecorator('HtmlTag')->setOption('class', 'form large');

        $this->setName('signup');

        $email = new App_Form_Element_Text('email');
        $email->setLabel('signup_label_email')
            ->setDescription('signup_subtext_email')
            ->setAttrib('size', '32')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty')
            ->addValidator(new App_Validate_UniqueEmail);

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


        $check_terms = new App_Form_Element_Checkbox('terms_agree');
        $check_terms->setLabel('signup_label_terms');
         
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton')
            ->setLabel('signup_label_submit');

        // create array of elements to add to the form
        $elements = array($email, $password, $password2, $timezone, $check_terms, $submit);

        $this->addElements($elements);

        // add error summary decorator (will list all validation errors at the
        // top of the form - all 'Error' decorators should be disabled since we
        // are not showing the errors next to the input item (just turning the
        // labels red)
        $this->addDecorator(new App_Form_Decorator_FormErrors(
                   array('placement'=>Zend_Form_Decorator_Abstract::PREPEND,
                    'message'=>'signup_error_summary')));

    }

    public function isValid($data)
    {
        // inject another validator on the password element to check
        // password1/password2 equality
        $password = $this->getElement('password');
        $password2= $this->getElement('password2');
        $password->addValidator(new App_Validate_PasswordMatch($data['password2']));

        $valid = parent::isValid($data);

        // if passed initial validation; check for terms
        if ($valid){
            if (!isset($data['terms_agree']) || (int)$data['terms_agree'] <= 0){
                $this->addError('signup_error_terms');
                $valid = false;
            }
        }

        return $valid;
    }

}
