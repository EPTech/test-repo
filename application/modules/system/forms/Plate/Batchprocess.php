<?php

class System_Form_Plate_Batchprocess extends My_Bootstrap_Form {

    public function init() {

        $this->setMethod('post');
        //$this->setAction('');
        $this->setAttrib('enctype', 'multipart/form-data');
        $this->setAttrib('class', 'form-horizontal');
        // $fileDestination = realpath(APPLICATION_PATH . '/../public/images/importfiles/');


 $stateResource = new Default_Resource_States();
        $stateList = array();
        $stateList[""] = "Select";
        foreach ($stateResource->getStates() as $state) {
            $stateList[$state->state_id] = $state->state;
        }
        
        $this->addElement('file', 'dbfile', array(
            'label' => 'File',
            'description' => 'only files with the extensions: .xls, .ods, .xlsx are allowed',
            'required' => true,
            //'destination' => $fileDestination,

            'validators' => array(
                array('Count', false, array(1)),
                array('Size', false, array(1048576 * 5)),
                array('Extension', false, array('xls,xlsx,ods')),
            ),
            'decorators' => array(
                array('Description', array('class' => 'alert alert-info')),
                "File",
                
                
            )
        ));

        $pcategory = new Zend_Db_Table("plate_categories");
        $pcatSelect = $pcategory->select();
        $pcategories = $pcategory->fetchAll($pcatSelect);
        $pcatList = array();
        $pcatList[""] = "Select";
        foreach ($pcategories as $pcat) {
            $pcatList[$pcat->cat_id] = $pcat->category;
        }

        $this->addElement('select', 'pcategory', array(
            'label' => 'Plate category',
            'required' => true,
            'multiOptions' => $pcatList
        ));


        //state
        $this->addElement('select', 'state', array(
            'label' => 'State',
            'required' => true,
            'multiOptions' => $stateList
        ));

        //lga
        $lgaList = array();
        $lgaList[""] = "Select";
        $this->addElement('select', 'lga', array(
            'label' => 'Lga',
            'required' => true,
            'multiOptions' => $lgaList,
            'registerInArrayValidator' => false
        ));


        $this->addElement('submit', 'add', array(
            'class' => 'btn btn-primary',
            'label' => 'Upload',
        ));


        $this->setDecorators(array(
            array('Description', array('class' => 'alert alert-error')),
            "FormElements",
            'Form'
        ));
    }

}

?>