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
namespace Controller\programs;
use Model\articles\Artciles;
use Model\articles\Categories;
use Model\programs\Programs;
use OWeb\types\UserException;

/**
 * Description of Categorie
 *
 * @author oliverde8
 */
class Categorie  extends Module{
	
	private $categories;
    private $programs;
	
	public function init() {
		parent::init();
        $this->applyTemplateController('Controller\programs\Template');
		$this->categories = \Model\programs\Categories::getInstance();
        $this->programs = Programs::getInstance();


	}

	public function onDisplay() {
		$this->view->cats = $this->categories;

        try{
		    $this->view->category = $this->categories->getElement($this->getParam('catId'));
        }catch (\Exception $ex){
            $userEx = new UserException($this->l('No Such Category'));
            $userEx->setUserDescription($this->l('Maybe wrong link? or the category was deleted'));
            throw $userEx;
        }

        try{
            $this->view->programs = $this->programs->getPrograms($this->view->category, 0, 100, true);
        }catch (\Exception $ex){
            $userEx = new UserException($this->l('Empty Category'));
            $userEx->setUserDescription($this->l('This category has no articles in it'));
            throw $userEx;
        }
	}
	
}

?>
