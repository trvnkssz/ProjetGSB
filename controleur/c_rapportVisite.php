<?php
if (!isset($_SESSION['login'])) {
    header('Location: index.php?uc=connexion&action=connexion');
    exit();
}

require_once(__DIR__ . "/../modele/bd.rapportVisite.inc.php");
require_once(__DIR__ . "/../modele/bd.praticien.inc.php");

$action = $_REQUEST['action'] ?? 'liste';
$idVisiteur = $_SESSION['matricule'];

switch ($action) {
    case 'liste':
        $rapports = getRapportsVisiteurTous($idVisiteur);
        include("vues/v_listeRapports.php");
        break;

    case 'nouveau':
        $lesPraticiens = getPraticiens();
        include("vues/v_saisirRapport.php");
        break;

    case 'modifier':
        if (!isset($_GET['idRapport'])) {
            header('Location: index.php?uc=rapportVisite&action=liste');
            exit();
        }
        $idRapport = $_GET['idRapport'];
        $rapport = getRapportById($idVisiteur, $idRapport);
        $lesPraticiens = getPraticiens();
        include("vues/v_saisirRapport.php");
        break;

    case 'consulter':
        if (!isset($_GET['idRapport'])) {
            header('Location: index.php?uc=rapportVisite&action=liste');
            exit();
        }
        $idRapport = $_GET['idRapport'];
        $rapport = getRapportById($idVisiteur, $idRapport);
        include("vues/v_consulterRapport.php");
        break;

    case 'validerSaisie':
        $idPraticien = $_POST['idPraticien'] ?? null;
        $dateVisite = $_POST['dateVisite'] ?? null;
        $motif = trim($_POST['motif'] ?? '');
        $bilan = trim($_POST['bilan'] ?? '');
        $etat = isset($_POST['saisieDefinitive']) ? 'validé' : 'saisi en cours';

        if (empty($idPraticien) || empty($dateVisite) || empty($motif) || empty($bilan)) {
            $erreur = "Tous les champs sont obligatoires.";
            $lesPraticiens = getPraticiens();
            include("vues/v_saisirRapport.php");
            break;
        }

        if (!empty($_POST['idRapport'])) {
            updateRapportVisite($idVisiteur, $_POST['idRapport'], $idPraticien, $dateVisite, $motif, $bilan, $etat);
        } else {
            insertRapportVisite($idVisiteur, $idPraticien, $dateVisite, $motif, $bilan, $etat);
        }

        $message = ($etat === 'validé') ? "Rapport validé avec succès." : "Rapport enregistré (saisi en cours).";
        include("vues/v_message.php");
        break;

    default:
        header('Location: index.php?uc=rapportVisite&action=liste');
        break;
}
?>
