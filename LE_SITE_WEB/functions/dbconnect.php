<?php

function db_connect()
{
    $dsn = 'mysql:host=51.254.125.218;port=3735;dbname=fredi21';
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