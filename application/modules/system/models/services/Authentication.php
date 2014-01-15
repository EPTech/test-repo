<?php

/**
 * Reportmaker_Service_Authentication
 * 
 * The authentication service provides authentication services for
 * the reportmaker module
 * 
 */
class System_Service_Authentication {

    /**
     * @var Zend_Auth_Adapter_DbTable
     */
    protected $_authAdapter;

    /**
     * @var Storefront_Model_User
     */
    protected $_userModel;

    /**
     * @var Zend_Auth
     */
    protected $_auth;

    /**
     * @var admin status
     */
    protected $_aStatus;

    /**
     * Construct 
     * 
     * @param null|Reportmaker_Model_User $userModel 
     */
    public function __construct(System_Model_User $userModel = null) {
        $this->_userModel = null === $userModel ? new System_Model_User() : $userModel;
    }

    /**
     * Authenticate a user
     *
     * @param  array $credentials Matched pair array containing email/password
     * @return boolean
     */
    public function authenticate($credentials) {

        $adapter = $this->getAuthAdapter($credentials);


        $auth = $this->getAuth();

        $result = $auth->authenticate($adapter);
        $loginLabel->label = "";
        $loginLabel = new Zend_Session_Namespace();
        $model = new System_Model_Adminuser();
        if (!$result->isValid()) {

            //begin updating pin misses
            $user = $model->getUserByUsername($credentials['user_email']);
            if (null !== $user) {
                $pin_missed = $user->pin_missed + 1;
                $data = array();
                $data['pin_missed'] = $pin_missed;

                //maximum number of pin misses
                $max_pin_misses = $model->getResource('Parameter')->getParameterByName('no_of_pin_misses')->parameter_value;
                if (($max_pin_misses - 1) == $user->pin_missed) {
                    $data['user_account_lock'] = 1;
                }
                //update only when password is wrong and maximum no of pin has not being entered
                if ($max_pin_misses != $user->pin_missed) {
                    $userInfo = $user->toArray();

                    $user_id = $userInfo['user_id'];
                              $constraint = $model->getResource('Adminuser')->getAdapter()->quoteInto("user_id =  ? ", $user_id);
  
                    $model->getResource('Adminuser')->update($data, $constraint);
                }
            }

            if ($user !== null) {
                //before checking login failed check if account is locked
                if ($user->user_account_disable == '1') {
                    $this->clear();
                    $loginLabel->label = "<b>Your user profile has been disabled. Please contact the administrator</b>";

                    return false;
                } else if ($user->user_account_lock == '1') {
                    $this->clear();
                    $loginLabel->label = "<b>Your user profile has been locked. Please contact the administrator</b>";

                    return false;
                }
            }
            //end checking if account is locked
            //end updating pin misses  
            $loginLabel->label = '<b>Login failed, please try again</b>';
            return false;
        }

        $user = $this->_userModel->getUserByEmail($credentials['user_email']); //$adapter->getResultRowObject(); //
//var_dump($user); die;        
//begin checking user details

        $adminModel = new System_Model_Adminuser();
        $dhrmin = date('Hi');
        $worktime = $adminModel->getParameter('working_hours')->parameter_value;

        if ($user->override_wh == 1) {
            $worktime = $user->extend_wh;
        }
        $ddate = date('w');
        $worktimesplit = explode("-", $worktime);
        $lowertime = str_replace(":", "", $worktimesplit[0]);
        $uppertime = str_replace(":", "", $worktimesplit[1]);

        $lowerstatus = ($lowertime < $dhrmin) == '' ? "0" : "1";
        $upperstatus = ($dhrmin < $uppertime) == '' ? "0" : "1";

        $pass_dateexpire = $user->user_pass_expiry_date;
        //    echo 'expire date is  '.   $pass_dateexpire.'<br/>';
        $expiration_date = strtotime($pass_dateexpire);
        $today = date('Y-m-d');
        $today_date = strtotime($today);

        if ($user->user_account_disable == '1') {
            $this->clear();
            $loginLabel->label = "<b>Your user profile has been disabled. Please contact the administrator</b>";

            return false;
        } else if ($user->user_account_lock == '1') {

            $this->clear();
            $loginLabel->label = "<b>Your user profile has been locked. Please contact the administrator</b>";

            return false;
        } else if ($user->day_1 == 0 && $ddate == '1') {
            $this->clear();
            //You are not allowed to login on Sunday
            $loginLabel->label = "<b>You are not allowed to login on Monday</b>";
            return false;
        } else if ($user->day_2 == '0' && $ddate == '2') {
            $this->clear();
            //You are not allowed to login on Monday
            $loginLabel->label = "<b>You are not allowed to login on Tuesday</b>";
            return false;
        } else if ($user->day_3 == '0' && $ddate == '3') {
            $this->clear();
            //You are not allowed to login on Monday
            $loginLabel->label = "<b>You are not allowed to login on Wednesday</b>";
            return false;
        } else if ($user->day_4 == '0' && $ddate == '4') {
            $this->clear();
            //You are not allowed to login on Monday
            $loginLabel->label = "<b>You are not allowed to login on Thursday</b>";
            return false;
        } else if ($user->day_5 == '0' && $ddate == '5') {
            $this->clear();
            //You are not allowed to login on Monday
            $loginLabel->label = "<b>You are not allowed to login on Friday</b>";
            return false;
        } else if ($user->day_6 == '0' && $ddate == '6') {
            $this->clear();
            //You are not allowed to login on Monday
            $loginLabel->label = "<b>You are not allowed to login on Saturday</b>";
            return false;
        } else if ($user->day_7 == '0' && $ddate == '7') {
            $this->clear();
            //You are not allowed to login on Monday
            $loginLabel->label = "<b>You are not allowed to login on Sunday</b>";
            return false;
        } else if (!(($lowerstatus == 1) && ($upperstatus == 1))) {
            $this->clear();
            //You are not allowed to login due to working hours violation
            $loginLabel->label = "<b>You are not allowed to login at this time. The time is not within your working hours</b>";
            return false;
        } else if ($expiration_date <= $today_date) {
            $this->clear();
            //  echo "this is it ";
            $loginLabel->label = "<b>Your password has expired,  click " . ' <a style="font-size:14px; text-decoration:underline;" href="/system/user/change-pass">Here</a> ' . " to change password </a><b>";
            return false;
        } else if ($user->user_password_status == '1') {
            $this->clear();
            // echo 'benjay ';
            $loginLabel->label = "<b>You are required to change your password, click " . ' <a style="font-size:14px; text-decoration:underline;" href="/system/user/change-pass">Here</a> ' . " to change password </a></b>";
            return false;
        }

        $loginLabel->label = "";
        //     echo 'labe is '.$loginLabel->label ; exit;
        $data['pin_missed'] = 0;
        $data['last_used'] = date("Y-m-d h:i:s");
        ///don't move this block of code from here
        $user->last_used =  $data['last_used'];
        /////don't move this block of code from here 
        $auth->getStorage()->write((object) $user);
        //reset bin missed to 0
        
         $where = $model->getResource('Adminuser')->getAdapter()->quoteInto("user_id =  ? ", $user->user_id);
         //echo "user id is ".$user->user_id; exit;
        $model->getResource('Adminuser')->update($data, $where);
        return true;
    }

