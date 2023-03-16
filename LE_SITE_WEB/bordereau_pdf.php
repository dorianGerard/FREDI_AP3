<?php
session_start();

require('functions/dbconnect.php');
$dbh = db_connect();

try {
    $sql = "SELECT YEAR(dat_remise) as dat_remise,nom,prenom,CONCAT(adherent.adr1, ' ',  adherent.adr2, ' ', adherent.adr3) as adresse,lib_club,CONCAT(club.adr1, ' ',  club.adr2, ' ', club.adr3) as adresse_club,nr_licence,id_note 
    FROM note,utilisateur,adherent,club 
    WHERE adherent.id_club=club.id_club 
    AND adherent.id_utilisateur=utilisateur.id_utilisateur 
    and note.id_utilisateur=adherent.id_utilisateur 
    and utilisateur.id_utilisateur= :id_utilisateur";
    $params = array(
        ":id_utilisateur" => $_SESSION["id"]
    );
    $sth = $dbh->prepare($sql);
    $sth->execute($params);
    $row = $sth->fetch(PDO::FETCH_ASSOC);
}
catch (PDOException $e) {
    die("<p>Erreur lors de la requête SQL : " . $e->getMessage() . "</p>");
}

try {
    $sql = "SELECT mt_km FROM periode";
    $sth = $dbh->prepare($sql);
    $sth->execute();
    $fisc = $sth->fetch(PDO::FETCH_ASSOC);
}
catch (PDOException $e) {
    die("<p>Erreur lors de la requête SQL : " . $e->getMessage() . "</p>");
}

$id_note = $row["id_note"];

try {
    $sql = "SELECT date_ligne,lib_trajet , nb_km,nb_km*ligne.mt_km as total_frais_km  , mt_peage,mt_repas,mt_hebergement,ligne.mt_total as total_lfrais 
    FROM note,utilisateur,adherent,motif,ligne,periode 
    WHERE adherent.id_utilisateur=utilisateur.id_utilisateur 
    and note.id_utilisateur=adherent.id_utilisateur 
    and note.id_periode=periode.id_periode 
    and note.id_note=ligne.id_note 
    and ligne.id_motif=motif.id_motif 
    and utilisateur.id_utilisateur= :id_utilisateur 
    and note.id_note= :id_note";
    $params = array(
        ":id_utilisateur" => $_SESSION["id"],
        ":id_note" => $id_note
    );
    $sth = $dbh->prepare($sql);
    $sth->execute($params);
    $frais = $sth->fetchAll(PDO::FETCH_ASSOC);
}
catch (PDOException $e) {
    die("<p>Erreur lors de la requête SQL : " . $e->getMessage() . "</p>");
}

require_once "fpdf/fpdf.php";

// Instanciation de l'objet dérivé
$pdf = new FPDF();

// Metadonnées
$pdf->SetTitle('bordereau ', true);
$pdf->SetAuthor('FREDI', true);
$pdf->SetSubject('note de frais des bénévoles', true);


// Création d'une page
$pdf->AddPage();

// Définit l'alias du nombre de pages {nb}
$pdf->SetMargins(2, 10, 40);
$pdf->AliasNbPages();

// Titre de page
$pdf->SetFont('helvetica', '', 16);
$pdf->Cell(0, 8, utf8_decode('Note de frais des bénévoles'), 0, 0, 'L');
$pdf->SetTextColor(0, 31, 243);
$pdf->Cell(0, 8, utf8_decode($row["dat_remise"]), 0, 1, 'R');
$pdf->SetTextColor(0, 0, 0);
$pdf->Ln(10);

// Contenu
$pdf->SetFont('Arial', '', 8);
$pdf->SetX(10);
$pdf->Cell(30, 8, utf8_decode("Je soussigné(e)"), 0, 0, "L");
$pdf->Ln(1);
$pdf->SetTextColor(0, 31, 243);
$pdf->Cell(0, 20, utf8_decode($row["nom"] . "  " . $row["prenom"]), 0, 1, 'C');
$pdf->SetTextColor(0, 0, 0);

$pdf->SetX(10);
$pdf->Cell(30, 8, utf8_decode("demeurant"), 0, 0, "L");
$pdf->Ln(1);
$pdf->SetTextColor(0, 31, 243);
$pdf->Cell(0, 8, utf8_decode($row["adresse"]), 0, 1, 'C');
$pdf->SetTextColor(0, 0, 0);
$pdf->Ln(2);

$pdf->SetX(10);
$pdf->Cell(30, 8, utf8_decode("certifie renoncer au remboursement des frais ci-dessous et les laisser à l'association"), 0, 1, "L");
$pdf->Ln(1);
$pdf->SetTextColor(0, 31, 243);
$pdf->Cell(0, 8, utf8_decode($row["lib_club"]), 0, 1, 'C');
$pdf->Cell(0, 0, utf8_decode($row["adresse_club"]), 0, 1, 'C');
$pdf->SetTextColor(0, 0, 0);
$pdf->Ln(2);

