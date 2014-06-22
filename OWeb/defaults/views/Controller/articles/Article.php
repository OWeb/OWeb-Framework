<div class="uk-panel uk-panel-box uk-margin-top">
<?php

\OWeb\manage\SubViews::getInstance()->getSubView('\Controller\articles\widgets\Article_Title')
    ->addParams('article', $this->article)
    ->display();

\OWeb\manage\SubViews::getInstance()->getSubView(
    'Controller\articles\widgets\show_article\\' . $this->article->getType()
)
    ->addParams('article', $this->article)
    ->addParams('short', false)
    ->addParams('image_level', 2)
    ->display();
?>
</div>