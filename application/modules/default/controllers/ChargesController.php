<?php

class ChargesController extends My_Crud_Controller {

    protected $_itemName = "Documents";

  
    protected function _getModelResource() {
        return new Default_Resource_Charges();
    }

    public function testgridAction(){

    // $db = Zend_Registry::get('db');
   // $grid = $this->grid();
      $config = new Zend_Config_Ini(getcwd().'\..\application\configs\application.ini', 'production');
  //  var_dump($config);
//die;
   //Grid Initialization
  $grid = Bvb_Grid::factory('Bvb_Grid_Deploy_Table', $config, 'id');


   $grid->query($this->_resource->select()->from('charges', array('charge_id', 'charge_title','charge_amount')));
 
  $form = new Bvb_Grid_Form();
 $form->setAdd(true)->setEdit(true)->setDelete(true)->setAddButton(true);
 $grid->setForm($form);
 $this->view->pages = $grid->deploy();
 //$this->render('index');

    }
     public function printorcAction() {

        $this->_helper->layout()->disableLayout();
    $this->_helper->
        $mvregno = $this->_getParam('id');
        $vid = $this->_getParam('vid');
        $this->_helper->
        $chargeId = $this->_getParam('chargeid');
        if ($vid === null and $mvregno === null) {
            $this->_flashMessenger->addMessage(array('alert alert-warning' => "No vin or mvregno"));
            $this->_helper->redirector("index", "profiles", 'default');
        }

        $mloResource = new System_Resource_Mlos();
        $mloId = Zend_Auth::getInstance()->getIdentity()->mlo_id;
        $mlo = $mloResource->getMloById($mloId);
        $this->view->mlo = $mlo;

        $customerResource = new Default_Resource_Customers();
        $customer = $customerResource->getCustomerByMvregno($mvregno);

        $this->view->customer = $customer;

        $vehicleResource = new Default_Resource_Vehicles();
        $vehId = $this->_getParam('vid');
        $vehicle = $vehicleResource->getVehicleById($vehId);
        $this->view->vehicle = $vehicle;

        $cpaymentTbl = new Zend_Db_Table("charge_payments");
        $select = $cpaymentTbl->select()->from('charge_payments', array('lastpayid' => new Zend_Db_Expr('MAX(payment_id)')))
                ->where("mvreg_no = ?", $mvregno)
                ->where("charge_id = ?", $chargeId);
        //die($select);
        $payment = $cpaymentTbl->fetchRow($select);
        $paymentId = 0;
        if ($payment !== null) {
            if ($payment->lastpayid != "") {
                $paymentId = $payment->lastpayid;
            }
        }

        if ($paymentId == 0) {
            $payment = null;
        } else {
            $cpaymentTbl = new Default_Resource_Chargepayments();
            $select = $cpaymentTbl->select()->where("payment_id = ?", $paymentId);
//die($select);
            $payment = $cpaymentTbl->fetchRow($select);
        }
        $this->view->payment = $payment;
    }
    
