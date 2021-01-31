<?php declare(strict_types=1);
require_once('autoload.php');

$authentication = new UserAuthentication();
$title = 'Do-Dwish | Votre panier';
$p = new WebPage($title);

if (!$authentication->isUserConnected()) {
    header('Location: secure_form.php');
    exit();

}else{
    $p->appendToHead(<<<HTML
    <meta charset="UTF-8"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="img/logo-letter.png"/>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script type="text/javascript" src="js/supprimerDuPanier.js"></script>
    <script type="text/javascript" src="js/ajaxRequest.js"></script>
HTML
    );

    $p->appendNavbar();
    
    //Ajout des éléments et du prix du panier
    $tabPanier = Panier::getElement($_SESSION['__UserAuthentication__']['user']->getId());
    $prixTotal = Panier::getPrixTotal($_SESSION['__UserAuthentication__']['user']->getId());

    $getImage = MyPDO::getInstance()->prepare(<<<SQL
        SELECT img
        FROM article
        WHERE nom = :nom
SQL
    	);

    
    $p->appendContent(<<<HTML
    <div class="container-fluid pb-5">
        <div class="container">
            <h2 class="mt-5">Votre Panier</h2>
            <div class="row px-0 px-sm-3 mt-4">
HTML
    );

    //On ajoute chaque article à la page
    for ($i=0; $i < count($tabPanier); $i++) { 

        $name = ucfirst($tabPanier[$i]['nom']);
        $description = $tabPanier[$i]['description'];
        $prix = number_format(floatval($tabPanier[$i]['prix']), 2, ',', ' ');
        

        //Choix de l'image selon le contexte
        if ($name == "Sandwich composé") {
            $img = "img/sandwich_01.jpg";
        }else {
            $getImage->execute(array(":nom" => $name));
            $img = $getImage->fetch()['img'];
        }
        
        $idArticlePanier = $tabPanier[$i]['id'];
        $p->appendContent(<<<HTML
            <div class="col-12 col-md-6 mt-md-3">
                <a class="box" onClick="supprimerDuPanier({$idArticlePanier})">
                    <div class="row carte-elmt mr-md-1">
                        <div class="col-8 p-0">
                            <img class="img-carte w-100 h-100" src={$img}>
                        </div>
                        <div class="col-4 text-center">
                            <h6 class="mt-2">{$name}</h6>
                            <hr/>
                            <p class="carte-desc">{$description}<br></p>
                            <hr>
                            <p>{$prix}€</p>
                        </div>
                            
                    </div>
                </a>
            </div>
HTML
        );
    }

    $prixTotalFormate = number_format(floatval($prixTotal), 2, ',', ' ');
    $p->appendContent(<<<HTML
                </div>
                <hr>
                <h2>
                    Prix total: {$prixTotalFormate} €
                    <a class="btn btn-outline-success" href="infoCommande.php">Valider ma commande</a>
                </h2>
            </div>
        </div> 
HTML
    );
    $p->appendFooter();
}

echo $p->toHTML();