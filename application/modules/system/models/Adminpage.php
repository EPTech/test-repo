<?php

class System_Model_Adminpage extends PP_Model_Acl_Abstract {

    protected $_acl;

    public function getResourceId() {
        return 'Adminpage';
    }

    public function setAcl(PP_Acl_Interface $acl) {
        
    }

    public function getLastId() {
        return $this->getResource('Adminprofile')->getLastid();
    }

    public function getAcl() {
        
    }

    public function getAdminpageById($id) {
        $id = (int) $id;
        return $this->getResource('Adminpage')->getUserById($id);
    }

    public function getAdminpages($paged = false, $order = null) {
        //if user is not admin pullout only their record
        if (Zend_Auth::getInstance()->getIdentity()->role_id != "Admin") {
            return $this->getUserByEmail(Zend_Auth::getInstance()->getIdentity()->email);
        }
        if (!$this->checkAcl('listUser')) {
            //  throw new RM_Acl_Exception("Insufficient rights");
        }
        return $this->getResource("Adminpage")->getUsers($paged, $order);
    }

    public function registerAdminpage($post) {
        $form = $this->getForm('adminpageAdd');
        $date = new Zend_Date();
        $date_val = $date->get(Zend_Date::YEAR) . "-" . $date->get(Zend_Date::MONTH_SHORT) . "-" . $date->get(Zend_Date::DAY_SHORT);
        $time_val = $date->get(Zend_Date::HOUR_SHORT) . ":" . $date->get(Zend_Date::MINUTE_SHORT) . ":" . $date->get(Zend_Date::SECOND_SHORT);
        return $this->_save($form, $post, array('date_created' => $date_val, 'time_created' => $time_val));
    }

    public function updateAdminprofile(array $info, $defaults = array()) {
        $form = $this->getForm('adminprofileEdit');
        if (!$form->isValid($info)) {
            return false;
        }

        $data = $form->getValues();

        //apply any defaults
        foreach ($defaults as $col => $value) {
            $data[$col] = $value;
        }

        $profile = $this->getResource('Adminprofile')->getAdminprofileById($data['role_id']);
        // var_dump($profile); exit;
        return $this->getResource('Adminprofile')->saveRow($data, $profile);
    }

    public function registerAdminprofile(array $info, $defaults = array()) {
        $form = $this->getForm('adminprofileAdd');
        if (!$form->isValid($info)) {
            return false;
        }

        $data = $form->getValues();
        //apply any defaults
        foreach ($defaults as $col => $value) {
            $data[$col] = $value;
        }

        return $this->getResource('Adminprofile')->saveRow($data);
    }

    protected function _save(Zend_Form $form, array $info, $defaults = array()) {

        if (!$form->isValid($info)) {
            return false;
        }

        $data = $form->getValues();

        //apply any defaults
        foreach ($defaults as $col => $value) {
            $data[$col] = $value;
        }

        return $this->getResource('Adminpage')->saveRow($data);
    }

    public function getAdminprofiles() {
        return $this->getResource('Adminprofile')->getAdminprofiles();
    }

    public function getAdminprofileById($id) {
        return $this->getResource('Adminprofile')->getAdminprofileById($id);
    }

    public function deleteAdminpageById($id) {
        return $this->getResource('Adminpage')->deleteAdminpageById($id);
    }

    public function deleteAdminpages(array $ids) {
        return $this->getResource('Adminpage')->deleteAdminpages($ids);
    }

    public function deleteAdminprofileById($id) {

        return $this->getResource('Adminprofile')->deleteAdminprofileById($id);
    }

    public function deleteAdminprofiles(array $ids) {
        return $this->getResource('Adminprofile')->deleteAdminprofiles($ids);
    }

    public function getaclAdminprofiles() {
        return $this->getResource('Adminprofile')->getaclAdminprofiles();
    }

    public function getAdminMenus() {
        return $this->getResource('Adminmenu')->getAdminmenus();
    }

    public function getAdminModules() {
        return $this->getResource('Adminpage')->getAdminModules();
    }

