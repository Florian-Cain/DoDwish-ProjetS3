<?php declare(strict_types=1);
require_once('autoload.php');

$authentication = new UserAuthentication();
$title = 'Do-Dwish | Historique';
$p = new WebPage($title);

if (!$authentication->isUserConnected()) {
    header('Location: secure_form.php');
    exit();
}else{

    function getPrixFromCommande($commande){
        $prix = 0;

        $contenu = json_decode($commande['contenu']);

        for($i = 0; $i<count($contenu); $i++){
            $article = json_decode(json_encode($contenu[$i]), true);
            $prix+=$article['prix'];
        }

        return $prix;
    }

    function getArticleFromCommande($numCommande){

        $commande = MyPDO::getInstance()->prepare(<<<SQL
            SELECT contenu
            FROM commande
            WHERE numCom = $numCommande
        SQL
        );
        $commande->execute();
        $contenu = json_decode($commande->fetch()['contenu']);
 
        $listArticles = array();

        $article = json_decode(json_encode($contenu[0]), true);
        if($article['nom'] == "Sandwich composé"){

            $article['nom'] = $article['nom']." : ".$article['description'];
        }
        array_push($listArticles, array('nom' => $article['nom'],'qte' => 1));

        for($i = 1; $i <count($contenu); $i++){
            $article = json_decode(json_encode($contenu[$i]), true);

            

            if($article['nom'] == "Sandwich composé"){

                $article['nom'] = $article['nom']." : ".$article['description'];
            }

            $test = false;
            $j=0;
            while($test == false && $j < count($listArticles)){
                
                if($listArticles[$j]['nom'] == $article['nom']){
                    $listArticles[$j]['qte'] ++;
                    $test = true;
                }
                $j++;
            }
            if($test == false){
                array_push($listArticles, array('nom' => $article['nom'],'qte' => 1));
            }
        }

        $content = "";


        foreach($listArticles as $article){
            $content.="<div class='row'>
                <div class='col-sm-8 col-md-6 col-lg-8 offset-sm-2 offset-0'>
                    <p class='text-center'>".$article['nom']."</p>
                </div>
                <div class='col-sm-2 col-md-4 col-lg-2'>
                    <p class='text-center'>x ".$article['qte']."</p>
                </div>
            </div>";
        }

        return $content;

    }


    $p->appendToHead(<<<HTML
	<meta charset="UTF-8"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="img/logo-letter.png"/>
HTML
    );

    $p->appendNavBar();

    $userId = $_SESSION['__UserAuthentication__']['user']->getId();

    $commandes = MyPDO::getInstance()->prepare(<<<SQL
	SELECT ville, cp, adr, heure, date, idUser, numCom, valide,contenu
	FROM commande
    WHERE idUser = $userId
SQL
);
	
	$commandes->execute();
    $listCommandes = array();

	while (($commande = $commandes->fetch()) != false){

        if($commande['valide'] == 0){
            $valide = "bg-success";
        }
        else{
            $valide = "bg-danger";
        }

        $prix = getPrixFromCommande($commande);
        $contenu = getArticleFromCommande($commande['numCom']);
        $p->appendContent(<<<HTML
		<div class="container-fluid mb-2">
            <div class="row {$valide} py-4 text-white">
                <div class="col-12 mb-2">
                    <h3 class="text-center">Commande du {$commande['date']} {$commande['heure']}</h3>
                </div>
                <div class="col-3">
                    <h5 class="text-center">Contenu</h5>
                    $contenu
                </div>
                <div class="col-4 offset-1">
                    <h5 class="text-center">Lieu de livraison</h5>
                    <p class="text-center">{$commande['ville']} - {$commande['cp']}</p>
                    <p class="text-center">{$commande['adr']}</p>
                </div>
                <div class="col-4">
                    <h5 class="text-center">Prix</h5>
                    <p class="text-center">{$prix} €</p>
                </div>
            </div>
        </div>
HTML
    );
    }



    
    
    $p->appendFooter();
}

echo $p->toHTML();