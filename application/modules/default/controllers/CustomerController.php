<?php

class CustomerController extends Zend_Controller_Action {

    public function init() {
        
    }

    public function registerAction() {

        //post information
        $post = $_POST;

        //add customer information
        $cus = array();

        //customer resource
        $customerResource = new Default_Resource_Customers();
        $cusColumns = $customerResource->getAllColumns();

        //set up customer information
        foreach ($cusColumns as $col) {
            if (isset($post[$col])) {
                $cus[$col] = $post[$col];
            }
        }

        //override set up "title" index to "title_id" i
        $cus['title_id'] = $post['title'];

        //add vehicle information
        //vehicle resource
        $vehicleResource = new Default_Resource_Vehicles();
        $vehColumns = $vehicleResource->getAllColumns();
        $veh = array();
        //set up vehicle information
        foreach ($vehColumns as $col) {
            if (isset($post[$col])) {
                $veh[$col] = $post[$col];
            }
        }

        $vModel = $vModelResource->getVmodelById($post['vehicle_model']);
        //override set up "title" index to "title_id" i
        $vehvin = $vehicleResource->getVin(12);
        $veh['vehicle_model_id'] = $post['vehicle_model'];
        $veh['mvreg_no'] = $post['mvregno'];
        $veh['vehicle_vin'] = $vehvin;
        $veh['created'] = date('Y-m-d H:i:s');
        $mloID = $post['staff']; // Zend_Auth::getInstance()->getIdentity()->mlo_id; //to be change when login is implemented
        $staffId = $post['mlo']; // My_Auth::getInstance()->getIdentity()->staff_id; //to be change when login is implemented
        $veh['mlo'] = $mloID;
        $veh['staff'] = $staffId;


        if ($vModel !== null) {
            $veh['vehicle_model'] = $vModel->model;
            $veh['model_year'] = $vModel->year;
            $veh['manufacturer_id'] = $vModel->manufacturer_id;
        }

        $prevResource = new Default_Resource_Previousowner();
        $prev = array();

        $prev["mvregno"] = $post['mvregno'];
        $prev['title_id'] = $post["title_id"];
        $prev['surname'] = $post["po_lastname"];
        $prev['oname'] = $post["po_firstname"];
        //$prev['address1'] = $copost["address1"];
        //$prevOwner['email'] = $copost["email"];
        //$prevOwner['gender'] = $copost["gender"];
        //$prevOwner['phone'] = $copost["phone"];
        $prev['city'] = $post["po_city"];
        $prev['state'] = $post["po_state"];
        $prev['lga'] = $post["po_lga"];
        $prev['vehvin'] = $vehvin;
        $prev['country'] = $post["po_country"];
        // $prevOwner['payment_id'] = $insertedPaymentId;
        $prev['process_date'] = date("Y-m-d");
        $prev['process_time'] = date("H:i:s");

        //select documents to process
        //fund cards
        //process documents
        //begin transaction
        $db = Zend_Db_Table::getDefaultAdapter();
        try {
            //commit transaction
            $customerResource->saveItem($cus);
            $vehicleResource->saveItem($veh);
            $prevResource->saveItem($prev);
            $db->commit();
            $this->_flashMessenger->addMessage(array("alert alert-success" => "Customer information saved successfully"));
        } catch (Exception $e) {
            $this->_flashMessenger->addMessage(array("alert alert-error" => "An error occured while saving customer's information " . $e->getMessage()));
            $db->rollBack();
        }
        $this->_helper->redirector('index', 'profiles', 'default');
    }

}
