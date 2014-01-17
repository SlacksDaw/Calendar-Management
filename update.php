<?php



header("Cache-Control: no-cache, must-revalidate"); 
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
header('Content-Type: text/html; charset=UTF-8');
require('sqlcon.php');
mysqli_set_charset ($connection, 'utf8');
mysqli_query($connection, "SET NAMES 'utf8'");
if (!$connection) {
	    die('Connect Error (' . mysqli_connect_errno() . ') '
	            . mysqli_connect_error());
	}
if(!ini_get("register_globals")) { //Register globals for now. Was built on a system using register globals and I took a lazy shortcut...
	foreach($_REQUEST as $key=>$item) {
    	$GLOBALS[$key] = $item;
	}
}

$Cdate = date('Y-m-d');
if ($date == 'no date' || $date == '') $date = '0000-00-00';
if ($url == 'Venue linked' || $url == 'Performer linked' || $url == 'Performer linkedVenue linked') $url = '';

	$linkQuery = "SELECT * FROM AdPerfs WHERE link = 1";
	$links = array();
	$linkData = mysqli_query($connection, $linkQuery);
	while ($row = mysqli_fetch_assoc($linkData)) {
		$links[$row['performer']] = $row['url'];
	}




if ($_GET['action'] == 'search' || $_GET['action'] == 'duplicates') {
	if ($_GET['action'] == 'duplicates') {
		$query = "SELECT * FROM music a 
		INNER JOIN music b 
		ON (a.date = b.date AND a.performer = b.performer) 
		WHERE a.id <> b.id ORDER BY a.date ASC";
	} else {
		$query = "SELECT * FROM music WHERE (date > $Cdate OR date = '0000-00-00') && $metric LIKE '%$search%' ORDER BY $sort $order, performer";
	}
	$Cdate = date('Y-m-d');
	$search = mysqli_real_escape_string($connection, $search);




	function linkListing($str) {//This is gonna get funky... Will definitely have to revisit
		global $links;
		if ($links[$str]) return $links[$str];
		else return false;
	} 

	$data = mysqli_query($connection, $query);
	mysqli_close($connection);
	if (mysqli_num_rows($data)) {
		while ($listings = mysqli_fetch_assoc($data)) {
			if ($listings['date'] != $date) {
				$date = $listings['date'];
			}
			$time = date('ga', strtotime($listings['startTime']));
			$perf = trim($listings['performer']);
			$ven = trim($listings['venue']);
			if (linkListing($perf)) {
				if (!$listings['url'])	$listings['url'] = 'Performer linked';
			}
			if (linkListing($ven)) {
				if (!$listings['url'] || $listings['url'] == 'Performer linked') $listings['url'] .= 'Venue linked';
			}
			if ($listings['date'] == '0000-00-00') $listings['date'] = 'no date';
			echo json_encode($listings);
			echo '^';
		}
	} else {
		echo $query;
		echo 'nope';
	}
	$query = null;
} else if ($_GET['action'] == 'update') {
	$query = "UPDATE `whatzup_calendars`.`music` SET `performer` = '$performer', `genre` = '$genre', `venue` = '$venue', `town` = '$town', `date` = '$date', `startTime` = '$time', `cover` = '$cover', `phone` = '$phone', `extra` = '$extra', `url` = '$url', `appr` = '$appr' WHERE id = '$id'  LIMIT 1";
	mysqli_query($connection, $query);
} else if ($_GET['action'] == 'delete') {
		$query = "DELETE FROM `whatzup_calendars`.`music` WHERE `music`.`id` = {$_POST['id']} LIMIT 1";
	mysqli_query($connection, $query);
} else if ($_GET['action'] == 'adDelete') {
		$query = "DELETE FROM `whatzup_calendars`.`AdPerfs` WHERE `AdPerfs`.`id` = {$_POST['id']} LIMIT 1";
	mysqli_query($connection, $query);
} else if ($_GET['action'] == 'approve') {
	$query = "UPDATE music SET appr = $appr WHERE id = '{$_POST['id']}'";
	mysqli_query($connection, $query);
} else if ($_GET['action'] == 'apprAd') {
	$query = "UPDATE AdPerfs SET link = $appr WHERE id = '{$_POST['id']}'";
	mysqli_query($connection, $query);
} else if ($_GET['action'] == 'modAd') {
	$query = "UPDATE AdPerfs SET performer = '$performer', type = '$type', url = '$url', city = '$city', phone = '$phone', cover = '$cover'  WHERE id = $id LIMIT 1";
	mysqli_query($connection, $query);
} else if ($_GET['action'] == 'insert') {
	$query = "INSERT INTO music (performer, genre, venue, town, date, startTime, cover, phone, extra, url, appr)  VALUES('$performer', '$genre', '$venue', '$town', '$date', '$time', '$cover', '$phone', '$extra', '$url', 0)";
	mysqli_query($connection, $query);
}  else if ($_GET['action'] == 'adPerfs') {
		if ($type) {$claus = "WHERE type = '$type'";}
			else {$claus = '';}
		$linkQuery = "SELECT * FROM AdPerfs $claus ORDER BY SORTNAME(performer)";
	$linkData = mysqli_query($connection, $linkQuery);
	while ($row = mysqli_fetch_assoc($linkData)) {
		echo json_encode($row);
		echo '^';
	}
} else if ($_GET['action'] == 'addAd') {
	$query = "INSERT INTO AdPerfs (performer, url, city, phone, cover, link, type)  VALUES('$performer', '$url', '$city', '$phone', '$cover', '0', '$type')";
	mysqli_query($connection, $query);
} else if ($_GET['action'] == 'tet') {


		$linkQuery = "SELECT * FROM AdPerfs ORDER BY SORTNAME(performer)";
	$linkData = mysqli_query($connection, $linkQuery);
	$nRow = array();

	while ($row = mysqli_fetch_assoc($linkData)) {
		foreach($row as &$a) {
			$a = str_replace("'", "\'", $a);
		}
		$name = $row['performer'];
		unset($row['performer']);
		$nRow[$name] = $row;
		//echo json_encode($nRow);
	}
	echo json_encode($nRow);
}


//So much redundancy... These need consolidated. 


?>