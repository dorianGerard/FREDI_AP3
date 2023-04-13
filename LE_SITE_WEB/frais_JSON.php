<?php

include 'functions/logs_functions.php';
include "functions/dbconnect.php";
session_start();
$dbh = db_connect();

$errorSql = null;

$email = isset($_GET['email']) ? $_GET['email'] : '';
$password = isset($_GET['password']) ? $_GET['password'] : '';

// On récupère le mot de passe hashé de l'utilisateur concerné
$sql = "SELECT * FROM utilisateur WHERE mail=:email";
try {
    $sth = $dbh->prepare($sql);
    $sth->execute(array(":email" => $email));
    $rows = $sth->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
    $errorSql = $ex->getMessage();
}
$userID = $rows['id_utilisateur'];
$hashedPassword = $rows['mdp'];

// on verifie si le mdp est bon
if (password_verify($password, $hashedPassword)) {

    // si c'est la cas, on construit la partie du tableau concernant l'utilisateur, pas besoin de faire de requete, on possede deja les infos
    $utilisateur = array(
        "email" => $rows['mail'],
        "nom" => $rows['nom'],
        "prenom" => $rows['prenom'],
        "role" => $rows['role']
    );

    // on construit la partie du tableau concernant la periode
    $sql = "SELECT * FROM periode, note WHERE periode.id_periode = note.id_periode AND note.id_utilisateur = :userid";
    try {
        $sth = $dbh->prepare($sql);
        $sth->execute(array(":userid" => $userID));
        $rows = $sth->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $ex) {
        $errorSql = $ex->getMessage();
    }
    // if ($rows != NULL) {
    //     $periode = array(
    //         "libelle" => $rows['lib_periode'],
    //         "montant" => $rows['mt_km'],
    //         "statut" => $rows['est_active']
    //     );
    // }

    $periode = array();
    if ($rows != NULL) {
        $periode["libelle"] = $rows['lib_periode'];
        $periode["montant"] = $rows['mt_km'];
        $periode["statut"] = $rows['est_active'];
    }

    // on construit la partie du tableau concernant les lignes
    $sql = "SELECT id_ligne, date_ligne, mt_peage, mt_repas, mt_hebergement, mt_km, id_motif, nb_km, lib_trajet, L.mt_total FROM ligne L, note WHERE L.id_note = note.id_note AND note.id_utilisateur = :userid";
    try {
        $sth = $dbh->prepare($sql);
        $sth->execute(array(":userid" => $userID));
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $ex) {
        $errorSql = $ex->getMessage();
    }
    $lignes = array();
    if (count($rows) > 0) {
        foreach ($rows as $row) {

            //on reucupere les motifs
            $sql = "SELECT lib_motif FROM motif WHERE id_motif = :motifid";
            try {
                $sth = $dbh->prepare($sql);
                $sth->execute(array(":motifid" => $row['id_motif']));
                $motifs = $sth->fetch(PDO::FETCH_ASSOC);
                $motif = $motifs['lib_motif'];
            } catch (PDOException $ex) {
                $errorSql = $ex->getMessage();
            }

            $ligne = array(
                "id " => $row['id_ligne'],
                "date" => $row['date_ligne'],
                "libelle" => $row["lib_trajet"],
                "cout_peage" => $row['mt_peage'],
                "cout_repas" => $row['mt_repas'],
                "cout_hebergement" => $row['mt_hebergement'],
                "nb_km" => $row['nb_km'],
                "cout_km" => $row['mt_km'],
                "total_ligne" => $row['mt_total'],
                "motif" => $motif
            );
            $lignes[] = $ligne;
        }
    }

    if (isset($errorSql)) {
        $fraisArray = array(
            "message" => 'KO: ' . $errorSql
        );
    } else {
        // on place le resultat de chaque requete dans le tableau final
        $fraisArray = array(
            "message" => "OK : note g\u00e9n\u00e9r\u00e9e",
            "utilisateur" => $utilisateur,
            "periode" => $periode,
            "lignes" => $lignes,
        );
    }

    // écriture dans les logs
    $log = "Génération du JSON des notes de frais.\n";
    write_in_logs($log);
} else {
    $fraisArray = array(
        "message" => "KO : erreur sur le mdp",
    );
}

// affichage du JSON
$fraisJSON = json_encode($fraisArray, JSON_PRETTY_PRINT);
header("Content-type: application/json; charset=utf-8");
echo $fraisJSON;
