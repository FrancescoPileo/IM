<?php

$hello = "ciao";

function DbConnection(){
    $host = 'localhost';
    $username = 'root';
    $password = '123456';
    $database = 'IM';
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}


function getFrequency($conn, $sentiment, $month, $year){
    $query = "SELECT COUNT(*) AS 'n' FROM Tweet WHERE Sentiment='" . $sentiment . "'" .
        " AND MONTH(Date) = " . $month .
        " AND YEAR(Date) = " . $year;
    $ris = mysqli_fetch_assoc(mysqli_query($conn, $query));
    $conn->close();

    return $ris['n'];
}


?>
