<?php

class VehiclesController extends My_Crud_Controller {

    protected $_itemName = "Vehicles";

    protected function _getModelResource() {
        return new Default_Resource_Vehicles();
    }

    protected function _getCrudForm() {
        return new Default_Form_Vehicle_Base();
    }

    public function getCrudEditForm() {
        return new Default_Form_Vehicle_Edit();
    }

    protected function _getSearchFields() {
        return array('mvregno', 'lastname', 'firstname', 'state', 'lga');
    }

    public function modelsAction() {
        //  sleep(2);
        $make = $_POST['make'];
        $vModelsTbl = new Zend_Db_Table('vehicle_models');
        $vModelSelect = $vModelsTbl->select()->from('vehicle_models', array('id', 'year', 'model'));
        $vModelSelect->where("manufacturer_id = ?", $make);
        $vModelSelect->order('model');
        $vModelSelect->order('year');
        // die($vModelSelect);
        $vModels = $vModelsTbl->fetchAll($vModelSelect);

        $return = array();
        if ($vModels->count()) {
            $return['status'] = 1;
            $models = array();
            foreach ($vModels as $model) {
                $models[] = array('id' => $model->id, 'vmake' => $model->model . " , " . $model->year);
            }
            $return['ret'] = $models;
        } else {
            $return['status'] = 0;
            $return['ret'] = $vModels;
        }

        $this->_helper->json($return);
    }

    public function previousownerAction() {
        $form = new Default_Form_Ownership_Add();
        $this->view->post = 0;
        $id = $this->_getParam('id');
        if ($id === null) {
            $this->_helper->redirector('index');
        }
        $request = $this->getRequest();
        $action = "/" . $request->getModuleName() . '/' . $request->getControllerName() . '/' . $request->getActionName() . '/id/' . $id;
        $form->setAction($action);
        if ($request->isPost()) {
            $this->view->post = 1;
            $post = $request->getPost();

            if ($form->isValid($post)) {
                $post = $form->getValues();

                try {
                    $pOwnerResource = new Default_Resource_Previousowner();
                    $prevOwner = array();
                    $prevOwner["mvregno"] = $id;
                    $prevOwner['title_id'] = $copost["title_id"];
                    $prevOwner['surname'] = $copost["surname"];
                    $prevOwner['oname'] = $copost["oname"];
                    $prevOwner['address1'] = $copost["address1"];
                    $prevOwner['email'] = $copost["email"];
                    $prevOwner['gender'] = $copost["gender"];
                    $prevOwner['phone'] = $copost["phone"];
                    $prevOwner['city'] = $copost["city"];
                    $prevOwner['state'] = $copost["state"];
                    $prevOwner['lga'] = $copost["lga"];
                    $prevOwner['country'] = $copost["country"];
                    $prevOwner['payment_id'] = $insertedPaymentId;
                    $prevOwner['process_date'] = date("Y-m-d");
                    $prevOwner['process_time'] = date("H:i:s");
                    $pOwnerResource->saveItem($prevOwner);

                    $this->_flashMessenger->addMessage(array('alert alert-success' => "Previous owner details created successfully"));
                } catch (Exception $ex) {
                    $this->_flashMessenger->addMessage(array('alert alert-error' => "An error occured while creating the previous owner details <br/> " . $ex->getMessage()));
                }
                $this->_helper->redirector('view', 'profiles', 'default', array('id' => $id)); // $this->movetoLanding();
            }
        }
        $this->view->additem = $form;
    }

    public function resetFormValidation($form) {
        if ($form instanceof Zend_Form) {
            foreach ($form->getElements() as $element) {
                if ($element->getName() == 'sub') {
                    continue;
                }
                $element->setRequired(false);
            }
        }
    }

    public function setFormRequiredFields($form, $fields) {
        if ($form instanceof Zend_Form and is_array($fields)) {
            foreach ($fields as $field) {
                $form->getElement($field)->setRequired(true);
            }
        }
    }

