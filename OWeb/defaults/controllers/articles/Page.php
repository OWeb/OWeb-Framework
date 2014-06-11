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
namespace Controller\articles;

use Model\articles\Categories;
use \OWeb\manage\Extensions;

/**
 * Description of Page
 *
 * @author De Cramer Oliver
 */
class Page extends Module{
	
	private $categories;
	private $articles;
	
	
	public function init() {
		parent::init();
		$this->applyTemplateController('Controller\articles\Template');

        $this->categories = Categories::getInstance();
        $this->articles = \Model\articles\Artciles::getInstance();
	}

	public function onDisplay() {
		$cats = explode('.',  $this->getParam("name"));
		
		$article_title = $cats[sizeof($cats)-1];
		unset($cats[sizeof($cats)-1]);
		
		//Searching for the article
		
		//Firsty lets find the Root Element for pages.
		$childs = $this->categories->getAllRoot();
		$found = false;
		$i = 0;
		$cat = null;
		while($i < sizeof($childs) && !$found){
			
			if($childs[$i]->getName() == 'Page'){
				$found = true;
				$cat = $childs[$i];
				$childs = $childs[$i]->getChildrens();
			}
			$i++;
		}
		
		//Now lets find THe category tree
		foreach($cats as $num => $name){
			$i = 0;
			$cat = null;
			$found = false;
			while($i < sizeof($childs) && !$found){
				if($childs[$i]->getName() == $name){
					$found = true;
					$cat = $childs[$i];
					$childs = $childs[$i]->getChildrens();
				}
				$i++;
			}
		}
		
		$this->view->cats = $this->categories;
		$this->view->content = null;
		//Now the article of this category Finally.
		if($cat != null)
			$this->view->content = $this->articles->getArticleByNameCategory($article_title, $cat);
		
		
	}
}

?>
