<?php

class System_StaffController extends My_Crud_Controller {

    protected $_itemName = "Staff";

    protected function _getModelResource() {
        return new System_Resource_Staff();
    }

    protected function _getSearchFields() {
        return array('staff_username', 'surname', 'firstname','mobile','states.state', 'role_name', 'name');
    }

    protected function _getCrudForm() {
        return new System_Form_Staff_Add();
    }

    public function getCrudEditForm() {
        return new System_Form_Staff_Edit();
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

                $data = array();

                foreach ($post as $key => $value) {
                    $data[$key] = $value;
                }
                $data['salt'] = md5($this->_resource->createSalt());
                $data['staff_password'] = md5($data['password'] . $data['salt']);
              
                unset($data['password']);          
                unset($data['passwordVerify']);
                $this->_resource->saveItem($data);

                try {
                    $this->_flashMessenger->addMessage(array('alert alert-success' => "Staff created successfully"));
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
                                $data['staff_password'] = md5($data['password'] . $data['salt']);
                        }
                         unset($data['password']);
                        
                   // unset($data['dob']);
                    unset($data['passwordVerify']);
                    $this->_resource->updateItem($data);
                    $this->_flashMessenger->addMessage(array('alert alert-success' => "Staff information successfully updated"));
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

     public function itemstatusAction() {
        $post = $_POST;
        $data = array();
        $data["status"] = $_POST['dstatus'];
        foreach ($post['itemid'] as $item) {
            $data['staff_id'] = $item;
            $this->_resource->updateItem($data);
        }
        $ret = array();
        $ret['status'] = 1;
        $this->_helper->json($ret);
    }
}

