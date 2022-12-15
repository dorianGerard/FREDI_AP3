<?php

require_once "fpdf/fpdf.php";
require_once "classes/monpdf.php";
include 'functions/dbconnect.php';

$laDATA = db_connect();

// Lecture des club depuis la DB
try {
    $sql = "SELECT * FROM club ORDER BY lib_club";
    $result = $laDATA->prepare($sql);
    $result->execute();
    $clubs = $result->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
    $message += '\n' + $ex->getMessage();
}

// Lecture des notes depuis la DB
try {
    $sql = "SELECT * FROM club, adherent, note, periode, ligne, motif WHERE club.id_club = adherent.id_club AND adherent.id_utilisateur = note.id_utilisateur AND note.id_periode = periode.id_periode AND note.id_note = ligne.id_note AND ligne.id_motif = motif.id_motif ORDER BY lib_motif";
    $result = $laDATA->prepare($sql);
    $result->execute();
    $rows = $result->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
    $message += '\n' + $ex->getMessage();
}

// Calcul du montant total
$montantGlobal = 0;
foreach ($rows as $row) {
    $montantGlobal += $row['mt_total'];
}

// Instanciation de l'objet dérivé
$pdf = new Monpdf();

// Metadonnées
$pdf->SetTitle('Cumul des frais', true);
$pdf->SetAuthor('FREDI', true);
$pdf->SetSubject('Cumul des frais', true);
$pdf->mon_fichier = "cumul_des_frais.pdf";

// Création d'une page
$pdf->AddPage();

// Définit l'alias du nombre de pages {nb}
$pdf->AliasNbPages();

// Titre
$pdf->SetFont('', 'B', 16);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(0, 7, utf8_decode('Notes de frais'), false, 0, 'C');
$pdf->Ln(15);

// foreach ($clubs as $club) {
//     if (in_array($club['id_club'], $rows)) {
//         $pdf->SetFont('', '', 16);
//         $pdf->SetTextColor(0, 0, 0);
//         $pdf->Cell(0, 7, utf8_decode($club['lib_club']), false, 0, 'C');

if ($rows === false && $message) {
    $pdf->Cell(40, 10, 'Erreur : impossible de trouver les informations !');
    $pdf->Cell(40, 10, utf8_decode($message), false);
} else {
    $pdf->SetFont('', 'B', 12);
    $pdf->Cell(10, 7, utf8_decode('ID'), true);
    $pdf->Cell(35, 7, utf8_decode('Est valide ?'), true);
    $pdf->Cell(35, 7, utf8_decode('Total'), true);
    $pdf->Cell(35, 7, utf8_decode('Date de remise'), true);
    $pdf->Cell(35, 7, utf8_decode('Libelle période'), true);
    $pdf->Cell(35, 7, utf8_decode('Libelle Motif'), true);
    $pdf->Ln();

    $pdf->SetFont('', '', 12);
    foreach ($rows as $row) {
        // if ($club['id_club'] === $row['id_club']) {
        $pdf->Cell(10, 7, utf8_decode($row['id_note']), true);
        $pdf->Cell(35, 7, utf8_decode($row['est_valide'] === 0 ? 'Non' : 'Oui'), true);
        $pdf->Cell(35, 7, utf8_decode($row['mt_total']), true);
        $pdf->Cell(35, 7, utf8_decode($row['dat_remise']), true);
        $pdf->Cell(35, 7, utf8_decode($row['lib_periode']), true);
        $pdf->Cell(35, 7, utf8_decode($row['lib_motif']), true);
        $pdf->Ln();
        // }
    }
    $pdf->Ln(10);
    $pdf->Cell(40, 7, utf8_decode('Total: ' . count($rows) . ' notes'), false);
    $pdf->Ln();
    $pdf->Cell(40, 7, utf8_decode('Montant total global: ' . $montantGlobal . ' euros'), false);
}
//     }
// }

// Génération du document PDF
unlink('outfiles/' . $pdf->mon_fichier); // suppr fichier
$pdf->Output('f', 'outfiles/' . $pdf->mon_fichier);
header('Location: outfiles/' . $pdf->mon_fichier); // redirection vers le fichier dans le dossier outfiles
