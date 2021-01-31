<?php declare(strict_types=1);
require_once('autoload.php');

//Récupération de l'ID de l'article à ajouter au panier et de celui de l'utilisateur
if(isset($_GET["idArticle"]) && isset($_GET["idUser"])){
    $idUser    = intval($_GET["idUser"]);
    $idArticle = intval($_GET["idArticle"]);
   
    //Requête SQL : Récupération de l'article à ajouter
    $req = MyPDO::getInstance()->prepare(<<<SQL
    SELECT nom, prix, description
    FROM article
    WHERE idArticle = :idArticle
SQL
    );
    $req->execute([":idArticle"=>$idArticle]);

    $article     = $req->fetch();
    $nom         = $article["nom"];
    $description = $article["description"];
    $prix        = floatval($article["prix"]);

    //Ajout au panier
    Panier::addElement($idUser, $nom, $description, $prix);
}