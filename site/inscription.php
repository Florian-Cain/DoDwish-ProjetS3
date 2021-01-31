<?php declare(strict_types=1);
require_once('autoload.php');

$authentication = new UserAuthentication();
$title = 'Inscription';
$p = new WebPage($title);

$errorDetected = False;
$alreadyRegister = False;

if(isset($_POST['register'])){
	try{
		$authentication->register();
		$alreadyRegister = True;
	}catch(Exception $e){
		//$p->appendContent($e->getMessage());
		$errorDetected = True;
		$errorMessage = $e->getMessage();
	}
}

if ($alreadyRegister && $errorDetected == False){
	header('Location: secure_form.php');
	exit();
}

$p->appendToHead(<<<HTML
	<meta charset="UTF-8" />
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css">
HTML
);

$p->appendNavbar();

$p->appendContent(<<<HTML
	<div class="container pb-5">
            <div class="row">
                <div class="col-12">
                    <h2 class="text-center py-5">S'Inscrire</h2>
                    <h5 class="text-center py-2">Vous avez déjà un compte Do-Dwish ? <a href="secure_form.php">Se Connecter</a></h5>

                    <div class="col-xs-12 col-sm-10 offset-sm-1 pt-5 col-lg-6 offset-lg-3">
HTML
);

//Formulaire d'inscription
$form = $authentication->registerForm('');
$p->appendContent(<<<HTML
	{$form}
HTML
);

$p->appendContent(<<<HTML
	</div>
                    <div class="d-flex justify-content-center">
					<button class="py-2 mt-5 px-5 btn-red" type="submit" name="register" form="inscription2">
                        Inscription
                    </button>
                </div>
                </div>

            </div>
HTML
);

if ($errorDetected){
	$p->appendContent(<<<HTML
	<br>
	<div class="alert alert-danger" role="alert">
		<strong>{$errorMessage}</strong>
	</div>
HTML
	);
}

$p->appendContent(<<<HTML
	
	</div>
HTML
);

$p->appendFooter();

echo $p->toHTML();