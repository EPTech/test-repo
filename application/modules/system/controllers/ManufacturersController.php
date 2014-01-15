<?php

class System_ManufacturersController extends My_Crud_Controller {

    protected $_itemName = "Manufacturer";

    protected function _getModelResource() {
        return new System_Resource_Manufacturers();
    }

    protected function _getSearchFields() {
        return array('id', 'manufacturer');
    }

    protected function _getCrudForm() {
        return new System_Form_Manufacturer_Add();
    }

    public function getCrudEditForm() {
        return new System_Form_Manufacturer_Edit();
    }

}

