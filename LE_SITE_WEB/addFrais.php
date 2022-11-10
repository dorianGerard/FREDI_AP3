<?php include "functions/dbconnect.php"; $laDATA = db_connect(); session_start(); $submit = isset($_POST['submit']);

if ($submit)
{
    $trajet = isset($_POST['lib_trajet']) ? trim($_POST['lib_trajet']) : NULL;
    $date = isset($_POST['date_ligne']) ? trim($_POST['date_ligne']) : NULL;
    $km = isset($_POST['nb_km']) ? trim($_POST['nb_km']) : NULL;
    $peage = isset($_POST['mt_peage']) ? trim($_POST['mt_peage']) : NULL;
    $repas = isset($_POST['mt_repas']) ? trim($_POST['mt_repas']) : NULL;
    $hebergement = isset($_POST['mt_hebergement']) ? trim($_POST['mt_hebergement']) : NULL;
    $motif = isset($_POST['lib_motif']) ? trim($_POST['lib_motif']) : NULL;
try {
    $sql= "SELECT id_periode, mt_km FROM periode WHERE est_active = 1";
    $result = $laDATA -> prepare($sql);
    $result->execute();
    $row = $result->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
    die("Erreur lors de la requête SQL ligne 18 : " . $ex->getMessage());
}
    $prixKm = $row['mt_km'];
    $id_periode = $row['id_periode'];

    $mt_km = $km * $prixKm;
    $mt_total = $mt_km + $peage + $repas + $hebergement;
try {
    $sql= "INSERT INTO note(est_valide, mt_total, dat_remise, id_periode, id_utilisateur) VALUES(0, :mt_total, :date_remis, :id_periode, :id_utilisateur)";
    $result = $laDATA -> prepare($sql);
    $result->execute(array(":mt_total" => $mt_total, ":date_remis" => $date, ":id_periode" => $id_periode, ":id_utilisateur" => $_SESSION['id']));
} catch (PDOException $ex) {
    die("Erreur lors de la requête SQL ligne 30: " . $ex->getMessage());
}
try {
    $sql= "SELECT id_note FROM note WHERE id_utilisateur = :id_user AND dat_remise = :date";
    $result = $laDATA -> prepare($sql);
    $result->execute(array(":id_user" => $_SESSION['id'], ":date" => $date));
    $row = $result->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
    die("Erreur lors de la requête SQL Ligne 38: " . $ex->getMessage());
}
try {
    echo "INSERT INTO ligne(dat_ligne, lib_trajet, nb_km, mt_km, mt_peage, mt_repas, mt_hebergement, mt_total, id_motif, id_note) VALUES (".$date.", ".$trajet.", ".$km.", ".$mt_km.", ".$peage.", ".$repas.", ".$hebergement.", ".$mt_total.", ".$motif.", ".$row['id_note'].")\n";
    $sql= "INSERT INTO ligne(dat_ligne, lib_trajet, nb_km, mt_km, mt_peage, mt_repas, mt_hebergement, mt_total, id_motif, id_note) VALUES (:date_ligne, :lib_trajet, :nb_km, :mt_km, :mt_peage, :mt_repas, mt_hebergement, :mt_total, :id_motif, :id_note)";
    $result = $laDATA -> prepare($sql);
    $result->execute(array(":date_ligne" => $date,  ":lib_trajet" => $trajet, ":nb_km" => $km, ":mt_km" => $mt_km, ":mt_peage" => $peage, ":mt_repas" => $repas, "mt_hebergement" => $hebergement, ":mt_total" => $mt_total, ":id_motif" => $motif, ":id_note" => $row['id_note']));
} catch (PDOException $ex) {
    die("Erreur lors de la requête SQL ligne 45: " . $ex->getMessage());
}
    header("Location : frais.php");
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Ajouter une note de frais</title>
</head>
<body>
    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
    <p>Trajet : <input type="text" name="lib_trajet" required></p>
    <p>Date : <input type="date" name="date_ligne" required></p>
    <p>Nombre Km : <input type="text" name="nb_km"></p>
    <p>Montant péage : <input type="text" name="mt_peage"></p>
    <p>Montant repas : <input type="text" name="mt_repas"></p>
    <p>Montant hebergement : <input type="text" name="mt_hebergement"></p>
    <p>Motif : <select name="lib_motif" required>
        <?php 
            $sql = "SELECT * FROM motif";
            $result = $laDATA -> prepare($sql);
            $result->execute();
            $row = $result->fetchAll(PDO::FETCH_ASSOC);
            foreach($row as $rows)
            {
                echo "<option value='".$rows['id_motif']."'>".$rows['lib_motif']."</option>";
            }
        ?>
        </select><br/>
        <div class="BottonLa"><p><input type="submit" name="submit" value="Ajouter"> <input type="reset" value="Reset"> </p></div>
    </form>
    <p>Revenir au <a href="frais.php">note de frais</a></p>
    <p>Revenir a <a href="index.php">l'accueil</a></p>
</body>
</html>