<?php

?>
<!-- This is the tabbed navigation containing the toggling elements -->
<ul class="uk-tab" data-uk-tab="{connect:'#<?php echo $this->id; ?>'}">
    <?php foreach($this->sections as $title => $content) : ?>
        <li>
            <a href=""><?php echo $title; ?></a>
        </li>
    <?php endforeach; ?>
</ul>

<!-- This is the container of the content items -->
<ul <?php echo $this->displayId ?> class="uk-switcher uk-margin">
    <?php foreach($this->sections as $title => $content) : ?>
        <li>
            <?php
            if($content instanceof \OWeb\types\Controller)
                $content->display();
            else
                echo $content;
            ?>
        </li>
    <?php endforeach; ?>
</ul>