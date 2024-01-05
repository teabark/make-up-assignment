<?php

include('./constants.php');

try{
    $pdo = new PDO ("mysql:host=$hostname;dbname=$dbname", $dbusername, $dbpassword);
    $pdo-> setattribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "connected successfully!!";
}catch(PDOException $e){
    echo "connection failed" . $e-> getmessage();
}

?>