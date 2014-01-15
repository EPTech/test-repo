<?php

class PrintController extends Zend_Controller_Action {

    protected $_itemName = "Documents";

    protected function _getModelResource() {
        return new Default_Resource_Charges();
    }

    public function orcAction() {
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

    public function vrcAction() {
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

    public function hackneyAction() {
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

    public function roofrackAction() {
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

    public function lpAction() {
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

    public function cooAction() {
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

    public function orcheaderAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->getHelper('viewrenderer')->setNoRender();
    }

    public function vrcheaderAction() {
        $this->_helper->layout()->disableLayout();
        //$this->_helper->getHelper('viewrenderer')->setNoRender();
    }

    public function hackneyheaderAction() {
        $this->_helper->layout()->disableLayout();
        //$this->_helper->getHelper('viewrenderer')->setNoRender();
    }

    public function lpheaderAction() {
        $this->_helper->layout()->disableLayout();
        //$this->_helper->getHelper('viewrenderer')->setNoRender();
    }

    public function roofrackheaderAction() {
        $this->_helper->layout()->disableLayout();
        //$this->_helper->getHelper('viewrenderer')->setNoRender();
    }

    public function cooheaderAction() {
        $this->_helper->layout()->disableLayout();
    }

    public function titlereceiptheaderAction() {
        $this->_helper->layout()->disableLayout();
    }

    public function roadworthinessheaderAction() {
        $this->_helper->layout()->disableLayout();
    }

}

