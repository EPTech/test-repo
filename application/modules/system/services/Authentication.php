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
    protected $_mloResource;

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
    public function __construct(System_Resource_Mlos $mloResource = null) {
        $this->_mloResource = (null === $mloResource) ? new System_Resource_Mlos() : $mloResource;
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
       
        if (!$result->isValid()) {
             $loginLabel->label = '<b>Login failed, please try again</b>';
            return false;
        }

        $user = $this->_mloResource->getMloByEmail($credentials['user_email']); //$adapter->getResultRowObject(); //
        

        $loginLabel->label = "";
        $data = array();
        $lastVisit =  date("Y-m-d H:i:s");
        $data['last_login'] = $lastVisit;
        /////don't move this block of code from here 
        $auth->getStorage()->write((object) $user);
        //reset bin missed to 0
      
         $where = $this->_mloResource->getAdapter()->quoteInto("mlo_id =  ? ", $user->mlo_id);
         //echo "user id is ".$user->user_id; exit;
       $this->_mloResource->update($data, $where);
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
                            'mlo',
                            'username',
                            'password',
                            'MD5(CONCAT(?,salt))'
            );
            $this->setAuthAdapter($authAdapter);
            $this->_authAdapter->setIdentity($values['user_email']);
            $this->_authAdapter->setCredential($values['user_password']);
        }
        return $this->_authAdapter;
    }

}
