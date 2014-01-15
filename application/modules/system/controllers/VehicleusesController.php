<?php

class System_VehicleusesController extends My_Crud_Controller {

    protected $_itemName = "Vehicle use";

    protected function _getModelResource() {
        return new System_Resource_Vehicleuse();
    }

    protected function _getSearchFields() {
        return array('vehicle_use_id', 'vehicle_use');
    }

    protected function _getCrudForm() {
        return new System_Form_Vehicleuse_Add();
    }

    public function getCrudEditForm() {
        return new System_Form_Vehicleuse_Edit();
    }

}

