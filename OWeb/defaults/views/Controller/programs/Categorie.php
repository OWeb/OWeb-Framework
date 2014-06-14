<?php
$this->addHeader('articles.css', \OWeb\manage\Headers::css);
$this->addHeader('onprogress.css', \OWeb\manage\Headers::css);
$this->addHeader('programs.css', \OWeb\manage\Headers::css);

?>


<p> <?php
    \OWeb\manage\SubViews::getInstance()->getSubView('Controller\OWeb\widgets\Category_Parents')
        ->addParams('cat', $this->category)
        ->addParams('link', $this->url(array('page' => 'programs\Categorie', "catId"=>"")))
        ->display();
?></p>
<h1><?= $this->category->getName() ?> </h1>
    <div  class="programs">

<?php

    $prog_display = \OWeb\manage\SubViews::getInstance()->getSubView('Controller\programs\widgets\ProgramCard');

    $box_display = \OWeb\manage\SubViews::getInstance()->getSubView('Controller\OWeb\widgets\Box');
    $box_display->addParams('ctr', $prog_display)
        ->addParams('SecondBoxHeight', 500)
        ->addParams('Html Class', 'programBox');

    foreach($this->programs as $program){
        $prog_display->addParams('prog', $program);
        $box_display->addParams('SecondBoxContent', $program->getShortDescription("eng"));
        $box_display->display();
    }
?>
        </div>


