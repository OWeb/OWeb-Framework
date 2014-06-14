
<?php
	$this->addHeader('2Collone.css',\OWeb\manage\Headers::css); 
	$this->addHeader('articles.css',\OWeb\manage\Headers::css); 
	
	$link = $this->CurrentUrl();
	$link->addParam("mode", "CtrAction")
		->addParam("action", "dwld");
	header("Refresh: 10; $link");
?>
<div id="twoCollone">
	<div>
		<div class="ColloneGauche">
			<div>
				<h2>File Name : <?php echo $this->dwld->getName(); ?> </h2>
				<p id="cntdwn_main" style="visibility: none">Your Download will start in <span id="cntdwn">10</span> secs</p>
				<p id="cntdwn_done">If your download didn't start use the Direct Download</p>
				<p>
					<a href="<?php echo $link; ?>">
						<strong>Direct Download</strong>
					</a>
				</p>
				<p><strong>This File has been downloaded : </strong><?php echo $this->dwld->getNbDownload();?> <strong> times.</strong></p>
				<p><strong>Thanks for downloading</strong></p>
			</div>
		</div>
		<div class="ColloneClean"></div>
	</div>
</div>
<script>
function CountBack(secs) {
  if (secs < 0) {
    document.getElementById("cntdwn").innerHTML = "FinishMessage";
	$("#cntdwn_main").hide();
	$("#cntdwn_done").show();
    return;
  }
  document.getElementById("cntdwn").innerHTML = secs
  setTimeout("CountBack(" + (secs-1) + ")", 1000);
}
//$("#cntdwn_main").show();
$("#cntdwn_done").hide();
CountBack(10);

</script>