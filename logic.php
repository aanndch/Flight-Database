<?php

    $msg = '';
    $msgClass = '';

    if(!filter_has_var(INPUT_POST, 'submit')) {
        return;
    }

    $id = filter_var($_POST['id'], FILTER_SANITIZE_STRING);
    $fromCode = strtoupper($_POST['fromCode']);
    $toCode = strtoupper($_POST['toCode']);
    $flightNo = strtoupper($_POST['flightNo']);
    $departTime = $_POST['departTime'];
    $arrivalTime = $_POST['arrivalTime'];

    if(empty($id) || empty($fromCode) || empty($toCode) || empty($flightNo) || empty($departTime) || empty($arrivalTime)) {
        $msg = 'Please fill in all fields';
        $msgClass = 'alert-danger';
        return;
    }

    $host = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $dbname = "airportdb";  

    $conn = new mysqli ($host, $dbusername, $dbpassword, $dbname);

    if (mysqli_connect_error()) {
        return die('Connect Error ('. mysqli_connect_errno() .') '
        . mysqli_connect_error());
    }

    $sql = "SELECT *
            FROM airport
            WHERE airport_code = '$fromCode'";
    $result = $conn->query($sql);

    if($result->num_rows == 0) {
        $sql = "INSERT into airport(airport_code) values('$fromCode')";
        $conn->query($sql);
        $fromID = $conn->insert_id;
    } else {
        $obj = $result->fetch_object();
        $fromID = $obj->id;
    }

    $sql = "SELECT *
            FROM airport
            WHERE airport_code = '$toCode'";
    $result = $conn->query($sql);

    if($result->num_rows == 0) {
        $sql = "INSERT into airport(airport_code) values('$toCode')";
        $conn->query($sql);
        $toID = $conn->insert_id;
    } else {
        $obj = $result->fetch_object();
        $toID = $obj->id;
    }

    $sql = "SELECT
                *
            FROM
                flight as f
            WHERE
                (f.from_airport_id = $fromID  -- clash departures
                AND ABS(TIMEDIFF(f.depart_time, '$departTime')) <= TIME('00:10:00'))
                OR
                (f.to_airport_id = $toID -- clash arrivals
                AND ABS(TIMEDIFF(f.arrival_time, '$arrivalTime')) <= TIME('00:10:00'))
                OR
                (f.from_airport_id = $toID -- clash depart with my arrival
                AND ABS(TIMEDIFF(f.depart_time, '$arrivalTime')) <= TIME('00:10:00'))
                OR
                (f.to_airport_id = $fromID -- clash arrival with my depart
                AND ABS(TIMEDIFF(f.arrival_time, '$departTime')) <= TIME('00:10:00'))
            ";

    $result = $conn->query($sql);

    if($result->num_rows > 0) {
        $msg = "Input Flight $flightNo clashes with: ";
        while($obj = $result->fetch_object()) {
            $msg .= "$obj->flight_no, ";
        }
        $msgClass = "alert-danger";
        return; 
    }

    $sql = "INSERT INTO flight(id, from_airport_id, to_airport_id, flight_no, depart_time, arrival_time) values ('$id', '$fromID', '$toID', '$flightNo', '$departTime', '$arrivalTime')";
    if ($conn->query($sql)){
        $msg = "$flightNo was inserted";
        $msgClass = "alert-success";
    }
    else {
        $msg = 'Flight ID already exists';
        $msgClass = 'alert-danger';
    }
    $conn->close();

?>