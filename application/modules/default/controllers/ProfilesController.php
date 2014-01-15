<?php

class ProfilesController extends My_Crud_Controller {

    protected $_itemName = "Customers";

    protected function _getModelResource() {
        return new Default_Resource_Customers();
    }

    protected function _getCrudForm() {
        return new Default_Form_Customer_Add();
    }

    public function getCrudEditForm() {
        return new Default_Form_Customer_Edit();
    }

    protected function _getSearchFields() {
        return array('mvregno', 'lastname', 'firstname', 'states.state', 'lga');
    }

    public function contextInitializer() {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('index', 'json')
                ->addActionContext('vehicles', 'json')
                ->setAutoJsonSerialization(false)
                ->initContext();
    }

    public function viewAction() {
        $id = $this->_getParam('id');
        $vehicle = null;
        if ($id === null) {
            $this->_flashMessenger->addMessage(array('alert' => "No profile has been selected"));
            //$this->_helper->redirector('index');
            die($id);
        }

        $this->view->customer = $this->_resource->getCustomerById($id);
        $this->view->id = $id;
        $vehicleResource = new Default_Resource_Vehicles();
        $vehicleResource->getChargesByCustomer($id);
        $form = new Default_Form_Mvregfund_Add();

        $request = $this->getRequest();

        if ($vehicle !== null) {
            $action = "/" . $request->getModuleName() . '/' . $request->getControllerName() . '/fundmvcard' . '/id/' . $vehicle->mvreg_no;
            $form->setAction($action);
            $form->getElement('mvregno')->setValue($vehicle->mvreg_no);
        }

        $this->view->mvcardFundForm = $form;
//        $form = $this->_getCrudForm();
//        $form->setAction('');
//        $request = $this->getRequest();
//        if ($request->isPost()) {
//            $post = $request->getPost();
//            $state = $post['state'];
//            $lgaResource = new Default_Resource_Lgas();
//            $lgas = $lgaResource->getLgaByState($state);
//            if ($lgas->count()) {
//                $lgaList = array();
//                $lgaList[""] = "Select";
//                foreach ($lgas as $lga) {
//                    $lgaList[$lga->id] = $lga->name;
//                }
//               // var_dump($lgaList);
//                $form->getElement('lga')->setMultiOptions($lgaList); 
//               
//            }
//            if ($form->isValid($post)) {
//                $post = $form->getValues();
//
//                $data = array();
//
//                foreach ($post as $key => $value) {
//                    $data[$key] = $value;
//                }
//
//                try {
//                    $this->_resource->saveItem($data);
//                    $this->_flashMessenger->addMessage(array('alert alert-success' => $this->_messages['add']));
//                } catch (Exception $ex) {
//                    $this->_flashMessenger->addMessage(array('alert alert-error' => "An error occured while creating the item  <br/> " . $ex->getMessage()));
//                }
//              //  $this->_helper->redirector('profiles');
//            } 
//        }
//        $this->view->additem = $form;
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
                    $this->_flashMessenger->addMessage(array('alert alert-success' => "customer's information successfully updated"));
                } catch (Exception $ex) {
                    $this->_flashMessenger->addMessage(array('alert alert-error' => "An error occured while updating the " . $this->_itemName . " information <br/> " . $ex->getMessage()));
                }
                $this->_helper->redirector('view', 'profiles', 'default', array('id' => $itemId));
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
            $defaultCountryId = 157;
            $form->getElement('country')->setValue($defaultCountryId);

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

