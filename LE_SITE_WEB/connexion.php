<?php

include 'functions/logs_functions.php';

// On récupère le booléan du submit et on test si c'est Vrai ou Faux
$submit = isset($_POST['submit']);
if ($submit) {

    // Connexion a la BDD et initialisation de la SESSION
    include "functions/dbconnect.php";
    session_start();
    $dbh=db_connect();

    // On récupère les données du formulaire
    $pseudo = isset($_POST['pseudo']) ? trim($_POST['pseudo']) : null;
    $mdp = isset($_POST['mdp']) ? trim($_POST['mdp']) : null;

    // Requête pour récuperer l'id_utilisateur.
    $sql = "SELECT id_utilisateur FROM utilisateur WHERE pseudo = :pseudo";
    try {
        $sth = $dbh->prepare($sql);
        $sth->execute(array(":pseudo"=>$pseudo));
        $rows = $sth->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $ex) {
        die("Erreur lors de la requête SQL : " . $ex->getMessage());
    }
    $id_user = $rows['id_utilisateur'];

    // Si id_utilisateur retourne NULL, alors le Pseudo existe pas dans la BDD
    if($id_user == NULL){
        echo "Pseudo inconnu";
        // Faire une truc pour arreter le code PHP ICI
    }

    // On récupère le mot de passe hashé de l'utilisateur concerné
    $sql = "SELECT mdp FROM utilisateur WHERE id_utilisateur = :id_user";
    try {
        $sth = $dbh->prepare($sql);
        $sth->execute(array(":id_user"=>$id_user));
        $rows = $sth->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $ex) {
        die("Erreur lors de la requête SQL : " . $ex->getMessage());
    }
    $hash = $rows['mdp'];

    // Et on vérifie que le mot de passe entré concorde avec le mot de passe Hashé
    if (password_verify($mdp, $hash)) {

        //Requête pour récuperer le role de l'utilisateur
        $sql="SELECT role FROM utilisateur WHERE id_utilisateur = :id_user";
        try {
            $sth = $dbh->prepare($sql);
            $sth->execute(array(":id_user"=>$id_user));
            $roleUtil = $sth->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            die("Erreur lors de la requête SQL : " . $ex->getMessage());
        }

        // Switch pour récupérer le Strings du role
        switch ($roleUtil['role']) {
            case 1:                     
                $rolelib = "Adhérent";
                break;
            case 2:                     
                $rolelib = "Controleur";
                break;
            case 3:
                $rolelib = "Admin";
                break;
            default:
                echo "ya un couille dans le pate";
            }
            
            // On donne les données récupéré dans la SESSION
            $_SESSION['pseudo'] = $pseudo;
            $_SESSION['rolelib'] = $rolelib;
            $_SESSION['id'] = $id_user;
            $_SESSION['roleid'] = $roleUtil['role'];
            header("Location: index.php");

            // écriture dans les logs
            $log = "Connexion reussi pour" . $_POST['pseudo'] . "\n";
            write_in_logs($log);
    }
    // Si le mot de passe concorde pas, on affiche 'Mot de passe incorrect'
        else {
            $message = "Mot de passe incorrect";
            // écriture dans les logs
            $log = "Tentative de conexion echoué pour" . $_POST['pseudo'] . "\n";
            write_in_logs($log);
        }
    }

    ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Portail de connexion FREDI</title>
</head>
<body>
    <div class="logCSS" style="margin-top: 15%;">
        <form action="<?php $_SERVER['PHP_SELF'] ?>" method=POST>
            <p><br /><input class="logCSS" name="pseudo" placeholder="Pseudo" id="pseudo" type="text" required /></p>
            <p><br /><input class="logCSS" name="mdp" placeholder="Mot de passe" id="mdp" type="password" required /></p>
            <p><input class="button-3" style="background-color: #ff895d;" type="submit" name="submit" value="Se connecter" />&nbsp;<input class="button-3" style="background-color: #ff895d;" type="reset" value="Se créer un compte" /></p>
        </form>
  </div>
</body>
</html>