    public function addAction() {
        $form = $this->_getCrudForm();
        $this->view->post = 0;
        $id = $this->_getParam('id');
        if ($id === null) {
            $this->_helper->redirector('index');
        }
        $request = $this->getRequest();
        $action = "/" . $request->getModuleName() . '/' . $request->getControllerName() . '/' . $request->getActionName() . '/id/' . $id;
        $form->setAction($action);
        if ($request->isPost()) {
            $this->view->post = 1;
            $postValues = $request->getPost();
            $post = $postValues; // $request->getPost();
            $vModelId = $post['vehicle_model'];
            $vModelResource = new Default_Resource_Vehiclemodel();
            $vModel = $vModelResource->getVmodelById($vModelId);
            if ($vModel !== null) {
                $vModels = $vModelResource->getVmodelByMake($vModel->make);
                if ($vModels->count()) {

                    $models = array();
                    $models[""] = "Select";
                    foreach ($vModels as $model) {
                        $models[$model->id] = " Model : " . $model->model . " , " . $model->year;
                    }
                    // var_dump($models);
                    $form->getSubForm('vehicle')->getElement('vehicle_model')->setMultiOptions($models);
                }
            }

            $vehicleForm = $form->getSubForm('vehicle');
            $pownerForm = $form->getSubForm('powner');

            //check if previous ownerform is empty
            $pOwnerEmpty = true;
            foreach ($pownerForm->getElements() as $element) {
                $key = $element->getName();
                if ($key == 'sub') {
                    continue;
                }
                if ($post[$key] != '') {
                    $pOwnerEmpty = false;
                    break;
                }
            }

            if ($pOwnerEmpty) {
                $this->resetFormValidation($pownerForm);
            }

            //preprocess lga
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
                $form->getSubForm('powner')->getElement('lga')->setMultiOptions($lgaList);
            }


            if ($form->isValid($post)) {
                $post = $vehicleForm->getValues();

                $data = array();

                foreach ($post as $key => $value) {
                    $data[$key] = $value;
                }
                $vModelId = $data['vehicle_model'];

                //$vModel = $vModelResource->getVmodelById($vModelId);
                try {
                    $vehvin = $this->_resource->getVin(12);
                    $data['mvreg_no'] = $id;
                    $data['vehicle_vin'] = $vehvin;
                    $data['created'] = date('Y-m-d H:i:s');
                    $data['vehicle_model_id'] = $vModelId;
                    $mloID = Zend_Auth::getInstance()->getIdentity()->mlo_id; //to be change when login is implemented
                    $staffId = My_Auth::getInstance()->getIdentity()->staff_id; //to be change when login is implemented
                    $data['mlo'] = $mloID;
                    $data['staff'] = $staffId;


                    if ($vModel !== null) {
                        $data['vehicle_model'] = $vModel->model;
                        $data['model_year'] = $vModel->year;
                        $data['manufacturer_id'] = $vModel->manufacturer_id;
                    }


                    //then save vehicle form and redirect
                    $this->_resource->saveItem($data);
                    if (!$pOwnerEmpty) {
                        $copost = $form->getSubForm('powner')->getValues();
                        $pOwnerResource = new Default_Resource_Previousowner();
                        $prevOwner = array();
                        $prevOwner["mvregno"] = $id;
                        $prevOwner['title_id'] = $copost["title_id"];
                        $prevOwner['surname'] = $copost["surname"];
                        $prevOwner['oname'] = $copost["oname"];
                        $prevOwner['address1'] = $copost["address1"];
                        $prevOwner['email'] = $copost["email"];
                        $prevOwner['gender'] = $copost["gender"];
                        $prevOwner['phone'] = $copost["phone"];
                        $prevOwner['city'] = $copost["city"];
                        $prevOwner['state'] = $copost["state"];
                        $prevOwner['lga'] = $copost["lga"];
                        $prevOwner['vehvin'] = $vehvin;
                        $prevOwner['country'] = $copost["country"];
                        $prevOwner['payment_id'] = $insertedPaymentId;
                        $prevOwner['process_date'] = date("Y-m-d");
                        $prevOwner['process_time'] = date("H:i:s");
                        $pOwnerResource->saveItem($prevOwner);
                    }

                    $this->_flashMessenger->addMessage(array('alert alert-success' => "Vehicle created successfully"));
                    $this->_helper->redirector('view', 'profiles', 'default', array('id' => $id)); // $this->movetoLanding();
                } catch (Exception $ex) {
                    $this->_flashMessenger->addMessage(array('alert alert-error' => "An error occured while creating the vehicle  <br/> " . $ex->getMessage()));
                }
                $this->_helper->redirector('view', 'profiles', 'default', array('id' => $id)); // $this->movetoLanding();
            }
        }


