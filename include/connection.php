<?php 
$conn = mysqli_connect("10.50.139.159","root","","bayungporacarchive_db");

if(!$conn){
	die("Connection error: " . mysqli_connect_error());	
}
?>