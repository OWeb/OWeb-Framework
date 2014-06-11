<?php

/**
 * @author      Oliver de Cramer (oliverde8 at gmail.com)
 * @copyright    GNU GENERAL PUBLIC LICENSE
 *                     Version 3, 29 June 2007
 *
 * PHP version 5.3 and above
 *
 * LICENSE: This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see {http://www.gnu.org/licenses/}.
 */

namespace Controller\OWeb\widgets\jquery;

/**
 * Allows easily to create a Jquery plugin widget maintaining the main features of it inside.
 *
 * @author De Cramer Oliver
 */
abstract class CodeGenerator extends \OWeb\types\Controller{
	
	//List of all Default values
	private $defValues = array();
	
	//List of all values that must be put even throught they have a default value.
	private $necessary = array();
	
	private $id = null;
	private $class = null;
	
	private $function = null;
	
	
	public function init() {
		$this->InitLanguageFile();
	}

	
	/**
	 * Add an option that this code might have or must have 
	 * 
	 * @param String $name The name of the option
	 * @param String/int $defaultValue The default value this function has
	 * @param Boolean Does the code requires this option to work? 
	 */
	public function addOption($name, $defaultValue, $necessary=false){
		$this->defValues[$name] = $defaultValue;
		if($necessary)
			$this->necessary[$name] = true;
	}
	
	/**
	 * The class of the field this Jquery function that must be called on
	 * 
	 * @param String selector class
	 */
	public function setClassSelector($selector){
		$this->class = $selector;
	}
	
	/**
	 * The od of the field this Jquery function that must be called on
	 * 
	 * @param type selector id
	 */
	public function setIdSelector($selector){
		$this->id = $selector;
	}
	
	/**
	 * Set the name of the Jquery function to call. 
	 * 
	 * @param type $functionName
	 */
	public function setFunction($functionName){
		$this->function = $functionName;
	}


	/**
	 * Generates the jquery code with default values as well
	 * 
	 * @param type $selector
	 * @param type $function
	 * @return string
	 */
	protected function generateJqueryCode($selector, $function){
	
		$code = '$( "'.$selector.'" ).'.$function.'(';
		$optionsShown = false;
		
		
		foreach($this->necessary as $pname => $v){		
			$pvalue = $this->getParam($pname);
			if($pvalue == null)
				$pvalue = $this->defValues[$pname];
			if(!empty($pvalue) && $pvalue != ""){

				if(!$optionsShown){
					$code .= '{';
					$optionsShown = true;
				}else
					$code .=',';

				$code .= "\n $pname : \"$pvalue\"";
			}

		}
		
		foreach($this->getParams() as $pname => $pvalue){
		
			if((!isset($this->necessary[$pname]) && isset($this->defValues[$pname]))
					|| (isset($this->defValues[$pname]) && strtoupper($this->defValues[$pname]) != strtoupper($pvalue) )){
				
				if(!empty($pvalue) && $pvalue != ""){
				
					if(!$optionsShown){
						$code .= '{';
						$optionsShown = true;
					}else
						$code .=',';

					unset($necessary[$pname]);

				$code .= "\n $pname : $pvalue";
				}
			}
		}
		
		
		
		if($optionsShown)
			$code .= "\n}";
		
		$code .= ');';
		
		return $code;
	}

	/**
	 * Makes the necessary verifications to see if the Controller can be displayed. 
	 * Will also generate the selector for the jquery call as well as the code needed 
	 * for it in the html code
	 * 
	 * @return string The Jquery selector
	 * @throws \Controller\OWeb\widgets\jquery\OWeb\Exception If the function hasn't been set up
	 */
	public function prepareDisplay(){
		if($this->id == null && $this->class == null){
			$this->id = (String)(new \OWeb\utils\IdGenerator ());
			$displayId = 'id="'.$this->id.'"';
			$jid = "#".$this->id;
		}else if($this->id == null){
			$displayId = 'class="'.$this->class.'"';
			$jid = ".".$this->class;
		}else{
			$displayId = 'id="'.$this->id.'"';
			$jid = "#".$this->id;
		}
		
		if($this->function == null){
			$ex = new \OWeb\Exception("When creating a Jquery Code Generator you need to call setCallFunction to set up the function that needs to be called");
			$ex = new \OWeb\types\UserException($this->l("Function unset Error Title"), 0, $ex);
			$ex->setUserDescription($this->l("Function unset Error Desc"));
			throw $ex;
		}
		$this->view->id = $displayId;
		
		return $jid;
	}
	
	public function registerHeader(){
		$jid = $this->prepareDisplay();
		//$this->view->addHeader('jquery/jquery.js', \OWeb\manage\Headers::js);
		\OWeb\utils\js\jquery\HeaderOnReadyManager::getInstance()->add($this->generateJqueryCode($jid, $this->function));
	}
	
	public function onDisplay() {
		$this->registerHeader();
	}
	
}

?>
