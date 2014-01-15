<?php

class System_CarriageusesController extends My_Crud_Controller {

    protected $_itemName = "Carriage usage";

    protected function _getModelResource() {
        return new System_Resource_Carriageuses();
    }

    protected function _getSearchFields() {
        return array('id', 'usage');
    }

    protected function _getCrudForm() {
        return new System_Form_Carriageusage_Add();
    }

    public function getCrudEditForm() {
        return new System_Form_Carriageusage_Edit();
    }

}

