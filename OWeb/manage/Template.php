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

namespace OWeb\manage;

/**
 * Will load the Template of the page. 
 * You may load multiple Template using a custom mode.
 *
 * @author De Cramer Oliver
 */
class Template extends \OWeb\types\ExtensionDependable{
	
	private $content;
	
	private $language;

	private $content_heads = "";
	
    /**
     *
     * @param string $tmp The name of the template to load
     */
    function __construct($tmp='main') {
		parent::__construct();
		\OWeb\manage\Events::getInstance()->sendEvent('DisplayTemplate_Start@OWeb\manage\Template');
		
		$this->addDependance('core\url\Generator');
		
		$this->language = new \OWeb\types\Language();
		
		//First we prepare the page
		$this->prepareDisplay();
		
		//Then display the template
		ob_start();

		try{
			//Including The template
			include OWEB_DIR_TEMPLATES."/".$tmp.".php";
			$foo = ob_get_contents();
			//Clean
			ob_end_clean();
			echo $foo;
			
			\OWeb\manage\Events::getInstance()->sendEvent('DisplayTemplate_End@OWeb\manage\Template');
			
		}catch(\Exception $ex){
			//Clean
			ob_end_clean();
			
			if($tmp == 'main'){
				$ctr = \OWeb\manage\Controller::getInstance()->loadException($ex);
				$ctr->Init();
				$ctr->addParams("exception",$ex);
				\OWeb\manage\Controller::getInstance()->display();
			}else{
				\OWeb\manage\Events::getInstance()->sendEvent('DisplayTemplate_Fail@OWeb\manage\Template');
				new Template();
			}
		}
	}

    /**
     * Will get the output of the current controller to display it later on.
     */
    private function prepareDisplay(){
		
		\OWeb\manage\Events::getInstance()->sendEvent('PrepareContent_Start@OWeb\manage\Template');
		
		//We save the content so that if there is an error we don't show half displayed codes
		ob_start();
		try{/**/
			\OWeb\manage\Controller::getInstance()->display();			
		}catch(\Exception $e){
			\OWeb\manage\Events::getInstance()->sendEvent('PrepareContent_Fail@OWeb\manage\Template');
			ob_end_clean();
			ob_start();
			$ctr = \OWeb\manage\Controller::getInstance()->loadException($e);
			$ctr->addParams("exception",$e);
			\OWeb\manage\Controller::getInstance()->display();
		}
		
		\OWeb\manage\Events::getInstance()->sendEvent('PrepareContent_Succ@OWeb\manage\Template');
		
		$this->content = ob_get_contents();
		
		\OWeb\manage\Events::getInstance()->sendEvent('PrepareContent_End@OWeb\manage\Template');
		
		ob_end_clean();
		
		$this->content_heads = \OWeb\manage\Headers::getInstance()->toString();
		\OWeb\manage\Headers::getInstance()->reset();
	}
	
	/**
	 * Will display all the headers
	 */
	public function headers(){
		\OWeb\manage\Headers::getInstance()->display();
		echo $this->content_heads;
	}
	
	/**
	 * Will display the main controller/page 
	 */
	public function display(){
		\OWeb\manage\Events::getInstance()->sendEvent('DisplayContent_Start@OWeb\manage\Template');
		echo $this->content;
		\OWeb\manage\Events::getInstance()->sendEvent('DisplayContent_End@OWeb\manage\Template');
	}
	
	/**
	 * Adding a Header to the Page
	 * @param type $code The url, or code if the header.
	 * @param type $type The type of the header. 
	 */
	public function addHeader($code, $type){
		\OWeb\manage\Headers::getInstance()->addHeader($code, $type);
	}

    /**
     * Get text for the current language
     *
     * @param string $name The name of the text you look for
     * @return string Hopefully the text :D
     */
    protected function getLangString($name){
		return $this->language->get($name);
	}

    /**
     * Shortcut for getLanguageString()
     *
     * @param string $name The name of the text you look for
     * @return string Hopefully the text :D
     */
    protected function l($name){
		return $this->language->get($name);
	}

    /**
     * Get the current language
     *
     * @return string current language
     */
    protected function getLang(){
		return $this->language->getLang();
	}

    /**
     * Initialize the current language file.
     */
    protected function InitLanguageFile(){
		$this->language = Languages::getInstance()->getLanguage($this);
	}
	
}

?>
