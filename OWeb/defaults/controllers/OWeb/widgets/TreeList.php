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
namespace Controller\OWeb\widgets;

/**
 * This Controller allows you to display a Tree Element as a html list. 
 * 
 * @params link : The link that needs to be generaterd. The link will be as such = link + ElementId
 * @params categories : The Class that has All the root elements
 * @params class : The CSS class each element should have
 * @params classes : The CSS class array for affecting different classes to different depths of the tree
 * @prams showHidden : Should the hidden tree elements be shown? 
 *
 * @author De Cramer Oliver
 */
class TreeList extends \OWeb\types\Controller{
	
	
	public function init() {
		
	}

	public function onDisplay() {
		
		$this->view->link = $this->getParam("link");
		$this->view->tree = $this->getParam("tree");
		$this->view->class = $this->getParam("class");
		$this->view->classes = $this->getParam("classes");
		$this->view->showHidden = strtolower($this->getParam("showHidden")) == 'true';
		
		if($this->view->tree instanceof \Model\OWeb\treeElement\TreeManager)
			$this->view->root = $this->view->tree->getAllRoot();
		else{
			throw new \OWeb\Exception('TreeList needs a TreeManager to show the Tree');
		}
	}
}

?>
