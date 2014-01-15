<?php

class Zend_View_helper_FlashMessages extends Zend_View_helper_Abstract {

    public function flashMessages() {
        $messages = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger')->getMessages();
        $output = '';
        if (!empty($messages)) {
            
            foreach ($messages as $message) {
                $output .= '<div data-dismiss="alert" class="' . key($message) . '" >';
                $output .= '<button class="close">&times;</button>';
                $output .= current($message);
                $output .= '</div>';
            }
            
        }
        return $output;
    }

}

?>
