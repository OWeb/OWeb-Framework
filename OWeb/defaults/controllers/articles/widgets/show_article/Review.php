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
namespace Controller\articles\widgets\show_article;

/**
 * Description of Def
 *
 * @author De Cramer Oliver
 */
class Review extends \Controller\articles\Module{
	
	private $ext_bbCode;
	
	public function init() {
		parent::init();
		$this->ext_bbCode = \OWeb\manage\Extensions::getInstance()->getExtension('bbcode\SBBCodeParser');
	}

	public function onDisplay() {
		
		if($this->getParam('short')=="" || !is_bool($this->getParam('short')))
			$this->view->short = false;
		else 
			$this->view->short = $this->getParam('short');
		
		if($this->getParam('image_level')=="" || !is_numeric($this->getParam('image_level'))){
			$this->view->image_level = 2;
		}else{
			$this->view->image_level = $this->getParam('image_level');
		}
		
		$this->view->ext_bbCode = $this->ext_bbCode;
		$this->view->article = $this->getParam("article");
		
		
	}	
}

?>
