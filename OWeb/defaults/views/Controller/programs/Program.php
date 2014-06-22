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
$this->addHeader('articles.css', \OWeb\manage\Headers::css);
$this->addHeader('onprogress.css', \OWeb\manage\Headers::css);
$this->addHeader('programs.css', \OWeb\manage\Headers::css);


\OWeb\utils\js\jquery\HeaderOnReadyManager::getInstance()->add(
    '$( ".Description > .tabs" ).tabs();',
    \OWeb\manage\Headers::code
);
?>


<p>
    <?php
    \OWeb\manage\SubViews::getInstance()->getSubView('Controller\OWeb\widgets\Category_Parents')
        ->addParams('cat', $this->program->getCategory())
        ->addParams(
            'link',
            $this->url(array('page' => 'programs\Categorie', "catId" => ""))
        )
        ->display();
    ?>
</p>
<h1><?= $this->program->getName() ?> </h1>


<div class="program_info uk-grid">

    <div class="uk-width-1-2">
        <?php
        $prog_display = \OWeb\manage\SubViews::getInstance()->getSubView('Controller\programs\widgets\ProgramCard');

        $box_program = \OWeb\manage\SubViews::getInstance()->getSubView('Controller\OWeb\widgets\Box');
        $box_program->addParams('ctr', $prog_display)
            ->addParams('SecondBoxHeight', 500)
            ->addParams('Html Class', 'programBox');
        $prog_display->addParams('prog', $this->program);
        $box_program->display();
        ?>
    </div>
    <div class="uk-width-1-2">
        <?php

        $box_download = \OWeb\manage\SubViews::getInstance()->getSubView('Controller\OWeb\widgets\Box');
        $dwld_display = \OWeb\manage\SubViews::getInstance()->getSubView('Controller\programs\widgets\Downloads');
        $dwld_display->addParams('prog', $this->program);
        $box_download->addParams('ctr', $dwld_display)
            ->addParams('Html Class', 'DwldBox');
        $box_download->display();
        ?>
    </div>

    <div class="uk-width-1-1 Description">

        <?php
        $tabs_display = new \Controller\OWeb\widgets\uikit\SwitcherTabs();
        $tabs_display->init();

        //Adding Program description
        if ($this->program->getDesc_article() == null)
            $tabs_display->addSection(
                $this->l('Description'),
                $this->program->getShortDescription('eng')
            );
        else {
            $article_show = new \Controller\articles\widgets\show_article\Def();
            $article_show->init();
            $article_show->addParams('article', $this->program->getDesc_article())
                ->addParams('short', false)
                ->addParams('image_level', 2);
            $tabs_display->addSection($this->l('Description'), $article_show);
        }

        $galleryPath = $this->program->getGalleryPath();
        if (!empty($galleryPath)) {
            $gallery_show = new Controller\OWeb\widgets\jquery\plugins\GalleryView();
            $gallery_show->init();
            $gallery_show->addParams('path', $galleryPath);
            $tabs_display->addSection($this->l('Gallery'), $gallery_show);
        }

        $versions = $this->program->getVersions();
        if (!empty($versions)) {
            $logs_show = new Controller\programs\widgets\ChangeLogs();
            $logs_show->setVersions($versions);
            $tabs_display->addSection($this->l('ChangeLog'), $logs_show);
        }

        foreach ($this->program->getArticles() as $article) {
            $type            = 'Controller\articles\widgets\show_article\\' . $article->getType();
            $article_display = new $type();
            $article_display->init();
            $article_display->addParams('article', $article)
                ->addParams('short', false)
                ->addParams('image_level', 2);
            $tabs_display->addSection($article->getTitle('eng'), $article_display);
        }

        $tabs_display->display();
        ?>

    </div>


</div>




