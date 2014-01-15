<?php

require_once dirname(__FILE__) . '/Vehicles/Item.php';

class Default_Resource_Vehicles extends PP_Model_Resource_Db_Table_Abstract {

    protected $_name = 'vehicles';
    protected $_primary = 'vehicle_id';
    protected $_rowClass = "Default_Resource_Vehicles_Item";

    //for generating VIN,VRC,ORC,IRC 
    public function getVin($len) {
        $VIN = "";
        $char = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $c = 0;
        while ($c <= $len) {
            $random = rand(1, strlen($char));
            $VIN .= substr($char, $random - 1, 1);
            ++$c;
        }

        if (!empty($VIN))
            return $VIN; //echo $VIN;
    }

    public function getVehicleByOwner($mvregno) {
        $select = $this->select(true)->setIntegrityCheck(false);
        $select->joinInner('manufacturers', 'manufacturers.id = vehicles.manufacturer_id', array('manufacturer'))
                ->joinInner('vehicle_types', 'vehicle_types.id = vehicles.type_id', 'vehicle_type')
                ->where('vehicles.mvreg_no = ?', $mvregno)
                ->order('vehicles.vehicle_id');
        //die($select);
        return $select;
    }

    public function getVehicleById($id) {
        //return $this->find($id)->current();
        $select = $this->select(true)->setIntegrityCheck(false);
        $select->joinInner('manufacturers', 'manufacturers.id = vehicles.manufacturer_id', array('manufacturer'))
                ->joinInner('vehicle_types', 'vehicle_types.id = vehicles.type_id', 'vehicle_type')
                ->joinInner('vehicle_uses', 'vehicle_uses.vehicle_use_id = vehicles.usage_id', 'vehicle_use')
                ->joinInner('drive_trains', 'drive_trains.id = vehicles.drive_train_id', 'drive_train')
                ->joinInner('engine_categories', 'engine_categories.engcat_id =      vehicles.engcat_id', 'engcat_title')
                ->joinLeft('lgas', 'lgas.id = vehicles.lga_id', 'name')
                ->where('vehicles.vehicle_id = ?', $id);
        // die($sql);
        return $this->fetchRow($select);
    }

    public function getVehiclesByProfile($mvregno) {
        $select = $this->select();
        $select->where('mvreg_no = ?', $mvregno);
        return $this->fetchAll($select);
    }

    public function deleteVehicles(array $ids) {
        foreach ($ids as $val) {
            $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $val);
            $this->delete($where);
        }
        return true;
    }

    public function deleteVehicleById($id) {
        $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $id);
        return $this->delete($where);
    }

    public function getVehiclechargesByCustomer($mvregno) {
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array('baseTable' => new Zend_Db_Expr("(select veh.vehicle_id, veh.vehicle_vin, CONCAT( veh.model_year, ', ', manufacturers.manufacturer, ' ', veh.vehicle_model) AS vehicle_model,charges.charge_title,charges.charge_id from vehicles AS  veh
join charges on charges.charge_entity = 'vehicle'
left join manufacturers ON manufacturers.id = veh.manufacturer_id
WHERE veh.mvreg_no = '$mvregno'  
group by veh.vehicle_id, charges.charge_id
)")), array("vehicle_id", "vehicle_vin", "vehicle_model", "charge_title", "charge_id"));
        $select->joinLeft(array('chargePaymenBase' => new Zend_Db_Expr(" (
select * from (
select  charge_payments.payment_amount,charge_payments.charge_id,charge_payments.payment_id, charge_payments.payment_expiry,charge_payments.payment_date,charge_payments.entity_type_id from charge_payments 
inner join charges on charges.charge_id = charge_payments.charge_id
where charge_payments.mvreg_no = '$mvregno'  and charges.charge_entity = 'vehicle'
 order by charge_payments.payment_id desc )
as  cps  
 group by entity_type_id, charge_id 
)")), "chargePaymenBase.entity_type_id = baseTable.vehicle_id and  chargePaymenBase.charge_id = baseTable.charge_id", array("payment_amount", "payment_expiry"));
        $select->order("baseTable.vehicle_id");
        return $select;
        // die($select);
    }

    public function getChargesByCustomer($mvregno) {
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array('baseTable' => new Zend_Db_Expr("(select veh.vehicle_id, veh.vehicle_vin, CONCAT( veh.model_year, ', ', manufacturers.manufacturer, ' ', veh.vehicle_model) AS vehicle_model,charges.charge_title,charges.charge_id from vehicles AS  veh
join charges on charges.charge_entity = 'vehicle'
left join manufacturers ON manufacturers.id = veh.manufacturer_id
WHERE veh.mvreg_no = '$mvregno'  
group by veh.vehicle_id, charges.charge_id
)")), array("vehicle_id", "vehicle_vin", "vehicle_model", "charge_title", "charge_id"));
        $select->joinLeft(array('chargePaymenBase' => new Zend_Db_Expr(" (
select * from (
select  charge_payments.payment_amount,charge_payments.charge_id,charge_payments.payment_id, charge_payments.payment_expiry,charge_payments.payment_date,charge_payments.entity_type_id from charge_payments 
inner join charges on charges.charge_id = charge_payments.charge_id
where charge_payments.mvreg_no = '$mvregno'  and charges.charge_entity = 'vehicle'
 order by charge_payments.payment_id desc )
as  cps  
 group by entity_type_id, charge_id 
)")), "chargePaymenBase.entity_type_id = baseTable.vehicle_id and  chargePaymenBase.charge_id = baseTable.charge_id", array("payment_amount", "payment_expiry"));
        $select->order("baseTable.vehicle_id");
        return $select;
        // die($select);
    }

    public function getChargesByVehicle($id) {
        
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array('baseTable' => new Zend_Db_Expr("(select veh.vehicle_id, veh.vehicle_vin, CONCAT( veh.model_year, ', ', manufacturers.manufacturer, ' ', veh.vehicle_model) AS vehicle_model,charges.charge_title,charges.charge_id from vehicles AS  veh
join charges on charges.charge_entity = 'vehicle'
left join manufacturers ON manufacturers.id = veh.manufacturer_id
WHERE veh.vehicle_id = '$id'  
group by veh.vehicle_id, charges.charge_id
)")), array("vehicle_id", "vehicle_vin", "vehicle_model", "charge_title", "charge_id"));
        $select->joinLeft(array('chargePaymenBase' => new Zend_Db_Expr(" (
select * from (
select  charge_payments.payment_amount,charge_payments.charge_id,charge_payments.payment_id, charge_payments.payment_expiry,charge_payments.payment_date,charge_payments.entity_type_id from charge_payments 
inner join charges on charges.charge_id = charge_payments.charge_id
where charge_payments.entity_type_id = '$id'  and charges.charge_entity = 'vehicle'
 order by charge_payments.payment_id desc )
as  cps  
 group by entity_type_id, charge_id 
)")), "chargePaymenBase.entity_type_id = baseTable.vehicle_id and  chargePaymenBase.charge_id = baseTable.charge_id", array("payment_amount", "payment_expiry"));
        $select->order("baseTable.vehicle_id");
        return $select;
        // die($select);
    }

}

?>