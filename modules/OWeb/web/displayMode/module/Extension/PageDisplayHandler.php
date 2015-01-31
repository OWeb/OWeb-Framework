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
class PageDisplayHandler extends \OWeb\types\extension\Extension
{

    protected function init()
    {
        $this->addDependance('OWeb\web\displayMode\module\Extension\Template');
    }

    protected function ready()
    {
        $oweb = OWeb::getInstance();
        $ctrManager = $oweb->getManageController();

        $ctr = $oweb->getGet()->get('page', 'home');

        try {
            $ctr = str_replace("\\\\", "\\", $ctr);
            $ctr = str_replace(".", "\\", $ctr);

            $ctr = $ctrManager->loadController('Page\\' . $ctr);
            $ctr->loadParams();

        } catch (\Exception $ex) {
            $ctr = $ctrManager->loadController('Page\errors\http\NotFound');
            $ctr->loadParams();
        }

    }

    public function display()
    {
        /** @var Template $template */
        $template = OWeb::getInstance()->getManageExtensions()->getExtension('OWeb\web\displayMode', 'Template');
        $template->prepareDisplay('main');
    }
}