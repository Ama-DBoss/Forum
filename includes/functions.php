<?php 
session_start();

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

function successInfo(){
    if(isset($_SESSION['successInfo'])){
        $output = "<div class=\"alert alert-success\" role=\"alert\">";
        $output .= htmlentities($_SESSION['successInfo']);
        $output .= "</div>";

        $_SESSION['successInfo'] = null;
        return $output;
    }
}

function failureInfo(){
    if(isset($_SESSION['failureInfo'])){
        $output = "<div class=\"alert alert-danger\" role=\"alert\">";
        $output .= htmlentities($_SESSION['failureInfo']);
        $output .= "</div>";

        $_SESSION['failureInfo'] = null;
        return $output;
    }
}

function outcomeInfo(){
    if(isset($_SESSION['outcomeInfo'])){
        $output = "<div class=\"alert alert-warning\" role=\"alert\">";
        $output .= htmlentities($_SESSION['outcomeInfo']);
        $output .= "</div>";

        $_SESSION['outcomeInfo'] = null;
        return $output;
    }
}

function __construct() {
    $DBconn = $this->connectDB();
    if(!empty($DBconn)) {
        $this->selectDB($DBconn);
    }
}

function connectDB() {
    $DBconn = mysql_connect($this->host,$this->user,$this->password);
    return $DBconn;
}

function selectDB($DBconn) {
    mysql_select_db($this->database,$DBconn);
}

function runQuery($query) {
    $result = mysql_query($query);
    while($row=mysql_fetch_assoc($result)) {
        $resultset[] = $row;
    }		
    if(!empty($resultset))
        return $resultset;
}

function numRows($query) {
    $result  = mysql_query($query);
    $rowcount = mysql_num_rows($result);
    return $rowcount;	
}

function updateQuery($query) {
    $result = mysql_query($query);
    if (!$result) {
        die('Invalid query: ' . mysql_error());
    } else {
        return $result;
    }
}

function insertQuery($query) {
    $result = mysql_query($query);
    if (!$result) {
        die('Invalid query: ' . mysql_error());
    } else {
        return $result;
    }
}

function deleteQuery($query) {
    $result = mysql_query($query);
    if (!$result) {
        die('Invalid query: ' . mysql_error());
    } else {
        return $result;
    }
}

function getRealIP(){
    if(!empty($_SERVER['HTTP_CLIENT_IP'])) { //check for ip from shared internet
        $ip=$_SERVER['HTTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) { //check if ip is passed from proxy
        $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else {
        $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
?>
