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
class App_Form_Element_Checkbox extends Zend_Form_Element_Checkbox
{
    public function loadDefaultDecorators()
    {
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return;
        }

        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            $this->addDecorator('ViewHelper')
                ->addDecorator(new App_Form_Decorator_Label(array('tag' => null,
                            'escape' => false,
                            'placement'=>Zend_Form_Decorator_Abstract::APPEND)))
                ->addDecorator('HtmlTag', array('tag' => 'dd',
                            'id'  => $this->getName() . '-element'));

        }
    }

}
