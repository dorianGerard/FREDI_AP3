<?php
include 'functions/dbconnect.php';
include 'functions/logs_functions.php';
$laDATA = db_connect();

// On Récupère les variables en $_POST
$prenom = isset($_POST['prenom']) ? trim($_POST['prenom']) : NULL;
$nom = isset($_POST['nom']) ? trim($_POST['nom']) : NULL;
$username = isset($_POST['Username']) ? trim($_POST['Username']) : NULL;
$email = isset($_POST['email']) ? trim($_POST['email']) : NULL;
$adresse1 = isset($_POST['adresse1']) ? trim($_POST['adresse1']) : NULL;
$adresse2 = isset($_POST['adresse2']) ? trim($_POST['adresse2']) : NULL;
$adresseC = isset($_POST['adresseC']) ? trim($_POST['adresseC']) : NULL;
$ligue_user = isset($_POST['ligue_user']) ? trim($_POST['ligue_user']) : NULL;
$Nlicense = isset($_POST['Nlicense']) ? trim($_POST['Nlicense']) : NULL;
$mdp = isset($_POST['mdp']) ? trim($_POST['mdp']) : NULL;
$confirmMDP = isset($_POST['confirmMDP']) ? trim($_POST['confirmMDP']) : NULL;

if ($mdp == $confirmMDP) {
    $mdpHash = password_hash($confirmMDP, PASSWORD_BCRYPT);
    try {
        $sql = "INSERT INTO utilisateur(mail, nom, mdp, prenom, pseudo, role) VALUES (:email,:nom, :mdpHash, :prenom,:username, :ligue_user)";
        $result = $laDATA->prepare($sql);
        $result->execute(array(":email" => $email, ":mdpHash" => $mdpHash, ":nom" => $nom, ":prenom" => $prenom, ":username" => $username, ":ligue_user" => $ligue_user));
        // FAIRE SELECT ID UTILISATEUR POUR L'AJOUTER A L'INSERT EN BAS SINON IL Y AURA UNE ERRERU D'ID_UTILISATEUR INCONNU
        $sql = "SELECT id_utilisateur FROM utilisateur WHERE mail = :mail";
        $result = $laDATA->prepare($sql);
        $result->execute(array(":mail" => $email));
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $id_User = $row['id_utilisateur'];
        echo $id_User;
        $sql = "INSERT INTO adherent(id_utilisateur, nr_licence,adr1,adr2,adr3, id_club) VALUES (:id_user, :Nlicense, :adr1, :adr2, :adr3, :id_club)";
        $result = $laDATA->prepare($sql);
        $result->execute(array(":Nlicense" => $Nlicense, ":adr1" => $adresse1, ":adr2" => $adresse2, ":adr3" => $adresse3, ":id_user" => $id_User, "id_club" => $ligue_user));

        // écriture dans les logs
        $log = "Ajout de l'utilisateur: " . $username . " avec le mdp " . $confirmMDP . "\n";
        write_in_logs($log);

        header('location: index.php');
    } catch (PDOException $ex) {
        die("Erreur 11 lors de la requête SQL : " . $ex->getMessage());
    }
} else {
    echo "aie";
}
