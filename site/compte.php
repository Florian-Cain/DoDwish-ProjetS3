<?php declare(strict_types=1);
require_once('autoload.php');

$authentication = new UserAuthentication();
$title = 'Do-Dwish | Votre compte';
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

    //Récupération des données de l'utilisateur
	$user = $authentication->getUser();
    $nom = $user->getNom();
    $prenom = $user->getPrenom();
    $tel = $user->getTel();
    $mail = $user->getEmail();

    $p->appendNavbar();

    //Formulaire d'affichage des données de l'utilisateur
	$p->appendContent(<<<HTML
        <div class="container my-4">
            <div class="row">
                <div class="col-12">
                    <h2 class="text-center py-5">Les Informations relatives à votre compte appraissent ci dessous</h2>
                    <div class="col-xs-12 col-sm-10 offset-sm-1 col-lg-8 offset-lg-2">
                        <form>
                            <label>Nom</label>
                            <input class="form-control input-shadow mb-4" type="name" placeholder="Nom" aria-label="Nom" value={$nom} disabled>
                            <label>Prénom</label>
                            <input class="form-control input-shadow mb-4" type="name" placeholder="Prénom" aria-label="Prénom" value={$prenom} disabled>
                            <label>Numéro de téléphone</label>
                            <input class="form-control input-shadow mb-4" type="name" placeholder="Numéro de téléphone" aria-label="Numéro de téléphone" value={$tel} disabled>
                            <label>Mail</label>
                            <input class="form-control input-shadow mb-4" type="name" placeholder="Mail" aria-label="Mail" value={$mail} disabled>
                        </form>
                    </div>
                </div>
            </div>
        </div>
HTML
    );
    $p->appendFooter();
}

echo $p->toHTML();