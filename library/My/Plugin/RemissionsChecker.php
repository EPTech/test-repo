<?php

/**
 * SF_Plugin_AdminContext
 * 
 * This plugin detects if we are in the admininstration area
 * and changes the layout to the admin template.
 * 
 * This relies on the admin route found in the initialization plugin
 *
 * 
 */
class My_Plugin_RemissionsChecker extends Zend_Controller_Plugin_Abstract {

    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request) {
        //begin end
        //and (!in_array($action_name, $actionExceptions)) and (!in_array($controllerName, $controllerExceptions)) and (!in_array($moduleName, $moduleExceptions)

        if (Zend_Auth::getInstance()->hasIdentity() and My_Auth::getInstance()->hasIdentity()) {

            $staffid = My_Auth::getInstance()->getIdentity()->staff_id; //$row['ID'];
            $today = date("Y-m-d");
            $newdate = strtotime('-2 day', strtotime($today));
            $exclude_date = date('l', $newdate);
            $newdate = date('Y-m-d', $newdate);
            $remitTbl = new Zend_Db_Table("daily_transaction_post");
//            $remitSelect = $remitTbl->select()->where('trans_date = ? ', $newdate)
//                    ->where("trans_date < ?", $newdate)
//                    ->where("staffid = ?", $staffid);
//            $remissions = $remitTbl->fetchRow($remitSelect);
//            $sql = mysql_query(" select * from daily_transaction_post where (`trans_date` = '$newdate' and `trans_date` < '$newdate') and staffid = '$staffid'");

            $max_date = $newdate;
            $min_date = "2012-06-25";
            /////test 1/////////////////////
            $paymentTbl = new Zend_Db_Table('charge_payments');
            $paymentSelect = $paymentTbl->select()->from("charge_payments", array('amount_due' => new Zend_Db_Expr("SUM(payment_amount)")))
                    ->where("staff_operator = ? ", $staffid)
                    ->where(new Zend_Db_Expr(" transaction_date between '" . $min_date . "' and '" . $max_date . "' "))
                    ->group("staff_operator");
            $payments = $paymentTbl->fetchRow($paymentSelect);
            //          $test1 = " select sum(debit_amount) as 'amount_due' from audit_mvregister where staffid = '" . $_SESSION['StaffID'] . "' and `date` between '$min_date' and '$max_date' group by StaffID ";

            $currentRemitSelect = $remitTbl->select()->from('daily_transaction_post', array('tot' => new Zend_Db_Expr("SUM(amount_remitted)")))
                    ->where('staffid = ?', $staffid)
                    ->where(new Zend_Db_Expr('trans_date between "' . $min_date . '" and "' . $max_date . '"'))
                    ->group("staffid");
            $currentRemissions = $remitTbl->fetchRow($currentRemitSelect);
            $designation = My_Auth::getInstance()->getIdentity()->designation;
            ///$test2 = " select sum(amount_remitted) as 'tot' from daily_transaction_post where staffid = '" . $_SESSION['StaffID'] . "' and trans_date between '$min_date' and '$max_date' group by staffid";
            //	echo 'dis is '.$row['designation']."<br/>";
           // echo "designation is ".$designation; die;
            //or ($exclude_date == "Sunday" or $exclude_date == "Saturday")
            if ((($designation == "admin") or ($designation == "support")) or strtotime($min_date) > strtotime($max_date)) {
                ///// continue normally
             //   echo 'exclue is ' . $exclude_date . '<br/>';
              //  die;
            } else {

                //get exceptions from
                $config = new Zend_Config_Ini(
                               APPLICATION_PATH . '/configs/module.ini',
                                APPLICATION_ENV
                );


                $exceptions = $config->exceptions->toArray();
                $pass = true;
              
                $module = $request->getModuleName();
        $controller = $request->getControllerName();
                $action = $request->getActionName();
              
                foreach ($exceptions as $exception) {
                    $eModule = isset($exception['module']) ? $exception['module'] : $module;
                    $eController = isset($exception['controller']) ? $exception['controller'] : $controller;
                    $eAction = isset($exception['action']) ? $exception['action'] : $action;

                    if ((($eModule == $module) &&
                            ($eController == $controller) &&
                            ($eAction == $action))) {

                        $pass = false;
                        break;
                    }
                }
                if ($payments->amount_due != $currentRemissions->tot and $pass) {   //if($res_sql == 0)
                    /////go to remit only/////////////
                    //echo "hereczzx"; die;
                    $this->goToRemit($request);
                    // return;
                } else {
                    /////continue normally///////
                  //  echo 'normally';
                   // die;
                }
            }
            /////////////end validation///////////////////////////////////
        }
    }

    public function goToRemit($request) {
        $request->setModuleName('default');
        $request->setControllerName('remissions');
        $request->setActionName('index');
        $request->setDispatched(false);
    }

}

