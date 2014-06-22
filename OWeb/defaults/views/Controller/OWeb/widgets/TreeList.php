<?php
function drawTree($elements,$ctr, $depth=0){
	if(!empty($elements)){

		$class = $ctr->class;
		if(isset($ctr->classes[$depth]))
			$class .= " ".$ctr->classes[$depth];

		foreach($elements as $elem){
                    if ($elem->isVisible() || $ctr->showHidden){
                            echo '<li class="uk-parent '.$class.'">';?>
                                            <?php
                                            if($ctr->link != null){
                                            ?>
                                                    <a href="<?php echo $ctr->link.$elem->getId() ?>">
                                            <?php }
                                                    echo $elem->getName();
                                            if($ctr->link != null){
                                            ?>
                                            </a>
                                            <?php } ?>
                                    <?php $childrens = $elem->getChildrens() ?>
                                    <?php if(!empty($childrens)){ ?>
                                    <ul class="uk-parent">
                                        <?php drawTree($childrens,$ctr, $depth+1);?>
                                    </ul>
                                    <?php } ?>
                            </li>
                    <?php
                    }
                }
	}
}
?>

<ul class="uk-nav-sub">
    <?php drawTree($this->root,$this, 0); ?>
</ul>

