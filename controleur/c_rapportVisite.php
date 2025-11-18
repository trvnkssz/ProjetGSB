<?php
if (!isset($_SESSION['login'])) {
    header('Location: index.php?uc=connexion&action=connexion');
    exit();
}

require_once(__DIR__ . "/../modele/bd.rapportVisite.inc.php");
require_once(__DIR__ . "/../modele/bd.praticien.inc.php");
require_once(__DIR__ . "/../modele/medicament.modele.inc.php");

$action = $_REQUEST['action'] ?? 'liste';
$idVisiteur = $_SESSION['matricule'];

switch ($action) {
    case 'liste':
        $rapports = getRapportsVisiteurTous($idVisiteur);
        include("vues/v_listeRapports.php");
        break;

    case 'nouveau':
        $lesPraticiens = getPraticiens();
        $lesMedicaments = getAllNomMedicament();
        include("vues/v_saisirRapport.php");
        break;

    case 'modifier':
        if (!isset($_GET['idRapport'])) {
            header('Location: index.php?uc=rapportVisite&action=liste');
            exit();
        }
        $idRapport = $_GET['idRapport'];
        $rapport = getRapportById($idVisiteur, $idRapport);
        if (!$rapport) {
            $erreur = "Le rapport demandé est introuvable.";
            $rapports = getRapportsVisiteurTous($idVisiteur);
            include("vues/v_listeRapports.php");
            break;
        }
        $lesPraticiens = getPraticiens();
        $lesMedicaments = getAllNomMedicament();
        include("vues/v_saisirRapport.php");
        break;

    case 'consulter':
        if (!isset($_GET['idRapport'])) {
            header('Location: index.php?uc=rapportVisite&action=liste');
            exit();
        }
        $idRapport = $_GET['idRapport'];
        $rapport = getRapportById($idVisiteur, $idRapport);
        if (!$rapport) {
            header('Location: index.php?uc=rapportVisite&action=liste');
            exit();
        }
        include("vues/v_consulterRapport.php");
        break;

    case 'validerSaisie':
        $idPraticien = $_POST['idPraticien'] ?? null;
        $dateVisite = $_POST['dateVisite'] ?? null;
        $motif = trim($_POST['motif'] ?? '');
        $bilan = trim($_POST['bilan'] ?? '');
        $medicamentPresente = $_POST['medicamentPresente'] ?? '';
        $medicamentPrescrit = $_POST['medicamentPrescrit'] ?? '';
        $etat = isset($_POST['saisieDefinitive']) ? 'validé' : 'saisi en cours';
        $idRapport = $_POST['idRapport'] ?? null;

        $champsObligatoires = ['idPraticien' => $idPraticien, 'dateVisite' => $dateVisite];
        if ($etat === 'validé') {
            $champsObligatoires['motif'] = $motif;
            $champsObligatoires['bilan'] = $bilan;
        }

        $champsManquants = array_filter($champsObligatoires, fn($valeur) => empty($valeur));

        if (!empty($champsManquants)) {
            $erreur = ($etat === 'validé')
                ? "Pour valider le rapport, tous les champs doivent être remplis."
                : "Le praticien et la date de visite sont requis pour enregistrer le brouillon.";
            $lesPraticiens = getPraticiens();
            $lesMedicaments = getAllNomMedicament();
            $rapport = [
                'RAP_NUM' => $idRapport,
                'PRA_NUM' => $idPraticien,
                'RAP_DATEVISITE' => $dateVisite,
                'RAP_MOTIF' => $motif,
                'RAP_BILAN' => $bilan,
                'RAP_ETAT' => $etat,
                'MEDICAMENT_PRESENTE' => $medicamentPresente,
                'MEDICAMENT_PRESCRIT' => $medicamentPrescrit,
            ];
            include("vues/v_saisirRapport.php");
            break;
        }

        if (!empty($idRapport)) {
            updateRapportVisite($idVisiteur, $idRapport, $idPraticien, $dateVisite, $motif, $bilan, $etat);
        } else {
            $idRapport = insertRapportVisite($idVisiteur, $idPraticien, $dateVisite, $motif, $bilan, $etat);
        }

        if ($etat === 'validé') {
            $message = "Rapport validé avec succès.";
            include("vues/v_message.php");
        } else {
            $info = "Rapport enregistré en saisie en cours.";
            $rapport = getRapportById($idVisiteur, $idRapport);
            $lesPraticiens = getPraticiens();
            $lesMedicaments = getAllNomMedicament();
            $rapport['MEDICAMENT_PRESENTE'] = $medicamentPresente;
            $rapport['MEDICAMENT_PRESCRIT'] = $medicamentPrescrit;
            include("vues/v_saisirRapport.php");
        }
        break;

    default:
        header('Location: index.php?uc=rapportVisite&action=liste');
        break;
}
?>
