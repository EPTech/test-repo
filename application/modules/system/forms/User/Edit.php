<?php

class System_Form_User_Edit extends System_Form_User_Base {

    public function init() {
        
         //prefill admin menu from database
        $admin_model = new System_Model_Adminpage();
        $adminprofiles = $admin_model-> getaclAdminprofiles();
        $profileopt = array();
        foreach($adminprofiles as  $adminprofile){
            $profileopt[$adminprofile->role_id] = $adminprofile->role_name;
        }
        
        $this->addElement('select', 'role_id', array(
            'label' => 'Role',
             'required' => true,
            'multiOptions' =>  $profileopt,
        ));

       $this->addElement('select', 'status', array(
            'label' => 'Account Status',
             'required' => true,
            'multiOptions' => array('' => 'Select', 'active' => 'active', 'inactive' => 'inactive', 'deactivated' => 'deactivated'),
        ));


        parent::init();


        //specialize form
       $this->getElement('password')->setRequired(false);
        $this->getElement('passwordVerify')->setRequired(false);
        $this->getElement('submit')->setLabel('Save User');
    }

}

?>