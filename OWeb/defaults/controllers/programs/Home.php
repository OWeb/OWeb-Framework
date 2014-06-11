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

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
use Model\programs\Categories;
use OWeb\manage\Extensions;

/**
 * Description of Home
 *
 * @author oliverde8
 */
class Home extends Module{
	
	
	private $categories;
	private $programs;
	
	public function init() {
		parent::init();
        $this->applyTemplateController('Controller\programs\Template');
		$this->categories = Categories::getInstance();
		$this->programs = new \Model\programs\Programs($this->categories, new \Model\articles\Artciles(new \Model\articles\Categories()));
	}

	public function onDisplay() {


		$this->view->cats = $this->categories;
		
		$this->categories->getAll();
		
		$this->view->previews_title = array();
		$this->view->previews = array();

        $this->view->previews_title[] = 'Some interesting University Projects';
        $this->view->previews[] =  $this->programs->getPrograms($this->categories->getElement(2), 0, 100);

		$this->view->previews_title[] = '<img src="'.OWEB_HTML_URL_IMG.'/page/programing/tm-logo_small.jpg" /> 
					<img src="'.OWEB_HTML_URL_IMG.'/page/programing/mp-logo_small.png" />
					Related Projects';
		$this->view->previews[] =  $this->programs->getPrograms($this->categories->getElement(1), 0, 100);

        //We need the latest elements
        $this->view->newest = $this->programs->getNewestPrograms(0,4);

        //The latest updated programs
        $this->view->updated = $this->programs->getLatestUpdatedPrograms(0,4);
	}
}

?>
