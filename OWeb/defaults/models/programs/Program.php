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
namespace Model\programs;
use Model\articles\Article;
use Page\programs\Categorie;

/**
 * Description of Programs
 *
 * @author oliverde8
 */
class Program {
	
	protected $id;
	protected $name;
	protected $img;
	protected $front_page;
	protected $category;
	protected $categories = array();
	protected $desc_article;
	protected $desc_articles = array();
	protected $date;
    protected $galleryPath;

    protected $short = array();
    protected $vshort = array();

	protected $versions = array();
    protected $masterVersion;
	protected $complementary = array();

    /**
     * @param int $id The id of the program
     * @param string $name The name of the program
     * @param string $img The logo of the program
     * @param bool $front_page should it be showed in the carousel
     * @param Categorie $category The category of the program
     * @param Article $desc_article L'article qui decrit le program
     * @param $date La date de creation du program
     * @param $galleryPath Path to the images of the gallery.
     */
    function __construct($id, $name, $img, $front_page, $category, $desc_article, $date, $galleryPath) {
		$this->id = $id;
		$this->name = $name;
		$this->img = $img;
		$this->front_page = $front_page;
		$this->category = $category;
		$this->desc_article = $desc_article;
		$this->date = $date;
		$this->galleryPath = $galleryPath;
	}

    /**
     * Returns the programs id
     * @return int
     */
    public function getId() {
		return $this->id;
	}

    /**
     * @param int $id The new id of the program
     */
    public function setId($id) {
		$this->id = $id;
	}

    /**
     * @return string The name of the Program
     */
    public function getName() {
		return $this->name;
	}

    /**
     * @param string $name The new name of the program
     */
    public function setName($name) {
		$this->name = $name;
	}

    /**
     * @return string the image logo of the program
     */
    public function getImg() {
		return $this->img;
	}

    /**
     * @param string $img The new logo of the program
      */
    public function setImg($img) {
		$this->img = $img;
	}

	public function getFront_page() {
		return $this->front_page;
	}

	public function setFront_page($front_page) {
		$this->front_page = $front_page;
	}

	public function getCategory() {
		return $this->category;
	}

	public function setCategory($category) {
		$this->category = $category;
	}

	public function getDesc_article() {
		return $this->desc_article;
	}

	public function setDesc_article(Article $desc_article) {
		$this->desc_article = $desc_article;
	}

	public function getDate() {
		return $this->date;
	}

	public function setDate($date) {
		$this->date = $date;
	}

    public function addLanguage($lang, $short, $vshort){
        $this->short[$lang] = $short;
        $this->vshort[$lang] = $vshort;
    }

    public function getShortDescription($lang){
        return isset($this->short[$lang]) ? $this->short[$lang] : "";
    }

    public function getVeryShortDescription($lang){
        return isset($this->vshort[$lang]) ? $this->vshort[$lang] : "";
    }

    function addCategory(CategorieElement $categorie){
        $this->categories[] = $categorie;
    }

    function addArticle(Article $article){
        $this->desc_articles[] = $article;
    }
	
	public function getArticles(){
		return $this->desc_articles;
	}

    public function checkLang($lang){
        return (isset($this->vshort[$lang]));
    }

    public function addVersion(Version $version){
        $this->versions[] = $version;
        if($version->getName() == 'main'){
            $this->masterVersion = $version;
        }
    }

    /**
     * @return array
     */
    public function getVersions()
    {
        return $this->versions;
    }

    /**
     * @return mixed
     */
    public function getMasterVersion()
    {
        return $this->masterVersion;
    }

    /**
     * @return \Model\programs\Path
     */
    public function getGalleryPath()
    {
        return $this->galleryPath;
    }


}

?>
