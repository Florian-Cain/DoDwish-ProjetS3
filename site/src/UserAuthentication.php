<?php declare(strict_types=1);

/**
 * Classe UserAuthentication
 * 
 * @var LOGIN_INPUT_NAME 	Login du User
 * @var PASSWORD_INPUT_NAME Mot de passe du User
 */
class UserAuthentication extends AbstractUserAuthentication
{
	const LOGIN_INPUT_NAME = 'login'; // string constante
	const PASSWORD_INPUT_NAME = 'password'; // string constante

	/**
	 * Formulaire de connexion
	 * 
	 * @param String $action 		Page sur laquelle l'utilisateur sera renvoyé après connexion
	 * @param String $submitText	Texte du bouton de confirmation
	 * 
	 * @retval String Formulaire de connexion
	 */
	public function loginForm(string $action, string $submitText = 'OK'): string 
	{
		$login = self::LOGIN_INPUT_NAME;
		$pass = self::PASSWORD_INPUT_NAME;
		$form = <<<HTML
			<form name="login" method="post" action=$action>
				<input name="{$login}" type="text" placeholder="login" required>
				<input name="{$pass}" type="password" placeholder="pass" required>
				<button type="submit">$submitText</button>
			</form>
		HTML;
		return $form;
	}

	/**
	 * Récupération de l'utilisateur connecté
	 * 
	 * @retval User Utilisateur connecté
	 */
	public function getUserFromAuth(): User
	{
		if(isset($_POST["login"]) and isset($_POST["password"])){
			$user = MyPDO::getInstance()->prepare(<<<SQL
				SELECT *
				FROM user
				WHERE email = :login
				AND mdpSHA512 = :pass
			SQL
			);
			$user->execute(array(":login" => $_POST[self::LOGIN_INPUT_NAME], ":pass" => hash('sha512', $_POST[self::PASSWORD_INPUT_NAME])));

			$tab = $user->fetch(PDO::FETCH_ASSOC);
			if ($tab == false){
				throw new AuthenticationException('Login ou mot de passe non valide');
			}else{
				$rep = new User($tab);
				$this->setUser($rep);
			return $rep;
			}
		}else{
			throw new AuthenticationException('Données saisies non valides');
		}
	}

	// -------- Partie Inscription -------- \\

	/**
	 * Formulaire d'inscription
	 * 
	 * @param String $action 		Page sur laquelle l'utilisateur sera renvoyé après inscription
	 * @param String $submitText	Texte du bouton de confirmation
	 * 
	 * @retval String Formulaire d'inscription
	 */
	public function registerForm(string $action, string $submitText = 's\'inscrire'): string
	{
		$login = self::LOGIN_INPUT_NAME;
		$pass = self::PASSWORD_INPUT_NAME;
		$form = <<<HTML
			<form name="register" method="post" action="$action" id="inscription2">
			<label>Nom</label>
				<input class="form-control input-shadow mb-4" type="text" name="nom" placeholder="  Nom" required/>
				<label>Prénom</label>
				<input class="form-control input-shadow mb-4" type="text" name="prenom" placeholder="  Prénom" required/>
				<label>Mail</label>
				<input class="form-control input-shadow mb-4" type="mail" name="{$login}" placeholder="  Mail" required/>
				<label>Numéro de téléphone</label>
				<input class="form-control input-shadow mb-4" type="tel" name="tel" placeholder="Téléphone " required/>
				<label>Mot de passe</label>
				<input class="form-control input-shadow mb-4" type="password" name="{$pass}" placeholder="Mot de passe " required/>
				<label>Confirmez votre mot de passe</label>
				<input class="form-control input-shadow mb-4" type="password" name="password2" placeholder="Confirmez le mot de passe " required/>
			</form>
		<!--<button type="submit" name="register" form="inscription2">$submitText</button>-->
			    
HTML;
		return $form;
	}

	/**
	 * Vérification si l'email existe
	 * 
	 * @param String $login Login de l'utilisateur
	 * 
	 * @retval bool Etat de l'existence de l'adresse mail
	 */
	protected function loginExist(string $login): bool{
		$ret = False;
		$user = MyPDO::getInstance()->prepare(<<<SQL
			SELECT email
			FROM user
			WHERE email = :login
		SQL
		);
		$user->execute(array(":login" => $login));
		$rep = $user->fetch(PDO::FETCH_ASSOC);
		if($rep != False){
			$ret = True;
		}
		return $ret;
	}

	/**
	 * Inscription de la personne dans la BDD
	 */
	public function register(): void
	{
		if(isset($_POST[self::LOGIN_INPUT_NAME]) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['tel'])
		&& isset($_POST[self::PASSWORD_INPUT_NAME]) && isset($_POST['password2']))
		{
			if(!empty($_POST[self::LOGIN_INPUT_NAME]) && !empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['tel'])
			&& !empty($_POST[self::PASSWORD_INPUT_NAME]) && !empty($_POST['password2']))
			{
				if($_POST[self::PASSWORD_INPUT_NAME] == $_POST['password2']){
					if($this->loginExist($_POST[self::LOGIN_INPUT_NAME]) == False){

						$insertion = MyPDO::getInstance()->prepare(<<<SQL
							INSERT INTO `user` (`email`, `mdpSHA512`, `nom`, `prenom`, `tel`) 
							VALUES (:email, :mdp, :nom, :prenom, :tel);
						SQL
						);

						$insertion->execute(array(":email" => $_POST[self::LOGIN_INPUT_NAME], ":mdp" => hash('sha512', $_POST[self::PASSWORD_INPUT_NAME]), ":nom" => $_POST['nom'], ":prenom" => $_POST['prenom'], ":tel" => $_POST['tel']));

					}else{
						throw new Exception("Un compte a déjà été créé avec cette adresse email");
					}
				}else{
					throw new Exception("Mot de passe non confirmé");
				}
			}else{
				throw new Exception("Données non valides");
			}
		}else{
			throw new Exception("Données non valides");
		}
	}
}