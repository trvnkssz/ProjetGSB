<?php
require_once("bd.inc.php");

function getPraticiens()
{
    $connexion = connexionPDO();
    $req = $connexion->prepare("SELECT PRA_NUM, PRA_NOM, PRA_PRENOM FROM praticien ORDER BY PRA_NOM ASC");
    $req->execute();
    return $req->fetchAll(PDO::FETCH_ASSOC);
}
?>
