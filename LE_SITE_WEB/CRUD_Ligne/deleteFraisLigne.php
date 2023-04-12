<?php

include "../functions/dbconnect.php";
session_start();
$laDATA = db_connect();

if ($_SESSION['roleid'] === 3 || $_SESSION['roleid'] === 2) {
    header("Location: index.php");
}
if(!isset($_POST['Delete'])) {
    header("Location: ../index.php");
}

$submitDelete = isset($_POST['submitDelete']);

$id_ligne = isset($_POST['idLigne']) ? trim($_POST['idLigne']) : NULL;

$date = isset($_POST['Date']) ? trim($_POST['Date']) : NULL;
$lib_trajet = isset($_POST['Nom']) ? trim($_POST['Nom']) : NULL;
$nb_km = isset($_POST['nbKm']) ? trim($_POST['nbKm']) : NULL;
$mt_peage = isset($_POST['mtPeage']) ? trim($_POST['mtPeage']) : NULL;
$mt_repas = isset($_POST['mtRepas']) ? trim($_POST['mtRepas']) : NULL;
$mt_hebergement = isset($_POST['mtHebergement']) ? trim($_POST['mtHebergement']) : NULL;

if ($submitDelete) {
    $id_ligne = isset($_POST['id_ligne']) ? $_POST['id_ligne'] : NULL;
    $sql = "delete from ligne where id_ligne=:id_ligne";
    try {
        $result = $laDATA->prepare($sql);
        $result->execute(array(":id_ligne" => $id_ligne));
    } catch (PDOException $e) {
        die("<p>Erreur lors de la requête SQL : " . $e->getMessage() . "</p>");
    }
    header('location: ../frais.php');
} else {
    $sql = "select * from ligne where id_ligne=:id_ligne";
    try {
        $result = $laDATA->prepare($sql);
        $result->execute(array(":id_ligne" => $id_ligne));
        $row = $result->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("<p>Erreur lors de la requête SQL : " . $e->getMessage() . "</p>");
    }

    $date = $row['date_ligne'];
    $lib_trajet = $row['lib_trajet'];
    $nb_km = $row['nb_km'];
    $mt_peage = $row['mt_peage'];
    $mt_repas = $row['mt_repas'];
    $mt_hebergement = $row['mt_hebergement'];
    $mt_hebergement = $row['mt_hebergement'];
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
    <h1>Supprimer une ligne</h1>
    <p>Retourner à l'<a href="../index.php">accueil</a></p>

    <form action='deleteFraisLigne.php' method='POST'>
        <p>Date : <br /><input type='date' class="logCSS" name='Date' value='<?php echo $date; ?>' disabled="disabled" /></p>
        <p>Trajet : <br /><input type='text' class="logCSS" name='Nom' value='<?php echo $lib_trajet; ?>' disabled="disabled" /></p>
        <p>Nombre Kilomètre : <br /><input type='number' class="logCSS" name='nbKm' value='<?php echo $nb_km; ?>' disabled="disabled" /></p>
        <p>Montant Péage : <br /><input type='number' class="logCSS" name='mtPeage' value='<?php echo $mt_peage; ?>' disabled="disabled" /></p>
        <p>Montant Repas : <br /><input type='number' class="logCSS" name='mtRepas' value='<?php echo $mt_repas; ?>' disabled="disabled" /></p>
        <p>Montant Hébergement : <br /><input type='number' class="logCSS" name='mtHebergement' value='<?php echo $mt_hebergement; ?>' disabled="disabled" /></p>
        <input type='hidden' name='id_ligne' value='<?php echo $id_ligne; ?>' />

        <p><br /><input type='submit' class="button-3" name='submitDelete' value='Supprimer' /></p>
    </form>

</body>

</html>
