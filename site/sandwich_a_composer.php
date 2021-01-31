<?php declare(strict_types=1);
require_once('autoload.php');

$authentication = new UserAuthentication();
$title = 'Do-Dwish | Sandwich à composer';
$p = new WebPage($title);

$prix = 8.5; //Prix du sandwich composé

if (!$authentication->isUserConnected()) {
    header('Location: secure_form.php');
    exit();
}elseif(isset($_POST["ajouterAuPanier"])){
    //Ajout au panier
    if (isset($_POST["add"])){
        if (count($_POST["add"])>=1){
            $idUser = $_SESSION['__UserAuthentication__']['user']->getId();
            $description = "";
            for ($i=0; $i < count($_POST["add"]); $i++) { 
                $description .= $_POST["add"][$i] . "; ";
            }
            Panier::addElement($idUser, "Sandwich composé", $description, $prix);
        }
    }
    header('Location: sandwich_a_composer.php');
    exit();
}elseif(isset($_POST["PasserCommande"])){
    if (isset($_POST["add"])){
        //Ajout au panier
        if (count($_POST["add"])>=1){
            $idUser = $_SESSION['__UserAuthentication__']['user']->getId();
            $description = "";
            for ($i=0; $i < count($_POST["add"]); $i++) { 
                $description .= $_POST["add"][$i] . "; ";
            }
            Panier::addElement($idUser, "Sandwich composé", $description, $prix);
        }
    }
    header('Location: panier.php');
    exit();
}else{

    // On récupère tout les types d'ingrédients sauf le pain
    $reqListTypeIngr = MyPDO::getInstance()->prepare(<<<SQL
        SELECT nom
        FROM typeIngredient
        ORDER BY nom
SQL
	);
    $reqListTypeIngr->execute();
    $listTypeIngr = [];
    while ($TypeIngr = $reqListTypeIngr->fetch()){
        if ($TypeIngr['nom'] != 'pain'){
            $listTypeIngr[] = $TypeIngr['nom'];
        }
    }

    // Récupérer les styles de pain
    $reqListPain = MyPDO::getInstance()->prepare(<<<SQL
    SELECT nomIngr as "nom"
    FROM Ingredient
    WHERE typeIngr = (select idTypeIngr
                        from typeIngredient
                        where nom = "pain")
    ORDER BY nomIngr
SQL
    );
    $reqListPain->execute();
    $listPain = [];
    while ($pain = $reqListPain->fetch()){
        $listPain[] = $pain['nom'];
    }


    $p->appendToHead(<<<HTML
        <meta charset="UTF-8"/>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css">
        <link rel="icon" href="img/logo-letter.png" />
HTML
    );

    $p->appendNavbar();
	$p->appendContent(<<<HTML
        <div class="nav-line container-fluid"></div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-sm-6 align-self-center">
                    <h2 class="text-center">Composez votre Sandwish</h2>
                    <p class="text-center">Légumes, viandes, fromage...<br>Tout est possible et le prix reste le même (8.50€)</p>
                </div>
                <div class="col-12 col-sm-6 p-0">
                    <img class="img-fluid w-100 h-100 p-0" src="img/burger_01.jpg">
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <form id='theForm' method="POST">
                <div class="row">
HTML
    );

    //Section pain (faite en première car important de choisir le pain en premier)
    $p->appendContent(<<<HTML
        <div class="col-md-3 py-4 col-sm-6 background-light">
            <h4 class="text-center">Pain</h4>
            <hr/>
HTML
    );

    for ($i=0; $i<count($listPain); $i++){
        $nomIngr = $listPain[$i];
        $p->appendContent(<<<HTML
            <input type="radio" name="add[]" value="{$nomIngr}">
            <label>$nomIngr</label><br>
HTML
        );
    }

$p->appendContent(<<<HTML
    </div>
HTML
);

//FIN partie pain

$reqListIngr = MyPDO::getInstance()->prepare(<<<SQL
SELECT nomIngr as "nom"
FROM Ingredient
WHERE typeIngr = (select idTypeIngr
                    from typeIngredient
                    where nom = :type)
ORDER BY nomIngr
SQL
);
//Ajout des autres catégories ainsi que leurs ingrédients
    $compteurType = 1;

    for ($i=0; $i<count($listTypeIngr); $i++){
        $nomTypeIngr = $listTypeIngr[$i];
        $nomTypeIngrToDisplay = ucwords($nomTypeIngr);

        $reqListIngr->execute(array(":type" => $nomTypeIngr));
        $listIngr = [];
        while ($ingr = $reqListIngr->fetch()){
            $listIngr[] = $ingr['nom'];
        }

        if ($compteurType % 2 == 1){
            $p->appendContent(<<<HTML
            <div class="col-md-3 py-4 col-sm-6">
                <h4 class="text-center">$nomTypeIngr</h4>
                <hr/>          
HTML
            );
        }else{
            $p->appendContent(<<<HTML
                <div class="col-md-3 py-4 col-sm-6 background-light">
                    <h4 class="text-center">$nomTypeIngrToDisplay</h4>
                    <hr/>          
HTML
            );
        }

    for ($j=0; $j<count($listIngr); $j++){
        $nomIngr = $listIngr[$j];
        $p->appendContent(<<<HTML
            <input type="checkbox" name="add[]" value="{$nomIngr}">
            <label>$nomIngr</label><br>
HTML
        );
    }

$p->appendContent(<<<HTML
    </div>
HTML
);
        $compteurType += 1;
    }
// Fin Ajout des autres catégories ainsi que leurs ingrédients


    $p->appendContent(<<<HTML
                </div>
            </form>
        </div>
        <div class="container my-5">
            <div class="row justify-content-center">
                <div class="d-flex mr-4 justify-content-center">
                    <a href="panier.html">
                        <button class="px-4 py-2 btn-red" type="submit" form="theForm" name="ajouterAuPanier">
                            Ajouter au panier
                        </button>
                    </a>
                </div>
                <div class="d-flex ml-4 justify-content-center">
                    <a href="commande.html">
                        <button class="px-4 py-2 btn-red" type="submit" form="theForm" name="PasserCommande">
                            Passer Commande
                        </button>
                    </a>
                </div>
            </div>
        </div>
HTML
    );

    $p->appendFooter();

}

echo $p->toHTML();