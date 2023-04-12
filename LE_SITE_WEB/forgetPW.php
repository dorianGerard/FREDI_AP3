<?php
//on inclue le fichier php pour pouvoir aller chercher la fonction
include 'functions/dbconnect.php';
include 'functions/logs_functions.php';

// Connexion à la base
$dbh = db_connect();

// Lecture du formulaire
$email = isset($_POST['email']) ? $_POST['email'] : '';
$submit = isset($_POST['submit']);

// Check dans la base
if ($submit) {
  // Formulaire validé
  $sql = "SELECT mail FROM utilisateur WHERE mail = :email "; //on prepare la requete, cette requete verfie si un utilisateur dans la base correspond au mail rentré
  try {
    $sth = $dbh->prepare($sql);
    $sth->execute(array(":email" => $email));
    $row = $sth->fetch(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    die("<p>Erreur lors de la requête SQL : " . $e->getMessage() . "</p>");
  }
  if (!empty($row) && isset($row['mail'])) { //si la mail correspond alors on renvoie un nouveau mdp

    // on genere un nouveau mot de passe
    $newPassword = getRandomPassword();
    $newPasswordHashed = password_hash($newPassword, PASSWORD_BCRYPT);

    //update password on database
    $sql = "UPDATE utilisateur SET mdp=:mdp WHERE mail=:email"; //on prepare la requete
    try {
      $sth = $dbh->prepare($sql);
      $sth->execute(array(":email" => $email, ":mdp" => $newPasswordHashed));
    } catch (PDOException $e) {
      die("<p>Erreur lors de la requête SQL : " . $e->getMessage() . "</p>");
    }

    $message = "Votre nouveau mot de passe est: <span style='color: blue;'>" . $newPassword . "</span>";

    // écriture dans les logs
    $log = "Nouveau mdp assigné pour " . $row['mail'] . ": " . $newPassword . "\n";
    write_in_logs($log);
  } else {
    // sinon cela signifie qu'aucun compte avec cette adresse mail n'existe
    $message = "L'adresse Email tapé ne correspond pas.";
    // écriture dans les logs
    $log = "Tentative de récuperation de mot de passe échoué avec l'adresse: " . $email . "\n";
    write_in_logs($log);
  }
} else {
  $message = "Entrez l'adresse mail associée à votre compte";
}

function getRandomPassword()
{
  $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890$!*';
  $pass = array();
  $alphaLength = strlen($alphabet) - 1;
  for ($i = 0; $i < 16; $i++) {
    $n = rand(0, $alphaLength);
    $pass[] = $alphabet[$n];
  }
  return implode($pass);
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>FREDI - Mot de passe oublié</title>
  <link rel="stylesheet" href="css/styles.css">
  <link rel="icon" href="img/logo_ph30.jpg">
</head>

<body>
  <div class="logCSS">
    <h1>Mot de passse oublié</h1>
    <p><?php echo $message; ?></p>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
      <input class="logCSS" name="email" id="email" type="text" placeholder="Adresse Email" />
      <p><input class="button-3" type="submit" name="submit" value="Confirmer" />&nbsp;<input class="button-3" type="reset" value="Réinitialiser" /></p>
    </form>
    <p>Retour a l'<a href="index.php">accueil</a></p>
  </div>
</body>

</html>