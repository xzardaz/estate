<html>
<head>
	<title>
		sample title
	</title>
	<!-- TODO: propper paths -->
	<link rel="stylesheet" href="<?php echo base_url()."css/style.css"  ?>">  </link>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.1.min.js">
	</script>
	<script type="text/javascript" src="<?=base_url();?>js/swfobject.js">
	</script>
	<!-- 
	<script type="text/javascript" src="<?=base_url();?>js/sql.js">
	</script>
	-->
	<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
	<script charset="utf-8" type="text/javascript" src="<?=base_url();?>js/all.js"></script>
	<script type="text/javascript"
      		src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBxdE883_vL4xH4ApZQrfrAkvJPVYmi_tE&sensor=TRUE">
    	</script>
</head>

<body>


<div id="hHead">
	<div id="hMenu">
		<nav>
			<ul>
				<li><a href="#">Home</a></li>
				<li  ><a href="#" id="headerSearchIcon">Find</a>

					<ul>
						<li><a id="headerFlatIcon" href="/test_ci/browse/flats">Flats</a></li>
						<li><a id="headerStoreIcon" href="#">Stores</a></li>
						<li><a id="headerGarageIcon" href="#">Garages</a></li>
						<li><a id="headerFieldIcon" href="#">Field</a></li>
						<li><a id="headerHouseIcon" href="#">Houses</a></li>
					</ul>
				</li>
				<li><a id="headerArticleIcon" href="#">Articles</a></li>
				<li><a id="headerFAQIcon" href="<?php echo base_url()."faq"; ?>">F.A.Q.</a></li>
			</ul>
		</nav>	
	</div>
</div>
<div id="pbody">
