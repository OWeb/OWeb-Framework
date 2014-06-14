<?php

	$this->addHeader('articles.css',\OWeb\manage\Headers::css); 


    $diplay_title = \OWeb\manage\SubViews::getInstance()->getSubView('\Controller\articles\widgets\Article_Title');
    $display_content = \OWeb\manage\SubViews::getInstance()->getSubView('Controller\articles\widgets\show_article\Def');

if(!isset($this->showCatHeader) || $this->showCatHeader)
	\OWeb\manage\SubViews::getInstance()->getSubView('\Controller\articles\widgets\Category_Description')
		->addParams('categories', $this->cats)
		->addParams('catId', $this->mcat->getId())
		->display();

	// Articles -->
$nbArticle = 0;
if(is_array($this->articles)){
	foreach($this->articles as $article){
        $diplay_title->addParams('article', $article)
				->display();

        $display_content->addParams('short', true)
				->addParams('image_level', 1)
				->addParams('article', $article)
				->display();
		$nbArticle++;
	}
}
$nbArticleShown = $nbArticle + $this->cpage*$this->nbElementPage;
?>
				<div class="pageNb">
<?php

	\OWeb\manage\SubViews::getInstance()->getSubView('\Controller\OWeb\widgets\MultiPage')
				->addParams('link',$this->CurrentUrl())
				->addParams('cpage',$this->cpage)
				->addParams('pname','npage')
				->addParams('lpage',$this->nbArticle/$this->nbElementPage)
				->display();

?>
</div>