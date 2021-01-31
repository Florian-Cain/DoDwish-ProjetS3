<?php declare(strict_types=1);
require_once('autoload.php');

$authentication = new UserAuthentication();
$title = 'Do-Dwish | Accueil';
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
HTML
    );

    $p->appendNavBar();

    $p->appendContent(<<<HTML
		<div class="container">
			<div class="row mt-4">
				<div class="col-12"><h2><span style='font-weight: 600;'>Do-Dwish</span> composez selon vos envies !</h2></div>
				<div class="col-12"><p>Sandwich à composer - frites - boissons - desserts... et bien d'autres encore !</p></div>
				<div class="col-xs-4">
					<a href='#carte'>
						<button class="ml-3 pr-3 btn-red" type="button">
							<img class="mx-2 mb-1" height="20px" src="icon/basket.svg">
							Voir la carte
						</button>
					</a>
				</div>
				<div class="col-xs-4">
					<button class="ml-3 pr-3 btn-wht" type="button">
						<img class="mx-2 mb-1" height="20px" src="icon/share.svg">
						Partager
					</button>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="row mt-4 px-3">
				<div class="col-12 p-0">
					<img class="img-home w-100 p-0 mb-5" src="img/burger_01.jpg">
				</div>
			</div>
		</div>

		<div class="container-fluid background-light pb-5 mt-5 pt-5" id="carte">
			<div class="container">
				<h2 class="mt-5">La Carte</h2>
				<div class="row px-0 px-sm-3 mt-4">
					<div class="col-12 col-md-6 ">
						<a class="box" href="sandwich_a_composer.php">
							<div class="row carte-elmt mr-md-1">
								<div class="col-8 p-0">
									<img class="img-carte w-100 h-100" src="img/burger_01.jpg">
								</div>
								<div class="col-4 text-center">
									<h6 class="mt-2">Sandwich à composer</h6>
									<hr/>
									<p class="carte-desc"> - Pain<br> - Viande<br> - Garniture<br> - Sauce<br> - ect...<br></p>
								</div>
							</div>
						</a>
					</div>

					<div class="col-12 col-md-6 my-md-0 my-3">
						<a class="box" href="petiteFaim.php">
							<div class="row carte-elmt ml-md-1">
								<div class="col-8 p-0">
									<img class="img-carte w-100 h-100" src="img/frite_01.jpg">
								</div>
								<div class="col-4 text-center">
									<h6 class="mt-2">Petite <br> faim</h6>
									<hr/>
									<p class="carte-desc"> - Potatoes<br> - Frite moyenne<br> - Grande frite<br><br><br></p>
								</div>
							</div>
						</a>
					</div>
					<div class="col-12 col-md-6 mt-md-3">
						<a class="box" href="boisson.php">
							<div class="row carte-elmt mr-md-1">
								<div class="col-8 p-0">
									<img class="img-carte w-100 h-100" src="img/boisson_01.jpg">
								</div>
								<div class="col-4 text-center">
									<h6 class="mt-2">Boissons</h6>
									<hr/>
									<p class="carte-desc"> - Oasis<br> - 7up<br> - Orangina<br> - Ice Tea<br> - ect...<br><br></p>
								</div>
							</div>
						</a>
					</div>

					<div class="col-12 col-md-6 my-md-0  mt-md-3 my-3">
						<a class="box" href="dessert.php">
							<div class="row carte-elmt ml-md-1">
								<div class="col-8 p-0">
									<img class="img-carte w-100 h-100" src="img/brownie_01.jpg">
								</div>
								<div class="col-4 text-center">
									<h6 class="mt-2">Desserts</h6>
									<hr/>
									<p class="carte-desc"> - Tiramisu<br> - Tarte au daim<br> - Cookie<br> - Brownie<br><br></p>
								</div>
							</div>
						</a>
					</div>
				</div>
			</div>
		</div>
HTML
    );
    
    $p->appendFooter();
}

echo $p->toHTML();