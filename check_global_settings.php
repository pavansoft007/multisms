<?php
$mysqli = new mysqli('localhost', 'root', '', 'multisms');
if ($mysqli->connect_errno) {
    die('Connect Error: ' . $mysqli->connect_error);
}
$result = $mysqli->query('SELECT * FROM global_settings');
while($row = $result->fetch_assoc()) {
    print_r($row);
    echo "\n----------------------\n";
}
$mysqli->close();
