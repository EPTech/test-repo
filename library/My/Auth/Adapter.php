<?php

class My_Auth_Adapter implements Zend_Auth_Adapter_Interface {

    private $_username;
    private $_password;

    public function __construct(array $params) {
        $this->_username = $params['username'];
        $this->_password = $params['password'];
    }

    /**
     * @return Zend_Auth_Result
     */
    public function authenticate() {
        //lookup user
        $usersTbl = new System_Model_DbTable_Users();
        $adapter = $usersTbl->getAdapter();

        $select = $usersTbl->select()
                ->from($usersTbl, array('username', 'id', 'role_id', 'count' => new Zend_Db_Expr('COUNT(id)')))
                ->where('username = ?', $this->_username)
                ->where('password = ?', md5($this->_password))
                ->group('username');

        $user = $usersTbl->fetchRow($select);

        if (!$user || !$user->count) {
            return new Zend_Auth_Result(Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, null);
        }

        //update user's last seen time
        $usersTbl->update(array('last_seen' => new Zend_Db_Expr('NOW()')), $adapter->quoteInto('id = ?', $user->id));


        return new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $user);
    }

}