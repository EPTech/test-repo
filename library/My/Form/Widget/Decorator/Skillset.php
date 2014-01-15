<?php

class My_Form_Widget_Decorator_Skillset extends Zend_Form_Decorator_Abstract{
	public function render($content)
    {
        $element = $this->getElement();
        if (!$element instanceof My_Form_Widget_Skillset) {
            // only want to render Date elements
            return $content;
        }

 
        $view = $element->getView();
        if (!$view instanceof Zend_View_Interface) {
            // using view helpers, so do nothing if no view present
            return $content;
        }
        
        $view->headScript()->appendFile(
		    '/js/jquery.js',
		    'text/javascript'
		)->appendScript("
    		$(function(){
    			$('.widget .add').click(function(e){
    				e.preventDefault();
    				var origRow = $($(this).parents('.row').get(0));
    				var origName = origRow.parent().attr('name');
    				var rowIndex = origRow.index();
    				var clone = origRow.clone();
    				clone.find('.add').removeClass('add').addClass('remove').text('[Remove]');
    				
    				var inputs = origRow.before(clone).find(':input');
    				
    				rowIndex++;
    				
    				$.each(inputs, function(index, item){
    					$(clone.find(':input').get(index)).val($(item).val());
    					$(item).attr('name', origName + '['+ (rowIndex) +']' + '[' +$(item).attr('key') + ']').val('');
                                        
                                        if($(item).is(':checkbox')){
                                            $(item).attr('checked', false);
                                        }
    				});
    			});
    			
    			$('.widget .remove').live('click', function(e){
    				e.preventDefault();
    				var parentRow = $($(this).parents('.row').get(0));
    				var parentWidget = $(parentRow).parents('.widget').get(0);
    				var origName = $(parentWidget).attr('name');
    				
    				$(parentRow).remove();
    				
    				$(parentWidget).find('.cloneable.row').each(function(rowIndex){
    					$(this).find(':input').each(function(){
    						$(this).attr('name', origName + '['+ (rowIndex) +']' + '[' +$(this).attr('key') + ']');
    					});
    				});
    			});
                        
                        $('.widget input[key=primary]').live('click', function(){
                            $('.widget input[key=primary]').not($(this)).attr('checked', false);
                        });
    		});
    	");
		
        $name  = $element->getFullyQualifiedName();
        $value = $element->getValue();
        //Zend_Debug::dump($value);
        
        $mask = '<div class="cloneable row iblock">
                        <div class="lfloat"><span>%skillsElement%</span><div><small>Skillset</small></div></div>
                        <div class="lfloat"><span>%expElement%</span><div><small>Years</small></div></div>
                        <div class="lfloat"><span>%typeElement%<small>Primary</small></span><div></div></div>
                        <div class="lfloat action"><a class="%buttonCls% nowrap" href="">%buttonLbl%</a></div>
                </div>';
        
        $markup = '<div name="'.$name.'" class="widget">';
        
        $table = new Zend_Db_Table('skillsets');
        $rowSet = $table->fetchAll();
        
        $skillsets = array(''=>'Select Skillset:');
        
        foreach ($rowSet as $row) {
            $skillsets[$row->id] = $row->name;
        }
        
        if(is_array($value) && count($value)){
	        foreach ($value as $key => $skillset){
				$elSkills = $view->formSelect($name.'['.$key.'][skillset_id]',$skillset['skillset_id'],array('key'=>'skillset_id'), $skillsets);
				$elExp = $view->formText($name.'['.$key.'][experience]', $skillset['experience'], array('size'=>'3','key'=>'experience'), null);
				
                                $checked = (isset ($skillset['primary']) && ($skillset['primary'] == '1')) ? 'checked="checked"' : '';
                                $elType = '<input type="checkbox" name="'.$name.'['.$key.'][primary]'.'" value="1" key="primary" '.$checked.'/>';
					
                                $temp = str_replace(
                                            array('%skillsElement%', '%expElement%', '%typeElement%'),
                                            array($elSkills, $elExp, $elType),
                                            $mask
                                        );
                                    
				if(count($value) == ($key + 1)){
                                    $markup .= str_replace(
                                                array('%buttonCls%', '%buttonLbl%'),
                                                array('add', '[Add]'),
                                                $temp
                                            );
				}else{
                                    $markup .= str_replace(
                                                array('%buttonCls%', '%buttonLbl%'),
                                                array('remove', '[Remove]'),
                                                $temp
                                            );
				}
			}
        }else{
			$elSkills = $view->formSelect($name.'[0][skillset_id]',null,array('key'=>'skillset_id'), $skillsets);
			$elExp = $view->formText($name.'[0][experience]', null, array('size'=>'3','key'=>'experience'));
                        $elType = '<input type="checkbox" name="'.$name.'[0][primary]'.'" value="1" key="primary"/>';
					
				
                        $markup .= str_replace(
                                    array('%skillsElement%', '%expElement%', '%typeElement%', '%buttonCls%', '%buttonLbl%'),
                                    array($elSkills, $elExp, $elType, 'add', '[Add]'),
                                    $mask
                                );
        }
        $markup .= '</div>';
 
        switch ($this->getPlacement()) {
            case self::PREPEND:
                return $markup . $this->getSeparator() . $content;
            case self::APPEND:
            default:
                return $content . $this->getSeparator() . $markup;
        }
    }
}