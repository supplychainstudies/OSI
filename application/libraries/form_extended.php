<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
include_once('form.php');

/***
* Extension of Form library for CodeIgniter
*
*   author: 		Bianca Sayan
* 	copyright: 		(c) 2010
*   license: 		http://creativecommons.org/licenses/by-sa/2.5/
*	file: 			libraries/Form_extended.php
*	description: 	added functions will use xml files to create views of data and also generate database-ready triples
*/

class Form_extended extends Form {

    public function Form_extended () {
		parent::Form();
	} /*** END ***/

    public $action;
    public $data; 
	public $triples = array();
	


    public function build_group_triples ($subject, $post_data, $group, $path = "", $depth = 0, $change_predicates = null) {  
    /***
     * @private
     * Build a fieldset
     */
        static $first_run;
        static $tabindex;
        
        $tabindex = isset ($tabindex) ? $tabindex : 1;

        if ($first_run !== false) {

            $first_run = false;
        }
		
		$triples = array();				
		if ($subject == NULL) {
			$subject = toBNode("");
		}
		if (isset($group['__attrs']['linked_type']) == true) {
			if (isset($change_predicates) == true) {
				if (isset($change_predicates[$group['__attrs']['name']]) == true) {
					$group['__attrs']['linked_type'] = $change_predicates[$group['__attrs']['name']];
				}
			}
			$new_subject_attrs = toBNode($group['__attrs']['name']);
			$triples_attrs[] = array(
				'subject' => $subject,
				'predicate' => $group['__attrs']['linked_type'],
				'object' => $new_subject_attrs 
			);
		} else {
			$new_subject_attrs = $subject;
		}
		
        foreach ($group as $name => $val) {
            if ($name == '__attrs') {
                continue;
            }
            elseif ($name == 'fieldset') {
                foreach ($val as $_group) {	
					$count = 0;
					if (isset($_group['__attrs']['root']) == true) {
						$_group = $this->load($_group['__attrs']['root']);
					}
					if (isset($_group['__attrs']['multiple']) == true) {
						
						if (is_array($path) == true) {
							$count_var = str_replace(" ", "_", strtolower($_group['__attrs']['name'])) . "_counter_" . implode("-", $path). "-" . "0";
						} else {
							$count_var = str_replace(" ", "_", strtolower($_group['__attrs']['name'])) . "_counter_0";
						}
						if (isset($post_data[$count_var]) == true) {
							$count = $post_data[$count_var];
						}				
					}
					
					
					for ($i = 0 ; $i<= $count ; $i++) {
						//$new_path = "";
						$new_path = $path;
						if (isset($_group['__attrs']['multiple']) == true) {
							//$new_path = $path;
							if ($new_path == "") {
								$new_path = array($i);
							}
							else {
								$new_path[] = $i;
							}	
						} 
						
						$triples_hold = $this->build_group_triples ($new_subject_attrs, $post_data, $_group, $new_path, $depth+1, $change_predicates);
						if (count($triples_hold) > 0 && count($triples) > 0 ) {
							$triples = array_merge($triples, $triples_hold);
						}
						elseif (count($triples_hold) > 0 && count($triples) <= 0 ) {
							$triples = $triples_hold;
						}						
					}

                }				
            }
            else {
				$name = $name."_";
				$values = "";
				if (isset($post_data[$name]) == true) {
					if (is_array($path) == true && is_array($post_data[$name]) == true) {
						$multiple = "[" . implode("][", $path) . "]";
						eval("\$values = \$post_data[\"$name\"]$multiple;");					
					} else {
						$values = $post_data[$name];
					}
					if (is_array($values) == false && $values != "") {
						$values = array($values);
					}
				} elseif (isset($val[0]['value']) == true && isset($val[0]['linked_type']) == true ) {
					$values = array($val[0]['value'][0]);			
				}
				if (isset($val[0]['linked_type']) == true && is_array($values) == true) {		
					foreach ($values as $value){
						if ($value != "") {
							foreach (explode("|", $val[0]['__attrs']['rules']) as $dothis) {
								if ($dothis == "trim") {
									$value = trim($value);
								}
								if ($dothis == "uriparse") {
									$value = str_replace("_", ":", $value);
								}
								if ($dothis == "sha1") {
									$value = sha1($value);
								}							
							}
							$triples[] = array(
								'subject' => $new_subject_attrs,
								'predicate' => $val[0]['linked_type'][0],
								'object' => $value
							);
						}
					}
				}
            }             
        }  
		if(isset($triples) == true && isset($triples_attrs) == true) {
			if (count($triples) > 0 ) {
				return array_merge($triples_attrs, $triples);
			}			
		} elseif(isset($triples) == true && isset($triples_attrs) != true) {
			if (count($triples) > 0 ) {
				return $triples;
			}			
		} elseif(isset($triples) != true && isset($triples_attrs) == true) {
				return $triples_attrs;		
		}
    } /* END build_group */




