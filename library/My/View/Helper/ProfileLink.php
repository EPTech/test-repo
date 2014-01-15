<?php
class My_View_Helper_ProfileLink extends Zend_View_Helper_Abstract
{
    public $view;

    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }

    public function profileLink()
    {
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $user = $auth->getIdentity();
            return '<div class="iblock"><i class="icon-user"></i><span><a href="/me">'.$user->username.'</a></span> <span><a href="/logout">Logout</a></span></div>';
        } 
    }
}