        $fields = array('title_id', 'oname', 'surname', 'gender', 'phone', 'address1', 'city', 'country', 'state', 'lga');
        $this->setFormRequiredFields($pownerForm, $fields);
        $this->view->additem = $form;
    }

    public function movetoLanding($parameter) {
        $id = $parameter['id'];
        $this->_helper->redirector('index', 'vehicles', 'default', array('id' => $id));
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

    public function viewAction() {
        // die('ojimss');
        $id = $this->_getParam('vid');
        if ($id === null) {
            $this->_helper->redirector('profiles');
        }

        $this->view->id = $id;
        $vehicleResource = new Default_Resource_Vehicles();
        $vehicle = $vehicleResource->getVehicleById($id);
        $this->view->vehicle = $vehicle;


        $form = new Default_Form_Mvregfund_Add();

        $request = $this->getRequest();

        if ($vehicle !== null) {
            $action = "/" . $request->getModuleName() . '/' . $request->getControllerName() . '/fundmvcard' . '/id/' . $vehicle->mvreg_no;
            $form->setAction($action);
            $form->getElement('mvregno')->setValue($vehicle->mvreg_no);
        }

        $this->view->mvcardFundForm = $form;
    }

    public function fundmvAction() {
        $form = new Default_Form_Mvregfund_Add();
        $request = $this->getRequest();
        $post = $request->getPost();

        if (isset($post['formid'])) {
            $form->setAttrib('id', $post['formid']);
            $form->getElement('sub')->setAttrib("id", "mvfundpcharges");
            $form->addElement('hidden', 'formid', array(
                'value' => 'mvfundpchargesForm'
            ));
        }

        $this->view->form = $form;

        if (!$form->isValid($post)) {
            $form = $this->view->render('vehicles/fundmv.phtml');

            $retArr = array('status' => 0, 'form' => $form);
            // return $this->render("index");
        } else {
            $form = $this->view->render('vehicles/fundmv.phtml');
            //begin processing funding of mvreg card
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
                $operatorName = Zend_Auth::getInstance()->getIdentity()->mlo_name; //lastname . "" . Zend_Auth::getInstance()->getIdentity()->mla_firstname; //to be change when login is implemented
                $staffId = My_Auth::getInstance()->getIdentity()->staff_id; //""; //to be change when login is implemented


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
            $mvcardb = $mvregisterResource->getMvregisterByMvregno($mvreg);
            //end funding of mvreg card
            $retArr = array('status' => 1, 'rowid' => $id, 'form' => $form, 'creditbalance' => number_format($mvcardb->creditbalance, 2));
        }


        $this->_helper->json($retArr);
    }

    public function renewAction() {

        //calculate total cost
        $post = $this->_getParam('itemid');


        $request = $this->getRequest();
        $postData = $request->getPost();
        $ret = array();
        $ret['post'] = $postData;
        $totalDocuments = 0;
        $post = $postData['itemid'];
        $vehicleResource = new Default_Resource_Vehicles();

        $csettings = new Default_Resource_Chargesettings();
        $cpaymentResource = new Default_Resource_Chargepayments();
        $chargesResource = new Default_Resource_Charges();
        $failedCharge = false;
        $total = 0;
        $vehicle = $vehicleResource->getVehicleById($postData['vehicle_id']);
        $extraInfo = "";
        foreach ($post as $item) {
            $split = explode('P', $item);
            $vehicle_id = $split[0];
            $charge_id = $split[1];
            //$vehicle = $vehicleResource->getVehicleById($vehicle_id);
            if ($vehicle !== null) {
                $charge = $csettings->getChargeByVehicle($charge_id, $vehicle->engcat_id);
                if ($charge !== null) {
                    $total += $charge->charge_amount;
                } else {
                    $failedCharge = true;
                }
            }
        }

        // echo "total is ".$total; //die; 
        //get mvreg card balance
        $mvbalanceSucceded = false;
        if ($vehicle !== null) {

            $mvregisterResource = new Default_Resource_Mvregister();
            $mvcard = $mvregisterResource->getMvregisterByMvregno($vehicle->mvreg_no);
            if ($mvcard !== null) {
                // var_dump($mvcard); die;
                $mvbalanceSucceded = true;
                $ret['mvregno'] = $vehicle->mvreg_no;
                $ret['creditbalance'] = number_format($mvcard->creditbalance, 2);
            }
        }

        if ($failedCharge) {
            $ret['status'] = 0;
            $ret['message'] = "An error occured while making the request. Please contact the administrator ";
        } else {

            if ($mvbalanceSucceded) {

                if ($mvcard->creditbalance >= $total) {
                    //success
                    //  echo "total is ".$total."credit on card is ".$ret['creditbalance']; die;
                    $mvregisterResource = new Default_Resource_Mvregister();
                    $db = Zend_Db_Table::getDefaultAdapter();
                    foreach ($post as $item) {
                        $split = explode('P', $item);
                        $vehicle_id = $split[0];
                        $charge_id = $split[1];

                        if ($vehicle !== null) {
                            $chargeSetting = $csettings->getChargeByVehicle($charge_id, $vehicle->engcat_id);
                            if ($chargeSetting !== null) {
                                $charges = $chargesResource->getChargeById($charge_id);
                                if ($charges !== null) {
                                    if ($charge_id == 8) {
                                        //check if previous owner information exist 
                                        $prevOwnerResource = new Default_Resource_Previousowner();
                                        $prevOwnerInf = $prevOwnerResource->getPreviousOwnerByVin($vehicle->vehicle_vin);
                                        if ($prevOwnerInf === null) {
                                            $extraInfo .= " Change of ownership unprocessed. Previous owner information does not exist for vehicle vin " . $vehicle->vehicle_vin . " ";
                                            continue;
                                        }
                                    }

                                    $data = array();
                                    $startingDate = date("Y-m-d");
                                    $lastPayment = $cpaymentResource->getLastPayment($charge_id, $vehicle->mvreg_no);

                                    if ($lastPayment !== null) {
                                        if (strtotime($lastPayment->last) > strtotime($startingDate)) {
                                            $startingDate = $lastPayment->last;
                                        }
                                    }

                                    $expiryDate = date('Y-m-d', strtotime($startingDate . $charges->charge_cycle_addition_string));
                                    $data['payment_expiry'] = $expiryDate;
                                    $data['mvreg_no'] = $vehicle->mvreg_no;
                                    $data['charge_id'] = $charge_id;
                                    $data['payment_amount'] = $chargeSetting->charge_amount;
                                    $data['payment_date'] = $startingDate;
                                    $data['entity_type_id'] = $vehicle->vehicle_id;
                                    // $data['operator'] = 1; // to be changed when login is implemented
                                    $data['mlo_operator'] = Zend_Auth::getInstance()->getIdentity()->mlo_id; //to be change when login is implemented
                                    $data['staff_operator'] = My_Auth::getInstance()->getIdentity()->staff_id; //to be change when login is implemented

                                    $data['payment_time'] = date("H:i:s");
                                    $cpaymentResource->saveItem($data);

                                    $totalDocuments +=1;
                                    //begin update audit mvregister
                                    $actionText = "";

                                    //log in payment audit table
                                    $date = date("Y-m-d");
                                    $actionText = $charges->charge_title;
                                    $mvreg = $vehicle->mvreg_no;
                                    $Amount = $chargeSetting->charge_amount;
                                    $action_id = $charges->charge_id;
                                    $operatorID = Zend_Auth::getInstance()->getIdentity()->mlo_id; //to be change when login is implemented
                                    $operatorName = Zend_Auth::getInstance()->getIdentity()->mla_lastname . "" . Zend_Auth::getInstance()->getIdentity()->mla_firstname; //to be change when login is implemented
                                    $staffId = My_Auth::getInstance()->getIdentity()->staff_id; //to be change when login is implemented

                                    $mvregActionResource = new Default_Resource_Mvregactions();
                                    $mvregAction = $mvregActionResource->getMvregactionById($action_id);
                                    $actionText = "";
                                    if ($mvregAction !== null) {
                                        $actionText = $mvregAction->action_name;
                                    }
                                    $sql1 = "INSERT INTO audit_mvregister(
		audit_mvregister.`index`,
		audit_mvregister.`date`,
		audit_mvregister.mvregno,
		audit_mvregister.cardcategory,
		audit_mvregister.debit_amount,
		audit_mvregister.action,
                audit_mvregister.vehvin,
		audit_mvregister.operatorID,
		audit_mvregister.operator,
		audit_mvregister.action_category_id,
                audit_mvregister.StaffID)
		VALUES(NULL,'" . $date . "','" . $mvreg . "', 1, '" . $Amount . "','" . $actionText . "','" . $vehicle->vehicle_id . "','" . $operatorID . "','" . $operatorName . "','" . $action_id . "','" . $staffId . "')";
                                    $db->query($sql1);
                                    //the mvregcard balance is added to the amount being Deducted from the SuperCard

                                    $where = $mvregisterResource->getAdapter()->quoteInto('mvregno = ?', $mvreg);
                                    $mvregisterResource->update(array('creditbalance' => new Zend_Db_Expr('creditbalance - ' . $Amount)), $where);

                                    //end update mvregister
                                    //begin update mvregister
                                    //end update mvregister
                                }
                            }
                        }
                    }

                    if ($vehicle !== null) {
                        $mvreg = $vehicle->mvreg_no;
                        $mvcardb = $mvregisterResource->getMvregisterByMvregno($mvreg);
                        if ($mvcardb !== null) {
                            $ret['creditbalance'] = $mvcardb->creditbalance;
                        }
                    }
                    $ret['status'] = 1;
                    $ret['message'] = "request was successfully executed. " . $extraInfo;
                    $ret['no_document'] = $totalDocuments;
                } else {
                    $ret['status'] = 0;
                    $ret['message'] = "Insufficient mvreg card balance";
                    $ret['no_document'] = $totalDocuments;
                }
            }
        }

        $this->_helper->json($ret);
    }

    public function renewmultipleAction() {

        //calculate total cost
        // var_dump($listData);
        // var_dump($postData);
        // die;
        //$ownershipForm = new Default_Form_Ownership_Add();
        //$this->view->oform = $ownershipForm;


        $request = $this->getRequest();
        $postData = $request->getPost();

        $ret = array();
        $ret['post'] = $postData;
        $extraInfo = "";
        $mvregno = $postData['mvregno'];
        //  echo "i am ".$mvregno;
        //  var_dump($postData); die;
        $post = $postData['itemid'];
        $vehicleResource = new Default_Resource_Vehicles();

        $csettings = new Default_Resource_Chargesettings();
        $cpaymentResource = new Default_Resource_Chargepayments();
        $chargesResource = new Default_Resource_Charges();
        $failedCharge = false;
        $total = 0;
        $totalDocuments = 0;
        //   $vehicle = $vehicleResource->getVehicleById($_POST['vehicle_id']);

        foreach ($post as $item) {
            $split = explode('P', $item);
            $vehicle_id = $split[0];
            $charge_id = $split[1];
            $vehicle = $vehicleResource->getVehicleById($vehicle_id);
            if ($vehicle !== null) {
                $charge = $csettings->getChargeByVehicle($charge_id, $vehicle->engcat_id);
                if ($charge !== null) {
                    $total += $charge->charge_amount;
                } else {
                    $failedCharge = true;
                }
            }
        }

        //  echo "total is ".$total; die; 
        //get mvreg card balance
        $mvbalanceSucceded = false;
        // if ($vehicle !== null) {

        $mvregisterResource = new Default_Resource_Mvregister();
        $mvcard = $mvregisterResource->getMvregisterByMvregno($mvregno);
        // var_dump($mvcard); die;
        if ($mvcard !== null) {
            //var_dump($mvcard); die;
            $mvbalanceSucceded = true;
            $ret['mvregno'] = $vehicle->mvreg_no;
            $ret['creditbalance'] = number_format($mvcard->creditbalance, 2);
        }
        // }

        if ($failedCharge) {
            $ret['status'] = 0;
            $ret['message'] = "An error occured while making the request. Please contact the administrator ";
        } else {

            if ($mvbalanceSucceded) {
//echo "i am balance ".$ret['creditbalance']." total ".$total; die;
                if ($mvcard->creditbalance >= $total) {
                    //success
                    //  echo "total is ".$total."credit on card is ".$ret['creditbalance']; die;
                    $mvregisterResource = new Default_Resource_Mvregister();
                    $db = Zend_Db_Table::getDefaultAdapter();
                    foreach ($post as $item) {
                        $split = explode('P', $item);
                        $vehicle_id = $split[0];
                        $charge_id = $split[1];
                        $vehicle = $vehicleResource->getVehicleById($vehicle_id);
                        if ($vehicle !== null) {
                            $chargeSetting = $csettings->getChargeByVehicle($charge_id, $vehicle->engcat_id);
                            if ($chargeSetting !== null) {
                                $charges = $chargesResource->getChargeById($charge_id);
                                if ($charges !== null) {

                                    if ($charge_id == 8) {
                                        //check if previous owner information exist 
                                        $prevOwnerResource = new Default_Resource_Previousowner();
                                        $prevOwnerInf = $prevOwnerResource->getPreviousOwnerByVin($vehicle->vehicle_vin);
                                        if ($prevOwnerInf === null) {
                                            $extraInfo .= " Change of ownership unprocessed. Previous owner information does not exist for vehicle vin " . $vehicle->vehicle_vin . " ";
                                            //  echo "vehicle id is ".$vehicle->vehicle_id;
                                            //  die;
                                            continue;
                                        }
                                    }
                                    $data = array();

                                    $lastPayment = $cpaymentResource->getLastPayment($charge_id, $vehicle->mvreg_no);
                                    $startingDate = date("Y-m-d");
                                    if ($lastPayment !== null) {
                                        if (strtotime($lastPayment->last) > strtotime($startingDate)) {
                                            $startingDate = $lastPayment->last;
                                        }
                                    }

                                    $expiryDate = date('Y-m-d', strtotime($startingDate . $charges->charge_cycle_addition_string));
                                    $data['payment_expiry'] = $expiryDate;
                                    $data['transaction_date'] = date("Y-m-d");
                                    $data['mvreg_no'] = $vehicle->mvreg_no;
                                    $data['charge_id'] = $charge_id;
                                    $data['payment_amount'] = $chargeSetting->charge_amount;
                                    $data['payment_date'] = $startingDate;
                                    $data['entity_type_id'] = $vehicle->vehicle_id;
                                    //    $data['operator'] = 1; // to be changed when login is implemented
                                    $data['mlo_operator'] = Zend_Auth::getInstance()->getIdentity()->mlo_id; //to be change when login is implemented
                                    $data['staff_operator'] = My_Auth::getInstance()->getIdentity()->staff_id; //to be change when login is implemented

                                    $data['payment_time'] = date("H:i:s");
                                    $cpaymentResource->saveItem($data);
                                    $totalDocuments += 1;

                                    // save previous owner details;
                                    //begin update audit mvregister
                                    $actionText = "";

                                    //log in payment audit table
                                    $date = date("Y-m-d");
                                    $actionText = $charges->charge_title;
                                    $mvreg = $vehicle->mvreg_no;
                                    $Amount = $chargeSetting->charge_amount;
                                    $action_id = $charges->charge_id;
                                    $operatorID = Zend_Auth::getInstance()->getIdentity()->mlo_id; //to be change when login is implemented
                                    $operatorName = Zend_Auth::getInstance()->getIdentity()->mlo_name; //to be change when login is implemented
                                    $staffId = My_Auth::getInstance()->getIdentity()->staff_id; //to be change when login is implemented


                                    $sql1 = "INSERT INTO audit_mvregister(
		audit_mvregister.`index`,
		audit_mvregister.`date`,
		audit_mvregister.mvregno,
		audit_mvregister.cardcategory,
		audit_mvregister.debit_amount,
		audit_mvregister.action,
                audit_mvregister.vehvin,
		audit_mvregister.operatorID,
		audit_mvregister.operator,
		audit_mvregister.action_category_id,
                audit_mvregister.StaffID)
		VALUES(NULL,'" . $date . "','" . $mvreg . "', 1, '" . $Amount . "','" . $actionText . "','" . $vehicle->vehicle_id . "','" . $operatorID . "','" . $operatorName . "','" . $action_id . "','" . $staffId . "')";
                                    $db->query($sql1);
                                    //the mvregcard balance is added to the amount being Deducted from the SuperCard

                                    $where = $mvregisterResource->getAdapter()->quoteInto('mvregno = ?', $mvreg);
                                    $mvregisterResource->update(array('creditbalance' => new Zend_Db_Expr('creditbalance - ' . $Amount)), $where);

                                    //end update mvregister
                                    //begin update mvregister
                                    //end update mvregister
                                }
                            }
                        }
                    }
                    // echo "we are ".$mvregno; die;
                    $mvcardb = $mvregisterResource->getMvregisterByMvregno($mvregno);
                    if ($mvcardb !== null) {
                        $ret['creditbalance'] = $mvcardb->creditbalance;
                    }

                    $ret['status'] = 1;
                    $ret['message'] = "request was successfully executed. " . $extraInfo;
                    $ret['no_document'] = $totalDocuments;
                    // $ret['prevOwnerForm'] = $prevOwnersForm;
                } else {
                    $ret['status'] = 0;
                    $ret['message'] = "Insufficient mvreg card balance";
                    $ret['no_document'] = $totalDocuments;
                }
            }
        }

        $this->_helper->json($ret);
    }

    public function estimateAction() {
        $post = $this->_getParam('itemid');

        $ret = array();
        $ret['post'] = $post;
        $vehicleResource = new Default_Resource_Vehicles();
        $vehicle = $vehicleResource->getVehicleById($_POST['vehicle_id']);
        $csettings = new Default_Resource_Chargesettings();
        $failedCharge = false;
        $total = 0;
        foreach ($post as $item) {
            $split = explode('P', $item);
            $vehicle_id = $split[0];
            $charge_id = $split[1];

            if ($vehicle !== null) {

                $charge = $csettings->getChargeByVehicle($charge_id, $vehicle->engcat_id);
                if ($charge !== null) {
                    $total += $charge->charge_amount;
                } else {
                    $failedCharge = true;
                }
            }
        }

//get mvreg card balance


        if ($vehicle !== null) {
            $mvregisterResource = new Default_Resource_Mvregister();
            $mvcard = $mvregisterResource->getMvregisterByMvregno($vehicle->mvreg_no);
            if ($mvcard !== null) {
                $ret['mvregno'] = $vehicle->mvreg_no;
                $ret['creditbalance'] = number_format($mvcard->creditbalance, 2);
            }
        }

        if ($failedCharge) {
            $ret['status'] = 0;
            $ret['message'] = "An error occured while making tbe request. Please contact the administrator ";
        } else {
            $ret['status'] = 1;
            $ret['message'] = "request was successfully executed";
            $ret['total'] = number_format($total);
        }

        $this->_helper->json($ret);
    }

    public function estimatemultipleAction() {
        $post = $this->_getParam('itemid');

        $ret = array();
        $ret['post'] = $post;
        $vehicleResource = new Default_Resource_Vehicles();

        $csettings = new Default_Resource_Chargesettings();
        $failedCharge = false;
        $total = 0;
        $mvregno = $_POST['mvregno'];
        foreach ($post as $item) {
            $split = explode('P', $item);
            $vehicle_id = $split[0];
            //$mvregno = $vehicle->mvreg_no;
            $vehicle = $vehicleResource->getVehicleById($vehicle_id);

            $charge_id = $split[1];

            if ($vehicle !== null) {

                $charge = $csettings->getChargeByVehicle($charge_id, $vehicle->engcat_id);
                if ($charge !== null) {
                    $total += $charge->charge_amount;
                } else {
                    //  var_dump($charge);
                    // echo "chargeid is ".$charge_id.' vehicled cate is '.$vehicle->engcat_id; die;
                    $failedCharge = true;
                }
            }
        }

//get mvreg card balance


        $mvregisterResource = new Default_Resource_Mvregister();
        $mvcard = $mvregisterResource->getMvregisterByMvregno($mvregno);
        if ($mvcard !== null) {
            $ret['mvregno'] = $vehicle->mvreg_no;
            $ret['creditbalance'] = number_format($mvcard->creditbalance, 2);
        }


        if ($failedCharge) {
            $ret['status'] = 0;
            $ret['message'] = "An error occured while making tbe request. Please contact the administrator ";
        } else {
            $ret['status'] = 1;
            $ret['message'] = "request was successfully executed";
            $ret['total'] = number_format($total);
        }

        $this->_helper->json($ret);
    }

    public function estimatepersonalchargesAction() {
        $post = $this->_getParam('itemid');

        $ret = array();
        $ret['post'] = $post;

        $csettings = new Default_Resource_Chargesettings();
        $failedCharge = false;
        $total = 0;
        $mvregno = $_POST['mvregno'];
        foreach ($post as $item) {

            $charge_id = $item;
            $cSetting = $csettings->getChargeByDocument($charge_id);

            if ($cSetting !== null) {
                $total += $cSetting->charge_amount;
            } else {
                $failedCharge = true;
            }
        }

//get mvreg card balance


        $mvregisterResource = new Default_Resource_Mvregister();
        $mvcard = $mvregisterResource->getMvregisterByMvregno($mvregno);
        if ($mvcard !== null) {
            $ret['mvregno'] = $mvregno;
            $ret['creditbalance'] = number_format($mvcard->creditbalance, 2);
        }


        if ($failedCharge) {
            $ret['status'] = 0;
            $ret['message'] = "An error occured while making tbe request. Please contact the administrator ";
        } else {
            $ret['status'] = 1;
            $ret['message'] = "request was successfully executed";
            $ret['total'] = number_format($total);
        }

        $this->_helper->json($ret);
    }

    public function renewpersonalchargesAction() {

        //calculate total cost
        $post = $this->_getParam('itemid');

        $ret = array();
        $ret['post'] = $post;
        $mvregno = $_POST['mvregno'];
       $totalDocuments = 0;
        $csettings = new Default_Resource_Chargesettings();
        $cpaymentResource = new Default_Resource_Chargepayments();
        $chargesResource = new Default_Resource_Charges();
        $failedCharge = false;
        $total = 0;
        //   $vehicle = $vehicleResource->getVehicleById($_POST['vehicle_id']);

        foreach ($post as $item) {
            $charge_id = $item;
            $charge = $csettings->getChargeByDocument($charge_id);
            if ($charge !== null) {
                $total += $charge->charge_amount;
            } else {
                $failedCharge = true;
            }
        }

        //  echo "total is ".$total; die; 
        //get mvreg card balance
        $mvbalanceSucceded = false;
        // if ($vehicle !== null) {

        $mvregisterResource = new Default_Resource_Mvregister();
        $mvcard = $mvregisterResource->getMvregisterByMvregno($mvregno);
        if ($mvcard !== null) {
            //var_dump($mvcard); die;
            $mvbalanceSucceded = true;
            $ret['mvregno'] = $mvcard->creditbalance;
            $ret['creditbalance'] = number_format($mvcard->creditbalance, 2);
        }
        // }

        if ($failedCharge) {
            $ret['status'] = 0;
            $ret['message'] = "An error occured while making the request. Please contact the administrator ";
        } else {

            if ($mvbalanceSucceded) {
//echo "i am balance ".$ret['creditbalance']." total ".$total; die;
                if ($mvcard->creditbalance >= $total) {
                    //success
                    //  echo "total is ".$total."credit on card is ".$ret['creditbalance']; die;
                    $mvregisterResource = new Default_Resource_Mvregister();
                    $db = Zend_Db_Table::getDefaultAdapter();
                    foreach ($post as $item) {
                        $charge_id = $item;
                        $chargeSetting = $csettings->getChargeByDocument($charge_id);
                        if ($chargeSetting !== null) {
                            $charges = $chargesResource->getChargeById($charge_id);
                            if ($charges !== null) {
                                $data = array();
                                $startingDate = date("Y-m-d");
                                 $lastPayment = $cpaymentResource->getLastPayment($charge_id, $mvregno);
                                // var_dump($lastPayment); //die;
                                // echo "i am ".$mvregno; die;
                                    if ($lastPayment !== null) {
                                        if (strtotime($lastPayment->last) > strtotime($startingDate)) {
                                            $startingDate = $lastPayment->last;
                                        }
                                    }
                                $expiryDate = date('Y-m-d', strtotime($startingDate . $charges->charge_cycle_addition_string));
                                $data['payment_expiry'] = $expiryDate;
                                $data['mvreg_no'] = $mvregno;
                                $data['charge_id'] = $charge_id;
                                $data['payment_amount'] = $chargeSetting->charge_amount;
                                $data['payment_date'] = $startingDate;
                                $data['entity_type_id'] = $mvregno;
                                $data['mlo_operator'] = Zend_Auth::getInstance()->getIdentity()->mlo_id; // to be changed when login is implemented
                                $data['staff_operator'] = My_Auth::getInstance()->getIdentity()->staff_id;
                                $data['payment_time'] = date("H:i:s");
                                $cpaymentResource->saveItem($data);
                                   $totalDocuments += 1;
                                //begin update audit mvregister
                                $actionText = "";

                                //log in payment audit table
                                $date = date("Y-m-d");
                                $actionText = $charges->charge_title;
                                $Amount = $chargeSetting->charge_amount;
                                $action_id = $charges->charge_id;
                                $operatorID = Zend_Auth::getInstance()->getIdentity()->mlo_id; //to be change when login is implemented
                                $operatorName = Zend_Auth::getInstance()->getIdentity()->realname; //to be change when login is implemented
                                $staffId = My_Auth::getInstance()->getIdentity()->staff_id; //to be change when login is implemented

                                $sql1 = "INSERT INTO audit_mvregister(
		audit_mvregister.`index`,
		audit_mvregister.`date`,
		audit_mvregister.mvregno,
		audit_mvregister.cardcategory,
		audit_mvregister.debit_amount,
		audit_mvregister.action,
                audit_mvregister.operatorID,
		audit_mvregister.operator,
		audit_mvregister.action_category_id,
                audit_mvregister.StaffID)
		VALUES(NULL,'" . $date . "','" . $mvregno . "', 1, '" . $Amount . "','" . mysql_escape_string($actionText) . "','" . $operatorID . "','" . $operatorName . "','" . $action_id . "','" . $staffId . "')";
//echo $sql1; die;          
                        $db->query($sql1);
                                //the mvregcard balance is added to the amount being Deducted from the SuperCard

                                $where = $mvregisterResource->getAdapter()->quoteInto('mvregno = ?', $mvregno);
                                $mvregisterResource->update(array('creditbalance' => new Zend_Db_Expr('creditbalance - ' . $Amount)), $where);

                                //end update mvregister
                                //begin update mvregister
                                //end update mvregister
                            }
                        }
                    }
                   // echo "i am ".$post['mvregno'].' '.$mvregno; 
                   // var_dump($post); die;
                    $mvcardb = $mvregisterResource->getMvregisterByMvregno($mvregno);
                   
                    $ret['status'] = 1;
                    if ($mvcardb !== null) {
                        $ret['creditbalance'] = $mvcardb->creditbalance;
                    }
                    $ret['message'] = "request was successfully executed";
                      $ret['no_document'] = $totalDocuments;
                } else {
                    $ret['status'] = 0;
                    $ret['message'] = "Insufficient mvreg card balance";
                      $ret['no_document'] = $totalDocuments;
                }
            }
        }

        $this->_helper->json($ret);
    }

}

