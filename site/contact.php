<?php declare(strict_types=1);
require_once('autoload.php');

$authentication = new UserAuthentication();
$title = 'Do-Dwish | Contact';
$p = new WebPage($title);


$p->appendToHead(<<<HTML
<meta charset="UTF-8"/>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
<link rel="stylesheet" href="css/style.css">
<link rel="icon" href="img/logo-letter.png"/>
HTML
);

$p->appendNavbar();

$p->appendContent(<<<HTML
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-4 align-self-center">
                <h2 class="text-center">Où sommes nous ?</h2>
                <p class="text-center mb-5">1 Rue de l'imaginaire - 51100 Reims</p>
                <p class="text-center">Téléphone : 03 24 56 78 90</p>
                <p class="text-center mb-4">Email : do-dwish.official@gmail.com</p>
            </div>
            <div class="col-12 col-md-6 col-lg-8 p-0">
                <img class="img-fluid w-100 h-100 p-0" src="img/burger_01.jpg">
            </div>
        </div>
    </div>
HTML
);
$p->appendFooter();

echo $p->toHTML();