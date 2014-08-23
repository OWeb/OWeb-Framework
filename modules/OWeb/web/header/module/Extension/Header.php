<?php

namespace OWeb\web\header\module\Extension;

use OWeb\manage\Dispatcher;
use OWeb\OWeb;
use OWeb\web\header\module\Model\Event;
use OWeb\web\header\module\Model\Header as HeaderElement;

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
class Header extends \OWeb\types\extension\Extension
{
    /**
     * The file you want to include is a a javasripty file, a .js.
     */
    const javascript = 0;
    const js = 0;

    /**
     * YOu want to include javascript code
     */
    const jsCode = 3;

    /**
     * A Css
     */
    const css = 1;

    /**
     * You just want to add a bit of Code inside the header. This code want't be modified.
     */
    const code = 2;

    //List of all CSS headers
    private $css_headers = array();

    //List of all Javascript headers
    private $js_headers = array();

    //List of other headers
    private $other_headers = array();

    /**
     * @var Dispatcher
     */
    private $eventM;


    function init()
    {
        $this->eventM = OWeb::getInstance()->getManageEvents();
        $this->addAlias("addHeader", "addHeader");
    }

    protected function ready()
    {

    }

    public function reset()
    {
        unset($this->css_headers);
        unset($this->js_headers);
        unset($this->other_headers);
        $this->css_headers = array();
        $this->js_headers = array();
        $this->other_headers = array();
    }

    /**
     * Allows you to add a header to the we page
     *
     * @param        $header The url to the css, js or the name of the css or js file. The path will be added automatically.
     * @param int    $type   The type of the Header you want to add.
     * @param string $key    Key to make the header unique.
     */
    public function addHeader($header, $type = -1, $key = null)
    {
        if ($header instanceof Header)
            $this->other_headers[] = $header;
        else
            $this->addAndCreateHeader($header, $type, $key);
    }

    public function addAndCreateHeader($code, $type, $key)
    {

        switch ($type) {
            case self::javascript :
                $code = $this->getPath(OWEB_HTML_DIR_JS, $code);
                break;
            case self::css :
                $code = $this->getPath(OWEB_HTML_DIR_CSS, $code);
        }
        $header = new HeaderElement($code, $type);

        if($key == null){
            $code = md5($header->getCode());
        }else{
            $code = $key;
        }

        switch ($type) {
            case self::jsCode :
                $this->js_headers[$code] = $header;
                break;
            case self::css :
                $this->css_headers[$code] = $header;
                break;
            default:
                $this->other_headers[] = $header;
                break;
        }
    }

    public function addJs($file)
    {
        $this->addHeader($file, self::js);
    }

    public function addCss($file)
    {
        $this->addHeader($file, self::css);
    }

    public function addJsCode($code)
    {
        $this->addHeader($code, self::jsCode);
    }

    public function addCode($code)
    {
        $this->addHeader($code, self::code);
    }

    private function getPath($path1, $path2)
    {
        if (($path2[0] == 'h' && $path2[1] == 't' && $path2[2] == 't' && $path2[3] == 'p') || ($path2[0] == '/' && $path2[1] == '/' ))
            return $path2;
        else
            return $path1 . '/' . $path2;
    }

    /**
     * Display the Headers that has been added.
     */
    public function display()
    {
        $this->eventM->dispatchEvent(Event::name_start_display);

        echo "\n<!--OWEB displays all CSS includes-->\n";

        //Displaying all Css Headers
        foreach ($this->css_headers as $id => $h) {
            echo $h->getCode($id);
        }

        echo "\n<!--OWEB displays all JS includes and codes-->\n";

        //Displaying Javascript Headers
        foreach ($this->js_headers as $id => $h) {
            echo $h->getCode($id);
        }

        echo "\n<!--OWEB displays personalized header codes-->\n";
        //Displaying all other headers
        foreach ($this->other_headers as $id => $h) {
            echo $h->getCode($id);
        }

        $this->eventM->dispatchEvent(Event::name_start_display);
    }

    public function getAllHeaders()
    {
        $all = array();
        foreach ($this->css_headers as $id => $h) {
            $all[$id] = $h->getCode($id);
        }
        foreach ($this->js_headers as $id => $h) {
            $all[$id] = $h->getCode($id);
        }
        foreach ($this->other_headers as $id => $h) {
            $all[$id] = $h->getCode($id);
        }
        return $all;
    }
} 