<?php

$this->addHeader('_OWeb/errors/errors.css',\OWeb\manage\Headers::css); 
?>


<div class="OWeb_error">
    <div>
        <?php
        if(!empty($this->img)){
        ?>
            <img src="<?php echo $this->img; ?>" desc="Error Image"/>
        <?php } ?>

        <h1>Error : <?php echo $this->title; ?></h1>

        <p>
            <?php echo nl2br($this->desc, true); ?>

        </p>

        <div class="end"></div>
    </div>
</div>
