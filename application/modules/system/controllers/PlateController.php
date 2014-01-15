<?php

class System_PlateController extends My_Crud_Controller {

    protected $_itemName = "Title Plate";

    protected function _getModelResource() {
        return new System_Resource_Plate();
    }

    public function contextInitializer() {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('index', 'json')
                ->setAutoJsonSerialization(false)
                ->initContext();
    }

    protected function _getSearchFields() {
        return array('plateno', 'platenumbers.status', 'category', 'name');
    }

    public function batchprocessAction() {
        $form = new System_Form_Plate_Batchprocess();
        $this->view->form = $form;
        $request = $this->getRequest();
        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                //echo "first layer " . "<br/>"; //exit;
                if ($form->dbfile->receive()) {
                    $location = $form->dbfile->getFileName();

                    require(APPLICATION_PATH . '/../library/php-excel-reader/excel_reader2.php');

                    // require(APPLICATION_PATH . '/../library/images/merchant/corporate/');
                    require(APPLICATION_PATH . '/../library/php-excel-reader/SpreadsheetReader.php');
                    //$indicatorResource = new System_Resource_Perfind();

                    $tableColumns = array();
//                    $indicatorResource->getAllColumns();
                    $tableColumns[] = 'plateno';
                    $post = $form->getValues();
                    $Reader = new SpreadsheetReader($location);
                    foreach ($Reader as $Row) {
                        $size = count($Row);
                        $data = array();
                        for ($i = 0; $i < $size; $i++) {
                            $data[$tableColumns[$i]] = $Row[$i];
                        }

                        // $data['plateno'] = $newPlate;
                        $data['lga'] = $post['lga'];
                        $data['state'] = $post['state'];
                        $data['date_added'] = date("Y-m-d");
                        $data['status'] = 0;
                        $data['pcategory'] = $post['pcategory'];
                        $data['staff'] = My_Auth::getInstance()->getIdentity()->staff_id;
                        $data['mlo'] = Zend_Auth::getInstance()->getIdentity()->mlo_id;

                        try {
                            $this->_resource->saveItem($data);
                        } catch (Exception $e) {
                            //$e->getMessage();
                            $this->_flashMessenger->addMessage(array("alert alert-error" => "an error occurred when importing data. check to make sure your file contains valid data"));
                            break;
                        }
                        //var_dump($data); 
                        //save data
                    }
                    //die;
                    $this->_flashMessenger->addMessage(array("alert alert-success" => "batch process complete successfully successfully"));
                    $this->_helper->redirector('index');
                }
            }
        }
    }

    public function addAction() {
        $form = $this->_getCrudForm();

        $request = $this->getRequest();
        $action = "/" . $request->getModuleName() . '/' . $request->getControllerName() . '/' . $request->getActionName();
        $form->setAction($action);
        $this->view->enableMultipleEntry = 0;
        if ($request->isPost()) {
            $post = $request->getPost();
            
            if ($post['multiple_plates'] == 1 and $post['plates_serial'] == 1) {
                $this->view->enableMultipleEntry = 1;
            }
            // var_dump($post); die;
            $state = $post['state'];
            $lgaResource = new Default_Resource_Lgas();
            $lgas = $lgaResource->getLgaByState($state);
            if ($lgas->count()) {
                $lgaList = array();
                $lgaList[""] = "Select";
                foreach ($lgas as $lga) {
                    $lgaList[$lga->id] = $lga->name;
                }
                // var_dump($lgaList);
                $form->getElement('lga')->setMultiOptions($lgaList);
            }

            if ($form->isValid($post)) {
                $post = $form->getValues();
                //$form->getValues();

                $data = array();


//                foreach ($post as $key => $value) {
//                    $data[$key] = $value;
//                }

                try {
                    //$this->_resource->saveItem($data);
                    if ($_POST['multiple_plates'] == 1 and $_POST['plates_serial'] == 1) {
                        $first_plateno = $post['first_plateno'];
                        $pieces = explode("-", $first_plateno);
                        if (isset($pieces[1])) {
                            $digits = $pieces[1];
                            $lastTwo = substr($digits, -2);
                            $digitsOnly = substr($digits, 0, -2);
                            //remove leading zeros
                            $plateDigit = ltrim($digitsOnly, '0');
                            $total_plates = $post['no_plates'];
                            for ($i = 0; $i < $total_plates; $i++) {

                                if ($i == 0) {
                                    $newPlate = $post['first_plateno'];
                                } else {
                                    $plateDigit += 1;
                                    $formattedPlateDigit = str_pad($plateDigit, 3, '0', STR_PAD_LEFT);
                                    $newPlate = $pieces[0] . '-' . $formattedPlateDigit . $lastTwo;
                                }

                                $data = array();
                                $data['plateno'] = $newPlate;
                                $data['lga'] = $post['lga'];
                                $data['state'] = $post['state'];
                                $data['date_added'] = date("Y-m-d");
                                $data['status'] = 0;
                                $data['pcategory'] = $post['pcategory'];
                                $data['staff'] = My_Auth::getInstance()->getIdentity()->staff_id;
                                $data['mlo'] = Zend_Auth::getInstance()->getIdentity()->mlo_id;
                                $this->_resource->saveItem($data);
                                // echo "last two is " . $newPlate . '<br/>';
                            }
                            // die;
                        }
                    } else {
                        $data = array();
                        $data['plateno'] = $post['plateno'];
                        $data['lga'] = $post['lga'];
                        $data['state'] = $post['state'];
                        $data['date_added'] = date("Y-m-d");
                        $data['status'] = 0;
                        $data['pcategory'] = $post['pcategory'];
                        $data['operator'] = My_Auth::getInstance()->getIdentity()->staff_id;
                        $this->_resource->saveItem($data);
                    }
                    $this->_flashMessenger->addMessage(array('alert alert-success' => "Plate number(s) generated successfully"));
                } catch (Exception $ex) {
                    $this->_flashMessenger->addMessage(array('alert alert-error' => "An error occured while creating the item  <br/> " . $ex->getMessage()));
                }
                $this->_helper->redirector('index');
            }
        }

        $this->view->additem = $form;
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
            // var_dump($post);
            $state = $post['state'];
            $lgaResource = new Default_Resource_Lgas();
            $lgas = $lgaResource->getLgaByState($state);
            if ($lgas->count()) {
                $lgaList = array();
                $lgaList[""] = "Select";
                foreach ($lgas as $lga) {
                    $lgaList[$lga->id] = $lga->name;
                }
                //var_dump($lgaList);
                $form->getElement('lga')->setMultiOptions($lgaList);
            }
            if ($form->isValid($post)) {
                $post = $form->getValues();

                $data = array();

                foreach ($post as $key => $value) {
                    $data[$key] = $value;
                }

                try {
                    $this->_resource->updateItem($data);
                    $this->_flashMessenger->addMessage(array('alert alert-success' => "Mla's information successfully updated"));
                } catch (Exception $ex) {
                    $this->_flashMessenger->addMessage(array('alert alert-error' => "An error occured while updating the " . $this->_itemName . " information <br/> " . $ex->getMessage()));
                }
                $this->_helper->redirector('index');
            } else {
                // exit;
                $form->getElement('lga')->setValue($post['lga']);
                $form->getElement('state')->setValue($post['state']);
            }
        }

        $item = $this->_resource->getItemById($itemId);

        if ($item !== null and !$request->isPost()) {
            $state = $item->state;
            $form->populate($item->toArray());

            //populate local govt
            $lgaResource = new Default_Resource_Lgas();
            $lgas = $lgaResource->getLgaByState($state);
            if ($lgas->count()) {
                $lgaList = array();
                $lgaList[""] = "Select";
                foreach ($lgas as $lga) {
                    $lgaList[$lga->id] = $lga->name;
                }
                //var_dump($lgaList);
                $form->getElement('lga')->setMultiOptions($lgaList);
            }
        }

        $this->view->edititem = $form;
    }

    protected function _getCrudForm() {
        return new System_Form_Plate_Add();
    }

    public function getCrudEditForm() {
        return new System_Form_Plate_Edit();
    }

    public function viewAction() {
        $this->_helper->layout()->disableLayout();
        $id = $this->_getParam('id');
        $this->view->mla = null;
        if ($id !== null) {
            //  $this->_helper->redirector('index');
            $mla = $this->_resource->getMlaById($id);
            $this->view->mla = $mla;
        }
    }

    public function getmlaAction() {
        $term = $_GET['term']; //$this->_getParam('autocomplete');

        $terms = $this->_resource->getMlaByTerm($term);
        //var_dump($terms); die;
        $return = $terms->toArray();
        $this->_helper->json($return);
    }

}

