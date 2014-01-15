<?php

class System_ChargecyclesController extends My_Crud_Controller {

    protected $_itemName = "Charge cycles";

    protected function _getModelResource() {
        return new System_Resource_Chargecycles();
    }

    protected function _getSearchFields() {
        return array('engcat_id', 'engcat_title');
    }

    protected function _getCrudForm() {
        return new System_Form_Chargecycle_Add();
    }

    public function getCrudEditForm() {
        return new System_Form_Chargecycle_Edit();
    }

}

