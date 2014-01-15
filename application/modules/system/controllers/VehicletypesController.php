<?php

class System_VehicletypesController extends My_Crud_Controller {

    protected $_itemName = "Vehicle type";

    protected function _getModelResource() {
        return new System_Resource_Vehicletypes();
    }

    protected function _getSearchFields() {
        return array('id', 'vehicle_type');
    }

    protected function _getCrudForm() {
        return new System_Form_Vehicletype_Add();
    }

    public function getCrudEditForm() {
        return new System_Form_Vehicletype_Edit();
    }

}