    public function addAction() {
        $form = $this->_getCrudForm();
        $id = $this->_getParam('id');
        $request = $this->getRequest();
        $action = "/" . $request->getModuleName() . '/' . $request->getControllerName() . '/' . $request->getActionName() . '/id/' . $id;
        //$action = "/default/customer/register";
        $this->view->customerExist = true;
        if ($id === null) {
            $mvcardResource = new Default_Resource_Mvcard();
            //add mvregno 
            $this->view->customerExist = false;
            $unUsedCard = $mvcardResource->getUnusedCard();
            $card = 0;
            if ($unUsedCard !== null) {
                $card = $unUsedCard->cardno;
            }
            if (!$request->isPost()) {
                $form->removeElement('sub');

                $form->addElement('text', 'mvregno', array(
                    'label' => 'Mvregno',
                    'value' => $card,
                //    'readonly' => 'readonly',
                    'validators' => array(
                        array('Db_RecordExists', true, array('table' => 'mvcards', 'field' => 'cardno', 'messages' => 'Mvregno does not exist'))
                    )
                ));

                $form->addElement('submit', 'sub', array(
                    'class' => 'fan-sub btn btn-primary ',
                    'required' => false,
                    'ignore' => true,
                    'label' => 'Save',
                    'decorators' => array("ViewHelper"
                        )));
            }
        }
        $form->setAction($action);
        if ($request->isPost()) {
         $post = $request->getPost();
            //var_dump($post); die;
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
                $post = $request->getPost(); // $form->getValues();
               
                $mvregisterResource = new Default_Resource_Mvregister();
                $data = array();

                foreach ($post as $key => $value) {
                    $data[$key] = $value;
                }

                unset($data['sub']);
                try {
                    //activate card
                   //  var_dump($post2); die;
                    $mvregisterResource->activateCard($post);
                    //die;
                    $card = array();
                    $card['cardno'] = $post['mvregno'];
                    $card['status'] = 1;
                    //update card bank information
                    $mvcardResource->updatecard($card);
                    //save customer information
                    $this->_resource->saveItem($data);
                    //redirect
                    $this->_flashMessenger->addMessage(array('alert alert-success' => "Customer created. Mvregno " . $data['mvregno'] . " attached to customer"));
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

    public function statesAction() {
        $lgaResource = new Default_Resource_Lgas();

        $state = $_POST['state'];
        $lgas = $lgaResource->getLgaByState($state);
        $return = array();
        if ($lgas->count()) {
            $return['status'] = 1;
            $return['ret'] = $lgas->toArray();
        } else {
            $return['status'] = 0;
            $return['ret'] = $lgas;
        }

        $this->_helper->json($return);
    }

    public function vehiclesAction() {

        // action body
        $id = $this->_getParam('id');

        if ($id === null) {
            $this->_helper->redirector('profiles');
        }

        $this->view->id = $id;
        $vehicleResource = new Default_Resource_Vehicles();

        $vehicleSelect = $vehicleResource->getVehicleByOwner($id);

        $fields = array('manufacturer', 'vehicle_model', 'vehicle_engine_num', 'vehicle_color', 'vehicle_type');
        // var_dump($fields); exit;
        $data = new My_DataTable($vehicleSelect, $fields, $_GET);
        $this->view->items = $data;
    }

    public function cardbalanceAction() {
        $id = $this->_getParam("id");
        if ($id === null) {
            $this->_helper - redirector("index");
        }
        $mvregisterResource = new Default_Resource_Mvregister();
        $this->view->customer = $this->_resource->getCustomerById($id);
        $this->view->mvregcard = $mvregisterResource->getMvregisterByMvregno($id);
    }

    public function scardbalanceAction() {
        $id = $this->_getParam("id");
        if ($id === null) {
            $this->_helper - redirector("index");
        }
        $this->_helper->layout()->disableLayout();
        $scardregisterResource = new Default_Resource_Scardregister();
        $this->view->mlo = $scardregisterResource->getScardregisterByScardno($id);
    }

    public function fundAction() {

        $id = $this->_getParam('id');
        $form = new Default_Form_Mvregfund_Add();
        $form->getElement('scardno')->setAttrib('id', 'checkscardbalance');
        $form->getElement('sub')->setAttrib('id', 'fund-sub');
        $request = $this->getRequest();
        $action = "/" . $request->getModuleName() . '/' . $request->getControllerName() . '/' . $request->getActionName() . '/id/' . $id;
        $form->setAction($action);
        if ($request->isPost()) {
            $post = $request->getPost();
            if ($form->isValid($post)) {
                $post = $form->getValues();

                $data = array();

                foreach ($post as $key => $value) {
                    $data[$key] = $value;
                }

                //begin transactions
                $db = Zend_Db_Table::getDefaultAdapter();

                try {
                    $db->beginTransaction();
                    $action_id = 5;

                    $mvregActionResource = new Default_Resource_Mvregactions();
                    $mvregAction = $mvregActionResource->getMvregactionById($action_id);
                    $actionText = "";
                    if ($mvregAction !== null) {
                        $actionText = $mvregAction->action_name;
                    }
                    //log in payment audit table
                    $date = date("Y-m-d");
                    $mvreg = $post['mvregno'];
                    $Amount = $post['fund_amount'];
                    $superno = $post['scardno'];
                    $fullname = 'SUPERCARD';
                    $operator = Zend_Auth::getInstance()->getIdentity()->mlo_id; //1; //to be change when login is implemented
                    $operatorName = Zend_Auth::getInstance()->getIdentity()->mla_lastname . "" . Zend_Auth::getInstance()->getIdentity()->mla_firstname; //to be change when login is implemented
                    $staffId = My_Auth::getInstance()->getIdentity()->staff_id; //""; //to be change when login is implemented
                    // $mloID = Zend_Auth::getInstance()->getIdentity()->mlo_id; //to be change when login is implemented
                    //   $staffId = My_Auth::getInstance()->getIdentity()->staff_id;
                    $sql1 = "INSERT INTO audit_mvregister(
		audit_mvregister.`index`,
		audit_mvregister.`date`,
		audit_mvregister.mvregno,
		audit_mvregister.cardcategory,
		audit_mvregister.amount,
		audit_mvregister.action,
		audit_mvregister.tellerno,
		audit_mvregister.payee,
		audit_mvregister.operatorID,
		audit_mvregister.operator,
		audit_mvregister.action_category_id,
                audit_mvregister.StaffID)
		VALUES(NULL,'" . $date . "','" . $mvreg . "', 1, '" . $Amount . "','" . $actionText . "','" . $superno . "','" . $fullname . "','" . $operator . "','" . $operatorName . "','" . $action_id . "','" . $staffId . "')";
                    $db->query($sql1);
                    //the mvregcard balance is added to the amount being Deducted from the SuperCard
                    $mvregisterResource = new Default_Resource_Mvregister();
                    $where = $mvregisterResource->getAdapter()->quoteInto('mvregno = ?', $mvreg);
                    $mvregisterResource->update(array('creditbalance' => new Zend_Db_Expr('creditbalance + ' . $Amount)), $where);

                    //the Supercard balance is charged with amount being Deducted
                    $scardregisterResource = new Default_Resource_Scardregister();
                    $where = $scardregisterResource->getAdapter()->quoteInto('scardno = ?', $superno);
                    $scardregisterResource->update(array('creditbalance' => new Zend_Db_Expr('creditbalance - ' . $Amount)), $where);

                    //the Supercard Audit History is updated with the amount deducted from the supercard
                    $sql2 = "INSERT INTO audit_scardregister(
		audit_scardregister.`index`,
		audit_scardregister.`date`,
		audit_scardregister.scardno,
		audit_scardregister.cardcategory,
		audit_scardregister.debit_amount,
		audit_scardregister.tellerno,
		audit_scardregister.payee,
		audit_scardregister.operatorID,
		audit_scardregister.operator,
                audit_scardregister.StaffID)
		VALUES(NULL,'" . $date . "','" . $superno . "','" . '3' . "','" . $Amount . "','" . $mvreg . "','" . $fullname . "','" . $operator . "','" . $operatorName . "','" . $staffId . "')";
                    $db->query($sql2);
                    $db->commit();
                    $this->_flashMessenger->addMessage(array('alert alert-success' => "mvreg card balance updated successfully"));
                } catch (Exception $ex) {
                    $db->rollBack();
                    $this->_flashMessenger->addMessage(array('alert alert-error' => "An error occured while creating the item  <br/> " . $ex->getMessage()));
                }
                $this->_helper->redirector('view', 'profiles', 'default', array('id' => $id));
            }
        } else {
            if ($id !== null) {
                $form->getElement('mvregno')->setValue($id);
            }
        }
        $this->view->form = $form;
    }

}
