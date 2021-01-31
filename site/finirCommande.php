<?php declare(strict_types=1);
require_once('autoload.php');


//Récupération du numero de commande à validé
if(isset($_GET["numCommande"])){
    $numCommande = intval($_GET["numCommande"]);
    
    $commande = MyPDO::getInstance()->prepare(<<<SQL
        UPDATE commande
        SET valide=1
        WHERE numCom = $numCommande
SQL
);
    $commande->execute();
}