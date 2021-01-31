<?php declare(strict_types=1);
require_once('autoload.php');

$authentication = new UserAuthentication();
$title = 'Do-Dwish | Horaires';
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
            <div class="col-12 col-md-6 col-lg-5 align-self-center">
                <h2 class="text-center">Nos Horaires d'ouverture</h2>
                <p class="text-center mb-4'">C'est avec plaisir que nous livrerons <br>7 jours sur 7 <br>de 11h à minuit</p>
                <p class="text-center mb-4">Dans Reims et ses alentours</p>
            </div>
            <div class="col-12 col-md-6 col-lg-7 p-0">
                <img class="img-fluid w-100 h-100 p-0" src="img/burger_01.jpg">
            </div>

        </div>
    </div>
HTML
);
$p->appendFooter();

echo $p->toHTML();