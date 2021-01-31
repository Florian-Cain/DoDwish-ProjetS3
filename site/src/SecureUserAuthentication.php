<?php declare(strict_types=1);

/**
 * Classe SecureUserAuthentication
 * 
 * @var CODE_INPUT_NAME			Nom de code
 * @var SESSION_CHALLENGE_KEY	Nom de la session
 * @var RANDOM_STRING_SIZE		Taille d'une clé random
 */
class SecureUserAuthentication extends AbstractUserAuthentication
{
	const CODE_INPUT_NAME = 'code';
	const SESSION_CHALLENGE_KEY = 'challenge';
	const RANDOM_STRING_SIZE = 16;

	/**
	 * Formulaire de connexion
	 * 
	 * @param String $action 		Page vers laquelle sera redirigé l'utilisateur après la connexion
	 * @param String $submitText	Texte de confirmation du bouton d'envoi
	 * 
	 * @retval String Formulaire de connexion
	 */
	public function loginForm(string $action, string $submitText = 'Se connecter'): string 
	{
		$key = Random::string(self::RANDOM_STRING_SIZE);
		Session::start();
		$_SESSION[self::SESSION_KEY][self::SESSION_CHALLENGE_KEY] = $key;

		$codeInputName = self::CODE_INPUT_NAME;
		$form = <<<HTML
			<script type="text/javascript" src="js/sha512.js"></script>
			<form id="login_form" name="login" method="post" action=$action>
				<label>Mail</label>
				<input id="login" class="form-control input-shadow mb-4" type="text" placeholder="email" aria-label="Mail" required>
				<label>Mot de passe</label>
				<input id="password" class="form-control input-shadow mb-4" type="password" placeholder="password" aria-label="Mot de passe" required>
				<input id="challenge" type="hidden" value="{$key}">
				<input id="code" name=$codeInputName type="hidden">
				<!--<button type="submit">$submitText</button>-->
			</form>
			<script type='text/javascript'>
				document.getElementById('login_form').onsubmit = function () {
					let login = document.getElementById('login');
					let password = document.getElementById('password');
					let challenge = document.getElementById('challenge');
					let code = document.getElementById('code');
					
					code.value = CryptoJS.SHA512(CryptoJS.SHA512(password.value).toString() +challenge.value +CryptoJS.SHA512(login.value).toString()).toString();
					console.log(code.value);
				}
			</script>
		HTML;
		
		return $form;
	}

	/**
	 * Récupération de l'utilisateur authentifié
	 * 
	 * @retval User Utilisateur authentifié
	 */
	public function getUserFromAuth(): User
	{
		Session::start();
		if (isset($_SESSION[self::SESSION_KEY][self::SESSION_CHALLENGE_KEY]) && isset($_POST[self::CODE_INPUT_NAME])) {
			$challenge = $_SESSION[self::SESSION_KEY][self::SESSION_CHALLENGE_KEY];
            
            $stmt = MyPDO::getInstance()->prepare(<<<SQL
				SELECT idUser, prenom, nom, email, tel
				FROM user
				WHERE SHA2(CONCAT(mdpSHA512, :challenge, SHA2(email, 512)), 512) = :code
SQL
);
            $stmt->execute([':code' => $_POST[self::CODE_INPUT_NAME], ':challenge' => $challenge]);

            if (($user = $stmt->fetch())) {
				$this->setUser($userObj = new User($user));
				return $userObj;
			}else{
				throw new Exception("Adresse email ou mot de passe invalide");
			}
		}else{
			throw new Exception("Pas de challenge ou de tentative de connexion");
		}
	}
}