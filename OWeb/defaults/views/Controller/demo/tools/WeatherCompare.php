<?php ?>
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>

<script src="http://oliver-decramer.com/data/tool/weatherCompare/js/highstock.js"></script>
<script src="http://oliver-decramer.com/data/tool/weatherCompare/js/highcharts.js"></script>
<script src="http://oliver-decramer.com/data/tool/weatherCompare/js/highcharts-all.js"></script>
<script src="http://oliver-decramer.com/data/tool/weatherCompare/js/highcharts-more.js"></script>
<script src="http://oliver-decramer.com/data/tool/weatherCompare/js/modules/exporting.js"></script>

<script type="text/javascript"src="http://oliver-decramer.com/data/tool/weatherCompare/js/main.js"></script>

<style>
	.ui-progressbar {
		position: fixed;
		width : 500px;
		margin-left: -250px;
		left: 50%;
		top: 50%;
	}
	.progress-label {
		width : 500px;
		text-align: center;
		margin-top: 4px;
		font-weight: bold;
		text-shadow: 1px 1px 0 #fff;
	}
	#canvas{
		display: none;
	}

	#canvas svg{
		border : 1px;
		border-style: solid;
		border-color: black;
	}

	div.activeCitie {
		width: 15px;
		height: 15px;
		margin: 2px;
		float : left;
	}

	li{
		margin-left: -40px;
		list-style-type: none;
	}

	fieldset{
		margin-top: 15px;
		padding-left: 0px;
		width: 250px;
	}

	fieldset div{
		float : left;
		width: 70px;
	}

</style>

<div class="container">	
	<div class="row">
		<div class="col-md-7">
			<div id="container">

			</div>
		</div>
		<div class="col-md-4">
			<div id="container2">

			</div>
		</div>
	</div>
</div>

<div class="container">	
	<div class="row">

		<div class="col-md-1">

		</div>

		<div class="col-md-3">
			<h2>Active Cities</h2>
			<div id="activeCities">
				<ul id="sortable">

				</ul>
			</div>
		</div>
		<div class="col-md-3">
			<h2>Active Data</h2>
			<input class="activeData" id="activeD_temperatureAvg" type="checkbox" name="" value="" checked>Temperature Average<br>
			<input class="activeData" id="activeD_temperatureRange" type="checkbox" name="" value="" checked>Temperature Range<br>

			<input class="activeData" id="activeD_humAv" type="checkbox" name="" value="" checked>Average Humidity<br>
			<input class="activeData" id="activeD_humMax" type="checkbox" name="" value="">Maximum/Minimum Humidity<br>

			<input class="activeData" id="activeD_totalRain" type="checkbox" name="" value="">Total Rain<br>
			<!-- <input class="activeData" id="activeD_averageRain" type="checkbox" name="" value="">Average Rain<br>-->
		</div>
		<div class="col-md-3">
			<h2>Data Precision</h2>
			<input class="dataPrec" id="dataPrec_month" type="radio" name="TimeLine" value="" checked>Monthly<br>
			<input class="dataPrec" id="dataPrec_day" type="radio" name="TimeLine" value="" >Day<br>

			<fieldset>
				<div><input class="dataPrec" id="dataPrec_month1" type="checkbox" name="" value="" checked>Jan</div>
				<div><input class="dataPrec" id="dataPrec_month2" type="checkbox" name="" value="" checked>Feb</div>
				<div><input class="dataPrec" id="dataPrec_month3" type="checkbox" name="" value="" checked>Mar</div>
				<div><input class="dataPrec" id="dataPrec_month4" type="checkbox" name="" value="" checked>Apr</div>
				<div><input class="dataPrec" id="dataPrec_month5" type="checkbox" name="" value="" checked>May</div>
				<div><input class="dataPrec" id="dataPrec_month6" type="checkbox" name="" value="" checked>Jun</div>
				<div><input class="dataPrec" id="dataPrec_month7" type="checkbox" name="" value="" checked>Jul</div>
				<div><input class="dataPrec" id="dataPrec_month8" type="checkbox" name="" value="" checked>Aug</div>
				<div><input class="dataPrec" id="dataPrec_month9" type="checkbox" name="" value="" checked>Sep</div>
				<div><input class="dataPrec" id="dataPrec_month10" type="checkbox" name="" value="" checked>Oct</div>
				<div><input class="dataPrec" id="dataPrec_month11" type="checkbox" name="" value="" checked>Jan</div>
				<div><input class="dataPrec" id="dataPrec_month12" type="checkbox" name="" value="" checked>Dec</div>
			</fieldset>
		</div>
	</div>
</div>

<center>			
	<div id="progressbar">
		<div class="progress-label">Loading...</div>
	</div>
</center>