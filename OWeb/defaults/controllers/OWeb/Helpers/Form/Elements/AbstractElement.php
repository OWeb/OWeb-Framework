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

namespace Controller\OWeb\Helpers\Form\Elements;

/**
 * Description of Elements
 *
 * @author De Cramer Oliver
 */
abstract class AbstractElement extends \Controller\OWeb\Helpers\HtmlElement{
	
	/**
	 * Is this Element valid
	 * @var Boolean
	 */
	private $_valid = true;
	
	/**
	 * The Name of the input element
	 * @var String
	 */
	private $_name;
	
	/**
	 * The Text of the label of this input element
	 * @var String
	 */
	private $_title;
	
	/**
	 * A description for this input element
	 * @var String
	 */
	private $_desc = null;
	
	/**
	 * The type of the input element
	 * @var String
	 */
	private $_type;
	
	/**
	 * The value inside the input element
	 * @var String
	 */
	private $_val = null;
	
	/**
	 * After validation the string value might be transformed into something new. 
	 * @var mixed
	 */
	private $_trueVal = null;
	
	/**
	 *
	 * @var Array(\OWeb\utils\inputManagement\Validators)
	 */
	private $_validators = array();
	
	/**
	 * List of all Error messsages. (Recovered from the validators)
	 * @var Array(String)
	 */
	private $_errMessages = array();
	private $_errDesriptions = array();

	
	public function init() {
		parent::init();
		$this->applyTemplateController(new \Controller\OWeb\Helpers\Form\ElementTemplate());
		$this->addHtmlClass('OWebForm_input');
	}
	
	/**
	 * Set the type of the input element
	 * @param String $type
	 */
	protected function setType($type){
		$this->_type = $type;
	}
	
	/**
	 * Get the type of the input element
	 * @return String
	 */
	public function getType(){
		return $this->_type;
	}
	
	/**
	 * Set the Name of the input element
	 * @param String $name
	 */
	public function setName($name){
		$this->_name = $name;
	}
	
	/**
	 * Get the Name of the input element
	 * @return String
	 */
	public function getName(){
		return $this->_name;
	}
	
	/**
	 * Set a title to this input element. This is the information that
	 * will be showed in the label of the inout field. 
	 * 
	 * !!Some Elements may decide not to show their titles. 
	 * 
	 * @param String $title
	 */
	public function setTitle($title){
		$this->_title = $title;
	}
	
	/**
	 * The title given to the input element. 
	 * @return String
	 */
	public function getTitle(){
		return $this->_title;
	}
	
	/**
	 * Set a description to this input element. This is the information that
	 * will be showed somewhere alongside the input(View dependante).
	 * 
	 * !!Some Elements may decide not to show their titles, and some views might ignore it
	 * 
	 * @param String $title
	 */
	public function setDescription($desc){
		$this->_desc = $desc;
	}
	
	/**
	 * The description of the input element
	 * @return String
	 */
	public function getDescription(){
		return $this->_desc;
	}
	
	/**
	 * Adds a validator to the Elements. 
	 * 
	 * When validation asked only 1 validator needs to succes for the validation to be completed
	 * A validator may always return true and instead just modify the value of the field
	 * to make it conform to what is asked. 
	 * 
	 * Exemple : An Integer validator will return an int
	 *	A JS String validator will parse the string in order to make it JS compatible
	 * 
	 * @param \OWeb\utils\inputManagement\Validators $validator
	 */
	public function addValidator(\OWeb\utils\inputManagement\Validators $validator){
		$this->_validators[] = $validator;
	}
	
	/**
	 * Will remove all validators of this Element
	 */
	public function resetValidator(){
		$this->_validators[] = array();
	}
	
	/**
	 * Will start the validation procees. 
	 * 
	 * @return boolean Whatever the validation was sucessfull or not
	 */
	public function validate(){
		
		if(empty($this->_validators)){
			$this->_valid = true;
			return true;
		}
		
		$valid = false;
		$newValue = null;
		foreach ($this->_validators as $validator) {
			try{
				$newGeneratedValue = $validator->valideteValue($this->getVal());
				if(!$valid)
					$newValue = $newGeneratedValue;
				$valid = true;
			} catch ( \OWeb\types\UserException $ex){
				$valid = $valid || false;
				$this->_errMessages[] = $ex->getMessage();
				$this->_errDesriptions[] = $ex->getUserDescription();
			}
		}
		
		if($valid)
			$this->_trueVal = $newValue;
		else 
			$this->_trueVal = null;
		
		$this->_valid = $valid;
		
		return $valid;
	}
	
	/**
	 * This will return the true value of the element, if mvalidation sequence is launched
	 * it will return the new value
	 * 
	 * @return type
	 */
	public function getVal(){
		return $this->_trueVal == null ? $this->_val : $this->_trueVal;
	}
	
	/**
	 * Changes the value the Element
	 * @param String $val
	 */
	public function setVal($val){
		$this->_val = $val;
	}
	
	/**
	 * Returns the list of Error Messages. If the validation test hasn't been run it will be empty
	 * @return type
	 */
	public function getErrMessage(){
		return $this->_errMessages;
	}
	
	/**
	 * The form to whom the element belongs might have a unique id affected by the user. 
	 * This class will apply that id as a class element in order to simplfy css styling. 
	 * 
	 * @param type $id
	 * @param type $nb
	 */
	public function setFormId($id, $nb){
		$this->addHtmlClass($id.'_'.$nb);
	}
	
	/**
	 * The onDisplay being final this is the only place were you can acces the view object.
	 */
	public function prepareDisplay(){
		
	}


	public final function onDisplay(){
		$this->prepareDisplay();
		
		$template = $this->getTemplateController();
				
		$this->view->htmlIdentifier = $this->getIdentifier();
		$this->view->title = $this->_title;
		$this->view->type = $this->_type;
		$this->view->desc = $this->_desc;
		$this->view->name = $this->_name;
		$this->view->errMessages = $this->_errMessages;
		$this->view->errDescriptions = $this->_errDesriptions;
		$this->view->val = $this->_val;
		$this->view->valid = $this->_valid;
		
		if($template != null){
			$template->view->htmlIdentifier = $this->view->htmlIdentifier;
			$template->view->title = $this->_title;
			$template->view->type = $this->_type;
			$template->view->desc = $this->_desc;
			$template->view->name = $this->_name;
			$template->view->errMessages = $this->_errMessages;
			$template->view->errDescriptions = $this->_errDesriptions;
			$template->view->val = $this->_val;
			$template->view->valid = $this->_valid;
		}
	}
}

?>
