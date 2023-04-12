<?php

include "../functions/dbconnect.php";
session_start();
$laDATA = db_connect();

if ($_SESSION['roleid'] === 3 || $_SESSION['roleid'] === 2) {
    header("Location: index.php");
}
if(!isset($_POST['add'])) {
    header("Location: ../index.php");
}

$submitAdd = isset($_POST['submitAdd']);

$id_note = isset($_POST['id_note']) ? trim($_POST['id_note']) : NULL; 
$idMotif = isset($_POST['id_motif']) ? trim($_POST['id_motif']) : NULL; 

echo $id_note."\n";
echo $idMotif;



if ($submitAdd) {
    $date = isset($_POST['Date']) ? trim($_POST['Date']) : NULL;
    $lib_trajet = isset($_POST['Nom']) ? trim($_POST['Nom']) : NULL;
    $nb_km = isset($_POST['nbKm']) ? trim($_POST['nbKm']) : NULL;
    $mt_peage = isset($_POST['mtPeage']) ? trim($_POST['mtPeage']) : NULL;
    $mt_repas = isset($_POST['mtRepas']) ? trim($_POST['mtRepas']) : NULL;
    $mt_hebergement = isset($_POST['mtHebergement']) ? trim($_POST['mtHebergement']) : NULL;

    $id_note = isset($_POST['id_note']) ? trim($_POST['id_note']) : NULL; 
    $idMotif = isset($_POST['id_motif']) ? trim($_POST['id_motif']) : NULL; 

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

    $sql = "INSERT INTO ligne (date_ligne, lib_trajet, nb_km, mt_km, mt_peage, mt_repas, mt_hebergement, mt_total, id_motif, id_note) values (:date_ligne, :lib_trajet, :nb_km, :mt_km, :mt_peage, :mt_repas, :mt_hebergement, :mt_total, :id_motif, :id_note)";
    try {
        $result = $laDATA->prepare($sql);
        $result->execute(array(
            ":id_note" => $id_note,
            ":date_ligne" => $date,
            ":lib_trajet" => $lib_trajet,
            ":nb_km" => $nb_km,
            ":mt_km" => $mt_km,
            ":mt_peage" => $mt_peage,
            ":mt_repas" => $mt_repas,
            ":mt_hebergement" => $mt_hebergement,
            ":mt_total" => $mt_total,
            ":id_motif" => $idMotif
        ));
    } catch (PDOException $e) {
        die("<p>Erreur lors de la requête SQL : " . $e->getMessage() . "</p>");
    }
    header('location: ../frais.php');
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Ajouter Ligne</title>
</head>

<body>
    <h1>Ajouter une ligne</h1>
    <p>Retourner à l'<a href="../index.php">accueil</a></p>
    <form action='addFraisLigne.php' method='POST'>
        <p>Date : <br /><input type='date' class="logCSS" name='Date' required /></p>
        <p>Trajet : <br /><input type='text' class="logCSS" name='Nom' required /></p>
        <p>Nombre Kilomètre : <br /><input type='number' class="logCSS" name='nbKm' required /></p>
        <p>Montant Péage : <br /><input type='number' class="logCSS" name='mtPeage' required /></p>
        <p>Montant Repas : <br /><input type='number' class="logCSS" name='mtRepas' required /></p>
        <p>Montant Hébergement : <br /><input type='number' class="logCSS" name='mtHebergement' required /></p>
        <p><br /><input type='submit' class="button-3" name='submitAdd' value='Ajouter' /></p>
        <?php echo "<input type='text' name='id_note' style='display:none;' value='" . $id_note . "'>";
        echo "<input type='text' name='id_motif' style='display:none;' value='" . $idMotif . "'>"; ?>
    </form>
    

</body>

</html>
