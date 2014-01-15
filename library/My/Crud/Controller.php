<?php

abstract class My_Crud_Controller extends Zend_Controller_Action {

    protected $_resource;
    protected $_form;
    protected $_searchFields;
    protected $_itemName = "";

    abstract protected function _getModelResource();

    abstract protected function _getCrudForm();

    abstract protected function _getSearchFields();

    protected $_messages = array(
        'add' => "Record Added successfully",
        "edit" => "Record Updated successfully",
        "delete" => "Record deleted successfully"
    );

    public function init() {
        $this->contextInitializer();

        /* Initialize action controller here */

        /* initialize crud model here */
        $this->_resource = $this->_getModelResource();

        //init flashmessenger
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');

        $itemName = $this->getItemName();
        if ($this->getItemName() == "") {
            $itemName = "Item";
        }
        $this->view->itemName = $itemName;
        
        $uri = $this->_request->getPathInfo();
        //die($uri);
        $activeNav = $this->view->navigation()->findByUri($uri);
        //s$activeNav->active = true;
    }

    public function contextInitializer() {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('index', 'json')
                ->setAutoJsonSerialization(false)
                ->initContext();
    }

    public function indexAction() {

        // action body
        $crudSelect = $this->_resource->getListing();

        $fields = $this->_getSearchFields();
        // var_dump($fields); exit;
        $data = new My_DataTable($crudSelect, $fields, $_GET);
        $this->view->items = $data;
    }

    public function addAction() {
        $form = $this->_getCrudForm();

        $request = $this->getRequest();
        $action = "/" . $request->getModuleName() . '/' . $request->getControllerName() . '/' . $request->getActionName();
        $form->setAction($action);
        if ($request->isPost()) {
            $post = $request->getPost();
            if ($form->isValid($post)) {
                $post = $form->getValues();

                $data = array();

                foreach ($post as $key => $value) {
                    $data[$key] = $value;
                }

                $others = $this->preprocessAdd();
                if (is_array($others)) {
                    foreach ($others as $key => $value) {
                        $data[$key] = $value;
                    }
                }

                try {
                    $this->_resource->saveItem($data);
                    $this->_flashMessenger->addMessage(array('alert alert-success' => $this->_messages['add']));
                } catch (Exception $ex) {
                    $this->_flashMessenger->addMessage(array('alert alert-error' => "An error occured while creating the item  <br/> " . $ex->getMessage()));
                }
                $this->_helper->redirector('index'); // $this->movetoLanding();
            }
        }
        $this->view->additem = $form;
    }

    public function movetoLanding($parameters) {
        $this->_helper->redirector('index');
    }

    public function getCrudEditForm() {
        return "";
    }

    public function editAction() {

        $itemId = $this->_getParam('id');

        if ($itemId === null) {
            $this->_flashMessenger->addMessage(array('alert alert-error' => "Please select an item to edit"));
            $this->_helper->redirector('index');
        }

        $request = $this->getRequest();
        if ($this->getCrudEditForm() == "") {
            $form = $this->_getCrudForm();
        } else {
            $form = $this->getCrudEditForm();
        }

        $action = "/" . $request->getModuleName() . '/' . $request->getControllerName() . '/' . $request->getActionName() . '/id/' . $itemId;
        $form->setAction($action);


        //add the primary key as element
        $form->addElement('hidden', $this->_resource->getPrimaryKey());



        if ($request->isPost()) {
            $post = $request->getPost();
            if ($form->isValid($post)) {
                $post = $form->getValues();

                $data = array();

                foreach ($post as $key => $value) {
                    $data[$key] = $value;
                }

                $others = $this->preprocessEdit();
                if (is_array($others)) {
                    foreach ($others as $key => $value) {
                        $data[$key] = $value;
                    }
                }

                try {
                    $this->_resource->updateItem($data);
                    $this->_flashMessenger->addMessage(array('alert alert-success' => $this->_itemName . " successfully updated"));
                } catch (Exception $ex) {
                    $this->_flashMessenger->addMessage(array('alert alert-error' => "An error occured while updating the " . $this->_itemName . " information <br/> " . $ex->getMessage()));
                }
                $this->_helper->redirector('index');
            }
        }
        $item = $this->_resource->getItemById($itemId);

        if ($item !== null) {
            $form->populate($item->toArray());
        }
        $this->view->edititem = $form;
    }

    public function preprocessAdd() {
        
    }

    public function preprocessEdit() {
        
    }

    public function deleteAction() {

        $itemId = $this->_getParam('id');
        if ($itemId === null) {
            $this->_flashMessenger->addMessage(array('alert alert-error' => "Please select an item to edit"));
            $this->_helper->redirector('index');
            exit;
        }

        $this->_resource->deleteItemById($itemId);
        $this->_flashMessenger->addMessage(array('alert alert-success' => "Item removed successfully"));
        $this->_helper->redirector('index');
    }

    public function deleteallAction() {
        $request = $this->getRequest();
        if (!$request->isPost()) {
            $this->_flashMessenger->addMessage(array('alert alert-error' => "Please select an item to edit"));
            $this->_helper->redirector('index');
            exit;
        }

        $post = $request->getPost();
        $items = $post['itemid'];
        $this->_resource->deleteItems($items);
        $this->_flashMessenger->addMessage(array('alert alert-success' => "Item(s) removed successfully"));
        $this->_helper->redirector('index');
    }

    public function setItemName($name = "Item") {
        $this->_itemName = $name;
    }

    public function getItemName() {
        return $this->_itemName;
    }

}

