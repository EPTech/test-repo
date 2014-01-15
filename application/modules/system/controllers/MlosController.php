<?php

class System_MlosController extends My_Crud_Controller {

    protected $_itemName = "MLO";

    protected function _getModelResource() {
        return new System_Resource_Mlos();
    }

    protected function _getSearchFields() {
        return array('mlo_id', 'username', 'email', 'role_name', 'phone', 'address');
    }

    protected function _getCrudForm() {
        return new System_Form_Mlo_Add();
    }

    public function getCrudEditForm() {
        return new System_Form_Mlo_Edit();
    }

    public function mlaAction() {

        $form = new System_Form_Mla_Change();

        $id = $this->_getParam('id');



        if ($id === null) {
            $this->_helper->redirector('index');
            exit;
        }
        $form->setAction("/system/mlos/mla/id/" . $id);
        $form->getElement('mlo_id')->setValue($id);

        $mlo = $this->_resource->getMloById($id);
        $this->view->mlo = null;
        if ($mlo !== null) {
            $this->view->mlo = $mlo;
        }

        $this->view->mla = null;

        if ($mlo !== null) {
            $mlaResource = new System_Resource_Mla();
            if ($mlo->mla_id != "") {
                $mla = $mlaResource->getMlaById($mlo->mla_id);
            }
            $this->view->mla = $mla;
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = $request->getPost();
            // var_dump($post); die;
            if ($form->isValid($post))
                $post = $form->getValues();
            $post = $form->getValues();
            //  var_dump($post); die;
            $data = array();
            $data['mlo_id'] = $post['mlo_id'];
            $data['mla_id'] = $post['mla_id'];
            //  var_dump($data); die;
            try {
                $this->_resource->updateItem($data);
                $this->_flashMessenger->addMessage(array('alert alert-success' => "Mla attached successfully"));
            } catch (Exception $ex) {
                $this->_flashMessenger->addMessage(array('alert alert-error' => "An error occured while attaching the mla  <br/> " . $ex->getMessage()));
            }
            $this->_helper->redirector("index");
        }
        $this->view->form = $form;
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
                $data['salt'] = md5($this->_resource->createSalt());
                $data['password'] = md5($data['password'] . $data['salt']);


                unset($data['passwordVerify']);


                try {
                    $this->_resource->saveItem($data);
                    $this->_flashMessenger->addMessage(array('alert alert-success' => "Mlo created successfully"));
                } catch (Exception $ex) {
                    $this->_flashMessenger->addMessage(array('alert alert-error' => "An error occured while creating the item  <br/> " . $ex->getMessage()));
                }
                $this->_helper->redirector('index');
            } else {
                // return $this->render('view');
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
                    if ($data['password'] != "") {
                        $data['salt'] = md5($this->_resource->createSalt());
                        $data['password'] = md5($data['password'] . $data['salt']);
                    } else {
                        unset($data['password']);
                    }
                    // unset($data['dob']);
                    unset($data['passwordVerify']);
                    $this->_resource->updateItem($data);
                    $this->_flashMessenger->addMessage(array('alert alert-success' => "Mlo's information successfully updated"));
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

    public function viewAction() {
        $this->_helper->layout()->disableLayout();
        $id = $this->_getParam('id');
        $this->view->mlo = null;
        if ($id !== null and $id != "") {
            //  $this->_helper->redirector('index');
           $mlo = $this->_resource->getMloById($id);
            $this->view->mlo = $mlo;
        }
    }

}