	public function build_views ($xarray, $group, $depth = 0) {  

	        static $first_run;
	        static $tabindex;

	        $tabindex = isset ($tabindex) ? $tabindex : 1;

	        if ($first_run !== false) {

	            $first_run = false;
	        }
	        $tabs = repeater("\t", $depth);
	
			//$html_header = "<h". $depth . ">" . $group['__attrs']['linked_type']) . "</h" . $depth . ">\n";
			//$html_content = "";
			
			$html_before = "$tabs\t".'<ul class="layout">'."\n";
			$html = "";
	        foreach ($group as $name => $val) {
	            if ($name == '__attrs') {
	                continue;
	            }
	            elseif ($name == 'fieldset') {			
	                foreach ($val as $_group) {	
						if (isset($_group['__attrs']['linked_type']) == true) {
							if(isset($xarray[str_replace("lca:", "http://footprinted.org/vocab#", $_group['__attrs']['linked_type'])]) == true) {
								$html .= "<div class=\"level".$depth."\"><h1 class=\"level".$depth."\">" . $_group['__attrs']['name'] . "</h1>";
								foreach ($xarray[str_replace("lca:", "http://footprinted.org/vocab#", $_group['__attrs']['linked_type'])] as $_xarray) {
									$html .= "$tabs\t<li>\n". $this->build_views ($_xarray, $_group, $depth+1) ."$tabs\t</li>\n";
								}
								$html .= "</div>";
							}
						}
	                }				
	            }
	            else {
					if (isset($val[0]['linked_type'][0]) == true) {
						if(isset($xarray[str_replace("lca:", "http://footprinted.org/vocab#", $val[0]['linked_type'][0])]) == true) {
						foreach ($xarray[str_replace("lca:", "http://footprinted.org/vocab#", $val[0]['linked_type'][0])] as $value) {
								$html .= "$tabs\t<li>\n".$val[0]['label'][0]." - ".$value."</li>\n";
							}
						}
					}
	            }             
	        } 
	 	    $html_after = "$tabs\t".'</ul>'."\n";
			if ($html != "") {
				return $html_before.$html.$html_after;
			}
	   
	}















