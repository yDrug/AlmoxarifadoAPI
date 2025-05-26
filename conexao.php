<?php

//ambiente marcosvirgilio.online
$servername = '127.0.0.1';
$username = 'root';
$password = 'Aluno1234@#$';
$dbname = 'almoxarifado';

// Create connection
$con = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

?>