    public function getAuth() {
        if (null === $this->_auth) {
            $this->_auth = Zend_Auth::getInstance();
        }
        return $this->_auth;
    }

    public function getIdentity() {
        $auth = $this->getAuth();
        if ($auth->hasIdentity()) {
            return $auth->getIdentity();
        }
        return false;
    }

    /**
     * Clear any authentication data
     */
    public function clear() {
        $this->getAuth()->clearIdentity();
    }

    /**
     * Set the auth adpater.
     *
     * @param Zend_Auth_Adapter_Interface $adapter
     */
    public function setAuthAdapter(Zend_Auth_Adapter_Interface $adapter) {
        $this->_authAdapter = $adapter;
    }

    /**
     * Get and configure the auth adapter
     * 
     * @param  array $value Array of user credentials
     * @return Zend_Auth_Adapter_DbTable
     */
    public function getAuthAdapter($values) {
        if (null === $this->_authAdapter) {
            $authAdapter = new Zend_Auth_Adapter_DbTable(
                            Zend_Db_Table_Abstract::getDefaultAdapter(),
                            'admin_users',
                            'username',
                            'user_password',
                            'MD5(CONCAT(?,salt))'
            );
            $this->setAuthAdapter($authAdapter);
            $this->_authAdapter->setIdentity($values['user_email']);
            $this->_authAdapter->setCredential($values['user_password']);
        }
        return $this->_authAdapter;
    }

}
