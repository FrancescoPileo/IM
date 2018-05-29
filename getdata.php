<?php
/**
 * Created by PhpStorm.
 * User: pil
 * Date: 29/05/18
 * Time: 10.40
 */

$host = 'localhost';
$username = 'root';
$password = '123456';
$database = 'IM';
$conn = new mysqli($host, $username, $password, $database);

if (!$conn) {
    die('Could not connect: ' . mysqli_error($conn));
}

$sql = "SELECT Region,SUM(if (Sentiment='pos', 1, 0)) as 'pos',SUM(if (Sentiment='neu', 1, 0)) as 'neu',SUM(if (Sentiment='neg', 1, 0)) as 'neg' FROM Tweet WHERE Region!='null' AND Country='italia' GROUP BY Region";

$result = $conn->query($sql);

$jsonOBJ = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {

        $object = (Object) [
            'State' => $row['Region'],
            'freq' => ['positivo' => intval($row['pos']), 'neutrale' => intval($row['neu']), 'negativo' => intval($row['neg'])]];
        array_push($jsonOBJ, $object);
    }
    echo json_encode($jsonOBJ);
} else {
    $sql = "SELECT Region, 0 as 'pos', 0 as 'neu', 0 as 'neg' FROM Tweet WHERE Country='italia' GROUP BY Region";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {

            $object = (Object)[
                'State' => $row['Region'],
                'freq' => ['positivo' => intval($row['pos']), 'neutrale' => intval($row['neu']), 'negativo' => intval($row['neg'])]];
            array_push($jsonOBJ, $object);
        }
        echo json_encode($jsonOBJ);
    }
}

mysqli_close($conn);
