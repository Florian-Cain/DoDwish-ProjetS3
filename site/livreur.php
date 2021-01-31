<?php declare(strict_types=1);
require_once('autoload.php');

$authentication = new UserAuthentication();
$title = 'Do-Dwish | Page Livreur';
$p = new WebPage($title);

if (!$authentication->isUserConnected() || $authentication->getUser()->getId() !=2) {
    header('Location: secure_form.php');
    exit();
}else{


    
    $p->appendToHead(<<<HTML
        <meta charset="UTF-8"/>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
        <link rel="stylesheet" href="./css/style.css">
        <link rel="icon" href="img/logo-letter.png" />
HTML
    );

	$p->appendNavbar();


    //Attribution d'une latitude et longitude random a defaut d'avoir acces a l'api de google
    function getLatLong(){

        $lat = mt_rand(4920,4930)/100;

        $lon = mt_rand(395,410)/100;
        return array("lat" => $lat,"lon" => $lon);
    }

    function distanceBetween($pos1,$pos2){
        // convert from degrees to radians
        $latFrom = deg2rad($pos1['lat']);
        $lonFrom = deg2rad($pos1['lon']);
        $latTo = deg2rad($pos2['lat']);
        $lonTo = deg2rad($pos2['lon']);
      
        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;
      
        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
          cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return round($angle * 6371,2);
    }

    function getTempsRestant($commande) {
        $tempsMax = new DateTime($commande['heure']);
        date_add($tempsMax,date_interval_create_from_date_string('30 mins'));

        $now = new DateTime('now');

        return ($now->diff($tempsMax))->format('%H:%I:%S');
      }

    function sortList($list,$positionInit){
        $listCopy = $list;
        $minDist = distanceBetween($positionInit, $list[0]['coord']);
        $plusProcheLieu = 0;
        for($i = 1; $i<count($list); $i++){
            if(distanceBetween($positionInit, $list[$i]['coord'])<$minDist){
                $minDist = distanceBetween($positionInit, $list[$i]['coord']);
                $plusProcheLieu = $i;
            }
        }
        $sortedList = array($list[$plusProcheLieu]);
        array_splice($listCopy,$plusProcheLieu,1);

        while(count($listCopy)!=0){
            $minDist = distanceBetween($sortedList[count($sortedList)-1]['coord'],$listCopy[0]['coord']);
            $bestLieu = 0;
            for($j = 1; $j<count($listCopy); $j++){
                if(distanceBetween($sortedList[count($sortedList)-1]['coord'], $listCopy[$j]['coord'])<$minDist){
                    $minDist = distanceBetween($sortedList[count($sortedList)-1]['coord'], $listCopy[$j]['coord']);
                    $bestLieu = $j;
                }
            }
            array_push($sortedList,$listCopy[$bestLieu]);
            array_splice($listCopy,$bestLieu,1);
        }

        return $sortedList;
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

    function getuserFromCommande($idUser){
        $client = MyPDO::getInstance()->prepare(<<<SQL
        SELECT nom, prenom, tel
        FROM user
        WHERE idUser = $idUser
    SQL
    );
        $client->execute();
        return $client->fetch();
    }

    $active_delivery = MyPDO::getInstance()->prepare(<<<SQL
	SELECT ville, cp, adr, heure, date, idUser, numCom
	FROM commande
    WHERE valide = 0
SQL
);
	
	$active_delivery->execute();
    $list_commandes = array();

	while (($delivery = $active_delivery->fetch()) != false){
        array_push($list_commandes, $delivery);
    }

    if(count($list_commandes) != 0){

        //Attribution d'une latitude et longitude random pour toutes les commandes et le livreur
        for($i = 0; $i < count($list_commandes); $i++){
            $list_commandes[$i]['coord'] = getLatLong();
        }
        $positionLivreur = getLatLong();

        //tri de la liste en fonction de la position du livreur
        $list_commandes = sortList($list_commandes, $positionLivreur);    
        $distanceFirst = distanceBetween($positionLivreur,$list_commandes[0]['coord']);


        //client correspondant a la premiere commande
        $idUser = $list_commandes[0]['idUser'];
        $client = getuserFromCommande($idUser);
    

        //Contenu de sa commande
        $numCommande = $list_commandes[0]['numCom'];
        $content = getArticleFromCommande($numCommande);


        //temps restant sur la commande
        $tempsRestant = getTempsRestant($list_commandes[0]);


        //Affichage livraison en cours
        $p->appendContent(<<<HTML
        <div class="container-fluid py-4 bg-danger">
            <div class="row mb-3">
                <div class="col-12">
                    <h3 class="text-center text-white">Livraison en cours </h3>
                </div>
            </div>
            <div class="row  text-light">
                <div class="col-md-4 col-lg-2 col-sm-12 my-sm-4 my-md-0">
                    <h5 class="text-center">Lieu</h5>
                    <p class="text-center">{$list_commandes[0]['ville']} - {$list_commandes[0]['cp']}</p>
                    <p class="text-center">{$list_commandes[0]['adr']}</p>
                </div>
                <div class="col-md-4 col-lg-2 col-sm-12 my-sm-4 my-md-0">
                    <h5 class="text-center">Contenu</h5>
                    {$content}
                </div>
                <div class="col-md-4 col-lg-2 col-sm-12 my-sm-4 my-md-0">
                    <h5 class="text-center">Client</h5>
                    <p class="text-center">{$client['nom']} {$client['prenom']}</p>
                    <p class="text-center">{$client['tel']}</p>
                </div>
                <div class="col-md-4 col-lg-2 col-sm-12 my-sm-4 my-md-0">
                    <h5 class="text-center">Distance </h5>
                    <p class="text-center">{$distanceFirst} km</p>
                </div>
                <div class="col">
                    <div class="row">
                        <div class="col-12">
                            <h5 class="text-center text-white">Temps restant : {$tempsRestant}</h5>
                        </div>
                        <div class="col-12 text-center my-2">
                            <button class="ml-3 pr-3 btn-red " type="button" onClick="finirCommande({$numCommande})">
                                <img class="mx-2 mb-1" height="20px" src="icon/basket.svg">
                                Commande Livrée
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
HTML);

        //Si plus d'une commande -> affichage des commandes suivantes
        if(count($list_commandes)>1){

            $p->appendContent(<<<HTML
                <h5 class="text-center mb-3 mt-5">Livraisons suivantes </h5>
            HTML);

            for($i = 1; $i<count($list_commandes);$i++){

                $distanceDuLivreur = distanceBetween($positionLivreur,$list_commandes[$i]['coord']);
                $distanceCommandePre = distanceBetween($list_commandes[$i-1]['coord'],$list_commandes[$i]['coord']);

                
                //client correspondant a la commande
                $idUser = $list_commandes[$i]['idUser'];
                $client = getuserFromCommande($idUser);
            

                //Contenu de sa commande
                $numCommande = $list_commandes[$i]['numCom'];
                $content = getArticleFromCommande($numCommande);


                $p->appendContent(<<<HTML
               <div class="container-fluid py-4  my-2 bg-info">
                    
                    <div class="row  text-light">
                        <div class="col-md-3 col-sm-12 my-sm-4 my-md-0">
                            <h5 class="text-center">Lieu</h5>
                            <p class="text-center">{$list_commandes[$i]['ville']} - {$list_commandes[$i]['cp']}</p>
                            <p class="text-center">{$list_commandes[$i]['adr']}</p>
                        </div>
                        <div class="col-md-3 col-sm-12 my-sm-4 my-md-0">
                            <h5 class="text-center">Contenu</h5>
                            {$content}
                        </div>
                        <div class="col-md-3 col-sm-12 my-sm-4 my-md-0">
                            <h5 class="text-center">Client</h5>
                            <p class="text-center">{$client['nom']} {$client['prenom']}</p>
                            <p class="text-center">{$client['tel']}</p>
                        </div>
                        <div class="col-md-3 col-sm-12 my-sm-4 my-md-0">
                            <h5 class="text-center">Distance </h5>
                            <p class="text-center">{$distanceDuLivreur} km du livreur</p>
                            <p class="text-center">{$distanceCommandePre} km de la commande precedente</p>
                        </div>
                    </div>
                </div>
        HTML);   
            }
        }

        

    }
    //Si aucune commande -> affichage d'un message le precisant
    else{
        $p->appendContent(<<<HTML
        <div class="container mt-5">
            <div class="row">
                <h3>Pas de Commande pour le moment </h3>
            </div>
        </div>
HTML);
    }


    $p->appendFooter();
   
    $p->appendContent(<<<HTML
    <script type="text/javascript" src="js/finirCommande.js"></script>
    <script type="text/javascript" src="js/ajaxRequest.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    HTML);
}

echo $p->toHTML();