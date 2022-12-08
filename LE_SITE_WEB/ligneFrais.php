<?php 
    include "functions/dbconnect.php";
    session_start();
    $laDATA = db_connect();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ligne de frais</title>
</head>
<body>
    <a href="frais.php">note de frais</a>
    <table>
        <tr>
            <th>Id</th>
            <th>Date</th>
            <th>Nom</th>
            <th>Nombre KM</th>
            <th>Montant péage</th>
            <th>Montant repas</th>
            <th>Montant Hébergement</th>
            <th>Total</th>
            <th>ID Motif</th>
        </tr>
        <tr>
        <?php  
        
        $sql = "SELECT * FROM ligne WHERE id_note = :id_note";
        $result = $laDATA -> prepare($sql);
        $result->execute(array(":id_note" => 32));
        $row = $result->fetchAll(PDO::FETCH_ASSOC);
        
        ?>
        </tr>
    </table>
</body>
</html>