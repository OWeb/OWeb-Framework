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

if($this->program->getMasterVersion() != null){
?>

<div class="program">
    <h3 class="version version_master">
            <span class="name">Master Realese</span>
    </h3>

    <ul>
        <?php
            $lRelease = $this->program->getMasterVersion()->getLastRealese();
            if($lRelease != null){
                showRealese($lRelease, $this->program->getMasterVersion());
            }else{
                echo '<li>'.$this->l('noRelease').'</li>';
            }
        ?>
    </ul>

    <?php
    foreach($this->program->getVersions() as $version){
        if($version->getName() != 'main'){
            ?>
            <h3 class="version version_master">
                <span class="name">Version : <?=$version->getName()?></span>
            </h3>

            <ul>
                <?php
                $lRelease = $version->getLastRealese();
                if($lRelease != null){
                    showRealese($lRelease, $version);
                }else{
                    echo '<li>'.$this->l('noRelease').'</li>';
                }
                ?>
            </ul>

            <?php
        }
    }


    ?>

<p>More Information Soon</p>
</div>

<?php
}else{
    echo '<p>'.$this->l('noRelease').'</p>';
}


function showRealese(\Model\programs\Revision $rev, \Model\programs\Version $ver){

    $more = "";
    if(is_numeric($rev->getDownloadLink())){
        $dwld = new \Model\downloads\Download($rev->getDownloadLink());
        $link = "downloads.Download.html?id=".$rev->getDownloadLink();
        $more = "(nbDownload : ".$dwld->getNbDownload().')';
    }else{
        $link = $rev->getDownloadLink();
    }

    echo '<li><span class="release_name">V'.$rev->getRevisionName().'</span> -<a href="'.$link.'"><span class="release_dwld">Download</span></a>-  '.$more.'';

    if($rev->IsBeta())
        echo '<span class="realese_beta"> BETA</span>';
    echo '</li>';

    if($rev->IsBeta()){
        //Try to find other realeses;
        $realeses = $ver->getAllRevisions();
        foreach($realeses as $r){
            if(!$r->IsBeta()){
                echo '<li>V'.$r->getRevisionName().' -<span class="release_dwld">Download</span></li>-';
                break;
            }
        }
    }
}

?>