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

namespace Extension\contentDisplay;


use OWeb\manage\Events;
use OWeb\types\Extension;

class ajaxContentLoader extends Extension{

    private $id;

    protected function init() {
        $this->id = time();
        $this->addAction('get', 'getContent');
        $this->add_Event('PrepareContent_Succ@OWeb\manage\Template', 'resetDisplay');

        $this->addAlias('loadWithAjax', 'getContentToUpdate');
    }

    public function resetDisplay(){
        \OWeb\manage\Headers::getInstance()->addHeader("ajaxContentLoader.js", \OWeb\manage\Headers::js);
    }

    protected function getContent(){
        $man_ctr = \OWeb\manage\Controller::getInstance();

        //On va voir quel page on doit charger
        $get = \OWeb\OWeb::getInstance()->get_get();

        if (isset($get['page'])) {
            $ctr = $get['page'];
        } else {
            $ctr = OWEB_DEFAULT_PAGE;
        }

        try {
            $ctr = str_replace("\\\\", "\\", $ctr);
            $ctr = str_replace(".", "\\", $ctr);
            $ctr = $man_ctr->loadController('Page\\' . $ctr);
            $ctr->loadParams();
            $man_ctr->initController();

        } catch (\Exception $ex) {
            $ctr = $man_ctr->loadController('Page\OWeb\errors\http\NotFound');
        }

        ob_start();
        try{
            $ctr->display();
        }catch(\Exception $e){
            ob_end_clean();
            ob_start();

            $ctr = $man_ctr->loadException($e);
            $ctr->addParams("exception",$e);
            $man_ctr->display();
        }

        $this->content = ob_get_contents();
        ob_end_clean();

        Events::getInstance()->sendEvent('Didplay_Prepare@OWeb\manage\Headers');

        $header = \OWeb\manage\Headers::getInstance()->getAllHeaders();

        return array('headers' => $header, "content" => $this->content);
    }

    public function getContentToUpdate($url, $timeout){
        echo'<div id="ajaxContentLoader-'.$this->id.'"></div>';

        /**
         * @var \OWeb\utils\js\jquery\HeaderOnReadyManager $headersOnReady
         */
        $headersOnReady = \OWeb\utils\js\jquery\HeaderOnReadyManager::getInstance();

        $code = 'ajaxContentLoader("ajaxContentLoader-'.$this->id.'", '.json_encode($url).', '.($timeout*1000).');';

        $headersOnReady->add($code);
        $this->id++;
    }

} 