<?php 
$conn = mysqli_connect("10.50.128.29","root","","bayungporacarchive_db");

if(!$conn){
	die("Connection error: " . mysqli_connect_error());	
}
?>