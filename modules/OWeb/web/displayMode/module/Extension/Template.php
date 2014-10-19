<?php
/**
 * @author       Oliver de Cramer (oliverde8 at gmail.com)
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

namespace OWeb\web\displayMode\module\Extension;

use OWeb\OWeb;

/**
 * Handles the incoming parameters to display a web page.
 *
 * @TODO add error support
 * @package OWeb\web\displayMode\module\Extension
 */
class Template extends \OWeb\types\extension\Extension
{

    private $content;

    /**
     * @var UrlGenerator
     */
    private $urlGeneration;

    /** @var Header $headers */
    private $content_heads = "";

    protected function init()
    {
        $this->addDependance('OWeb\web\header\module\Extension\Header');
        $this->addDependance('OWeb\web\displayMode\module\Extension\UrlGenerator');
    }

    protected function ready()
    {
        $this->content_heads = OWeb::getInstance()->getManageExtensions()->getExtension('OWeb\web\header\module\Extension\Header');
        $this->urlGeneration = OWeb::getInstance()->getManageExtensions()->getExtension('OWeb\web\displayMode\module\Extension\UrlGenerator');
    }

    public function prepareDisplay($tmp = 'main')
    {
        /** @var Header $headers */
        $this->content_heads = OWeb::getInstance()->getManageExtensions()->getExtension('OWeb\web\header\module\Extension\Header');

        $this->urlGeneration = OWeb::getInstance()->getManageExtensions()->getExtension('OWeb\web\displayMode\module\Extension\UrlGenerator');


        //First we prepare the page
        $this->_prepareDisplay();

        //Then display the template
        ob_start();

        //try{
        //Including The template
        include OWEB_DIR_TEMPLATES."/".$tmp.".php";

        $foo = ob_get_contents();
        //Clean
        ob_end_clean();

        echo $foo;
    }

    /**
     * Will get the output of the current controller to display it later on.
     */
    private function _prepareDisplay(){
        //We save the content so that if there is an error we don't show half displayed codes
        ob_start();

        try{/**/
            OWeb::getInstance()->getManageController()->display();
        }catch(\Exception $e){
            //\OWeb\manage\Events::getInstance()->sendEvent('PrepareContent_Fail@OWeb\manage\Template');

            ob_end_clean();
            ob_start();

            $ctr = OWeb::getInstance()->getManageController()->loadException($e);
            OWeb::getInstance()->getManageController()->display();
        }

        $this->content = ob_get_contents();


        ob_end_clean();
    }

    public function l($text){
        return $text;
    }

    /**
     * Will display all the headers
     */
    public function headers(){
        $this->content_heads->display();
    }

    public function addHeader($header, $type = -1, $key = null){
        $this->content_heads->addHeader($header, $type, $key);
    }

    public function url($page, $params=array()){
        return $this->urlGeneration->getLink($page, $params);
    }

    public function getCurrentUrl(){
        return $this->urlGeneration->getCurrentUrl();
    }


    /**
     * Will display the main controller/page
     */
    public function display(){
        echo $this->content;
    }
}