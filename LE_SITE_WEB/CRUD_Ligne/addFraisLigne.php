<?php

include "../functions/dbconnect.php";
session_start();
$laDATA = db_connect();

$submitAdd = isset($_POST['submitAdd']);

$id_note = isset($_GET['id_note']) ? $_GET['id_note'] : NULL;

echo $id_note;

$date = isset($_POST['Date']) ? trim($_POST['Date']) : NULL;
$lib_trajet = isset($_POST['Nom']) ? trim($_POST['Nom']) : NULL;
$nb_km = isset($_POST['nbKm']) ? trim($_POST['nbKm']) : NULL;
$mt_peage = isset($_POST['mtPeage']) ? trim($_POST['mtPeage']) : NULL;
$mt_repas = isset($_POST['mtRepas']) ? trim($_POST['mtRepas']) : NULL;
$mt_hebergement = isset($_POST['mtHebergement']) ? trim($_POST['mtHebergement']) : NULL;

if ($submitAdd) {
    $sql = "INSERT INTO ligne (id_note, date_ligne, lib_trajet, nb_km, mt_peage, mt_repas, mt_hebergement) values (:id_note, :date_ligne, :lib_trajet, :nb_km, :mt_peage, :mt_repas, :mt_hebergement)";
    try {
        $result = $laDATA->prepare($sql);
        $result->execute(array(
            ":id_note" => $id_note,
            ":date_ligne" => $date,
            ":lib_trajet" => $lib_trajet,
            ":nb_km" => $nb_km,
            ":mt_peage" => $mt_peage,
            ":mt_repas" => $mt_repas,
            ":mt_hebergement" => $mt_hebergement
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
    <title>Ajouter Ligne</title>
</head>

<body>
    <h1>Ajouter une ligne</h1>

    <form action='addFraisLigne.php' method='POST'>
        <p>Date : <br /><input type='date' name='Date' required /></p>
        <p>Trajet : <br /><input type='text' name='Nom' required /></p>
        <p>Nombre Kilomètre : <br /><input type='text' name='nbKm' required /></p>
        <p>Montant Péage : <br /><input type='text' name='mtPeage' required /></p>
        <p>Montant Repas : <br /><input type='text' name='mtRepas' required /></p>
        <p>Montant Hébergement : <br /><input type='text' name='mtHebergement' required /></p>
        <p><br /><input type='submit' name='submitAdd' value='Ajouter' /></p>
    </form>

</body>

</html>