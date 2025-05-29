<?php
$host = "localhost";
$username = "netforces";
$password = "S6978a49";
$dbname = "netforces";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}
?>