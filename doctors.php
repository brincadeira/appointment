<?php
include 'db.php';
include 'config.php';    
$db = new db($dbhost, $dbuser, $dbpass, $dbname);
$doctors = $db->query('SELECT * FROM doctors')->fetchAll();
$db->close();
echo json_encode($doctors);