<?php
	header("Cache-Control: no-cache, must-revalidate"); 
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
	header('Content-Type: text/html; charset=UTF-8');





?>
<head>
<style>
body {
	max-height:100%;
	overflow:hidden;
}
p.dateHead {text-decoration:  underline; margin: 5.2px 0.0px 10px 0.0px; line-height: 16px; font: 18.0px Times-serif}
p.p2 {margin: 0.0px 0.0px 6px 11px; text-indent: -11px; line-height: 12px; font: 16px 'Arial Narrow', Helvetica, san-serif}
span.s1 {text-decoration: underline}
input.time, input.cover {
	width:50px;
}
input.city, input.phone {
	width:100px;
}
input.genre {
	width:80px;
}
input.performer {
	width:130px;
}
div.row {
	border:1px solid black;
	margin:6px;
	padding:4px;
}
div.row.dupe {
	background-color:red;
}
p.dateHead {
	border:1px solid black;
}
div.notApproved {
	background-color:red;
}
div.approved {
	background-color:green;
}
.hidden {
	display:none;
}
div#content {
	max-height:87%;
	min-width:1290px;
	overflow-y:scroll;
}
div#content div.row {
	border:1px solid black;
}
div.headers {
	min-width:1240px;
}
p.headers {
	display:inline;
	cursor:pointer;
}
p.headers:hover {
	color:red;
}
span.approval {
	height: 27px;
	width: 30px;
	border: 1px solid red;
	overflow: hidden;
	margin: -45px -53px 0px 0px !important;
	float: right;
}

span.approved {
	background-image: url('images/approved.gif');
}
.ui-widget .ui-menu-item {
font-size: 10px;
}
input:not([type=button]) {
	width:138px;
}
button.adManage {
	display:block;
	float:right;
}
button.delete {
	float:right;
}
</style>
<script src='//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js' ></script>
<script type="text/javascript" src="js/music.js"></script>
<script type="text/javascript" src="js/main.js"></script>
<link rel="stylesheet" type="text/css" href="music.css">


</head>
<br><br>
<button class='adManage' onClick='location.href="manage.php"' value='Manage Advertisers'>Manage Advertisers</button>
Sort By:
<select id='sortBy'>
	<option value='id' >Time Entered</option>
	<option selected value='date'>Date</option>
	<option value='performer'>Performer name</option>
	<option value='venue'>Venue Name</option>
</select>
<select id='sortOrd' style='display:none;'>
	<option value='ASC'>Ascending</option>
	<option value='DESC' selected>Descending</option>
</select>
Search By:
<select id='seMetric'>
	<option value='performer'>Performer name</option>
	<option value='venue'>Venue</option>
	<option value='date'>Date</option>
</select>
<input id='search'>

	<input type='button' value='search' onclick='search(this.previousElementSibling.value, document.getElementById("seMetric").value);'>
	<input type='button' id='unapproved' onclick='search(0)' value='Show All unapproved'>
	<input type='button' id='showAll' onclick='search(" ", "performer")' value='Show All'>
	<input type='button' onclick='dupe()' value='New Listing'>
	<br><br>
	<div class='headers'>
		<p name='performer' class='headers performer' style='margin-left:20px;'>Performer
		<p name='genre' class='headers genre' style='margin-left:85px;'>Genre/Description
		<p name='venue' class='headers venue' style='margin-left:55px;'>Venue
		<p name='town' class='headers town' style='margin-left:110px;'>Town
		<p name='date' class='headers date' style='margin-left:100px;'>Date
		<p name='startTime' class='headers startTime' style='margin-left:100px;'>Times
		<p name='cover' class='headers cover' style='margin-left:100px;'>Cover
		<p name='phone' class='headers phone' style='margin-left:100px;'>Phone
		<p name='extra' class='headers extra' style='margin-left:50px; font-size:13px; '>Extra (e.g. all ages/18 plus)
		<p name='URL' class='headers url' style='margin-left:45px;'>URL 

	</div>
	</div>
	<div id='content'>

<!-- 	while ($listings = mysqli_fetch_assoc($data)) {
		if ($listings['date'] != $date) {
			$date = $listings['date'];
			echo "<p class='dateHead'>". date('l, F d', strtotime($listings['date'])) ."</p>";
		}
		$time = str_replace('am', 'p.m.', date('ga', strtotime($listings['startTime'])));
		echo "<div class='row'>
				<p> 
				Performer:<input name='performer' value ='{$listings['performer']}'> 
				Genre:<input name='genre' value ='{$listings['genre']}'> 
				Venue:<input name='venue' value ='{$listings['venue']}'> 
				City:<input name='town' value ='{$listings['town']}'>
				Start Time:<input name='startTime' value ='$time'> 
				End Time:<input name='endTime' value ='$time'> 
				Cover:<input name='cover' value ='{$listings['cover']}'> 
				Phone:<input name='phone' value ='{$listings['phone']}'>
				<input name='id' class='id' type='hidden' value='{$listings['id']}'>
				</p>
				
				<input type='button' onclick='update(this.parentNode)' value='update'>

			</div>
			";
	} -->






































