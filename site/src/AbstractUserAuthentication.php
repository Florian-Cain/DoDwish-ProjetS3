<?php declare(strict_types=1);

/**
 * Classe AbstractUserAuthentication
 * 
 * @var SESSION_KEY			Clé de session
 * @var SESSION_USER_KEY	Clé d'utilisateur de session
 * @var LOGOUT_INPUT_NAME	Clé de déconnexion
 * @var $user				Utilisateur
 */
abstract class AbstractUserAuthentication
{
	const SESSION_KEY = '__UserAuthentication__'; // string constante
	const SESSION_USER_KEY = 'user'; // string constante

	const LOGOUT_INPUT_NAME = 'logout'; // string constante
	
	private $user; // User

	/**
	 * Formulaire de connexion
	 * 
	 * @param String $action		Lien de la page sur laquelle l'utilisateur sera redirigé après connexion
	 * @param String $submitText	Message du bouton de confirmation
	 * 
	 * @retval String Formulaire de connexion
	 */
	abstract public function loginForm(string $action, string $submitText = 'OK'): string; 

	/**
	 * Récupération de la connexion
	 * 
	 * @retval User Utilisateur à vérifier
	 */
	abstract public function getUserFromAuth(): User;

	/**
	 * Etablissement de l'utilisateur
	 * 
	 * @param $user Utilisateur
	 */
	protected function setUser(User $user): void
	{
		$this->user = $user;
		Session::start();
		$_SESSION[self::SESSION_KEY][self::SESSION_USER_KEY] = $this->user;
	}

	/**
	 * Vérification de la connexion
	 * 
	 * @retval bool Etat de la connexion
	 */
	public function isUserConnected(): bool
	{
		Session::start();
		if (isset($_SESSION[self::SESSION_KEY][self::SESSION_USER_KEY])){
			$ret = true;
		}else{
			$ret = false;
		}
		return $ret;
	}

	/**
	 * Formulaire de déconnexion
	 * 
	 * @param String $action Lien de la page sur laquelle l'utilisateur sera redirigé après déconnexion
	 * @param String $text	 Message du bouton de confirmation
	 */
	public function logoutForm(string $action, string $text): string
	{
		$name = self::LOGOUT_INPUT_NAME;
		$form = <<<HTML
			<form name="logout" method="post" action=$action>
				<button class="ml-3 pr-3 mt-2 btn-logout"  type="submit" name="{$name}" value="{$name}">
					<div class="row">
						<div class="col-3">
							<img class="mx-2 mb-1" height="20px" src="icon/logout.svg">
						</div>
						<div class="col-3">
							$text
						</div>
					</div>
				</button>
			</form>
HTML;
		return $form;
	}

	/**
	 * Déconnexion de l'utilisateur
	 */
	public function logoutIfRequested(): void
	{
		if(isset($_POST[self::LOGOUT_INPUT_NAME])){
			Session::start();
			unset($_SESSION[self::SESSION_KEY][self::SESSION_USER_KEY]);
			$this->user = null;
		}
	}

	/**
	 * Récupération des clés de l'utilisateur de la session
	 * 
	 * @retval User Utilisateur de la session
	 */
	protected function getUserFromSession():User {
		Session::start();
		if (isset($_SESSION[self::SESSION_KEY][self::SESSION_USER_KEY])){
			$ret = $_SESSION[self::SESSION_KEY][self::SESSION_USER_KEY];
		}else{
			throw new NotLoggedInException("User non connecté");
		}
		return $ret;
	}

	/**
	 * Constructeur
	 */
	public function __construct(){
		try{
			$this->user = $this->getUserFromSession(); 
		}catch(NotLoggedInException $e){
			//var_dump("Échec d'authentification: {$e->getMessage()}");
		}
	}

	/**
	 * Retourne le nom de l'utilisateur
	 * 
	 * @retval User Utilisater connecté
	 */
	public function getUser(): User {
		if (isset($this->user)){
			$ret = $this->user;
		}else{
			throw new NotLoggedInException("User non connecté");
		}
		return $ret;
	}
}