    public function platereceiptAction() {
        $this->_helper->layout()->disableLayout();
        $mvregno = $this->_getParam('id');
        $vid = $this->_getParam('vid');
        $chargeId = $this->_getParam('chargeid');
        if ($vid === null and $mvregno === null) {
            $this->_flashMessenger->addMessage(array('alert alert-warning' => "No vehicle or mvregno"));
            $this->_helper->redirector("index", "profiles", 'default');
        }

        $mloResource = new System_Resource_Mlos();
        $mloId = Zend_Auth::getInstance()->getIdentity()->mlo_id;
        $mlo = $mloResource->getMloById($mloId);
        $this->view->mlo = $mlo;

        $customerResource = new Default_Resource_Customers();
        $customer = $customerResource->getCustomerByMvregno($mvregno);
        $this->view->customer = $customer;

        $vehicleResource = new Default_Resource_Vehicles();
        $vehId = $this->_getParam('vid');
        $vehicle = $vehicleResource->getVehicleById($vehId);
        $this->view->vehicle = $vehicle;

        $cpaymentTbl = new Zend_Db_Table("charge_payments");
        $select = $cpaymentTbl->select()->from('charge_payments', array('lastpayid' => new Zend_Db_Expr('MAX(payment_id)')))
                ->where("mvreg_no = ?", $mvregno)
                ->where("charge_id = ?", $chargeId);
        //die($select);
        $payment = $cpaymentTbl->fetchRow($select);
        $paymentId = 0;
        if ($payment !== null) {
            if ($payment->lastpayid != "") {
                $paymentId = $payment->lastpayid;
            }
        }

        if ($paymentId == 0) {
            $payment = null;
        } else {
            $cpaymentTbl = new Default_Resource_Chargepayments();
            $select = $cpaymentTbl->select()->where("payment_id = ?", $paymentId);
//die($select);
            $payment = $cpaymentTbl->fetchRow($select);
        }
        $this->view->payment = $payment;
    }

    public function printvrcAction() {
        $this->_helper->layout()->disableLayout();
        $mvregno = $this->_getParam('id');
        $vin = $this->_getParam('vin');
        $chargeId = $this->_getParam('chargeid');
        if ($vin === null and $mvregno === null) {
            $this->_flashMessenger->addMessage(array('alert alert-warning' => "No vin or mvregno"));
            $this->_helper->redirector("index", "profiles", 'default');
        }


        $service = 2;
        $cpaymentTbl = new Zend_Db_Table("charge_payments");
        $select = $cpaymentTbl->select()->from('charge_payments', array('lastpayid' => new Zend_Db_Expr('MAX(payment_id)')))
                ->where("mvreg_no = ?", $mvregno)
                ->where("charge_id = ?", $chargeId);
        //die($select);
        $payment = $cpaymentTbl->fetchRow($select);
        $paymentId = 0;
        if ($payment !== null) {
            if ($payment->lastpayid != "") {
                $paymentId = $payment->lastpayid;
            }
        }

        if ($paymentId == 0) {
            $customer = null;
        } else {
            $customerTbl = new Zend_Db_Table('customers');
            $select = $customerTbl->select(true)->setIntegrityCheck(false)
                    ->joinInner('vehicles', 'vehicles.mvreg_no = customers.mvregno', 'vehicles.*')
                    ->joinLeft('vehicle_uses', 'vehicle_uses.vehicle_use_id = vehicles.vehicle_use_id', array('vehicle_use'))
                    ->joinLeft('vehicle_models', 'vehicle_models.id = vehicles.vehicle_model_id', array('make', 'model'))
                    ->joinLeft('states', 'states.state_id = vehicles.state_id', array('state'))
                    ->joinInner('charge_payments', 'charge_payments.mvreg_no = customers.mvregno', array('payment_amount', 'payment_expiry'))
                    ->where('customers.mvregno = ?', $mvregno)
                    ->where('charge_payments.payment_id = ?', $paymentId)
                    ->where('vehicles.vehicle_vin =  ?', $vin);
//die($select);
            $customer = $customerTbl->fetchRow($select);
        }
        $this->view->customer = $customer;
    }

