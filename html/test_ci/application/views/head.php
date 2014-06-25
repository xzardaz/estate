<!DOCTYPE html>
<html>
<head>
	<title>
		sample title
	</title>
	<style>
		<?php
			echo file_get_contents("css/style.css");
			echo file_get_contents("css/fotorama.css");
			echo file_get_contents("css/ui-lightness/style.css");
		?>
	</style>
	<script type="text/javascript">
		<?php
			echo file_get_contents("js/boot.js");
		?>
	</script>
	<!-- TODO: propper paths -->
	<!--<link rel="stylesheet" href="<?php echo base_url()."css/style.css"  ?>">  </link>
	<link rel="stylesheet" href="<?php echo base_url()."css/fotorama.css"  ?>">  </link>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.1.js"></script>
	<script type="text/javascript" src="<?=base_url();?>js/fotorama.js"></script>
	<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
	<script type="text/javascript" src="<?=base_url();?>js/swfobject.js"></script>-->
	<!--<script charset="utf-8" type="text/javascript" src="<?=base_url();?>js/all.js"></script>
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBxdE883_vL4xH4ApZQrfrAkvJPVYmi_tE&sensor=TRUE"></script>
	<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=drawing"></script>-->
	<!--<link rel="stylesheet" href="<?=base_url();?>css/ui-lightness/style.css">-->
	<!--<script src="http://yui.yahooapis.com/3.17.2/build/yui/yui-min.js"></script>-->
	<!-- 
	<script type="text/javascript" src="<?=base_url();?>js/sql.js">
	</script>
	-->
</head>

<body>


<div id="hHead">
	<div id="hMenu">
		<nav>
			<ul>
				<li><a href="#">Home</a></li>
				<li  ><a href="<?=base_url();?>browse" class="knownPage" id="headerSearchIcon">Find</a>

					<ul>
						<li><a id="headerFlatIcon" class="knownPage" href="/test_ci/browse/flats">Flats</a></li>
						<li><a id="headerStoreIcon" class="knownPage" href="/test_ci/browse/stores">Stores</a></li>
						<li><a id="headerGarageIcon" class="knownPage" href="/test_ci/browse/garages">Garages</a></li>
						<li><a id="headerFieldIcon" class="knownPage" href="/test_ci/browse/field">Field</a></li>
						<li><a id="headerHouseIcon" class="knownPage" href="/test_ci/browse/houses">Houses</a></li>
					</ul>
				</li>
				<li><a id="headerArticleIcon" href="#">Articles</a></li>
				<li><a id="headerFAQIcon" href="<?php echo base_url()."faq"; ?>" class="knownPage">F.A.Q.</a></li>
			</ul>
		</nav>	
	</div>
</div>
<div id="pbody">
