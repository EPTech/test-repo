<?php

class Default_Resource_Chargepayments extends PP_Model_Resource_Db_Table_Abstract {

    protected $_name = 'charge_payments';
    protected $_primary = 'payment_id';

    public function getChargepaymentsById($id) {
        $select = $this->select();
        $select->where('payment_id = ?', $id);
        return $this->fetchRow($select);
    }

    public function getBulkTransactionByStaff($mlo, $trans_date, $staff) {
        $select = $this->select();
        $select->from("charge_payments", array('amount_due' => new Zend_Db_Expr("SUM(payment_amount)"), 'staff_operator', 'mlo_operator'))
                ->where("staff_operator = ?", $staff)
                ->where("transaction_date = ?", $trans_date)
                ->group("mlo_operator");
       // die($select);
        return $this->fetchAll($select);
    }

    public function getPaymentByMlo($mlo, $date, $staff) {
        $select = $this->select();
        $select->from('charge_payments', array('paid' => new Zend_Db_Expr("SUM(payment_amount)")));
        $select->where('mlo_operator = ?', $mlo)->where("staff_operator = ?", $staff);
        $select->where("transaction_date = ?", $date);
        //die($select);
        return $this->fetchRow($select);
    }

    public function getGrandTotal() {
        $select = $this->select();
        $select->from("charge_payments", array("total_amount" => new Zend_Db_Expr('SUM(payment_amount)')));
        //die($select);
        return $this->fetchRow($select);
    }

    public function getListing() {
        $select = $this->select(true)->setIntegrityCheck(false)
                ->joinLeft('charges', 'charges.charge_id = charge_payments.charge_id', array('charge_title'))
                ->joinLeft('mlo', 'mlo.mlo_id = charge_payments.mlo_operator', array('mlo_name', 'mlo_state_name' => new Zend_Db_Expr("(Select states.state from states where states.state_id = mlo.state)"), 'mlo_lga_name' => new Zend_Db_Expr("(Select lgas.name from lgas where lgas.id = mlo.lga)")));
        return $select;
    }

    public function getWdsreport($date) {

        $select = $this->select(true)->setIntegrityCheck(false)
                ->joinLeft('charges', 'charges.charge_id = charge_payments.charge_id', array('charge_title'))
                ->joinLeft('mlo', 'mlo.mlo_id = charge_payments.mlo_operator', array('mlo_name', 'mlo_state_name' => new Zend_Db_Expr("(Select states.state from states where states.state_id = mlo.state)"), 'mlo_lga_name' => new Zend_Db_Expr("(Select lgas.name from lgas where lgas.id = mlo.lga)")))
                ->where('transaction_date = ?', $date)
                ->order("mlo_operator desc");


        // die($select);
        return $this->fetchAll($select);
    }

    public function getChargeByVehicle($chargeId, $engCat) {
        $select = $this->select();
        $select->where(new Zend_Db_Expr("(charge_id = $chargeId and engcat_id = $engCat) or (charge_id = $chargeId and engcat_id = 0)"))
                ->order('engcat_id desc')
                ->limit(1);
        // die($select);
        return $this->fetchRow($select);
    }

    public function getLastPayment($charge, $mvregno) {
        $select = $this->select();
        $select->from('charge_payments', array('last' => new Zend_Db_Expr(" MAX(payment_expiry)")));
        $select->where("charge_id = ?", $charge);
        $select->where("mvreg_no = ?", $mvregno);
        //die($select);
        return $this->fetchRow($select);
    }

    public function getDefaultChargesettingsByChargeId($id) {
        $select = $this->select();
        $select->where('charge_id = ?', $id)->where("engcat_id = ?", 0)->limit(1);
        return $this->fetchRow($select);
    }

    public function getAllChargesettingsByChargeId($id) {
        $select = $this->select();
        $select->where('charge_id = ?', $id);
        return $this->fetchAll($select);
    }

    public function getChargesettingsByChargeandEngine($charge, $engine) {
        $select = $this->select();
        $select->where('charge_id = ?', $charge)->where('engcat_id = ?', $engine);
        return $this->fetchAll($select);
    }

    public function deleteChargesettings($charge, $engine) {
        $where = $this->getAdapter()->quoteInto('engcat_id = ?', $engine);
        $where = $this->getAdapter()->quoteInto('charge_id = ?', $charge);
        return $this->delete($where);
    }

    public function deleteChargesettingBycharge($charge) {
        $where = $this->getAdapter()->quoteInto('charge_id = ?', $charge);
        return $this->delete($where);
    }

//    public function deleteCountries(array $ids) {
//        foreach ($ids as $val) {
//            $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $val);
//            $this->delete($where);
//        }
//        return true;
//  }
}

?>