<?php
class System_Form_User_Wizard extends My_Form_Wizard {

    public function init() {
        $this->addSubForms(array(
            'sharingformular' => new System_Form_User_Step_SharingFormula($this->getSessionNamespace()),
            'sharingformular2' => new System_Form_User_Step_SharingFormula2($this->getSessionNamespace())
        ));
    }

}

?>