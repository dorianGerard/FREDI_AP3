<?php

function db_connect()
{
    $dsn = 'mysql:host=localhost;port=3306;dbname=fredi21';
    $user = 'SELECTO';
    $PW = '$ùS3L3C7Où$';
    try {
        $laDATA = new PDO($dsn, $user, $PW, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        $laDATA->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $ex) {
        die('Erreur lors de la connexion SQL : ' . $ex->getmessage());
    }
    return $laDATA;
}  


?>
