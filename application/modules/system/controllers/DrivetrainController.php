<?php

class System_DrivetrainController extends My_Crud_Controller {

    protected $_itemName = "Drive train";

    protected function _getModelResource() {
        return new System_Resource_Drivetrains();
    }

    protected function _getSearchFields() {
        return array('id', 'drive_train');
    }

    protected function _getCrudForm() {
        return new System_Form_Drivetrain_Add();
    }

    public function getCrudEditForm() {
        return new System_Form_Drivetrain_Edit();
    }

}

