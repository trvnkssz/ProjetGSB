<?php
require_once("bd.inc.php");

function getRapportsVisiteurTous($idVisiteur)
{
    $connexion = connexionPDO();
    $sql = "
        SELECT rv.RAP_NUM, rv.RAP_DATEVISITE, rv.RAP_MOTIF, rv.RAP_BILAN, rv.RAP_ETAT,
               p.PRA_NUM, p.PRA_NOM, p.PRA_PRENOM
        FROM rapport_visite rv
        INNER JOIN praticien p ON rv.PRA_NUM = p.PRA_NUM
        WHERE rv.VIS_MATRICULE = :vis
        ORDER BY rv.RAP_DATEVISITE DESC
    ";
    $req = $connexion->prepare($sql);
    $req->bindValue(':vis', $idVisiteur);
    $req->execute();
    return $req->fetchAll(PDO::FETCH_ASSOC);
}

function getRapportById($idVisiteur, $numRapport)
{
    $connexion = connexionPDO();
    $sql = "
        SELECT rv.RAP_NUM, rv.RAP_DATEVISITE, rv.RAP_MOTIF, rv.RAP_BILAN, rv.RAP_ETAT,
               p.PRA_NUM, p.PRA_NOM, p.PRA_PRENOM
        FROM rapport_visite rv
        INNER JOIN praticien p ON rv.PRA_NUM = p.PRA_NUM
        WHERE rv.VIS_MATRICULE = :vis AND rv.RAP_NUM = :num
    ";
    $req = $connexion->prepare($sql);
    $req->bindValue(':vis', $idVisiteur);
    $req->bindValue(':num', $numRapport);
    $req->execute();
    return $req->fetch(PDO::FETCH_ASSOC);
}

function insertRapportVisite($idVisiteur, $idPraticien, $dateVisite, $motif, $bilan, $etat)
{
    $connexion = connexionPDO();
    $reqNum = $connexion->prepare("SELECT COALESCE(MAX(RAP_NUM), 0) + 1 AS nextNum FROM rapport_visite WHERE VIS_MATRICULE = :vis");
    $reqNum->bindValue(':vis', $idVisiteur);
    $reqNum->execute();
    $nextNum = $reqNum->fetch(PDO::FETCH_ASSOC)['nextNum'];

    $req = $connexion->prepare("
        INSERT INTO rapport_visite (VIS_MATRICULE, RAP_NUM, PRA_NUM, RAP_DATEVISITE, RAP_MOTIF, RAP_BILAN, RAP_ETAT)
        VALUES (:vis, :num, :pra, :datev, :motif, :bilan, :etat)
    ");
    $req->bindValue(':vis', $idVisiteur);
    $req->bindValue(':num', $nextNum);
    $req->bindValue(':pra', $idPraticien);
    $req->bindValue(':datev', $dateVisite);
    $req->bindValue(':motif', $motif);
    $req->bindValue(':bilan', $bilan);
    $req->bindValue(':etat', $etat);
    $req->execute();

    return $nextNum;
}

function updateRapportVisite($idVisiteur, $numRapport, $idPraticien, $dateVisite, $motif, $bilan, $etat)
{
    $connexion = connexionPDO();
    $req = $connexion->prepare("
        UPDATE rapport_visite
        SET PRA_NUM = :pra, RAP_DATEVISITE = :datev, RAP_MOTIF = :motif, RAP_BILAN = :bilan, RAP_ETAT = :etat
        WHERE VIS_MATRICULE = :vis AND RAP_NUM = :num
    ");
    $req->bindValue(':pra', $idPraticien);
    $req->bindValue(':datev', $dateVisite);
    $req->bindValue(':motif', $motif);
    $req->bindValue(':bilan', $bilan);
    $req->bindValue(':etat', $etat);
    $req->bindValue(':vis', $idVisiteur);
    $req->bindValue(':num', $numRapport);
    $req->execute();
}
?>
