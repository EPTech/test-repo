<?php

class System_TitlesController extends My_Crud_Controller {

    protected $_itemName = "Title";

    protected function _getModelResource() {
        return new System_Resource_Titles();
    }

    protected function _getSearchFields() {
        return array('title_id', 'title_name');
    }

    protected function _getCrudForm() {
        return new System_Form_Titles_Add();
    }

    public function getCrudEditForm() {
        return new System_Form_Titles_Edit();
    }

}

