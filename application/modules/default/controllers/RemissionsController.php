<?php

class RemissionsController extends Zend_Controller_Action {

    public function init() {
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }

    public function indexAction(){
        
    }
    public function singleTransdateAction() {
        $form = new Default_Form_Remissions_Base();
        //set form action
        $form->setAction("/default/remissions/single-transdate/");
        $this->view->form = $form;
        $this->view->header = "Single Daily returns";

        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = $request->getPost();
            if ($form->isValid($post)) {
                $post = $form->getValues();
                $trans_date = $post['trans_date'];
                $this->_helper->redirector('single-details', 'remissions', 'default', array('date' => $trans_date));
            }
        }
    }

    public function bulkTransdateAction() {
        $form = new Default_Form_Remissions_Base();
        //set form action
        $form->setAction("/default/remissions/bulk-transdate/");
        $this->view->form = $form;
        $this->view->header = "Bulk Daily returns";

        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = $request->getPost();
            if ($form->isValid($post)) {
                $post = $form->getValues();
                $trans_date = $post['trans_date'];
                $this->_forward('bulk-details', 'remissions', 'default', array('date' => $trans_date));
            }
        }
    }

    public function bulkDetailsAction(){
        $trans_date = $this->_getParam('date');
        $remissionResource = new Default_Resource_Remissions();
        $cpayments = new Default_Resource_Chargepayments();
        $staffResource = new System_Resource_Staff();
        $mloResource = new System_Resource_Mlos();
        $mloId = Zend_Auth::getInstance()->getIdentity()->mlo_id;
        //$mloId = 9089;
        $staffId = My_Auth::getInstance()->getIdentity()->staff_id;
      $bulkPayments = $cpayments->getBulkTransactionByStaff($mloId, $trans_date, $staffId);
      $this->view->staffId = $staffId;
       $this->view->mloResource = $mloResource;
      $this->view->trans_date = $trans_date;
      $this->view->bPayments = $bulkPayments;
      $this->view->remissionResource = $remissionResource;
        
    }
    
    
     public function bulkpaymentAction() {
        //die("ojima");
        $remissionResource = new Default_Resource_Remissions();
        $cpayments = new Default_Resource_Chargepayments();
        $staffResource = new System_Resource_Staff();
        $mlo = $this->_getParam('mlo');//Zend_Auth::getInstance()->getIdentity()->mlo_id;
        $staffId = My_Auth::getInstance()->getIdentity()->staff_id;

        $trans_date = $this->_getParam('date');

        if ($trans_date === null) {
            $this->_flashMessenger->addMessage(array("alert alert-warning" => "Please select a transaction date"));
            $this->_helper->redirector('bulk-transdate');
        }

        $form = new Default_Form_Remissions_Bulkdetails();
        $form->getElement('trans_date')->setValue($trans_date);
        $staff = My_Auth::getInstance()->getIdentity()->staff_id;
        $this->view->remission = $remissionResource->getRemissionByMlo($mlo, $trans_date, $staff);
        $this->view->payment = $cpayments->getPaymentByMlo($mlo, $trans_date, $staff);

        $this->view->trans_date = $trans_date;
        $this->view->staff = $staffResource->getStaffById($staffId);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = $request->getPost();
            if ($form->isValid($post)) {
                $post = $form->getValues();
                $data = array();
                $lastRemission = $remissionResource->getLastRemission();
                $lastRId = ($lastRemission === null) ? 1 : $lastRemission->lastid;
                if ($lastRId == "") {
                    $lastRId = 1;
                }

                $totalAmountDue = ($this->view->payment === null) ? 0 : $this->view->payment->paid;
                $totalAmountremitted = ($this->view->remission === null) ? 0 : $this->view->remission->amt;
                $debt = $totalAmountDue - $totalAmountremitted;
                $outstanding = $totalAmountDue - $post['amount'];
                $lastRId = $lastRId + 1;
                $document_no = date("is") . $lastRId . 'ld';
                $current_state = 1;
                $data["dateposted"] = date("Y-m-d");
                $data["trans_date"] = $post['trans_date'];
                $data['timeposted'] = date("H:i:s");
                $data['amount_due'] = $debt;
                $data["amount_remitted"] = $post["amount"];
                $data['tellerno'] = $post['tellerno'];
                $data["outstanding"] = $outstanding;
                $data["teller_by"] = "";
                $data["bank_name"] = $post['bank_id'];
                $data["comment"] = $post["comment"];
                $data["mlo"] = $mlo;//Zend_Auth::getInstance()->getIdentity()->mlo_id;
                $data["staffid"] = My_Auth::getInstance()->getIdentity()->staff_id;
               // $data['mvreg_action'] = $post['charge_id'];
                $data['revenue_return_type'] = 2;
                $data['document_no'] = $document_no;
                $data['current_state'] = $current_state;
                try {
                    // var_dump($data); 
                    $insertedId = $remissionResource->saveItem($data);
                   
                    $this->_flashMessenger->addMessage(array('alert alert-success' => "Details saved successfully"));
                    $this->_helper->redirector("acknowledge-single", "remissions", "default", array("id" => $insertedId,'type' => 'bulk'));
                } catch (Exception $e) {
                    $this->_flashMessenger->addMessage(array('alert alert-error' => "An error occurred while processing the request " . $e->getMessage()));
                  // echo $e->getMessage();  die;
                    $this->_helper->redirector("single-transdate", "remissions", "default");
                }
            }
        }
        $this->view->form = $form;
    }

    public function acknowledgeSingleAction() {
        $id = $this->_getParam("id");
        $type = '';
        if ($id === null) {
            $this->_helper->redirector('index');
        }
        
        if($this->_getParam('type') !== null){
            $type = $this->_getParam('type');
        }
        
 //die;
        $remissionResource = new Default_Resource_Remissions();
        $staffResource = new System_Resource_Staff();
        $mloResource = new System_Resource_Mlos();
        $chargeResource = new Default_Resource_Charges();

        $remission = $remissionResource->getTransactionById($id);
        $mloId = ($remission === null) ? 0 : $remission->mlo;
        $staffId = ($remission === null) ? 0 : $remission->staffid;
        $chargeId = ($remission === null) ? 0 : $remission->mvreg_action;
        $this->view->mlo = $mloResource->getMloById($mloId);
        $this->view->staff = $staffResource->getStaffById($staffId);
        if($type == ''){
        $this->view->charge = $chargeResource->getChargeById($chargeId);
        }
        $this->view->type = $type;
        $this->view->remission = $remission;
        //die;
        //die;
    }

    public function singleDetailsAction() {
        //die("ojima");
        $remissionResource = new Default_Resource_Remissions();
        $cpayments = new Default_Resource_Chargepayments();
        $staffResource = new System_Resource_Staff();
        $mlo = Zend_Auth::getInstance()->getIdentity()->mlo_id;
        $staffId = My_Auth::getInstance()->getIdentity()->staff_id;

        $trans_date = $this->_getParam('date');

        if ($trans_date === null) {
            $this->_flashMessenger->addMessage(array("alert alert-warning" => "Please select a transaction date"));
            $this->_helper->redirector('single-transdate');
        }

        $form = new Default_Form_Remissions_Singledetails();
        $form->getElement('trans_date')->setValue($trans_date);
        $staff = My_Auth::getInstance()->getIdentity()->staff_id;
        $this->view->remission = $remissionResource->getRemissionByMlo($mlo, $trans_date, $staff);
        $this->view->payment = $cpayments->getPaymentByMlo($mlo, $trans_date, $staff);

        $this->view->trans_date = $trans_date;
        $this->view->staff = $staffResource->getStaffById($staffId);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = $request->getPost();
            if ($form->isValid($post)) {
                $post = $form->getValues();
                $data = array();
                $lastRemission = $remissionResource->getLastRemission();
                $lastRId = ($lastRemission === null) ? 1 : $lastRemission->lastid;
                if ($lastRId == "") {
                    $lastRId = 1;
                }

                $totalAmountDue = ($this->view->payment === null) ? 0 : $this->view->payment->paid;
                $totalAmountremitted = ($this->view->remission === null) ? 0 : $this->view->remission->amt;
                $debt = $totalAmountDue - $totalAmountremitted;
                $outstanding = $totalAmountDue - $post['amount'];
                $lastRId = $lastRId + 1;
                $document_no = date("is") . $lastRId . 'dl';
                $current_state = 1;
                $data["dateposted"] = date("Y-m-d");
                $data["trans_date"] = $post['trans_date'];
                $data['timeposted'] = date("H:i:s");
                $data['amount_due'] = $debt;
                $data["amount_remitted"] = $post["amount"];
                $data['tellerno'] = $post['tellerno'];
                $data["outstanding"] = $outstanding;
                $data["teller_by"] = "";
                $data["bank_name"] = $post['bank_id'];
                $data["comment"] = $post["comment"];
                $data["mlo"] = Zend_Auth::getInstance()->getIdentity()->mlo_id;
                $data["staffid"] = My_Auth::getInstance()->getIdentity()->staff_id;
                $data['mvreg_action'] = $post['charge_id'];
                $data['revenue_return_type'] = 1;
                $data['document_no'] = $document_no;
                $data['current_state'] = $current_state;
                try {
                    $insertedId = $remissionResource->saveItem($data);
                    $this->_flashMessenger->addMessage(array('alert alert-success' => "Details saved successfully"));
                    $this->_helper->redirector("acknowledge-single", "remissions", "default", array("id" => $insertedId));
                } catch (Exception $e) {
                    $this->_flashMessenger->addMessage(array('alert alert-error' => "An error occurred while processing the request " . $e->getMessage()));
                    $this->_helper->redirector("single-transdate", "remissions", "default");
                }
            }
        }
        $this->view->form = $form;
    }

    public function revenueAction() {
        // action body
        $cpayment = new Default_Resource_Chargepayments();
        $cpayments = $cpayment->getListing();
        $fields = array('mvreg_no', 'charge_title', 'mlo_name');
        // var_dump($fields); exit;
        $data = new My_DataTable($cpayments, $fields, $_GET);
        $this->view->items = $data;
        $this->view->totals = $cpayment->getGrandTotal();
    }

    public function wdsAction() {
        $cpayment = new Default_Resource_Chargepayments();
        $date = $this->_getParam('date');
        if ($date === null) {
            $date = date("Y-m-d");
        }
        $wdsSelect = $cpayment->getWdsreport($date);
        $this->view->items = $wdsSelect;
    }

}

