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

    $id = $_POST['id'];
    $country = $_POST['country'];
    $region = $_POST['region'];
    $province = $_POST['province'];
    $city = $_POST['city'];

    $sql = "UPDATE Tweet SET Country='" . $country . "', Region='" . $region .
                            "', Province='" . $province . "', City='" . $city . "'  WHERE Id = " . $id;

    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

    $conn->close();