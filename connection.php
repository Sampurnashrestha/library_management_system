<?php
$server = 'localhost';
$user = 'root';
$pass = '';
$database = 'libraryms';

$connection = new mysqli($server, $user, $pass, $database);

if ($connection->connect_error) {
    echo "Failed to connect: " . $connection->connect_error;
}
?>
