<?php
/**
 * Created by PhpStorm.
 * User: pil
 * Date: 26/05/18
 * Time: 9.02
 */



$host = 'localhost';
$username = 'root';
$password = '123456';
$database = 'IM';
$conn = new mysqli($host, $username, $password, $database);

if (isset($_GET['month']) && isset($_GET['year'])){
    $month = intval($_GET['month']);
    $year = intval($_GET['year']);

    $sql = "SELECT Country, SUM(if ((Sentiment='pos' AND MONTH(Date)=" . $month .  " AND YEAR(Date)=" . $year . "), 1, 0)) as 'pos', " .
        "SUM(if ((Sentiment='neu' AND MONTH(Date)=" . $month .  " AND YEAR(Date)=" . $year . "), 1, 0)) as 'neu', " .
        "SUM(if ((Sentiment='neg' AND MONTH(Date)=" . $month .  " AND YEAR(Date)=" . $year . "), 1, 0)) as 'neg' " .
        "FROM Tweet WHERE Country!='null' AND Country!='italia' AND Country!=''"
        . " GROUP BY Country";
} else {
    $sql = "SELECT Country, SUM(if (Sentiment='pos', 1, 0)) as 'pos',SUM(if (Sentiment='neu', 1, 0)) as 'neu',SUM(if (Sentiment='neg', 1, 0)) as 'neg' FROM Tweet WHERE Country!='null' AND Country!='' AND Country!='italia' GROUP BY Country";
}



if (!$conn) {
    die('Could not connect: ' . mysqli_error($conn));
}



$result = $conn->query($sql);

$jsonOBJ = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {

        $object = (Object) [
            'State' => $row['Country'],
            'freq' => ['positivo' => intval($row['pos']), 'neutrale' => intval($row['neu']), 'negativo' => intval($row['neg'])]];
        array_push($jsonOBJ, $object);
    }
    echo json_encode($jsonOBJ);
} else {
    $sql = "SELECT Country, 0 as 'pos', 0 as 'neu', 0 as 'neg' FROM Tweet WHERE Country!='italia' AND Country!='' AND Country!='null'  GROUP BY Country";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {

            $object = (Object)[
                'State' => $row['Country'],
                'freq' => ['positivo' => intval($row['pos']), 'neutrale' => intval($row['neu']), 'negativo' => intval($row['neg'])]];
            array_push($jsonOBJ, $object);
        }
        echo json_encode($jsonOBJ);
    }
}

mysqli_close($conn);