    public function printhackneyAction() {
        $this->_helper->layout()->disableLayout();
        $mvregno = $this->_getParam('id');
        $vid = $this->_getParam('vid');
        $chargeId = $this->_getParam('chargeid');
        if ($vid === null and $mvregno === null) {
            $this->_flashMessenger->addMessage(array('alert alert-warning' => "No vin or mvregno"));
            $this->_helper->redirector("index", "profiles", 'default');
        }

        $mloResource = new System_Resource_Mlos();
        $mloId = Zend_Auth::getInstance()->getIdentity()->mlo_id;
        $mlo = $mloResource->getMloById($mloId);
        $this->view->mlo = $mlo;

        $customerResource = new Default_Resource_Customers();
        $customer = $customerResource->getCustomerByMvregno($mvregno);
        $this->view->customer = $customer;

        $vehicleResource = new Default_Resource_Vehicles();
        $vehId = $this->_getParam('vid');
        $vehicle = $vehicleResource->getVehicleById($vehId);
        $this->view->vehicle = $vehicle;

        $cpaymentTbl = new Zend_Db_Table("charge_payments");
        $select = $cpaymentTbl->select()->from('charge_payments', array('lastpayid' => new Zend_Db_Expr('MAX(payment_id)')))
                ->where("mvreg_no = ?", $mvregno)
                ->where("charge_id = ?", $chargeId);
        //die($select);
        $payment = $cpaymentTbl->fetchRow($select);
        $paymentId = 0;
        if ($payment !== null) {
            if ($payment->lastpayid != "") {
                $paymentId = $payment->lastpayid;
            }
        }

        if ($paymentId == 0) {
            $payment = null;
        } else {
            $cpaymentTbl = new Default_Resource_Chargepayments();
            $select = $cpaymentTbl->select()->where("payment_id = ?", $paymentId);
//die($select);
            $payment = $cpaymentTbl->fetchRow($select);
        }
        $this->view->payment = $payment;
    }

    public function printroofrackAction() {
        $this->_helper->layout()->disableLayout();
        $mvregno = $this->_getParam('id');
        $vid = $this->_getParam('vid');
        $chargeId = $this->_getParam('chargeid');
        if ($vid === null and $mvregno === null) {
            $this->_flashMessenger->addMessage(array('alert alert-warning' => "No vin or mvregno"));
            $this->_helper->redirector("index", "profiles", 'default');
        }

        $mloResource = new System_Resource_Mlos();
        $mloId = Zend_Auth::getInstance()->getIdentity()->mlo_id;
        $mlo = $mloResource->getMloById($mloId);
        $this->view->mlo = $mlo;

        $customerResource = new Default_Resource_Customers();
        $customer = $customerResource->getCustomerByMvregno($mvregno);
        $this->view->customer = $customer;

        $vehicleResource = new Default_Resource_Vehicles();
        $vehId = $this->_getParam('vid');
        $vehicle = $vehicleResource->getVehicleById($vehId);
        $this->view->vehicle = $vehicle;

        $cpaymentTbl = new Zend_Db_Table("charge_payments");
        $select = $cpaymentTbl->select()->from('charge_payments', array('lastpayid' => new Zend_Db_Expr('MAX(payment_id)')))
                ->where("mvreg_no = ?", $mvregno)
                ->where("charge_id = ?", $chargeId);
        //die($select);
        $payment = $cpaymentTbl->fetchRow($select);
        $paymentId = 0;
        if ($payment !== null) {
            if ($payment->lastpayid != "") {
                $paymentId = $payment->lastpayid;
            }
        }

        if ($paymentId == 0) {
            $payment = null;
        } else {
            $cpaymentTbl = new Default_Resource_Chargepayments();
            $select = $cpaymentTbl->select()->where("payment_id = ?", $paymentId);
//die($select);
            $payment = $cpaymentTbl->fetchRow($select);
        }
        $this->view->payment = $payment;
    }

