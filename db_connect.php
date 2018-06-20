<?php

$host = 'localhost';
$username = 'mmlilingwa1';
$password = 'mmlilingwa1';
$dbname = 'mmlilingwa1';

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die('connection failed: ' . $conn->connect_error);
}

?>
