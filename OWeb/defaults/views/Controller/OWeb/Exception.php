<?php

$this->addHeader('2Collone.css',\OWeb\manage\Headers::css); 
?>


<?php 
	$h='
        $( "#exception_trace" ).accordion({
            collapsible: true,
			 heightStyle: "content",
			 active : "false"
        });';
	\OWeb\utils\js\jquery\HeaderOnReadyManager::getInstance()->add($h);
?>

<?php

$error_view = \OWeb\manage\SubViews::getInstance()->getSubView('Controller\OWeb\Error');

//Fatal error: Call to undefined function gets_class() in C:\ption.php on line 28
foreach($this->userException as $ex){

    $error_view->addParams('Title', $ex->getMessage())
            ->addParams('Description', $ex->getUserDescription())
            ->addParams('img', $ex->getUserImage())
            ->display();
}
?>


<div id="Errors">

    <?php echo '<h2>'.$this->l('title').'</h2>'; ?>

    <div id="exception_trace">
    <?php
    //Fatal error: Call to undefined function gets_class() in C:\ption.php on line 28
    foreach($this->messages as $ex){
        ?>
        <h3><?php echo get_class($ex); ?></h3>
        <div>
            <h4><?php echo $ex->getMessage(); ?></h4>
            <ul>
                <li><strong> File : </strong><?php echo $ex->getFile(); ?></li>
                <li><strong> Line : </strong><?php echo $ex->getLine(); ?></li>
                <li><strong> Trace : </strong>
                    <ul>
                        <?php foreach($ex->getTrace() as $t){ ?>
                        <li>
                            <strong><?php echo $t['file']; ?></strong>
                            <ul>
                                <?php
                                unset($t['file']);
                                foreach($t as $n=>$v){
                                    if(is_string($n) && is_string($v)){?>
                                    <li><strong><?= $n; ?> : </strong><?= $v;?></li>
                                <?php }
                                }?>
                            </ul>
                        </li>
                        <?php } ?>
                    </ul>
                </li>
            </ul>
        </div>
    <?php } ?>
</div>
</div>