    public function printlpAction() {
        $this->_helper->layout()->disableLayout();
        $mvregno = $this->_getParam('id');
        //$vid = $this->_getParam('vid');
        $chargeId = $this->_getParam('chargeid');
        if ($chargeId === null and $mvregno === null) {
            $this->_flashMessenger->addMessage(array('alert alert-warning' => "No document or mvregno"));
            $this->_helper->redirector("index", "profiles", 'default');
        }

        $mloResource = new System_Resource_Mlos();
        $mloId = Zend_Auth::getInstance()->getIdentity()->mlo_id;
        $mlo = $mloResource->getMloById($mloId);
        $this->view->mlo = $mlo;

        $customerResource = new Default_Resource_Customers();
        $customer = $customerResource->getCustomerByMvregno($mvregno);
        $this->view->customer = $customer;



        $cpaymentTbl = new Zend_Db_Table("charge_payments");
        $select = $cpaymentTbl->select()->from('charge_payments', array('lastpayid' => new Zend_Db_Expr('MAX(payment_id)')))
                ->where("mvreg_no = ?", $mvregno)
                ->where("charge_id = ?", $chargeId);
        //die($select);
        $payment = $cpaymentTbl->fetchRow($select);
        $paymentId = 0;
        if ($payment !== null) {
            if ($payment->lastpayid != "") {
                $paymentId = $payment->lastpayid;
            }
        }

        if ($paymentId == 0) {
            $payment = null;
        } else {
            $cpaymentTbl = new Default_Resource_Chargepayments();
            $select = $cpaymentTbl->select(true)->setIntegrityCheck(false)
                    ->joinInner('charges', 'charges.charge_id = charge_payments.charge_id', array('charge_title'))
                    ->joinInner('charge_cycles', 'charge_cycles.charge_cycle_id = charges.charge_cycle', array('charge_cycle_id', 'charge_cycle_title', 'charge_cycle_addition_string'))
                    ->where("payment_id = ?", $paymentId);
//die($select);
            $payment = $cpaymentTbl->fetchRow($select);
        }
        $this->view->payment = $payment;
    }

    public function printcooAction() {
        $this->_helper->layout()->disableLayout();
        $mvregno = $this->_getParam('id');
        $vid = $this->_getParam('vid');
        $vin = $this->_getParam('vin');
        $chargeId = $this->_getParam('chargeid');
        if ($vid === null and $mvregno === null) {
            $this->_flashMessenger->addMessage(array('alert alert-warning' => "No vin or mvregno"));
            $this->_helper->redirector("index", "profiles", 'default');
        }

        //previous owner
        $prevOwnerResource = new Default_Resource_Previousowner();
        $prevOwner = $prevOwnerResource->getPrevOwnerByVin($vin);
        $this->view->prevOwner = $prevOwner;

        $mloResource = new System_Resource_Mlos();
        $mloId = Zend_Auth::getInstance()->getIdentity()->mlo_id;
        $mlo = $mloResource->getMloById($mloId);
        $this->view->mlo = $mlo;

        $customerResource = new Default_Resource_Customers();
        $customer = $customerResource->getCustomerByMvregno($mvregno);
        $this->view->customer = $customer;

        $vehicleResource = new Default_Resource_Vehicles();
        $vehId = $this->_getParam('vid');
        $vehicle = $vehicleResource->getVehicleById($vehId);
        $this->view->vehicle = $vehicle;

        $cpaymentTbl = new Zend_Db_Table("charge_payments");
        $select = $cpaymentTbl->select()->from('charge_payments', array('lastpayid' => new Zend_Db_Expr('MAX(payment_id)')))
                ->where("mvreg_no = ?", $mvregno)
                ->where("charge_id = ?", $chargeId);
        //die($select);
        $payment = $cpaymentTbl->fetchRow($select);
        $paymentId = 0;
        if ($payment !== null) {
            if ($payment->lastpayid != "") {
                $paymentId = $payment->lastpayid;
            }
        }

        if ($paymentId == 0) {
            $payment = null;
        } else {
            $cpaymentTbl = new Default_Resource_Chargepayments();
            $select = $cpaymentTbl->select()->where("payment_id = ?", $paymentId);
//die($select);
            $payment = $cpaymentTbl->fetchRow($select);
        }
        $this->view->payment = $payment;
    }

