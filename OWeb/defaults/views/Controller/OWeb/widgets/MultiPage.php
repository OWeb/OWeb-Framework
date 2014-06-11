
<p class="MultiPage">
	<?php
	
	if($this->cpage == 0 && $this->lpage <= 1){
		
	}else{
	
		if($this->cpage > 0)
			echo '<span class="prevPage">
					<a href='.$this->link->addParam($this->pname, $this->cpage).'>'			
						.$this->l("previous page")
					.' </a>
				</span>';

		$page = $this->cpage+1;

		$nb = ($this->nbElement-1);

		$dernieres = $this->lpage - $page;
		$premieres = $page-1;

		if($dernieres + $premieres > $nb){
			if($dernieres > $premieres)
				$dernieres = (int)(($nb/2));
			else {
				$dernieres = (int)(($nb/2));
				$premieres = (int)(($nb/2));
			}
			if($premieres > $dernieres)
				$premieres = (int)(($nb/2));
		}


		?>

		<span class="previousPages">	
			<?php
				for($i=$premieres; $i>0; $i--){
					echo '<a href="'.$this->link->addParam($this->pname, $page-$i).'">';
					echo $page-$i.' ';
					echo '</a>';
				}
			?>
		</span>

		<span class="currentPage">
			<?php echo ''.$page.' '; ?>
		</span>

		<span class="nextPages">
			<?php
				for($i=1; $i<=$dernieres+1; $i++){
					echo '<a href="'.$this->link->addParam($this->pname, $page+$i).'">';
					echo $page+$i.'  ';
					echo '</a>';
				}
			?>
		</span>

		<?php
		if($this->cpage < $this->lpage-1)
			echo '<span class="nextPage">
					<a href='.$this->link->addParam($this->pname, $this->cpage+2).'> '			
						.$this->l("next page")
					.'</a>
				</span>';
	}
	?>

</p>
