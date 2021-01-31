<?php declare(strict_types=1);
require_once('autoload.php');

$authentication = new UserAuthentication();
$title = 'Do-Dwish | Petite Faim';
$p = new WebPage($title);

if (!$authentication->isUserConnected()) {
    header('Location: secure_form.php');
    exit();
}else{

    $p->appendToHead(<<<HTML
        <meta charset="UTF-8"/>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css">
        <link rel="icon" href="img/logo-letter.png" />
HTML
    );

    $p->appendNavbar();

    //Affichage des "petites faims"
    Article::afficher("petiteFaim", "Les petites faims", $p);
   
    $p->appendFooter();
}

echo $p->toHTML();