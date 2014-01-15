<?php

class System_MlaController extends My_Crud_Controller {

    protected $_itemName = "MLA";

    protected function _getModelResource() {
        return new System_Resource_Mla();
    }

    public function contextInitializer(){
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('index', 'json')
                ->addActionContext('getmla', 'json')
                ->setAutoJsonSerialization(false)
                ->initContext();
    }
    
    protected function _getSearchFields() {
        return array('mla.mla_id', 'mla_lastname', 'mla_firstname', 'role_name', 'states.state', 'name');
    }
    
    
     public function addAction() {
        $form = $this->_getCrudForm();

        $request = $this->getRequest();
        $action = "/" . $request->getModuleName() . '/' . $request->getControllerName() . '/' . $request->getActionName();
        $form->setAction($action);
        if ($request->isPost()) {
            $post = $request->getPost();
            $state = $post['state'];
            $lgaResource = new Default_Resource_Lgas();
            $lgas = $lgaResource->getLgaByState($state);
            if ($lgas->count()) {
                $lgaList = array();
                $lgaList[""] = "Select";
                foreach ($lgas as $lga) {
                    $lgaList[$lga->id] = $lga->name;
                }
                // var_dump($lgaList);
                $form->getElement('lga')->setMultiOptions($lgaList);
            }

            if ($form->isValid($post)) {
                $post = $form->getValues();
                //$form->getValues();

                $data = array();

                foreach ($post as $key => $value) {
                    $data[$key] = $value;
                }
                try {
                    $this->_resource->saveItem($data);
                    $this->_flashMessenger->addMessage(array('alert alert-success' => "Mla created successfully"));
                } catch (Exception $ex) {
                    $this->_flashMessenger->addMessage(array('alert alert-error' => "An error occured while creating the item  <br/> " . $ex->getMessage()));
                }
                $this->_helper->redirector('index');
            }
        }
        
        $this->view->additem = $form;
    }
    
    
       public function editAction() {

        $itemId = $this->_getParam('id');

        if ($itemId === null) {
            $this->_flashMessenger->addMessage(array('alert alert-error' => "Please select an item to edit"));
            $this->_helper->redirector('index');
        }

        $request = $this->getRequest();
        if ($this->getCrudEditForm() == "") {
            $form = $this->_getCrudForm();
        } else {
            $form = $this->getCrudEditForm();
        }

        $action = "/" . $request->getModuleName() . '/' . $request->getControllerName() . '/' . $request->getActionName() . '/id/' . $itemId;
        $form->setAction($action);


        //add the primary key as element
        $form->addElement('hidden', $this->_resource->getPrimaryKey());



        if ($request->isPost()) {
            $post = $request->getPost();
            // var_dump($post);
            $state = $post['state'];
            $lgaResource = new Default_Resource_Lgas();
            $lgas = $lgaResource->getLgaByState($state);
            if ($lgas->count()) {
                $lgaList = array();
                $lgaList[""] = "Select";
                foreach ($lgas as $lga) {
                    $lgaList[$lga->id] = $lga->name;
                }
                //var_dump($lgaList);
                $form->getElement('lga')->setMultiOptions($lgaList);
            }
            if ($form->isValid($post)) {
                $post = $form->getValues();

                $data = array();

                foreach ($post as $key => $value) {
                    $data[$key] = $value;
                }

                try {
                  $this->_resource->updateItem($data);
                    $this->_flashMessenger->addMessage(array('alert alert-success' => "Mla's information successfully updated"));
                } catch (Exception $ex) {
                    $this->_flashMessenger->addMessage(array('alert alert-error' => "An error occured while updating the " . $this->_itemName . " information <br/> " . $ex->getMessage()));
                }
                $this->_helper->redirector('index');
            } else {
                // exit;
                $form->getElement('lga')->setValue($post['lga']);
                $form->getElement('state')->setValue($post['state']);
            }
        }

        $item = $this->_resource->getItemById($itemId);

        if ($item !== null and !$request->isPost()) {
            $state = $item->state;
            $form->populate($item->toArray());

            //populate local govt
            $lgaResource = new Default_Resource_Lgas();
            $lgas = $lgaResource->getLgaByState($state);
            if ($lgas->count()) {
                $lgaList = array();
                $lgaList[""] = "Select";
                foreach ($lgas as $lga) {
                    $lgaList[$lga->id] = $lga->name;
                }
                //var_dump($lgaList);
                $form->getElement('lga')->setMultiOptions($lgaList);
            }
        }

        $this->view->edititem = $form;
    }

    protected function _getCrudForm() {
        return new System_Form_Mla_Add();
    }

    public function getCrudEditForm() {
        return new System_Form_Mla_Edit();
    }

    public function viewAction(){
        $this->_helper->layout()->disableLayout();
        $id = $this->_getParam('id');
        $this->view->mla = null;
        if($id !== null){
          //  $this->_helper->redirector('index');
             $mla = $this->_resource->getMlaById($id);
             $this->view->mla = $mla;
        }
        
    }
    public function getmlaAction() {
        $term = $_GET['term']; //$this->_getParam('autocomplete');

        $terms = $this->_resource->getMlaByTerm($term);
        //var_dump($terms); die;
        $return = $terms->toArray();
        $this->_helper->json($return);
    }

}