$pdf->SetX(10);
$pdf->Cell(30, 8, utf8_decode("en tant que don."), 0, 1, "L");
$pdf->Ln(2);


$pdf->SetX(10);
$pdf->Cell(30, 8, utf8_decode("Frais de déplacement"), 0, 0, "L");
$pdf->Cell(30, 8, utf8_decode("                  Tarif kilométrique appliqué pour le rembourssement : " . $fisc["mt_km"] . " euro"), 0, 1, "L");
$pdf->Ln(1);
$pdf->SetX(10);
$pdf->Cell(25, 10, utf8_decode("Date jj/mm/aaaa"), 1, 0, 'L');
$pdf->Cell(25, 10, utf8_decode("Motif"), 1, 0, 'L');
$pdf->Cell(25, 10, utf8_decode("Trajet"), 1, 0, 'L');
$pdf->Cell(22, 10, utf8_decode("Kms parcourus"), 1, 0, 'L');
$pdf->Cell(22, 10, utf8_decode("Total frais Kms"), 1, 0, 'L');
$pdf->Cell(20, 10, utf8_decode("Coût péages"), 1, 0, 'L');
$pdf->Cell(18, 10, utf8_decode("Coût repas"), 1, 0, 'L');
$pdf->Cell(26, 10, utf8_decode("Coût hébergement"), 1, 0, 'L');
$pdf->Cell(15, 10, utf8_decode("Total"), 1, 1, 'L');
$cal_total_frais = 0;
foreach ($frais as $frai) {
    $pdf->SetTextColor(0, 31, 243);
    $pdf->SetX(10);
    $pdf->Cell(25, 7, utf8_decode($frai["date_ligne"]), 1, 0, 'L');
    $pdf->Cell(25, 7, utf8_decode($frai["lib_trajet"]), 1, 0, 'L');
    $pdf->Cell(25, 7, utf8_decode($frai["lib_trajet"]), 1, 0, 'L');
    $pdf->Cell(22, 7, utf8_decode($frai["nb_km"]), 1, 0, 'L');
    $pdf->Cell(22, 7, utf8_decode($frai["total_frais_km"]), 1, 0, 'L');
    $pdf->Cell(20, 7, utf8_decode($frai["mt_peage"]), 1, 0, 'L');
    $pdf->Cell(18, 7, utf8_decode($frai["mt_repas"]), 1, 0, 'L');
    $pdf->Cell(26, 7, utf8_decode($frai["mt_hebergement"]), 1, 0, 'L');
    $pdf->Cell(15, 7, utf8_decode($frai["total_lfrais"]), 1, 1, 'L');
    $cal_total_frais = $frai["total_lfrais"] + $cal_total_frais;
}
$pdf->SetX(10);
$pdf->Cell(183, 7, utf8_decode("Montant total des frais de déplacement"), 1, 0, 'L');
$pdf->Cell(15, 7, utf8_decode("$cal_total_frais"), 1, 1, 'L');
$pdf->SetTextColor(0, 0, 0);
$pdf->Ln(2);
$pdf->SetX(10);
$pdf->Cell(30, 8, utf8_decode("Je suis licencié sous le n° de licence suivant :"), 0, 0, "L");
$pdf->Ln(1);
$pdf->SetTextColor(0, 31, 243);
$pdf->Cell(0, 8, utf8_decode("Licence n° " . $row["nr_licence"]), 0, 1, 'C');
$pdf->SetTextColor(0, 0, 0);
$pdf->Ln(1);

$pdf->SetX(10);
$pdf->Cell(30, 8, utf8_decode("Montant total des dons"), 0, 0, "L");
$pdf->SetTextColor(0, 31, 243);
$pdf->Cell(30, 8, utf8_decode("           $cal_total_frais"), 0, 1, 'L');
$pdf->SetTextColor(0, 0, 0);
$pdf->Ln(2);

$pdf->SetX(30);
$pdf->Cell(30, 8, utf8_decode("Pour bénéficier du reçu de dons, cette note de frais doit être accompagnée de toutes les justificatifs correspondants"), 0, 1, "L");
$pdf->Ln(2);

$pdf->SetX(60);
$pdf->Cell(30, 20, utf8_decode("Signature du bénévole:"), 0, 1, "L");
$pdf->Ln(2);

$pdf->SetX(10);
$pdf->Cell(100, 8, utf8_decode("Partie réservée à l'association"), "LTR", 1, "C");
$pdf->SetX(10);
$pdf->Cell(100, 8, utf8_decode("N° d'ordre du Reçu : 2009-007"), "LR", 1, "L");
$pdf->SetX(10);
$pdf->Cell(100, 8, utf8_decode("Remis le : "), "LR", 1, "L");
$pdf->SetX(10);
$pdf->Cell(100, 8, utf8_decode("Signature du Trésorier : "), "LBR", 1, "L");

// Génération du document PDF
$pdf->Output('f', 'outfiles/bordereau_' . $row["nom"] . '_' . $row["prenom"] . '.pdf');
header('Location: index.php');