    public function getModuleActions($module) {
        return $this->getResource('Adminpage')->getModuleActions($module);
    }

    public function getallAdminpages() {
        return $this->getResource('Adminpage')->getallAdminpages();
    }

    public function getAdminpageByContnAct($controller, $action) {
        return $this->getResource('Adminpage')->getAdminpageByContnAct($controller, $action);
    }

    public function registerAdminpage2profile($data) {
        return $this->getResource('Adminpage2profile')->saveRow($data);
    }

    public function deleteProfileById($id) {
        return $this->getResource('Adminpage2profile')->deleteProfileById($id);
    }

    public function getAdminpage2profileById($id) {
        return $this->getResource('Adminpage2profile')->getAdminpage2profileById($id);
    }

    public function getAdminpage2profileByPageId($id) {
        return $this->getResource('Adminpage2profile')->getAdminpage2profileByPageId($id);
    }

    public function getAdminControllers() {
        return $this->getResource('Adminpage')->getAdminControllers();
    }

    public function getMenus() {
        return $this->getResource('Menu')->getMenus();
    }

    public function getMenuById($id) {
        $id = (int) $id;
        return $this->getResource('Menu')->getMenuById($id);
    }

    public function createMenu($post) {
        $form = $this->getForm('menuAdd');
        return $this->_saveMenu($form, $post);
    }

    public function updateMenu($post) {
        $form = $this->getForm('menuEdit');
        return $this->_saveMenu($form, $post);
    }

    public function _saveMenu($form, $info, $default = array()) {

        if (!$form->isValid($info)) {
            // exit("i am here");
            return false;
        }

        $data = $form->getValues();

        //apply any defaults
        foreach ($default as $col => $value) {
            $data[$col] = $value;
        }

        $menu = array_key_exists('menu_id', $data) ? $this->getResource('Menu')->getMenuById($data['menu_id']) : null;
        return $this->getResource('Menu')->saveRow($data, $menu);
    }

    public function removeMenus(array $ids) {
        return $this->getResource('Menu')->deleteMenus($ids);
    }

    public function removeMenuById($id) {
        return $this->getResource('Menu')->deleteMenuById($id);
    }

    public function getItemsByMenu($menuid) {
        return $this->getResource('Menuitem')->getItemsByMenu($menuid);
    }

    public function getItemById($id) {
        return $this->getResource('Menuitem')->getItemById($id);
    }

    public function getItems() {
        return $this->getResource('Menuitem')->getItems();
    }

    public function deleteItems(array $ids) {
        return $this->getResource('Menuitem')->deleteItems($ids);
    }

    public function deleteItemById($id) {
        return $this->getResource('Menuitem')->deleteItemById($id);
    }

    public function deleteItemByMenuId($id) {
        return $this->getResource('Menuitem')->deleteItemByMenuId($id);
    }

    public function createMenuitem($post) {
       
        $form = $this->getForm('menuitemAdd');
      
        return $this->_saveMenuitem($form, $post);
    }

    public function updateMenuitem($post) {
        $form = $this->getForm('menuitemEdit');
        return $this->_saveMenuitem($form, $post);
    }

    public function _saveMenuitem($form, $info, $default = array()) {
      
        if (!$form->isValid($info)) {
            return false;
        }
       

        $data = $form->getValues();

        //apply any defaults
        foreach ($default as $col => $value) {
            $data[$col] = $value;
        }

        $menuitem = array_key_exists('menu_item_id', $data) ? $this->getResource('Menuitem')->getItemById($data['menu_item_id']) : null;
       
        return $this->getResource('Menuitem')->saveRow($data, $menuitem);
    }

     public function getLastPosition($menuid){
         return $this->getResource('Menuitem')->getLastPosition($menuid);
     }
     
     public function moveUp($itemid){
         return $this->getResource('Menuitem')->moveUp($itemid);
     }
     
      public function moveDown($itemid){
         return $this->getResource('Menuitem')->moveDown($itemid);
     }
}
