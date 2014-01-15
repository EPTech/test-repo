<?php

class System_RolesController extends My_Crud_Controller {

    protected $_itemName = "System roles";

    protected function _getModelResource() {
        return new System_Resource_Roles();
    }

    protected function _getSearchFields() {

        return array('role_id', 'role_name');
    }

    protected function _getCrudForm() {
        return new System_Form_Role_Add();
    }

    public function getCrudEditForm() {
        return new System_Form_Role_Edit();
    }

    public function enableitemsAction() {
        $post = $_POST;
        $data = array();
        $data["role_enabled"] = $_POST['dstatus'];
        foreach ($post['itemid'] as $item) {
            $data['role_id'] = $item;
            $this->_resource->updateItem($data);
        }
        $ret = array();
        $ret['status'] = 1;
        $this->_helper->json($ret);
    }

    public function disableitemsAction() {
        
    }

    public function preprocessAdd() {
        $data = array();
        $data['created'] = date("Y-m-d H:i:s");
        return $data;
    }

}