		public function build_edit ($xarray, $group, $depth = 0, $multiple = "") {  
		    /***
		     * @private
		     * Build a fieldset
		     */
		        static $first_run;
		        static $tabindex;

				$valid_attr = array(
		            'type', 'maxlength', 'value', 'options',
		            'selected', 'checked', 'rows', 'cols', 'size',
		            'onclick', 'onmouseover', 'onmouseout', 'onchange'
		        );
		        $valid_type = array(
		            'text', 'textarea', 'password', 'file',
		            'dropdown', 'radio', 'checkbox',
		            'submit', 'button', 'hidden', 'open'
		        );
				$tabs = "";
				$fieldset_name = str_replace(" ", "_", strtolower($group['__attrs']['name']));
		        $tabindex = isset ($tabindex) ? $tabindex : 1;

		        if ($first_run !== false) {

		            $first_run = false;
		        }
		        $tabs = repeater("\t", $depth);
		/*
				$html_header = "<h". $depth . ">" . $group['__attrs']['linked_type']) . "</h" . $depth . ">\n";
				$html_content = "";

				*/
				$html_before = "$tabs\t".'<ul class="layout">'."\n";
				$html = "";
		        foreach ($group as $name => $val) {
		            if ($name == '__attrs') {
		                continue;
		            }
		            elseif ($name == 'fieldset') {			
		                foreach ($val as $_group) {	
							if (isset($_group['__attrs']['linked_type']) == true) {
								if(isset($xarray[str_replace("lca:", "http://footprinted.org/vocab#", $_group['__attrs']['linked_type'])]) == true) {
									$html .= "<h1 class=\"level".$depth."\">" . $_group['__attrs']['name'] . "</h1><div class=\"level".$depth."\">";
									foreach ($xarray[str_replace("lca:", "http://footprinted.org/vocab#", $_group['__attrs']['linked_type'])] as $_xarray) {
										$html .= "$tabs\t<li>\n". $this->build_edit ($_xarray, $_group, $depth+1, $multiple) ."$tabs\t</li>\n";
									}
									$html .= "</div>";
								}
							}
		                }				
		            }
		            else {
						if (isset($val[0]['linked_type'][0]) == true) {
							$_val = $val;
							if(isset($xarray[str_replace("lca:", "http://footprinted.org/vocab#", $val[0]['linked_type'][0])]) == true) {
								
								foreach ($xarray[str_replace("lca:", "http://footprinted.org/vocab#", $val[0]['linked_type'][0])] as $instance => $value) {
									
									foreach ($val as $index => $def) {
				                    foreach ($def as $key => $val) {
				                        if ($key == '__attrs') {
				                            unset ($def[$key]);
				                            continue;
				                        }

				                        $def[$key] = $val[0];
				                    }
									$label = $def['label'];
					                $def['value'] = $value;	
									$idname = sprintf('name="%s"', $instance . "-" . $name);
																			
										// Add "*" on required items
				                    $label = isset($this->rules[$name]) && in_array('required', explode('|', $this->rules[$name]))
				                        ? "$label <em>*</em>"
				                        : $label;

				                    $row  = "";
				                    $row .= $def['type'] != 'hidden'
				                        ? "$tabs\t<li>\n"
				                        : '';

				                    $row .= $def['type'] != 'submit' && $def['type'] != 'hidden' &&  $def['type'] != 'button'
				                        ? "$tabs\t\t<label>$label</label>\n" : '';

				                    // Handle non-input elements
				                    switch ($def['type']) {
				                    case 'textarea':
				                        $input  = "$tabs\t\t<textarea $idname %s>". $def['value'] ."</textarea>\n";
				                        unset ($def['type'], $def['value']);
				                        break;  
				                    case 'dropdown':
				                        $def['value'] != false && $def['selected'] = $def['value'];
				                        unset ($def['value']);

				                        if (isset ($def['options'])) {
				                            $options = '<option></option>';
				                            foreach ($def['options'] as $_key => $_val) {
				                                $_val = is_array ($_val) ? $_val[0] : $_val;

				                                $sel = isset ($def['selected']) && $def['selected'] == $_key
				                                    ? ' selected="selected"' : '';

				                                $options .= "$tabs\t\t\t<option value=\"$_key\"$sel>$_val$tabs</option>\n";
				                            }
				                            unset ($def['type'], $def['options'], $def['selected']);

				                            $input = "$tabs\t\t<select $idname %s >\n$options$tabs\t\t</select>\n";
				                        }
				                        else {
				                            continue(2);
				                        }
				                        break;
				                    case 'hidden':
				                        $input = "$tabs\t<input $idname %s />\n";
				                        break;
									case 'submit':
				                    	$input = "$tabs\t<input type=\"image\" style=\"margin-left: 300px\" src=\"/assets/images/submit.gif\" />\n";
				                    	break;					
				                    default:
				                        $input = "$tabs\t\t<input ".$idname. " value=\"".$def["value"]."\" />\n";
				                    }     
				                    // Parse attributes
				                    $attributes = '';
				                    foreach ($def as $attr => $val) {
				                        if (in_array ($attr, $valid_attr)) {
				                            if ($attr == 'checked' && $val != false) {
				                                $val = 'checked';
				                            }
				                            elseif ($attr == 'checked') {
				                                continue;
				                            }

				                            $attributes .= " $attr=\"$val\" ";
				                        }
				                    }

				                    $row .= sprintf($input, $attributes). "<img src=\"/assets/images/delete.jpg\" onClick=\"toggle_delete('".$instance."-".$name."')\">";


									if (isset($def['note']) == true) {
										$row .= "<div class=\"note\">".$def['note']."</div>";
									}					
				                     $row .= isset($def['type']) && $def['type'] == 'hidden'
				                        ? ''
				                        : "$tabs\t</li>\n";

				                    $html .= "$row";								
								} 
							} 
						} // End of foreach ($xarray[str_replace("lca:", "http://footprinted.org/vocab#", $val[0]['linked_type'][0])] as $instance => $value)
					//} // End of if(isset($xarray[str_replace("lca:", "http://footprinted.org/vocab#", $val[0]['linked_type'][0])]) == true)
							
						// Add blank fields at the end	
						if (isset($_val[0]['linked_type']) == true) {				
						if (isset($xarray[str_replace("lca:", "http://footprinted.org/vocab#", $_val[0]['linked_type'][0])]) != true || isset($_val[0]['multiple']) == true) {
		                	foreach ($_val as $index => $def) {
		                    foreach ($def as $key => $val) {
		                        if ($key == '__attrs') {
		                            unset ($def[$key]);
		                            continue;
		                        }
		                        $def[$key] = $val[0];
		                    }

		                    // Skips defs that have no type attribute
		                    if (! isset($def['type']) || ! in_array ($def['type'], $valid_type)) {
		                        continue;
		                    }

		                    // Externally set data is present, merge with stored efinition
		                    if (isset ($this->set_data[$name]) && is_array ($this->set_data[$name])) {
		                        if ($def['type'] == 'checkbox' && isset ($this->set_data[$name]['value'])) {
		                            $this->set_data[$name]['checked'] = (bool)$this->set_data[$name]['value'];
		                            unset ($this->set_data[$name]['value']);    
		                        }
		                        elseif ($def['type'] == 'submit' && isset ($this->set_data[$name]['value'])) {
		                            unset ($this->set_data[$name]['value']);    
		                        }

		                        $def = array_merge($def, $this->set_data[$name]);
		                    }

		                    // We always want a default value
		                    $def['value'] = isset($def['value'])
		                        ? $def['value'] : '';

		                    // Choose a label
		                    $label = isset($def['label'])
		                         ? ucwords($def['label'])
		                        : ucwords($name);

							if ($def['type'] == 'hidden') {
								$idname = sprintf('name="%s"', $name);
							} elseif (isset($def['multiple']) == true) {
								$field_multiple = $multiple . "[0]";
								$idname = sprintf('name="%s"', $name."_".$field_multiple);
							} else {
		                        $idname = sprintf('tabindex="%s" name="%s"', $tabindex++, $name."_".$multiple);
							}

		                    // Add "*" on required items
		                    $label = isset($this->rules[$name]) && in_array('required', explode('|', $this->rules[$name]))
		                        ? "$label <em>*</em>"
		                        : $label;

		                    $row  = "";
		                    $row .= $def['type'] != 'hidden'
		                        ? "$tabs\t<li>\n"
		                        : '';

		                    $row .= $def['type'] != 'submit' && $def['type'] != 'hidden'
		                        ? "$tabs\t\t<label>$label</label>\n" : '';

		                    // Handle non-input elements
		                    switch ($def['type']) {
		                    case 'textarea':
		                        $input  = "$tabs\t\t<textarea $idname %s>". $def['value'] ."</textarea>\n";
		                        unset ($def['type'], $def['value']);
		                        break;  
		                    case 'dropdown':
		                        $def['value'] != false && $def['selected'] = $def['value'];
		                        unset ($def['value']);

		                        if (isset ($def['options'])) {
		                            $options = '<option></option>';
		                            foreach ($def['options'] as $_key => $_val) {
		                                $_val = is_array ($_val) ? $_val[0] : $_val;

		                                $sel = isset ($def['selected']) && $def['selected'] == $_key
		                                    ? ' selected="selected"' : '';

		                                $options .= "$tabs\t\t\t<option value=\"$_key\"$sel>$_val$tabs</option>\n";
		                            }
		                            unset ($def['type'], $def['options'], $def['selected']);

		                            $input = "$tabs\t\t<select $idname %s >\n$options$tabs\t\t</select>\n";
		                        }
		                        else {
		                            continue(2);
		                        }
		                        break;
		                    case 'hidden':
		                        $input = "$tabs\t<input $idname %s style=\"display:none;\" />\n";
		                        break;
							case 'submit':
		                    	$input = "$tabs\t<input type=\"image\" style=\"margin-left: 300px\" src=\"/assets/images/submit.gif\" />\n";
		                    	break;					
		                    default:
		                        $input = "$tabs\t\t<input $idname %s />\n";
		                    }     
		                    // Parse attributes
		                    $attributes = '';
		                    foreach ($def as $attr => $val) {
		                        if (in_array ($attr, $valid_attr)) {
		                            if ($attr == 'checked' && $val != false) {
		                                $val = 'checked';
		                            }
		                            elseif ($attr == 'checked') {
		                                continue;
		                            }

		                            $attributes .= " $attr=\"$val\" ";
		                        }
		                    }

		                    $row .= sprintf($input, $attributes);

							if (isset($def['note']) == true) {
								$row .= "<div class=\"note\">".$def['note']."</div>";
							}					
		                     $row .= isset($def['type']) && $def['type'] == 'hidden'
		                        ? ''
		                        : "$tabs\t</li>\n";

							if (isset($def['multiple']) == true) {
								$multiple_string = str_replace("]", "",str_replace("[", "", str_replace("][", "-", $field_multiple)));
								$row =  "<div id=\"div_".$name."\">".$row."</div>$tabs\t\t<div id=\"div_multiple_".$name."_".$multiple_string."\" class=\"level".$depth."\"></div><img src=\"http://".$_SERVER['SERVER_NAME']."/assets/images/button".$depth.".gif\"  value=\"Another &gt;&gt;\" onClick=\"addField('".$name."', '".$multiple_string."')\" /><input type=\"hidden\" id=\"".$name."_counter_".$multiple_string."\" name=\"".$name."_counter_".$multiple_string."\" value=\"0\">\n";
							}
		                    $html .= "$row";
		                } // End of 						                       
						}
		        			} // End of foreach ($val as $index => $def)
						} // End of if (isset($val[0]['multiple']) == true)
				} // End of if (isset($val[0]['linked_type'][0]) == true)
			}
		 	    $html_after = "$tabs\t".'</ul>'."\n";
				if (isset($group['__attrs']['multiple']) == true) {
					$multiple_string = str_replace("]", "",str_replace("[", "", str_replace("][", "-", $multiple)));
					$html .= "$tabs".''."<div id=\"div_multiple_".$fieldset_name."_".$multiple_string."\" class=\"level".$depth."\"></div><img src=\"http://".$_SERVER['SERVER_NAME']."/assets/images/tab".$depth.".gif\" value=\"Another &gt;&gt;\" class=\"more\" onClick=\"addField('".$fieldset_name."', '".$multiple_string."')\" /><input type=\"hidden\" id=\"".$fieldset_name."_counter_".$multiple_string."\"  name=\"".$fieldset_name."_counter_".$multiple_string."\" value=\"0\">\n";
				} else {
		        	$html .= "$tabs".''."\n";			
				}
				if ($html != "") {
					return $html_before.$html.$html_after;
				}
		    } /* END build_group */





















