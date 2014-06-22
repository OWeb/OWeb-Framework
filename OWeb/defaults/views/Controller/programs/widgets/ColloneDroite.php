
<div class="uk-width-1-4">
    <div class="uk-panel uk-panel-box  uk-margin-top">
			<h3>Categories</h3>
			<?php
			$catTree = \OWeb\manage\SubViews::getInstance()->getSubView('\Controller\OWeb\widgets\TreeList');
			$catTree->addParams('tree', $this->cats)
							->addParams('class','articles_category')
							->addParams('classes',array('articles_category_1'))
							->addParams('link',  $this->url(array('page' => 'programs\Categorie', "catId"=>"")));
							//->addParams('link', 'test'); 
			$catTree->display();
?>
	</div>
</div>
