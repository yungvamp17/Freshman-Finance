<?php

$conn = mysqli_connect('localhost:3309', 'root', '', 'user_db');

if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

?>