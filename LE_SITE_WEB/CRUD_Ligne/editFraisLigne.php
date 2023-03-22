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
    <link rel="stylesheet" href="../css/styles.css">
    <title>Modifier Ligne</title>
</head>
<body>
    <?php
    
    if($submitEdit) {
        $date = isset($_POST['Date']) ? trim($_POST['Date']) : NULL;
        $lib_trajet = isset($_POST['Nom']) ? trim($_POST['Nom']) : NULL;
        $nb_km = isset($_POST['nbKm']) ? trim($_POST['nbKm']) : NULL;
        $mt_peage = isset($_POST['mtPeage']) ? trim($_POST['mtPeage']) : NULL;
        $mt_repas = isset($_POST['mtRepas']) ? trim($_POST['mtRepas']) : NULL;
        $mt_hebergement= isset($_POST['mtHebergement']) ? trim($_POST['mtHebergement']) : NULL;
        $idLigne = isset($_POST['idLigne']) ? trim($_POST['idLigne']) : NULL; 

        try {
        $sql = "SELECT id_periode, mt_km FROM periode WHERE est_active = 1";
        $result = $laDATA -> prepare($sql);
        $result->execute();
        $row = $result->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            die("Erreur lors de la requête SQL l35: " . $ex->getMessage());
        }

        $mt_km = $row['mt_km'] * $nb_km;
        $mt_total = $mt_repas + $mt_hebergement + $mt_peage + $mt_km;

        try {
        $sql = "UPDATE ligne SET date_ligne = :dateL, lib_trajet = :lib_trajet, nb_km = :nb_km, mt_km = :mt_km, mt_peage = :mt_peage, mt_repas = :mt_repas, mt_hebergement = :mt_hebergement, mt_total = :mt_total WHERE id_ligne = :id_ligne";
        $result = $laDATA -> prepare($sql);
        $result->execute(array(":id_ligne" => $idLigne, ":dateL" => $date, ":lib_trajet" => $lib_trajet, ":nb_km" => $nb_km, ":mt_km" => $mt_km, ":mt_peage" => $mt_peage, ":mt_repas" => $mt_repas, ":mt_hebergement" => $mt_hebergement, ":mt_total" => $mt_total));
        } catch (PDOException $ex) {
            die("Erreur lors de la requête SQL l46: " . $ex->getMessage());
        }
        header('location: ../frais.php');
        
    }
    
    ?>
    <h1>Modifier une ligne</h1>
    <p>Retourner à l'<a href="../index.php">accueil</a></p>
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

echo        "<form action='editFraisLigne.php' method='POST'>";
echo            "<p>Date : <br/><input type='date' class='logCSS' name='Date' value='".$row['date_ligne']."' required/></p>";
echo            "<p>Trajet : <br/><input type='text' class='logCSS' name='Nom' value='".$row['lib_trajet']."' required/></p>";
echo            "<p>Nombre Kilomètre : <br/><input type='number' class='logCSS' name='nbKm' value='".$row['nb_km']."' required/></p>";
echo            "<p>Montant Péage : <br/><input type='number' class='logCSS' name='mtPeage' value='".$row['mt_peage']."' required/></p>";
echo            "<p>Montant Repas : <br/><input type='number' class='logCSS' name='mtRepas' value='".$row['mt_repas']."'required/></p>";
echo            "<p>Montant Hébergement : <br/><input type='number' class='logCSS' name='mtHebergement' value='".$row['mt_hebergement']."' required/></p>";
echo            "<p><br/><input type='submit' class='button-3' name='submitEdit' value='Envoyer' /><input type='text' name='idLigne' style='display:none;' value='".$idLigne."'></p>";
echo        "</form>";
echo        "<p>Retourner aux <a href='../frais.php'>notes de frais</a><p/>";
echo        "<p>Retourner a <a href='../index.php'>l'accueil</a><p/>";

} else {
    // Location
    header('location: ../frais.php');
}
    ?>
</body>
</html>
