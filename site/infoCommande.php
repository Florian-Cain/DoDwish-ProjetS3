<?php declare(strict_types=1);
require_once('autoload.php');

$authentication = new UserAuthentication();
$title = 'Do-Dwish | Info Commande';
$p = new WebPage($title);


if (!$authentication->isUserConnected()) {
    header('Location: secure_form.php');
    exit();
}
else{

        $p->appendToHead(<<<HTML
            <meta charset="UTF-8"/>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
            <link rel="stylesheet" href="css/style.css">
            <link rel="icon" href="img/logo-letter.png" />
    HTML
        );
    
        $p->appendNavbar();
    
        //Récupération d'informations sur l'utilisateur et la commande
        $user      = $_SESSION['__UserAuthentication__']['user'];
        $tel       = $user->getTel();
        $prixTotal = Panier::getPrixTotal($user->getId());
        $prixFormat = number_format(floatval($prixTotal), 2, ',', ' ');
    
        //Formulaire d'entrée des données de paiement de la commande
        $p->appendContent(<<<HTML
        <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h2 class="text-center py-5">Merci de renseigner les champs suivants</h2>
                        <h5 class="text-center py-2">Cette page est une page de démonstration, les informations banquaires ne seront pas traitées ou conservées ;D</h5>
                        <div class="col-xs-12 col-sm-10 offset-sm-1 pt-5 col-lg-6 offset-lg-3">
    
    
            <form id="commande" name="commande" method="POST" action="commande.php">
                <label>Ville</label>
                <input class="form-control input-shadow mb-4" name="ville" type="text" placeholder="Exemple: Reims" required>
    
                <label>Code postal</label>
                <input class="form-control input-shadow mb-4" name="cp" type="text" placeholder="Exemple: 51100" required>
    
                <label>Adresse de livraison</label>
                <input class="form-control input-shadow mb-4" name="adr" type="text" placeholder="Exemple: 1 rue de l'Exemple" required>
                
                <label>Numéro de téléphone</label>
                <input class="form-control input-shadow mb-4" name="tel" type="tel" value="{$tel}" required>
    
                <label>Heure de livraison souhaitée</label>
                <input class="form-control input-shadow mb-4" name='heure' type="time" value="13:45" required>
                
                <label>Numéro de carte</label>
                <input class="form-control input-shadow mb-4" name="numCarte" type="text" placeholder="Le numéro de votre carte" required>
               
                <label> Date d'expiration</label>
                <input class="form-control input-shadow mb-4" name="dateExpiration" type="date" required>
              
                <label> Cryptogramme visuel </label>
                <input class="form-control input-shadow mb-4" name="crypto" type="text" placeholder="Ce numéro figure généralement à l'arrière de votre carte" required>

                </div>
                    <p class="text-center"><a href="panier.php">Retour au panier</a></p>
                    <div class="d-flex justify-content-center">
                        <button class="py-2 mb-4 px-5 btn-red" type="submit" form="commande">
                            Valider le paiement de {$prixFormat}€
                        </button>
                    </div>
                </div>
            </form>
    
           
        </div>


        <script type="text/javascript" src="js/ajouterCommande.js"></script>
        <script type="text/javascript" src="js/ajaxRequest.js"></script>
    HTML
        );
        $p->appendFooter();
    }
    
    echo $p->toHTML();