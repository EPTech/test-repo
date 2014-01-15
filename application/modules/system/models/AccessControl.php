<?php
class System_Model_AccessControl
{
    public static function build()
    {
    	set_time_limit(0);//increase maximum execution time because this might take a while
    	
    	$front = Zend_Controller_Front::getInstance();
    	
        $module_dir = substr(
        str_replace("\\", "/", 
        $front->getModuleDirectory()), 0, 
        strrpos(
        str_replace("\\", "/", 
        $front->getModuleDirectory()), '/'));
        $temp = array_diff(scandir($module_dir), Array(".", "..", ".svn"));
        $modules = array();
        $controller_directorys = array();
        foreach ($temp as $module) {
            if (is_dir($module_dir . "/" . $module)) {
                array_push($modules, $module);
                array_push($controller_directorys, 
                str_replace("\\", "/", 
                $front->getControllerDirectory($module)));
            }
        }
        
        $resources = new System_Model_DbTable_Resources();
        $actions = new Zend_Db_Table('resource_actions');
        $permissions = new System_Model_DbTable_Permissions();
        
        $resources->delete(array());
        $actions->delete(array());
        $permissions->delete(array());
        
        foreach ($controller_directorys as $dir) {
            foreach (scandir($dir) as $dirstructure) {
                $file = $dir . "/" . $dirstructure;
                if (is_file($file)) {
                    if (strstr($dirstructure, "Controller.php") != false &&
                     $dirstructure != 'ErrorController.php') {
                        require_once ($dir . "/" . $dirstructure);
                        $fileReflection = new Zend_Reflection_File($file);
                        $classReflections = $fileReflection->getClasses();
                        foreach ($classReflections as $classReflection) {
                            if ($classReflection->isSubClassOf(
                            'Zend_Controller_Action')) {
                                $className = $classReflection->getName();
                                if (strstr($className, "_") != false) {
                                    $module = strtolower(
                                    substr($className, 0, 
                                    strpos($className, "_")));
                                    $className = substr($className, 
                                    strpos($className, "_") + 1);
                                } else {
                                    $module = $front->getDefaultModule();
                                }
                                $controller = strtolower(
                                substr($className, 0, 
                                strpos($className, "Controller")));
                                
                                $resourceId = strtolower($module . ':' . $controller);
                                $resources->insert(
                                		array('id' => $resourceId),
                                		array('module' => $module),
                                		array('controller' => $controller));
                                try{
                                $permissions->insert(array(
                                	'role_id' => '001',
                                	'resource_id' => $resourceId	
                                ));
                                }
                                catch(Exception $e){
                                    echo $e->getMessage(); 
                                }
                               // exit;
                                foreach ($classReflection->getMethods(
                                ReflectionMethod::IS_PUBLIC) as $methodReflection) {
                                    $methodName = $methodReflection->getName();
                                    try {
                                        $docblock = $methodReflection->getDocblock();
                                        $methodDescription = $docblock->getShortDescription();
                                        $description = $methodDescription ? $methodDescription : 'No description';
                                    } catch (Exception $ex) {
                                        $description = 'No description';
                                    }
                                    if (strstr($methodName, "Action") != false) {
                                        $action = strtolower(
                                        substr($methodName, 0, 
                                        strpos($methodName, "Action")));
                                        
                                        $actions->insert(
	                                        array(
	                                        	'resource_id' => $resourceId,
	                                        	'action' => $action,  
	                                        	'description' => $description
	                                        )
                                        );
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}