			public function build_flat_edit ($xarray, $group, $depth = 0, $multiple = "") {  

			    /***
			     * @private
			     * Build a fieldset
			     */
			        static $first_run;
			        static $tabindex;

					$valid_attr = array(
			            'type', 'maxlength', 'value', 'options',
			            'selected', 'checked', 'rows', 'cols', 'size',
			            'onclick', 'onmouseover', 'onmouseout', 'onchange'
			        );
			        $valid_type = array(
			            'text', 'textarea', 'password', 'file',
			            'dropdown', 'radio', 'checkbox',
			            'submit', 'button', 'hidden', 'open'
			        );
					$tabs = "";
					$fieldset_name = str_replace(" ", "_", strtolower($group['__attrs']['name']));
			        $tabindex = isset ($tabindex) ? $tabindex : 1;

			        if ($first_run !== false) {

			            $first_run = false;
			        }
			        $tabs = repeater("\t", $depth);
			/*
					$html_header = "<h". $depth . ">" . $group['__attrs']['linked_type']) . "</h" . $depth . ">\n";
					$html_content = "";

					*/
					$html_before = "$tabs\t".'<ul class="layout">'."\n";
					$html = "";
			        foreach ($group as $name => $val) {
			            if ($name == '__attrs') {
			                continue;
			            }
			            elseif ($name == 'fieldset') {			
			                foreach ($val as $_group) {	
								if (isset($_group['__attrs']['root']) == true) {
									$_group = $this->load($_group['__attrs']['root']);
								}
								if (isset($_group['__attrs']) == true) {
								if(isset($xarray[$_group['__attrs']['name']]) == true) {
									$html .= "<h1 class=\"level".$depth."\">" . $_group['__attrs']['name'] . "</h1><div class=\"level".$depth."\">";
									foreach ($xarray[$_group['__attrs']['name']] as $_xarray) {
										$html .= "$tabs\t<li>\n". $this->build_flat_edit ($_xarray, $_group, $depth+1, $multiple) ."$tabs\t</li>\n";
									}
								
									$html .= "</div>";
								}
								}
			                }				
			            }
			            else {
								$_val = $val;
								if(isset($xarray[$name]) == true && $name != "blank") {
									//var_dump($xarray);
									if(is_array($xarray[$name]) == false) {
										$pass_array = array ($xarray[$name]);
									} else {
										$pass_array = $xarray[$name];
									}
								
									foreach ($pass_array as $instance => $value) {
										foreach ($val as $index => $def) {
					                    foreach ($def as $key => $val) {
					                        if ($key == '__attrs') {
					                            unset ($def[$key]);
					                            continue;
					                        }

					                        $def[$key] = $val[0];
					                    }
										$label = $def['label'];
						                $def['value'] = $value;	
										$idname = sprintf('name="%s"', $instance . "-" . $name);

											// Add "*" on required items
					                    $label = isset($this->rules[$name]) && in_array('required', explode('|', $this->rules[$name]))
					                        ? "$label <em>*</em>"
					                        : $label;

					                    $row  = "";
					                    $row .= $def['type'] != 'hidden'
					                        ? "$tabs\t<li>\n"
					                        : '';

					                    $row .= $def['type'] != 'submit' && $def['type'] != 'hidden' &&  $def['type'] != 'button'
					                        ? "$tabs\t\t<label>$label</label>\n" : '';

					                    // Handle non-input elements
					                    switch ($def['type']) {
					                    case 'textarea':
					                        $input  = "$tabs\t\t<textarea $idname %s>". $def['value'] ."</textarea>\n";
					                        unset ($def['type'], $def['value']);
					                        break;  
					                    case 'dropdown':
					                        $def['value'] != false && $def['selected'] = $def['value'];
					                        unset ($def['value']);

					                        if (isset ($def['options'])) {
					                            $options = '<option></option>';
					                            foreach ($def['options'] as $_key => $_val) {
					                                $_val = is_array ($_val) ? $_val[0] : $_val;

					                                $sel = isset ($def['selected']) && $def['selected'] == $_key
					                                    ? ' selected="selected"' : '';

					                                $options .= "$tabs\t\t\t<option value=\"$_key\"$sel>$_val$tabs</option>\n";
					                            }
					                            unset ($def['type'], $def['options'], $def['selected']);

					                            $input = "$tabs\t\t<select $idname %s >\n$options$tabs\t\t</select>\n";
					                        }
					                        else {
					                            continue(2);
					                        }
					                        break;
					                    case 'hidden':
					                        $input = "$tabs\t<input $idname %s />\n";
					                        break;
										case 'submit':
					                    	$input = "$tabs\t<input type=\"image\" style=\"margin-left: 300px\" src=\"/assets/images/submit.gif\" />\n";
					                    	break;					
					                    default:
					                        $input = "$tabs\t\t<input ".$idname. " value=\"".$def["value"]."\" />\n";
					                    }     
					                    // Parse attributes
					                    $attributes = '';
					                    foreach ($def as $attr => $val) {
					                        if (in_array ($attr, $valid_attr)) {
					                            if ($attr == 'checked' && $val != false) {
					                                $val = 'checked';
					                            }
					                            elseif ($attr == 'checked') {
					                                continue;
					                            }

					                            $attributes .= " $attr=\"$val\" ";
					                        }
					                    }

					                    $row .= sprintf($input, $attributes). "<img src=\"/assets/images/delete.jpg\" onClick=\"toggle_delete('".$instance."-".$name."')\">";


										if (isset($def['note']) == true) {
											$row .= "<div class=\"note\">".$def['note']."</div>";
										}					
					                     $row .= isset($def['type']) && $def['type'] == 'hidden'
					                        ? ''
					                        : "$tabs\t</li>\n";

					                    $html .= "$row";								
									} 
								} 
							} // End of foreach ($xarray[str_replace("lca:", "http://footprinted.org/vocab#", $val[0]['linked_type'][0])] as $instance => $value)
						//} // End of if(isset($xarray[str_replace("lca:", "http://footprinted.org/vocab#", $val[0]['linked_type'][0])]) == true)

							// Add blank fields at the end	
							if (isset($_val[0]['linked_type']) == true) {				
							if (isset($xarray[str_replace("lca:", "http://footprinted.org/vocab#", $_val[0]['linked_type'][0])]) != true || isset($_val[0]['multiple']) == true) {
			                	foreach ($_val as $index => $def) {
			                    foreach ($def as $key => $val) {
			                        if ($key == '__attrs') {
			                            unset ($def[$key]);
			                            continue;
			                        }
			                        $def[$key] = $val[0];
			                    }

			                    // Skips defs that have no type attribute
			                    if (! isset($def['type']) || ! in_array ($def['type'], $valid_type)) {
			                        continue;
			                    }

			                    // Externally set data is present, merge with stored efinition
			                    if (isset ($this->set_data[$name]) && is_array ($this->set_data[$name])) {
			                        if ($def['type'] == 'checkbox' && isset ($this->set_data[$name]['value'])) {
			                            $this->set_data[$name]['checked'] = (bool)$this->set_data[$name]['value'];
			                            unset ($this->set_data[$name]['value']);    
			                        }
			                        elseif ($def['type'] == 'submit' && isset ($this->set_data[$name]['value'])) {
			                            unset ($this->set_data[$name]['value']);    
			                        }

			                        $def = array_merge($def, $this->set_data[$name]);
			                    }

			                    // We always want a default value
			                    $def['value'] = isset($def['value'])
			                        ? $def['value'] : '';

			                    // Choose a label
			                    $label = isset($def['label'])
			                         ? ucwords($def['label'])
			                        : ucwords($name);

								if ($def['type'] == 'hidden') {
									$idname = sprintf('name="%s"', $name);
								} elseif (isset($def['multiple']) == true) {
									$field_multiple = $multiple . "[0]";
									$idname = sprintf('name="%s"', $name."_".$field_multiple);
								} else {
			                        $idname = sprintf('tabindex="%s" name="%s"', $tabindex++, $name."_".$multiple);
								}

			                    // Add "*" on required items
			                    $label = isset($this->rules[$name]) && in_array('required', explode('|', $this->rules[$name]))
			                        ? "$label <em>*</em>"
			                        : $label;

			                    $row  = "";
			                    $row .= $def['type'] != 'hidden'
			                        ? "$tabs\t<li>\n"
			                        : '';

			                    $row .= $def['type'] != 'submit' && $def['type'] != 'hidden'
			                        ? "$tabs\t\t<label>$label</label>\n" : '';

			                    // Handle non-input elements
			                    switch ($def['type']) {
			                    case 'textarea':
			                        $input  = "$tabs\t\t<textarea $idname %s>". $def['value'] ."</textarea>\n";
			                        unset ($def['type'], $def['value']);
			                        break;  
			                    case 'dropdown':
			                        $def['value'] != false && $def['selected'] = $def['value'];
			                        unset ($def['value']);

			                        if (isset ($def['options'])) {
			                            $options = '<option></option>';
			                            foreach ($def['options'] as $_key => $_val) {
			                                $_val = is_array ($_val) ? $_val[0] : $_val;

			                                $sel = isset ($def['selected']) && $def['selected'] == $_key
			                                    ? ' selected="selected"' : '';

			                                $options .= "$tabs\t\t\t<option value=\"$_key\"$sel>$_val$tabs</option>\n";
			                            }
			                            unset ($def['type'], $def['options'], $def['selected']);

			                            $input = "$tabs\t\t<select $idname %s >\n$options$tabs\t\t</select>\n";
			                        }
			                        else {
			                            continue(2);
			                        }
			                        break;
			                    case 'hidden':
			                        $input = "$tabs\t<input $idname %s style=\"display:none;\" />\n";
			                        break;
								case 'submit':
			                    	$input = "$tabs\t<input type=\"image\" style=\"margin-left: 300px\" src=\"/assets/images/submit.gif\" />\n";
			                    	break;					
			                    default:
			                        $input = "$tabs\t\t<input $idname %s />\n";
			                    }     
			                    // Parse attributes
			                    $attributes = '';
			                    foreach ($def as $attr => $val) {
			                        if (in_array ($attr, $valid_attr)) {
			                            if ($attr == 'checked' && $val != false) {
			                                $val = 'checked';
			                            }
			                            elseif ($attr == 'checked') {
			                                continue;
			                            }

			                            $attributes .= " $attr=\"$val\" ";
			                        }
			                    }

			                    $row .= sprintf($input, $attributes);

								if (isset($def['note']) == true) {
									$row .= "<div class=\"note\">".$def['note']."</div>";
								}					
			                     $row .= isset($def['type']) && $def['type'] == 'hidden'
			                        ? ''
			                        : "$tabs\t</li>\n";

								if (isset($def['multiple']) == true) {
									$multiple_string = str_replace("]", "",str_replace("[", "", str_replace("][", "-", $field_multiple)));
									$row =  "<div id=\"div_".$name."\">".$row."</div>$tabs\t\t<div id=\"div_multiple_".$name."_".$multiple_string."\" class=\"level".$depth."\"></div><img src=\"http://".$_SERVER['SERVER_NAME']."/assets/images/button".$depth.".gif\"  value=\"Another &gt;&gt;\" onClick=\"addField('".$name."', '".$multiple_string."')\" /><input type=\"hidden\" id=\"".$name."_counter_".$multiple_string."\" name=\"".$name."_counter_".$multiple_string."\" value=\"0\">\n";
								}
			                    $html .= "$row";
			                } // End of 						                       
							}
			        			} // End of foreach ($val as $index => $def)
							} // End of if (isset($val[0]['multiple']) == true)
				}
			 	    $html_after = "$tabs\t".'</ul>'."\n";
					if (isset($group['__attrs']['multiple']) == true) {
						$multiple_string = str_replace("]", "",str_replace("[", "", str_replace("][", "-", $multiple)));
						$html .= "$tabs".''."<div id=\"div_multiple_".$fieldset_name."_".$multiple_string."\" class=\"level".$depth."\"></div><img src=\"http://".$_SERVER['SERVER_NAME']."/assets/images/tab".$depth.".gif\" value=\"Another &gt;&gt;\" class=\"more\" onClick=\"addField('".$fieldset_name."', '".$multiple_string."')\" /><input type=\"hidden\" id=\"".$fieldset_name."_counter_".$multiple_string."\"  name=\"".$fieldset_name."_counter_".$multiple_string."\" value=\"0\">\n";
					} else {
			        	$html .= "$tabs".''."\n";			
					}
					if ($html != "") {
						return $html_before.$html.$html_after;
					}
			    } /* END build_group */





}

?>