<?php declare(strict_types=1);
require_once('autoload.php');

$authentication = new SecureUserAuthentication();

$authentication->logoutIfRequested();

$title = 'Do-Dwish | Connexion';
$p = new WebPage($title);


$error2 = False;
$errorMessage = "Erreure inconnue";
try {
	// Tentative de connexion
	$user = $authentication->getUserFromAuth();
} catch (AuthenticationException $e) {
	// Récupération de l'exception si connexion échouée
	//$p->appendContent("Échec d'authentification&nbsp;: {$e->getMessage()}");
	//$error = True;
	//$errorMessage = "Une erreur est survenue: $e->getMessage()";
} catch (Exception $e) {
	//$p->appendContent("Un problème est survenu&nbsp;: {$e->getMessage()}");
	$error2 = True;
	$errorMessage = $e->getMessage();
}

if (!$authentication->isUserConnected()) {
	$p->appendCSS(<<<CSS
		form input {
			width : 4em ;
		}
CSS
	);
	$form = $authentication->loginForm('secure_form.php');

	$p->appendToHead(<<<HTML
		<meta charset="UTF-8" />
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
		<link rel="stylesheet" href="css/style.css">
HTML
	);

	$p->appendNavbar();

	$p->appendContent(<<<HTML
		<div class="container">
			<div class="row">
				<div class="col-12">
					<h2 class="text-center py-5">Se Connecter</h2>
					<h5 class="text-center py-2">Vous n'avez pas encore de compte Do-Dwish ? <a href="inscription.php">S'inscrire</a></h5>
					<div class="col-xs-12 col-sm-10 offset-sm-1 pt-5 col-lg-6 offset-lg-3">
						{$form}
					</div>
					<p class="text-center"><a href="#">Mot de passe oublié ?</a></p>
					<div class="d-flex justify-content-center">
						<button class="py-2 mb-4 px-5 btn-red" type="submit" form="login_form">
							Connexion
						</button>
					</div>
				</div>
			</div>
		
HTML
	);
	$p->appendFooter();
	if($error2){
		if("Adresse email ou mot de passe invalide" == $errorMessage){
			$p->appendContent(<<<HTML
			<br>
			<div class="alert alert-danger" role="alert">
				<strong>{$errorMessage}</strong>
			</div>
HTML
			);
		}
	}

	$p->appendContent(<<<HTML
	</div>
HTML
	);
	

}else{ // Mettre ici redirection vers la page d'accueil quand elle sera dispo
	header('Location: index.php');
	/*$form = $authentication->logoutForm('secure_form.php', "déconnexion");
	$user = $authentication->getUser();
	$userString = $user->profile();
	$p->appendContent(<<<HTML
        <h1>Page de déconnexion</h1>
		{$form}
		$userString;
HTML
);*/
}

echo $p->toHTML();