<?php declare(strict_types=1);
require_once('autoload.php');


//Récupération de l'ID de l'article à supprimer
if(isset($_GET["idArticle"])){
    $idArticle = intval($_GET["idArticle"]);
    //Suppression de l'article (dans le panier)
    Panier::delElement($idArticle);
}