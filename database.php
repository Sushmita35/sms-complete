<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "student-management-system";   // THIS MUST MATCH phpMyAdmin

$conn = mysqli_connect($host, $user, $password, $database);

if(!$conn)
{
    die("Connection failed: " . mysqli_connect_error());
}

?>