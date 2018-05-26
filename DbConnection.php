<?php

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

function getFrequencyByRegion($conn, $sentiment, $region){
    $query = "SELECT COUNT(*) AS 'n' FROM Tweet WHERE Sentiment='" . $sentiment . "' AND Region=\"" . $region . "\"";
    $ris = mysqli_fetch_assoc(mysqli_query($conn, $query));
    $conn->close();
    if ($ris['n']){
        return $ris['n'];
    } else {
        return 0;
    }
}

function getFrequencyByCountry($conn, $sentiment, $country){
    $query = "SELECT COUNT(*) AS 'n' FROM Tweet WHERE Sentiment='" . $sentiment . "' AND Country=\"" . $country . "\"";
    $ris = mysqli_fetch_assoc(mysqli_query($conn, $query));
    $conn->close();
    if ($ris['n']){
        return $ris['n'];
    } else {
        return 0;
    }
}

function getFrequencyAllCountries($conn){

    if (!$conn) {
        die('Could not connect: ' . mysqli_error($conn));
    }

    $sql = "SELECT Country ,SUM(if (Sentiment='pos', 1, 0)) as 'pos',SUM(if (Sentiment='neu', 1, 0)) as 'neu',SUM(if (Sentiment='neg', 1, 0)) as 'neg' FROM Tweet WHERE Country!='italia'"
        . " GROUP BY Country";

    $result = $conn->query($sql);

    $jsonOBJ = array();

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {

            $object = (Object) [
                'State' => strtoupper($row['Country']),
                'freq' => ['positivo' => intval($row['pos']), 'neutrale' => intval($row['neu']), 'negativo' => intval($row['neg'])]];
            array_push($jsonOBJ, $object);
        }

    } else {
        //nessuno stato straniero
    }

    mysqli_close($conn);

    return json_encode($jsonOBJ);
}




function getIdLatLon($conn){
    $return = null;
    $sql = "SELECT Id, Lat, Lon FROM Tweet";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // output data of each row
        $return = array();
        $i = 0;
        while($row = $result->fetch_assoc()) {
            $return[$i] = array($row["Id"], $row["Lat"], $row["Lon"]);
            $i++;
            //echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
        }
    } else {
        echo "0 results";
    }
    $conn->close();
    return $return;
}

function getNoCountry($conn){
    $return = null;
    $sql = "SELECT Id, Lat, Lon FROM Tweet WHERE Country=''";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // output data of each row
        $return = array();
        $i = 0;
        while($row = $result->fetch_assoc()) {
            $return[$i] = array($row["Id"], $row["Lat"], $row["Lon"]);
            $i++;
            //echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
        }
    } else {
        echo "0 results";
    }
    $conn->close();
    return $return;
}




function updateTweetLocation($conn){
    $return = false;
    $id = mysql_real_escape_string(html_entities($_POST['tweet_id']));
    $regione = mysql_real_escape_string(html_entities($_POST['regione']));

    $sql = "UPDATE Tweet SET Regione = '" . $regione . "' WHERE Id = " . $id;

    if ($conn->query($sql) === TRUE) {
        $return = true;
    }

    return $return;
}



?>