    public function getselectedestimateAction() {
        // sleep(2);
        // var_dump($_POST);
        // die;
        $ret = array();
        $cSettings = new Default_Resource_Chargesettings();
        $nairaLocale = new Zend_Locale('ig_NG');
        $currency = new Zend_Currency($nairaLocale);
        $chargeArr = array();
        $total = 0;
        foreach ($_POST['itemid'] as $item) {
            if (isset($_POST['enginesize' . $item])) {
                $engineSize = $_POST['enginesize' . $item];
            } else {
                $engineSize = 0;
            }
            $charge_id = $item;
            $charge = $cSettings->getChargeByVehicle($charge_id, $engineSize);
            if ($charge !== null) {
                $ret['status'] = 1;
                $cSettingRow = array_merge($charge->toArray(), array('amount' => $currency->toCurrency($charge->charge_amount)));
                $chargeArr[] = $cSettingRow;
                $total += $charge->charge_amount;
            }
        }

        $ret['status'] = 1;
        $ret['total'] = $currency->toCurrency($total);
        $ret['result'] = $chargeArr;
        $this->_helper->json($ret);
    }

    public function getestimateAction() {
        sleep(2);
        $ret = array();
        $charge_id = $_POST['chargeId'];
        $engineSize = $_POST['engineSize'];
        if ($engineSize == "") {
            $engineSize = 0;
        }
        $nairaLocale = new Zend_Locale('ig_NG');
        $currency = new Zend_Currency($nairaLocale);
        //$formatted = $currency->toCurrency($product->getPrice());
        $cSettings = new Default_Resource_Chargesettings();
        $charge = $cSettings->getChargeByVehicle($charge_id, $engineSize);
        if ($charge === null) {
            $ret['status'] = 0;
        } else {

            $ret['status'] = 1;
            $ret['result'] = $charge->toArray();
            $ret['amount'] = $currency->toCurrency($charge->charge_amount);
        }

        $this->_helper->json($ret);
    }

    public function estimateAction() {
        $charges = $this->_resource->getCharges();
        $this->view->charges = $charges;
        $engineCatResource = new Default_Resource_Enginecat();
        $engineCategories = $engineCatResource->getEnginecategories();
        $this->view->engCat = $engineCategories;
    }

    public function contextInitializer() {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('index', 'json')
                ->addActionContext('vehiclecharges', 'json')
                ->addActionContext('customercharges', 'json')
                ->addActionContext('pcharges', 'json')
                ->addActionContext('stock', 'json')
                ->setAutoJsonSerialization(false)
                ->initContext();
    }

    protected function _getSearchFields() {
        return array('charge_title', 'charge_entity', 'charge_cycle');
    }

    //protected function _getSearchFields() { }
    //protected function _

    public function documentAction() {
        
    }

    public function stockAction() {
        // action body
        $id = $this->_getParam('id');
        $dStockResource = new Default_Resource_Documentstock();
        $crudSelect = $dStockResource->getListing($id);

        $fields = array('charges.charge_title', 'operation', 'desc');
        // var_dump($fields); exit;
        $data = new My_DataTable($crudSelect, $fields, $_GET);
        $this->view->items = $data;
        $this->view->id = $id;
        $this->view->charge = $this->_resource->getChargeById($id);
    }

