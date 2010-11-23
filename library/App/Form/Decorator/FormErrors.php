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
class App_Form_Decorator_FormErrors extends Zend_Form_Decorator_Abstract
{
    public function __construct($options = null)
    {
        parent::__construct($options);

        //setMarkupListStart('<ul class="form_errors">');
    }

    public function render($content)
    {
        $form = $this->getElement();
        if (!$form instanceof Zend_Form) {
            return $content;
        }

        $message = $this->getOption('message');
        if (empty($message)){
            $message = '';
        }
        // use the forms translator for the summary message
        $translator = $form->getTranslator();
        if ($translator !== null){
            $message = $translator->translate($message);
        }

        $view = $form->getView();
        if (null === $view) {
            return $content;
        }

        $errors  = $form->getMessages();
        if (empty($errors)) {
            return $content;
        }
                
        $markup = '<div class="form_errors_block">';
        if (!empty($message)){
            $markup .= '<p class="message">' . $message . '</p>';
        }
        $markup .= '<ul class="form_errors">';

        foreach ($errors as $name => $list) {
            $element = $form->$name;

            if ($element instanceof Zend_Form_Element) {

                $label = $element->getLabel();
                if (empty($label)) {
                    $label = $element->getName();
                }
                $label = trim($label);
                if (empty($label)) {
                    $label = '';
                }
                if (null !== ($translator = $element->getTranslator())) {
                    $label = $translator->translate($label);
                }
                
                $error_msg = '';
                foreach ($list as $key => $error) {
                    $error_msg = $view->escape($error);
                    break; // just do the first error message for a field
                }
                
                $markup .= '<li><span class="label">' . $label . '</span>'
                        . $error_msg . '</li>';
            }
            else{
                if (is_string($list)){
                    $markup .= '<li>' . $list . '</li>';
                }
            }
        }

        $markup .= '</ul></div>';
        
        switch ($this->getPlacement()) {
            case self::APPEND:
                return $content . $this->getSeparator() . $markup;
            case self::PREPEND:
                return $markup . $this->getSeparator() . $content;
        }        

        return $content;  
    }

}
