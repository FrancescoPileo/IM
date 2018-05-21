<?php
/**
 * Created by PhpStorm.
 * User: pil
 * Date: 21/05/18
 * Time: 16.51
 */

    $host = 'localhost';
    $username = 'root';
    $password = '123456';
    $database = 'IM';
    $conn = new mysqli($host, $username, $password, $database);


    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $id = $_POST['tweet_id'];
    $regione = $_POST['regione'];

    $sql = "UPDATE Tweet SET Regione = '" . $regione . "' WHERE Id = " . $id;

    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

    $conn->close();