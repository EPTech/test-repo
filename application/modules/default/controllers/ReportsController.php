<?php

class ReportsController extends Zend_Controller_Action {

    public function init() {

        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('revenue', 'json')
                ->addActionContext('remissions', 'json')
                ->setAutoJsonSerialization(false)
                ->initContext();
    }

    public function indexAction() {
        
    }

    public function remissionsAction() {
        $remissionTbl = new Default_Resource_Remissions();
        $remissions = $remissionTbl->getListing();
        $fields = array('lastname', 'firstname', 'mlo_name');
        // var_dump($fields); exit;
        $data = new My_DataTable($remissions, $fields, $_GET);
        $this->view->items = $data;
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

