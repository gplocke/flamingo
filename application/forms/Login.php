<?php
/**
 * 
 */
class Form_Login extends App_Form
{
    public function __construct($options = null)
    {
        parent::__construct($options);

        // change the class on the outer <dl> tag
        $this->getDecorator('HtmlTag')->setOption('class', 'form medium');

        $this->setName('login');

        $username = new App_Form_Element_Text('username');

        $username->setLabel('Username (Email):')
            ->setAttrib('size', '32')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');

        $password = new App_Form_Element_Password('password');
        $password->setLabel('Password:')
            ->setAttrib('size', '32')
            ->addFilter('StripTags')
            ->addFilter('StringTrim');

        $redirect = new Zend_Form_Element_Hidden('_redirect_url');
        $redirect->getDecorator('HtmlTag')->setOption('class', 'hidden');
        $redirect->removeDecorator('Label');

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton')
            ->setLabel('Login');
        
        $this->addElements(array($username, $password, $redirect, $submit));
    }

    public function setRedirectUrl($url){
		$this->getElement('_redirect_url')->setValue($url);
	}

	public function getRedirectUrl(){
		return $this->getElement('_redirect_url')->getValue();
	}

}
