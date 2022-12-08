<?php
 
    include "../functions/dbconnect.php";
    session_start();
    $laDATA = db_connect();
    $submit = isset($_POST['edit']);
    $submitEdit = isset($_POST['submitEdit']);
 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Ligne</title>
</head>
<body>
    <?php
    
    if($submitEdit) {
        $date = isset($_POST['Date']) ? trim($_POST['Date']) : NULL;
        $lib_trajet = isset($_POST['Nom']) ? trim($_POST['Nom']) : NULL;
        $nb_km = isset($_POST['nbKm']) ? trim($_POST['nmKm']) : NULL;
        $idLigne = isset($_POST['mtPeage']) ? trim($_POST['mtPeage']) : NULL;
        $mt_Repas = isset($_POST['mtRepas']) ? trim($_POST['mtRepas']) : NULL;
        $mt_hebergement= isset($_POST['mtHebergement']) ? trim($_POST['mtHebergement']) : NULL;
        $mt_total = $mt_repas + $mt_hebergement + $mt_peage + $mt_???;
    }
    
    ?>
    <h1>Modifier une ligne</h1>
    <?php
    
    if($submit){
    $idLigne = isset($_POST['idLigne']) ? trim($_POST['idLigne']) : NULL;

// REQUETE POUR LES VALUES
try {
    $sql = "SELECT date_ligne, lib_trajet, nb_km, mt_peage, mt_repas, mt_hebergement FROM ligne WHERE id_ligne = :id_ligne";
    $result = $laDATA -> prepare($sql);
    $result->execute(array(":id_ligne" => $idLigne));
    $row = $result->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
    die("Erreur lors de la requête SQL : " . $ex->getMessage());
}

echo        "<form action='addFraisLigne.php' method='POST'>";
echo            "<p>Date : <br/><input type='date' name='Date' value='".$row['date_ligne']."' required/></p>";
echo            "<p>Trajet : <br/><input type='text' name='Nom' value='".$row['lib_trajet']."' required/></p>";
echo            "<p>Nombre Kilomètre : <br/><input type='text' name='nbKm' value='".$row['nb_km']."' required/></p>";
echo            "<p>Montant Péage : <br/><input type='text' name='mtPeage' value='".$row['mt_peage']."' required/></p>";
echo            "<p>Montant Repas : <br/><input type='text' name='mtRepas' value='".$row['mt_repas']."'required/></p>";
echo            "<p>Montant Hébergement : <br/><input type='text' name='mtHebergement' value='".$row['mt_hebergement']."' required/></p>";
echo            "<p><br/><input type='submit' name='submitEdit' value='Envoyer' /></p>";
echo        "</form>";
    } else {
        header('location: ../index.php');
    }
    
    ?>
</body>
</html>