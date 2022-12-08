<?php

function write_in_logs($whatToLog)
{
    // on indique le chemin du fichier
    $file = "logs/logs.txt";
    # Ouverture en mode écriture
    $fileopen = (fopen($file, 'a'));

    // get current date and hour
    $date = date('d-m-y h:i:s');
    // get ip address
    $ipaddress = getenv("REMOTE_ADDR");

    $newLineToLog = "[" . $date . "][" . $ipaddress . "]: " . $whatToLog;

    # Ecriture de "Début du fichier" dans le fichier texte
    fwrite($fileopen, $newLineToLog);

    # On ferme le fichier
    fclose($fileopen);
}   
