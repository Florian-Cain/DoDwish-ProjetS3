<?php declare(strict_types=1);
require_once('autoload.php');

$authentication = new UserAuthentication();
$title = 'Do-Dwish | Page administrateur';
$p = new WebPage($title);

if (!$authentication->isUserConnected() || $authentication->getUser()->getId() != 1) {
    header('Location: secure_form.php');
    exit();
}else{

	//Ajout des informations du formulaire dans la base de données
	if (isset($_POST['Nom']) && isset($_POST['Prix']) && isset($_POST['Description']) && isset($_POST['Type']) && isset($_POST['imgArt'])){
		$nom = htmlspecialchars($_POST['Nom']);
		$nom = str_replace(["\r", "\n", PHP_EOL], "", $nom);
		$prix = htmlspecialchars($_POST['Prix']);;
		$prix = str_replace(["\r", "\n", PHP_EOL], "", $prix);
		$desc = htmlspecialchars($_POST['Description']);;
		$desc = str_replace(["\r", "\n", PHP_EOL], "", $desc);
		$type = htmlspecialchars($_POST['Type']);;
		$type = str_replace(["\r", "\n", PHP_EOL], "", $type);

		$img = "img/" . $_POST['imgArt'];

		$add = MyPDO::getInstance()->prepare(<<<SQL
		INSERT INTO Article (nom, prix, description, type, img)
		VALUES (:nom, :prix, :desc, :type, :img)
SQL	
);
		$add->execute([
			":nom" => $nom,
			":prix" => $prix,
			":desc" => $desc,
			":type" => $type,
			":img" => $img,
		]);
	}

	//Suppression des articles choisis
	if(isset($_POST['aSupr'])){
		for ($j = 0; $j < count($_POST['aSupr']); $j++){

			$del=MyPDO::getInstance()->prepare(<<<SQL
			DELETE FROM article
			WHERE idArticle = :delArt
SQL
);
			$del->execute([":delArt" => $_POST['aSupr'][$j]]);
		}
	}

	//Ajout catégorie
	if(isset($_POST['newCtgr'])){
		if($_POST['newCtgr'] != 'pain'){
			$reqAjCatg=MyPDO::getInstance()->prepare(<<<SQL
			INSERT INTO `typeingredient` (`nom`) VALUES (:nom);
SQL
		);
			$reqAjCatg->execute([":nom" => $_POST['newCtgr']]);
		}
	}

	//Supprimer catégorie
	if(isset($_POST['delCatg'])){
		if($_POST['delCatg'] != 'pain'){
			$reqSupprCatg=MyPDO::getInstance()->prepare(<<<SQL
			DELETE FROM `typeingredient` 
			WHERE `typeingredient`.`nom` = :nom;
SQL
		);
			$reqSupprCatg->execute([":nom" => $_POST['delCatg']]);
		}
	}

	//Ajout ingrédient
	if(isset($_POST['newIngrNom']) && isset($_POST['newIngrCatg'])){
		$reqAjCatg=MyPDO::getInstance()->prepare(<<<SQL
		INSERT INTO `ingredient` (`nomIngr`, `typeIngr`) VALUES (:nom, :type);
SQL
	);
		$reqAjCatg->execute([":nom" => $_POST['newIngrNom'], ":type" => $_POST['newIngrCatg']]);
	}

	//Suppression des ingrédients choisis
	if(isset($_POST['aSuprIngr'])){
		for ($j = 0; $j < count($_POST['aSuprIngr']); $j++){

			$del=MyPDO::getInstance()->prepare(<<<SQL
			DELETE FROM ingredient
			WHERE idIngr = :delIngr
SQL
);
			$del->execute([":delIngr" => $_POST['aSuprIngr'][$j]]);
		}
	}

	if(isset($_POST['submitImg'])){
		$extentionList = ['.jpg', '.jpeg', '.png'];
		$fileName = $_FILES['imageUpload']['name'];
		$fileExt = "." . strtolower(substr(strrchr($fileName, "."), 1));

		if(in_array($fileExt, $extentionList)){
			$tempName = $_FILES['imageUpload']['tmp_name'];
			$finalFileName = "img/" . $fileName;
			move_uploaded_file($tempName, $finalFileName);
		}
	}

	// --------------- Fin des traitements, début de l'affichage ------------ \\

	$p->appendNavbar();

	//Formulaire permettant d'ajouter une catégorie pour les Sandwish à composer
	$p->appendContent(<<<HTML
<div class="col-12" name="add">
	<h2 class = "container-fluid col-12">Ajouter une catégorie de Sandwish à composer</h2>
	
	<form name="newCtgr" method="POST">
	<div class="commande">
		<div>
			<label >
				Nom
				
				<input class="form-control input-shadow mb-4" name="newCtgr" type="text" placeholder="Nom de la catégorie" required>
			</label>
		</div>
	</div>
		<button class="px-4 py-2 btn-red" type="submit">Ajouter</button>
	</form>
</div>
<hr>
HTML);

	

	//Récupération de toutes les catégories (utile aussi pour les ingrédients donc on stock dans une liste)
	$reqCatg=MyPDO::getInstance()->prepare(<<<SQL
		SELECT nom, idTypeIngr as id
		FROM typeingredient 
		WHERE nom != 'pain'
		ORDER BY nom;
SQL
	);
	$reqCatg->execute();

	//Formulaire permettant de supprimer une catégorie pour les Sandwish à composer
	$p->appendContent(<<<HTML
	<div class="col-12" name="add">
	<h2 class = "container-fluid col-12">Supprimer une catégorie de Sandwish à composer</h2>
	
	<form name="delCtgr" method="POST">
	<div class="commande">
		<div>
			<label >
				Nom
				<select class="form-control" name="delCatg">
HTML);

	$listCatg = [[1, 'pain']];
	while ($catg = $reqCatg->fetch()) {
		$nomCatg = $catg['nom'];
		$idCatg = $catg['id'];
		$listCatg[] = [$idCatg, $nomCatg];
		$p->appendContent(<<<HTML
			<option value="{$nomCatg}" require>{$nomCatg}</option> 
HTML);
	}

$p->appendContent(<<<HTML
				</select>
			</label>
		</div>
	</div>
		<button class="px-4 py-2 btn-red" type="submit">Supprimer</button>
	</form>
</div>
<hr>
HTML);

	//Formulaire permettant d'ajouter un ingrédiant à une catégorie
	$p->appendContent(<<<HTML
	<div class="col-12" name="add">
		<h2 class = "container-fluid col-12">Ajouter un ingrédiant de Sandwish à composer</h2>
		
		<form name="newIngr" method="POST">
		<div class="commande">
			<div>
				<label>
					Nom
					<input class="form-control input-shadow mb-4" name="newIngrNom" type="text" placeholder="Nom de la catégorie" required>
				</label>
				<label>
					Catégorie
					<select class="form-control" name="newIngrCatg">
HTML
	);

	for ($i=0; $i < count($listCatg); $i++) {
		$nomCatg = $listCatg[$i][1];
		$idCatg = $listCatg[$i][0];
		$p->appendContent(<<<HTML
		<option class="form-control" value="{$idCatg}" require>{$nomCatg}</option> 
HTML
		);
	}


	$p->appendContent(<<<HTML
				</select>
				</label>
			</div>
		</div>
			<button class="px-4 py-2 btn-red" type="submit">Ajouter</button>
		</form>
	</div>
	<hr>
HTML
	);

	//Formulaire de supression d'ingredient

		//Récupération des ingrédients présents dans la base de données
		$ingr = MyPDO::getInstance()->prepare(<<<SQL
		SELECT idIngr, nomIngr, nom as "type"
		FROM ingredient I, typeingredient T
		WHERE T.idTypeIngr = I.typeIngr
		ORDER BY type, nomIngr
	SQL
	);
		
		$ingr->execute();
	
		//Liste des articles (choix d'un article à supprimer)
		$p->appendContent(<<<HTML
	<br>
	<div name="del">
		<h2 class = "container-fluid">Liste des ingrédients proposés</h2>
		<form name="deleteIngr" method="POST">
		<table class="table">
					<tr>
						<th width="33%">Type</th>
						<th width="33%">Nom</th>
						<th width="33%">Suppression</th>
					</tr>
		</table>
	HTML);
	
		$i = 0;
		while ($com = $ingr->fetch()){
			$p->appendContent(<<<HTML
			
			<table scope ="row " class="table ">
					<tr>
						<td width="33%">{$com["type"]}</td>
						<td width="33%">{$com['nomIngr']}</td>
						<td width="33%">
							<label>
								<input type="checkbox" name="aSuprIngr[]" value="{$com['idIngr']}">
							</label>
						</td>
					</tr>
			</table>
			HTML);
		}
			
			$i += 1;
		$p->appendContent(<<<HTML
			</table>
			<div class="container my-5">
				<div class="row justify-content-center">
					<div class="d-flex mr-4 justify-content-center">
						<a href="panier.html">
							<button class="px-4 py-2 btn-red" type="submit">
								Supprimer
							</button>
						</a>
					</div>
					<div class="d-flex ml-4 justify-content-center">
						<a href="commande.html">
							<button class="px-4 py-2 btn-red" type="reset">
								Tout décocher
							</button>
						</a>
					</div>
				</div>
			</div>
		</form>
	</div>
	<hr>
	HTML);





	//Récupération du lien de toutes les images disponibles sur le serveur
	$listImg = [];
	$dir = opendir('./img');
	while ($fichier = readdir($dir)) {
		if($fichier != "." && $fichier != "..")
		$listImg[] = $fichier;
	}

	//Formulaire permettant d'ajouter un article dans la base de données
	$p->appendContent(<<<HTML
	<div class="col-12" name="add">
	<h2 class = "container-fluid col-12">Ajouter un article</h2>
	
	<form name="new" method="POST">
	<div class="commande">
		<div>
			<label >
				Nom
				<input class="form-control input-shadow mb-4" name="Nom" type="text" placeholder="Article" required>
			</label>
		</div>

		<div>
			<label>
				Description
				<input class="form-control input-shadow mb-4" name="Description" type="text" placeholder="Sa description" required>
			</label>
		</div>

		<div>	
			<label>
				Prix
				<input class="form-control input-shadow mb-4"name="Prix" type="number" min="0" step="0.01" placeholder="0.00" required>
			</label>
		</div>

		<div>	
			<label>
				Image
				<select class="form-control" name="imgArt">
HTML);

	for ($i=0; $i < count($listImg); $i++) { 
		$fileName = $listImg[$i];
		$p->appendContent(<<<HTML
			<option value="{$fileName}" require>{$fileName}</option>
HTML);
}


	$p->appendContent(<<<HTML
						</select>
					</label>
				</div>

				<div>
					Type
					<label>
						<input name="Type" type="radio" value="boisson" checked>
						Boissons
					</label>

					<label>
						<input name="Type" type="radio" value="dessert">
						Dessert
					</label>

					<label>
						<input name="Type" type="radio" value="petiteFaim">
						Petite faim
					</label>

				</div>
			</div>
			<button class="px-4 py-2 btn-red" type="submit">Ajouter</button>
			<button class="px-4 py-2 btn-red"type="reset">Effacer</button>
		</form>
	</div>
HTML);



	//Récupération des articles présents dans la base de données
	$art = MyPDO::getInstance()->prepare(<<<SQL
	SELECT nom "Nom", prix "Prix", description "Desc", type "Type", idArticle
	FROM Article
	ORDER BY Type, Nom
SQL
);
	
	$art->execute();

	//Liste des articles (choix d'un article à supprimer)
	$p->appendContent(<<<HTML
<br>
<div name="del">
	<h2 class = "container-fluid">Liste des articles proposés</h2>
	<form name="delete" method="POST">
	<table class="table">
				<tr>
					<th width="10%">Type</th>
					<th width="10%">Articles</th>
					<th width="60%">Description</th>
					<th width="10%">Prix</th>
					<th width="10%">Suppression</th>
				</tr>
	</table>
HTML);

	$i = 0;
	while (($com = $art->fetch()) != false){
		$p->appendContent(<<<HTML
		
		<table scope ="row " class="table ">
				<tr>
					<td width="10%">{$com["Type"]}</td>
					<td width="10%">{$com['Nom']}</td>
					<td width="60%">{$com["Desc"]}</td>
					<td width="10%">{$com["Prix"]}</td>
					<td width="10%">
						<label>
							<input type="checkbox" name="aSupr[]" value="{$com['idArticle']}">
						</label>
					</td>
				</tr>
		</table>
		HTML);
	}
		
		$i += 1;
	$p->appendContent(<<<HTML
		</table>
		<div class="container my-5">
            <div class="row justify-content-center">
                <div class="d-flex mr-4 justify-content-center">
                    <a href="panier.html">
                        <button class="px-4 py-2 btn-red" type="submit">
                            Supprimer
                        </button>
                    </a>
                </div>
                <div class="d-flex ml-4 justify-content-center">
                    <a href="commande.html">
                        <button class="px-4 py-2 btn-red" type="reset">
                            Tout décocher
                        </button>
                    </a>
                </div>
            </div>
        </div>
	</form>
</div>
HTML
);

	//Upload une image sur le site
	$p->appendContent(<<<HTML
		<div class="container-fluid">
		<h2>Ajouter une image</h2>
		
			<form method="POST" enctype="multipart/form-data">
				<input class="btn btn-secondary" type="file" name="imageUpload"><br>
				<input class="px-4 py-2 btn-red" type="submit" name="submitImg">
			</form>
		</div>
HTML
	);


    $p->appendToHead(<<<HTML
        <meta charset="UTF-8"/>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css">
        <link rel="icon" href="img/logo-letter.png" />
HTML
    );

    $p->appendFooter();

}

echo $p->toHTML();