    public function addstockAction() {
        // action body
        $id = $this->_getParam('id');

        if ($id === null) {
            $this->_flashMessenger->addMessage(array('alert alert-warning' => "Select a document to stock"));
            $this->_helper->redirector('index');
        }
        $this->view->id = $id;
        $charge = $this->_resource->getChargeById($id);
        $this->view->charge = $charge;
        $form = new Default_Form_Charges_Stock();

        $request = $this->getRequest();

        if ($request->isPost()) {
            $post = $request->getPost();
            if ($form->isValid($post)) {
                $post = $form->getValues();
                // var_dump($post); die;
                $stockResource = new Default_Resource_Documentstock();
                $data = array();
                $data['charge_id'] = $id;
                $data['qty'] = $post['qty'];
                $data['operation'] = 'add';
                $data['staff'] = My_Auth::getInstance()->getIdentity()->staff_id;
                $data['mlo'] = Zend_Auth::getInstance()->getIdentity()->mlo_id;
                $desc = "Stock ";
                $alertMessage = "";
                if ($charge !== null) {
                    $desc .= $charge->charge_title;
                    $alertMessage .= $charge->charge_title;
                }
                $data['desc'] = $desc;
                $data['action_date'] = date("Y-m-d");
                $data['action_time'] = date("H:i:s");

                try {
                    $stockResource->saveItem($data);
                    $alertMessage .= " Stocked successfully";

                    $this->_flashMessenger->addMessage(array('alert alert-success' => $alertMessage));
                } catch (Exception $e) {
                    $this->_flashMessenger->addMessage(array('alert alert-error' => "An error occurred while updating stock " . $e->getMessage()));
                }
                $this->_helper->redirector('stock', 'charges', 'default', array('id' => $id));
            }
        }

        $this->view->form = $form;
    }

    protected function _getCrudForm() {
        return new Default_Form_Charges_Add();
    }

    public function getCrudEditForm() {
        return new Default_Form_Charges_Edit();
    }

    public function chargeAction() {
        $chargeId = $_POST['charge'];
        $charge = $this->_resource->getChargeById($chargeId);
        $ret = array();
        if ($charge !== null) {
            $ret['status'] = 1;
            $ret['charge'] = $charge->toArray();
        } else {
            $ret['status'] = 0;
        }

        $csettings = new Default_Resource_Chargesettings();
        $chargeSetting = $csettings->getChargesettingsByChargeId($chargeId);

        if ($chargeSetting === null) {
            $ret['setup'] = 0;
        } else {
            $ret['setup'] = 1;
        }

        $this->_helper->json($ret);
    }

    public function reconfigureAction() {
        $id = $this->_getParam('id');

        if ($id === null) {
            $this->_flashMessenger->addMessage(array('alert alert-error' => "Select a charge to reconfigure"));
            $this->_helper->redirector('index');
        }

        $form = new Default_Form_Charges_Settingseditbase();
        $form->setAction('/default/charges/reconfigure/id/' . $id);
        $request = $this->getRequest();
        $this->view->vehicleEntity = 0;
        if (!$request->isPost()) {

            $engcatResource = new Default_Resource_Enginecat();
            foreach ($engcatResource->getEnginecategories() as $engcat) {
                $form->getSubForm('settings')->addChargesetting($engcat->engcat_id);
            }

            $csettings = new Default_Resource_Chargesettings();
            $settings = $csettings->getAllChargesettingsByChargeId($id);
            $chargeResource = new Default_Resource_Charges();
            $charge = $chargeResource->getChargeById($id);
            $defaultchargeSetting = $csettings->getDefaultChargesettingsByChargeId($id);
            if ($defaultchargeSetting !== null) {
                $form->getElement('flatfee')->setValue($defaultchargeSetting->charge_amount);
            }
            if ($charge !== null) {
                $form->getElement('charge_id')->setValue($id);
                $form->getElement('charge_name')->setValue($charge->charge_title);
                if ($charge->charge_entity == "vehicle") {
                    $this->view->vehicleEntity = 1;
                }
            }
            if ($settings->count()) {

                $fSettings = $settings->toArray();

                $data = array();

                foreach ($fSettings as $fsetting) {
                    $index = $fsetting['engcat_id'];
                    $data['settings']["$index"] = array(
                        'charge_amount' => $fsetting['charge_amount'],
                        'charge_item' => 1
                    );
                    //$form->populate($fsetting);
                }

                if ($form->getSubForm('settings') !== null) {
                    $form->getSubForm('settings')->populate($data);
                }
                // var_dump($data);
            }
        } else {

            $post = $request->getPost();
            // var_dump($post);
            //var_dump($form->getErrors());
            //build settings form
            if (isset($post['settings'])) {
                foreach ($post['settings'] as $key => $value) {
                    $form->getSubForm('settings')->addChargesetting($key);
                }
            }


            if ($form->isValid($post)) {
                $post = $form->getValues();
                // var_dump($post); exit;
                if (isset($post['settings']) and !empty($post['settings'])) {
                    try {
                        $csettings = new Default_Resource_Chargesettings();
                        $csettings->deleteChargesettingBycharge($post['charge_id']);
                        foreach ($post['settings'] as $key => $value) {

                            foreach ($value as $key2 => $value2) {
                                $itemSelected = $value2['charge_item'];
                                if ($itemSelected == 0) {
                                    continue;
                                }
                                //var_dump($value2);
                                $data = array();
                                // echo "it is ".$post['charge_id'].'<br/>';
                                $data['charge_id'] = $post['charge_id'];
                                $data['engcat_id'] = $value2['engcat_id'];
                                $data['charge_amount'] = $value2['charge_amount'];

                                $csettings->saveItem($data);
                                // var_dump($data); 
                            }
                        }
                        $default = array();
                        $default['charge_id'] = $post['charge_id'];
                        $default['engcat_id'] = 0;
                        $default['charge_amount'] = $post['flatfee'];

                        $csettings->saveItem($default);
                        // exit;
                        $this->_flashMessenger->addMessage(array('alert alert-success' => "The settings were successfully updated for the chosen charge"));
                    } catch (Exception $ex) {
                        $this->_flashMessenger->addMessage(array('alert alert-error' => " A problem occured while saving the settings. Please contact admin " . $ex->getMessage()));
                    }
                    $this->_helper->redirector('index', 'charges', 'default');
                }
            }
        }
        $this->view->form = $form;
    }

