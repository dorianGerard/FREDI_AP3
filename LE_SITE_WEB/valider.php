<?php

include "functions/dbconnect.php";
$laDATA = db_connect();

    if(isset($_POST['submitValider']))
    {
        $sql="UPDATE note SET est_valide = '1' WHERE note.id_note = ".$_POST['idnote'];
        $bdd = $laDATA->prepare($sql);
        $bdd->execute();
        
        header("Location: controleur.php");
        
    }

?>