<?php

class System_VehicleconditionsController extends My_Crud_Controller {

    protected $_itemName = "Vehicle condition";

    protected function _getModelResource() {
        return new System_Resource_Vehicleconditions();
    }

    protected function _getSearchFields() {
        return array('condition_id', 'condition_type');
    }

    protected function _getCrudForm() {
        return new System_Form_Vehiclecondition_Add();
    }

    public function getCrudEditForm() {
        return new System_Form_Vehiclecondition_Edit();
    }

}