    public function configureAction() {

        $form = new Default_Form_Charges_Settingsbase();

        $request = $this->getRequest();

        if (!$request->isPost()) {
            $this->view->postStatus = 0;

            $engcatResource = new Default_Resource_Enginecat();
            foreach ($engcatResource->getEnginecategories() as $engcat) {
                $form->getSubForm('settings')->addChargesetting($engcat->engcat_id);
            }
        } else {
            $this->view->postStatus = 1;
            $post = $request->getPost();

            //build settings form
            if (isset($post['settings'])) {
                foreach ($post['settings'] as $key => $value) {
                    $form->getSubForm('settings')->addChargesetting($key);
                }
            }


            if ($form->isValid($post)) {
                $post = $form->getValues();

                if (isset($post['settings']) and !empty($post['settings'])) {
                    try {
                        $csettings = new Default_Resource_Chargesettings();
                        foreach ($post['settings'] as $key => $value) {

                            foreach ($value as $key2 => $value2) {
                                $itemSelected = $value2['charge_item'];
                                if ($itemSelected == 0) {
                                    continue;
                                }
                                //var_dump($value2);
                                $data = array();
                                // echo "it is ".$post['charge_id'].'<br/>';
                                $data['charge_id'] = $post['charge_id'];
                                $data['engcat_id'] = $value2['engcat_id'];
                                $data['charge_amount'] = $value2['charge_amount'];

                                $csettings->saveItem($data);
                            }
                        }
                        $default = array();
                        $default['charge_id'] = $post['charge_id'];
                        $default['engcat_id'] = 0;
                        $default['charge_amount'] = $post['flatfee'];
                        $csettings->saveItem($default);
                        // exit;
                        $this->_flashMessenger->addMessage(array('alert alert-success' => "The settings were successfully configured for the chosen charge"));
                    } catch (Exception $ex) {
                        $this->_flashMessenger->addMessage(array('alert alert-error' => " A problem occured while saving the settings. Please contact admin " . $ex->getMessage()));
                    }
                    $this->_helper->redirector('index', 'charges', 'default');
                }
            }
        }


        $this->view->form = $form;
    }

