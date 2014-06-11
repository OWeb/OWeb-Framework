<?php
function drawTree($elements,$ctr, $depth=0){
	if(!empty($elements)){
		
		$class = $ctr->class;
		if(isset($ctr->classes[$depth]))
			$class .= " ".$ctr->classes[$depth];
		
		echo '<ul class="oweb_tree_element '.$class.'">';
		foreach($elements as $elem){ 
		if ($elem->isVisible() || $ctr->showHidden){
			echo '<li class="oweb_tree_element '.$class.'">';?>
				<p>
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
				</p>
				
				<?php drawTree($elem->getChildrens(),$ctr, $depth+1);?>
			</li>
		<?php
		} }
		echo '</ul>';
	}
}

drawTree($this->root,$this, 0);

?>
