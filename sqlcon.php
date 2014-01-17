<?php
error_reporting(0); //No error reporting because MySQL LIKES TO SHOUT OUT SENSITIVE INFORMATION ON ERROR
$connection = mysqli_connect("localhost", "USER_NAME", "PASSWORD") or die(mysql_error()); 
