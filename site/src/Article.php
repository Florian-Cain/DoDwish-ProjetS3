<?php declare(strict_types=1);
require_once('autoload.php');

/**
 * Classe Article
 */
class Article
{
    /**
     * Affichage d'un article
     * 
     * @param String  $type  Type de l'article
     * @param String  $titre Titre 
     * @param WebPage $p     Page Web
     */
    static public function afficher(string $type, string $titre, WebPage $p){

        // Récupérer tout les articles du type désiré
        $req = MyPDO::getInstance()->prepare(<<<SQL
        SELECT idArticle, nom, prix, description, img
        FROM article
        WHERE type = :type
        ORDER BY nom
SQL
        );
        $req->execute([":type" => $type]);

        $p->appendContent(<<<HTML
    <script type="text/javascript" src="js/ajouterAuPanier.js"></script>
    <script type="text/javascript" src="js/ajaxRequest.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    
    <div class="container-fluid pb-5">
        <div class="container">
            <h2 class="mt-5">{$titre}</h2>
            <div class="row px-0 px-sm-3 mt-4">
        
HTML
    );

    //On ajoute chaque article à la page
    while($article = $req->fetch()){
        $img = $article["img"];
        $name = ucfirst($article["nom"]);
        $description = $article["description"];
        $idArticle = $article["idArticle"];
        $idUser = $_SESSION['__UserAuthentication__']['user']->getId();
        $prixFormat = number_format(floatval($article['prix']), 2, ',', ' ');
        $p->appendContent(<<<HTML
            <div class="col-12 col-md-6 mt-md-3">
                <a class="box" onClick="ajouterAuPanier({$idArticle}, {$idUser})">
                    <div class="row carte-elmt mr-md-1">
                        <div class="col-8 p-0">
                            <img class="img-carte w-100 h-100" src={$img}>
                        </div>
                        <div class="col-4 text-center">
                            <h6 class="mt-2">{$name}</h6>
                            <hr/>
                            <p class="carte-desc">{$description}<br></p>
                            <hr>
                            <p>{$prixFormat}€</p>
                        </div>
                    </div>
                </a>
            </div>
HTML
    );
    }

    $p->appendContent(<<<HTML
                </div>
            </div>
        </div> 
HTML
    );
    }
}
