<?php


class Default_Resource_Chargepayments extends PP_Model_Resource_Db_Table_Abstract {

    protected $_name = 'charge_payments';
    protected $_primary = 'payment_id';
  
    public function getChargepaymentsById($id) {
        $select = $this->select();
        $select->where('payment_id = ?',$id);
        return $this->fetchRow($select);
    }

    public function getChargeByVehicle($chargeId, $engCat){
        $select = $this->select();
        $select->where(new Zend_Db_Expr("(charge_id = $chargeId and engcat_id = $engCat) or (charge_id = $chargeId and engcat_id = 0)"))
                ->order('engcat_id desc')
                ->limit(1);
       // die($select);
        return $this->fetchRow($select);
    }
     public function getDefaultChargesettingsByChargeId($id) {
        $select = $this->select();
        $select->where('charge_id = ?',$id)->where("engcat_id = ?", 0)->limit(1);
        return $this->fetchRow($select);
    }
     public function getAllChargesettingsByChargeId($id) {
        $select = $this->select();
        $select->where('charge_id = ?',$id);
        return $this->fetchAll($select);
    }
    public function getChargesettingsByChargeandEngine($charge,$engine){
        $select = $this->select();
        $select->where('charge_id = ?',$charge)->where('engcat_id = ?', $engine);
        return $this->fetchAll($select);
    }
   
  
    public function deleteChargesettings( $charge,$engine) {
        $where = $this->getAdapter()->quoteInto('engcat_id = ?', $engine);
        $where = $this->getAdapter()->quoteInto('charge_id = ?', $charge);
        return $this->delete($where);
  }

   public function deleteChargesettingBycharge( $charge) {
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