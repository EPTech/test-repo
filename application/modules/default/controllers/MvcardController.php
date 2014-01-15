<?php

class MvcardController extends My_Crud_Controller {

    protected $_itemName = "Mvreg card";

    protected function _getModelResource() {
        return new Default_Resource_Mvcard();
    }

    protected function _getCrudForm() {
        return new Default_Form_Mvcard_Add();
    }

    public function getCrudEditForm() {
        // return new Default_Form_Vehicle_Edit();
    }

    protected function _getSearchFields() {
        return array('cardno', 'batchno');
    }

    public function contextInitializer() {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('index', 'json')
                ->setAutoJsonSerialization(false)
                ->initContext();
    }

    public function generateAction() {
        $form = $this->_getCrudForm();

        $request = $this->getRequest();
        $action = "/" . $request->getModuleName() . '/' . $request->getControllerName() . '/' . $request->getActionName();
        $form->setAction($action);
        if ($request->isPost()) {
            $post = $request->getPost();
            if ($form->isValid($post)) {
                $post = $form->getValues();

                $data = array();

                foreach ($post as $key => $value) {
                    $data[$key] = $value;
                }

                $others = $this->preprocessAdd();
                if (is_array($others)) {
                    foreach ($others as $key => $value) {
                        $data[$key] = $value;
                    }
                }

                try {
                    $no_card = isset($data['no_cards']) ? $data['no_cards'] : 0;
                    $this->_resource->generate($no_card);
                    $this->_flashMessenger->addMessage(array('alert alert-success' => $no_card . " Mvreg card(s) generated successfully"));
                } catch (Exception $ex) {
                    $this->_flashMessenger->addMessage(array('alert alert-error' => "An error occured while creating the item  <br/> " . $ex->getMessage()));
                }
                $this->_helper->redirector('index'); // $this->movetoLanding();
            }
        }
        $this->view->additem = $form;
    }

    public function activateCard($post){
        $mvregisterResource = new Default_Resource_Mvregister();
        $data = array();
        $data['mvregno'] = $post['mvregno'];
        $data['cardcategory'] = 1;// $post['mvregno']
        $data['creditbalance'] = 0 ;//$post['mvregno']
        $data['active'] = 1; // $post['mvregno']
        $data['dateactivated'] = date("Y-m-d H:i:s") ; //$post['mvregno']
        $data['bearer_fname'] =  $post['firstname'];
        $data['bearer_lname'] =  $post['lastname'];
        $data['mvregno'] = $post['streetname'].' , '.$post['city'];
        $data['mobileno'] = $post['mobile'];
        $mvregisterResource->saveItem($data);
    }
}

