<?php
/**
 * 
 */
class Form_AddList extends App_Form
{
    public function __construct($options = null)
    {
        parent::__construct($options);

        // change the class on the outer <dl> tag
        $this->getDecorator('HtmlTag')->setOption('class', 'form large');

        $this->setName('add_list');

        $name = new App_Form_Element_Text('name');
        $name->setLabel('addlist_label_name')
            ->setAttrib('size', '50')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton')
            ->setLabel('addlist_label_submit');

        // create array of elements to add to the form
        $elements = array($name, $submit);

        $this->addElements($elements);

        // add error summary decorator (will list all validation errors at the
        // top of the form - all 'Error' decorators should be disabled since we
        // are not showing the errors next to the input item (just turning the
        // labels red)
        $this->addDecorator(new App_Form_Decorator_FormErrors(
                array('placement'=>Zend_Form_Decorator_Abstract::PREPEND,
                    'message'=>'addlist_error_summary')));

    }

}
