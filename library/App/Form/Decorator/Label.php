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
class App_Form_Decorator_Label extends Zend_Form_Decorator_Label
{
    /**
     * Get class with which to define label
     *
     * Appends 'error' to class, if there is an error in the form for the
     * associated element
     *
     * @return string
     */
    public function getClass()
    {
        $class = parent::getClass();

        $element = $this->getElement();

        if ($element->hasErrors()){
            if (!empty($class)){
                $class .= ' invalid';
            }else{
                $class = 'invalid';
            }
        }
       
        return $class;
    }


}
