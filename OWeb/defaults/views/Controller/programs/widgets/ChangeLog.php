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

?>

<div class="prog_changelog">
    <ul>
    <?php

    foreach($this->revisions as $rev){
        if($rev instanceof \Model\programs\Revision){;
            $more = "";
            if(is_numeric($rev->getDownloadLink())){
                $dwld = new \Model\downloads\Download($rev->getDownloadLink());
                $link = $link = "downloads.Download.html?id=".$rev->getDownloadLink();
                $more = "(nbDownload : ".$dwld->getNbDownload().')';
            }else{
                $link = $rev->getDownloadLink();
            }

            ?>
                <li>
                    <span class="version_title"><?=$rev->getRevisionName()?> </span>
                    <span class="version_date">(<?=date('d/m/y',$rev->getDate())?>)</span>
                    <span class="version_dwld">  <a href="<?=$link?>">Download</a> <?=$more?> </span><br/>
                    <span class="version_desc"><?=$rev->getDescription()?></span><br/>


                </li>
            <?php
        }
    }

    ?>
    </ul>
</div>