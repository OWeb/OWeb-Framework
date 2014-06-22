<?php

if(!empty($this->values)){
	
	echo '<h1 class="uk-h2">'.$this->l('Table of Content').'</h1>';
	
	$current = 0;

	foreach($this->values as $values){
		$value = $values[0];
		
		for($i = 0; $i < ($value - $current); $i++){
			echo '<ul>';
		}
		
		for($i = 0; $i < ($current - $value); $i++){
			echo '</li></ul>';
		}
		
		echo '<li><a href="#'.$values[2].'">'.$values[1].'</a>';
		
		if($value == $current)
			echo '</li>';
		
		
		$current = $value;
	}
	
	for($i = 0; $i < ($current); $i++)
	echo "</li></ul>";

}
?>

