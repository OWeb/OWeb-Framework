<?php


\OWeb\manage\SubViews::getInstance()->getSubView('Controller\articles\widgets\show_article\Def')
    ->addParams('article', $this->article)
    ->addParams('short', $this->short)
    ->addParams('image_level', $this->image_level)
    ->display();

echo $this->getBBHtml('[title=h1]' . $this->l('pros_cons') . '[/title]');
?>

<div class="pros_cons uk-grid">
    <div class="Pros uk-width-1-2">
        <ul>
            <?php
            $pros = explode('*', $this->article->getAttribute("pros"));
            foreach ($pros as $v)
                if ($v != "")
                    echo "<li><span>$v</span></li>"
            ?>
        </ul>
    </div>

    <div class="Cons uk-width-1-2">
        <ul>
            <?php
            $pros = explode('*', $this->article->getAttribute("cons"));
            foreach ($pros as $v)
                if ($v != "")
                    echo "<li><span>$v</span></li>"
            ?>
        </ul>
    </div>

</div>