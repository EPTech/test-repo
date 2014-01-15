<?php

class System_EnginetypesController extends My_Crud_Controller {

    protected $_itemName = "Engine categories";

    protected function _getModelResource() {
        return new System_Resource_Enginecat();
    }

    protected function _getSearchFields() {
        return array('engcat_id', 'engcat_title');
    }

    protected function _getCrudForm() {
        return new System_Form_Enginetypes_Add();
    }

    public function getCrudEditForm() {
        return new System_Form_Enginetypes_Edit();
    }

}