    public function settingsAction() {
        $form = new Default_Form_Charges_Settingsbase();
        $chargeResource = new Default_Resource_Charges();
        $this->view->subs = array();
        $subs = array();
        foreach ($chargeResource->getCharges() as $charge) {
            $form->getSubForm('settings')->addChargesetting($charge->charge_id);
        }

        $this->view->subs = $subs;

        $this->view->form = $form;
    }

    public function customerchargesAction() {

        // action body
        $id = $this->_getParam('id');

        if ($id === null) {
            $this->_helper->redirector('profiles');
        }

        $this->view->id = $id;

        $vehicleResource = new Default_Resource_Vehicles();

        $vehicleSelect = $vehicleResource->getChargesByCustomer($id);

        $fields = array('charge_title', 'vehicle_model');
        // var_dump($fields); exit;
        $data = new My_DataTable($vehicleSelect, $fields, $_GET);
        $this->view->items = $data;
        $form = new Default_Form_Mvregfund_Add();

        $this->view->mvcardFundForm = $form;
        $form->getElement('mvregno')->setValue($id);

        $oForm = new Default_Form_Ownership_Add();
        $this->view->ownershipForm = $oForm;

        // var_dump($data); die;
    }

    //
    public function pchargesAction() {

        // action body
        $id = $this->_getParam('id');

        if ($id === null) {
            $this->_helper->redirector('profiles');
        }

        $this->view->id = $id;

        $vehicleResource = new Default_Resource_Vehicles();

        $pchargesSelect = $vehicleResource->getPersonalChargesByCustomer($id);

        $fields = array('charge_title', 'charge_title');
        //die($pchargesSelect);
        // var_dump($fields); exit;
        $data = new My_DataTable($pchargesSelect, $fields, $_GET);

        $this->view->items = $data;

        $form = new Default_Form_Mvregfund_Add();

        $form->setAttrib('id', 'mvfundpchargesForm');

        $form->addElement('hidden', 'formid', array(
            'value' => 'mvfundpchargesForm'
        ));
        $form->getElement('sub')->setAttrib("id", "mvfundpcharges");
        $this->view->mvcardFundForm = $form;
        $form->getElement('mvregno')->setValue($id);
        // var_dump($data); die;
    }

    public function vehiclechargesAction() {

        // action body
        $id = $this->_getParam('id');

        if ($id === null) {
            $this->_helper->redirector('profiles');
        }

        $this->view->id = $id;
        $vehicleResource = new Default_Resource_Vehicles();

        $vehicleSelect = $vehicleResource->getChargesByVehicle($id);

        $fields = array('charge_title', 'vehicle_model', 'vehicle_engine_num', 'vehicle_color', 'vehicle_type');
        // var_dump($fields); exit;
        $data = new My_DataTable($vehicleSelect, $fields, $_GET);
        $this->view->items = $data;
        $oForm = new Default_Form_Ownership_Add();
        $this->view->ownershipForm = $oForm;
        // var_dump($data); die;
    }

    public function orcAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->getHelper('viewrenderer')->setNoRender();
    }

    public function vrcAction() {
        $this->_helper->layout()->disableLayout();
        //$this->_helper->getHelper('viewrenderer')->setNoRender();
    }

    public function hackneyAction() {
        $this->_helper->layout()->disableLayout();
        //$this->_helper->getHelper('viewrenderer')->setNoRender();
    }

    public function lpAction() {
        $this->_helper->layout()->disableLayout();
        //$this->_helper->getHelper('viewrenderer')->setNoRender();
    }

    public function roofrackAction() {
        $this->_helper->layout()->disableLayout();
        //$this->_helper->getHelper('viewrenderer')->setNoRender();
    }

    public function cooAction() {
        $this->_helper->layout()->disableLayout();
    }

    public function titlereceiptAction() {
        $this->_helper->layout()->disableLayout();
    }

    public function roadworthinessAction() {
        $this->_helper->layout()->disableLayout();
    }

}

