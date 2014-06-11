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

use Model\articles\Artciles;
use Model\articles\Categories;
use \OWeb\manage\Extensions;
use Model\articles\exception\ArticleNotFound;

/**
 * Description of Categories
 *
 * @author De Cramer Oliver
 */
class Categorie extends Module{
	
	private $nbElementPage = 10;
	
	private $categories;
	private $articles;
	
	public function init() {
		parent::init();
		$this->applyTemplateController('Controller\articles\Template');
		
		$this->categories = Categories::getInstance();
		$this->articles = Artciles::getInstance();
	}

	public function onDisplay() {
		$this->view->cats = $this->categories;
		$this->view->mcat = $this->categories->getElement($this->getParam("catId"));
		
		$page = $this->getParam('npage');
		if(empty($page))
			$page = 0;
		else 
			$page--;

        //try{
		    $this->view->articles = $this->articles->getCategoryArticles($this->view->mcat, $this->nbElementPage*$page, $this->nbElementPage);
		    $this->view->nbArticle = $this->articles->getNbCategoryArticles($this->view->mcat);
        /*}catch (ArticleNotFound $ex){
            $this->view->articles = array();
            $this->view->nbArticle = 0;
        }*/
		$this->view->cpage = $page;
		$this->view->nbElementPage = $this->nbElementPage;
	}
	
}

?>
