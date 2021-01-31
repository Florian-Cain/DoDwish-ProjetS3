<?php declare(strict_types=1);
require_once('autoload.php');

$commandeInsert = MyPDO::getInstance()->prepare(<<<SQL
            INSERT INTO commande (idUser, ville, cp, adr, date, heure, contenu, valide)
            VALUES (:idUser, :ville, :cp, :adr, :date, :heure, :contenu, :valide);
SQL);

$authentication = new UserAuthentication();
//On vérifie que lae panier ne soit pas vide
if(Panier::getPrixTotal($_SESSION['__UserAuthentication__']['user']->getId()) > 0){

    $commandeInsert->execute(array(
        ":idUser"  => $_SESSION['__UserAuthentication__']['user']->getId(),
        ":ville"   => $_POST["ville"],
        ":cp"      => $_POST["cp"],
        ":adr"     => $_POST["adr"],
        ":date"    => date('d/m/y'),
        ":heure"   => $_POST["heure"],
        ":contenu"   => json_encode(Panier::getElement($_SESSION['__UserAuthentication__']['user']->getId())),
        ":valide" => 0,
    ));

    
    

    //Vider le panier
    Panier::viderPanier($_SESSION['__UserAuthentication__']['user']->getId());
    //Message de validation de la commande
    header('Location: merci.html');
    exit();
}else{
    //Redirection vers un message d'erreur
    header('Location: panierVide.html');
    exit();
}
    
