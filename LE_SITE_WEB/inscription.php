<?php
$submit = isset($_POST['submit']);

if ($submit) {
    include 'functions/dbconnect.php';
    $laDATA = db_connect();

    // On Récupère les données en $_POST
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

    try {
        $sql = "SELECT pseudo FROM utilisateur WHERE pseudo = :pseudo";
        $result = $laDATA -> prepare($sql);
        $result->execute(array(":pseudo"=>$username));
        $testUsername = $result->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $ex) {
        die("Erreur lors de la requête SQL : " . $ex->getMessage());
    }

    if(isset($testUsername))
    {
        $testUsername = True;
    } else {
        $testUsername = False;
    }

    try {
        $sql = "SELECT mail FROM utilisateur WHERE mail = :mail";
        $result = $laDATA -> prepare($sql);
        $result->execute(array(":mail"=>$email));
        $row = $result->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $ex) {
        die("Erreur lors de la requête SQL : " . $ex->getMessage());
    }

    if(isset($testMail))
    {
        $testMail = True;
    } else {
        $testMail = False;
    }

    // On vérifie que les 2 mots de passes rentrés concorde parfaitement
    if ($mdp == $confirmMDP && $testUsername == False && $testMail == False)
    {
        // On hash le mot de passe et l'incère dans une nouvelle variable
        $mdpHash = password_hash($confirmMDP, PASSWORD_BCRYPT);

        try {
            // On insert les données dans la table utilisateur
            $sql = "INSERT INTO utilisateur(mail, nom, mdp, prenom, pseudo, role) VALUES (:email,:nom, :mdpHash, :prenom,:username, :ligue_user)";
            $result = $laDATA -> prepare($sql);
            $result->execute(array(":email"=>$email,":mdpHash"=>$mdpHash,":nom"=>$nom,":prenom"=>$prenom,":username"=>$username,":ligue_user"=>$ligue_user));
            
            // On récupère l'id utilisateur fraichement créé juste avant 
            $sql = "SELECT id_utilisateur FROM utilisateur WHERE mail = :mail";
            $result = $laDATA -> prepare($sql);
            $result->execute(array(":mail"=>$email));
            $row = $result->fetch(PDO::FETCH_ASSOC);
            // On met la valeur de l'id dans la variable ci-dessous
            $id_User = $row['id_utilisateur'];

            // On insert le reste des données dans la table adherent concernant l'id_utilisateur concerné
            $sql = "INSERT INTO adherent(id_utilisateur, nr_licence,adr1,adr2,adr3, id_club) VALUES (:id_user, :Nlicense, :adr1, :adr2, :adr3, :id_club)";
            $result = $laDATA -> prepare($sql);
            $result->execute(array(":Nlicense"=>$Nlicense, ":adr1"=>$adresse1,":adr2"=>$adresse2,":adr3"=>$adresse3, ":id_user"=>$id_User, "id_club"=>$ligue_user));
            
            // Une fois toute s'est action terminé, on part dans la page connexion.
            header('location: connexion.php');

        } catch (PDOException $ex) {
            die("Erreur lors de la requête SQL : " . $ex->getMessage());
        }
        
    } 
    
    if($testMail == True)
    {
        echo "Cette adresse email existe déjà.";
    }
    if($testUsername == True)
    {
        echo "Ce nom d'utilisateur existe déjà.";
    }
    else {
        echo "Les mots de passe ne corresponde pas.";
    }
}
// Vérifier que le pseudo entré soit pas déjà existant dans la BDD
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8" />
	    <link rel="stylesheet" href="css/style.css">
        <title>Inscription</title>
    </head>

    <body>
        <h1><p>Creer son compte</p></h1>
        <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
            <p>Prénom : <input type="text" name="prenom" required></p>
            <p>Nom : <input type="text" name="nom" required></p>
            <p>Nom d'utilisateur : <input type="text" name="Username" required></p>
            <p>E-mail : <input type="text" name="email" required></p>
            <p>Adresse : <input type="text" name="adresse1" required></p>
            <p>Adresse N°2 : <input type="text" name="adresse2"></p>
            <p>Complèment d'adresse : <input type="text" name="adresseC"></p>
            <p>Choix du club : <select name="ligue_user" required>
                <option value="1">Club de Foot de Nancy</option>
                <option value="2">Club de Foot de Metz</option>
                <option value="3">Club de Handball de Lunéville</option>
                <option value="4">Club de Handball de Epinal</option>
                <option value="5">Club de Golf de Verdun</option>
                <option value="6">Club de Golf de Longwy</option>
                <option value="7">Club de Judo de ThionVille</option>
                <option value="8">Club de Judo de bar-le-Duc</option>
                <option value="9">Club de Rugby de Yutz</option>
                <option value="10">Club de Rugby de Bitche</option>
                </select><br/>
            </p>
            <p>Numéro de license: <input type="text" name="Nlicense" required></p>
            <p>Mot de passe : <input type="password" name="mdp" required></p>
            <p>Confirmation du mot de passe : <input type="password" name="confirmMDP" required></p>
            <p>Déjà un compte ? <a href="connexion.php">Se connecter</a></p>
            <div class="BottonLa"><p><input type="submit" name="submit" value="Inscription"> <input type="reset" value="Reset"> </p></div>
        </form>
    </body>
</html>