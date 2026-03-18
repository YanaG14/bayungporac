<?php 
$conn = mysqli_connect("localhost","admin","1234","bayungporacarchive_db");

if(!$conn){
	die("Connection error: " . mysqli_connect_error());	
}
?>