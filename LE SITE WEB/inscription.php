<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="css/style.css">
    <title>Inscription</title>
</head>

<body>
    <h1>
        <p>Creer son compte</p>
    </h1>
    <form action="action_inscription.php" method="post">
        <p>Prénom : <input type="text" name="prenom" required></p>
        <p>Nom : <input type="text" name="nom" required></p>
        <p>Nom d'utilisateur : <input type="text" name="Username" required></p>
        <p>E-mail : <input type="text" name="email" required></p>
        <p>Adresse : <input type="text" name="adresse1" required></p>
        <p>Adresse N°2 : <input type="text" name="adresse2"></p>
        <p>Complèment d'adresse : <input type="text" name="adresseC"></p>
        <p>Choix du club : <select name="ligue_user" name="ligue_user" required>
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
            </select><br />
        </p>
        <p>Numéro de license: <input type="text" name="Nlicense" required></p>
        <p>Mot de passe : <input type="text" name="mdp" required></p>
        <p>Confirmation du mot de passe : <input type="text" name="confirmMDP" required></p>
        <p>Déjà un compte ? <a href="login.php">Se connecter</a></p>
        <div class="BottonLa">
            <p><input type="submit" name="inscrip" value="Inscription"> <input type="reset" value="Reset"> </p>
        </div>
    </form>
</body>

</html>