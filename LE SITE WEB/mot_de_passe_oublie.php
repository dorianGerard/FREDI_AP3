<?php
//on inclue le fichier php pour pouvoir aller chercher la fonction
include 'functions/db_functions.php';

// Connexion à la base
$dbh = db_connect();

// Lecture du formulaire
$email = isset($_POST['email']) ? $_POST['email'] : '';
$submit = isset($_POST['submit']);

// Check dans la base
if ($submit) {
  // Formulaire validé
  $sql = "SELECT * FROM utilisateur WHERE mail = :email "; //on prepare la requete, cette requete verfie si un utilisateur dans la base correspond au mail rentré
  try {
    $sth = $dbh->prepare($sql);
    $sth->execute(array(":email" => $email));
    $row = $sth->fetch(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    die("<p>Erreur lors de la requête SQL : " . $e->getMessage() . "</p>");
  }
  if ($row['mail'] === $email) { //si la mail correspond alors on renvoie un nouveau mdp
    
    // on genere un nouveau mot de passe
    $newPassword = getRandomPassword(); 
    
    //update password on database
    $sql = "UPDATE utilisateur SET mdp=:mdp WHERE mail=:email"; //on prepare la requete
    try {
      $sth = $dbh->prepare($sql);
      $sth->execute(array(":email" => $email, ":mdp" => $newPassword));
    } catch (PDOException $e) {
      die("<p>Erreur lors de la requête SQL : " . $e->getMessage() . "</p>");
    }

    $message = "Votre nouveau de passe est: <span style='color: blue;'>" . $newPassword . "</span>";
  } else {
    // sinon cela signifie qu'aucun compte avec cette adresse mail n'existe
    $message = "Le mail tapé ne correspond pas.";
  }
} else {
  $message = "Entrez l'adresse mail associée à votre compte";
}

function getRandomPassword()
{
  $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
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
  <h1>Mot de passse oublié</h1>
  <p><?php echo $message; ?></p>
  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <p>Adresse E-mail<br />
      <input name="email" id="email" type="text" value="" />
    </p>
    <p><a href="index.php">Retour</a></p>
    <p><input type="submit" name="submit" value="Confirmer" />&nbsp;<input type="reset" value="Réinitialiser" /></p>
  </form>
</body>

</html>