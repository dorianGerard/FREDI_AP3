<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Periode</title>
</head>
<body>
    

<?php
    include "functions/dbconnect.php";
    $laDATA = db_connect();

    if(isset($_POST['createPeriode']))
    {
        try {
        $sql="INSERT INTO periode (lib_periode, est_active, mt_km) VALUES ('".$_POST['libPeriode']."', '0', '".$_POST['mtKmPeriode']."')";
        $bdd = $laDATA->prepare($sql);
        $bdd->execute();
                    } catch (PDOException $ex) {
                die("Erreur lors de la requête SQL : " . $ex->getMessage());
            }
        
        header("Location: controleur.php");
    }
    
    if(isset($_POST['activePeriode']))
    {
        // Met l'actuelle periode active à 0
try {
        $sql = "UPDATE periode SET est_active = '0' WHERE periode.est_active = '1'";
        // $sql = "UPDATE periode SET est_active = '0' WHERE periode.id_periode = '".$row."'";
        $result = $laDATA->prepare($sql);
        $result->execute();
    } catch (PDOException $ex) {
        die("Erreur lors de la requête SQL : " . $ex->getMessage());
    }
        try {
        $sql="UPDATE periode SET est_active = '1' WHERE periode.id_periode = '".$_POST['idperiode']."'";
        $bdd = $laDATA->prepare($sql);
        $bdd->execute();
    } catch (PDOException $ex) {
        die("Erreur lors de la requête SQL : " . $ex->getMessage());
    }
        
        header("Location: controleur.php");
    }
    else {
        header('Location: index.php');
    }


?>

</body>
</html>