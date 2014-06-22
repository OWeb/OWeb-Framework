<div class="uk-width-1-4 uk-margin-top">
    <ul class="uk-nav uk-nav-side uk-nav-parent-icon">
        <li class="uk-active uk-parent"><a><?= $this->l("Article Categories"); ?></a>
                <?php
                $catTree = \OWeb\manage\SubViews::getInstance()->getSubView('\Controller\OWeb\widgets\TreeList');
                $catTree->addParams('tree', $this->cats)
                    ->addParams('class', 'articles_category')
                    ->addParams('classes', array('articles_category_1'))
                    ->addParams(
                        'link',
                        $this->url(array('page' => 'articles\Categorie', 'catId' => ''))
                    );
                //->addParams('link', 'test');
                $catTree->display();
                ?>
        </li>
    </ul>
</div>
