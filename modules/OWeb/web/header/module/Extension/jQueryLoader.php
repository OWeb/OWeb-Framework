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

namespace OWeb\web\header\module\Extension;

use OWeb\OWeb;
use OWeb\web\header\module\Model\Event as HeaderListener;
use OWeb\web\header\module\Model\Header as HeaderType;
use OWeb\web\header\module\Model\HeaderInterface;
use OWeb\web\header\module\Model\int;
use OWeb\web\header\module\Model\String;
use OWeb\web\header\module\Model\void;

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
 *
 * @method void addHeader(String $header, int $type = -1, String $key = null);
 */
class jQueryLoader extends \OWeb\types\extension\Extension implements HeaderListener, HeaderInterface
{

    private $maxVersion = '2.1.1';
    private $minVersion = '0.0.0';

    protected function init()
    {
        $this->addDependance('OWeb\web\header\module\Extension\Header');
        $dispatcher = OWeb::getInstance()->getManageEvents();
        $dispatcher->registerEvent(HeaderListener::name_start_display, $this);
    }

    protected function ready()
    {
    }

    public function jQueryAskMin($min){
        if(version_compare($this->minVersion, $min) != -1){
            $this->minVersion = $min;
        }
    }

    public function jQueryAskMax($max){
        if(version_compare($this->maxVersion, $max) == -1){
            $this->maxVersion = $max;
        }
    }

    public function OWebWebHeader_StartDisplay(){
        if(version_compare($this->maxVersion, $this->minVersion)){
            //@todo WARN log
        }

        $jquery = new HeaderType('//ajax.googleapis.com/ajax/libs/jquery/'.$this->maxVersion.'/jquery.min.js', Header::javascript);
        echo $jquery->getCode();
    }

    public function OWebWebHeader_EndDisplay(){}
}