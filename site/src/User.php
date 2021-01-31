<?php declare(strict_types=1);

/**
 * Classe User
 
 * @var int    $id 		ID de l'utilisateur
 * @var String $nom 	Nom de l'utisateur
 * @var String $prenom  Prénom
 * @var String $email	E-mail de l'utilisateur
 * @var String $tel		Numéro de téléphone de l'utilisateur
 */
class User
{
	private $id; //int 
	private $nom; //string
	private $prenom; //string
	private $email; //string
	private $tel; //string

	/**
	 * Constructeur
	 * 
	 * @param $tab Tableau regroupant les informations de l'utilisateur
	 */
	public function __construct($tab)
	{
		if (isset($tab["idUser"])) {
			$this -> id = $tab["idUser"];
		}else{
			$this -> id = 0;
		}
		if (isset($tab["nom"])) {
			$this -> nom = $tab["nom"];
		}else{
			$this -> nom = "default";
		}
		if (isset($tab["prenom"])) {
			$this -> prenom = $tab["prenom"];
		}else{
			$this -> prenom = "default";
		}
		if (isset($tab["email"])) {
			$this -> email = $tab["email"];
		}else{
			$this -> email = "default";
		}
		if (isset($tab["tel"])) {
			$this -> tel = $tab["tel"];
		}else{
			$this -> tel = "0000000000";
		}
	}

	/**
	 * Récupération de l'ID de l'utilisateur
	 * 
	 * @retval int ID de l'utilisateur
	 */
	public function getId(): int
	{
		return intval($this->id);
	}

	/**
	 * Récupération du nom de l'utilisateur
	 * 
	 * @retval String Nom de l'utilisateur
	 */
	public function getNom(): string
	{
		return $this->nom;
	}

	/**
	 * Récupération du prénom de l'utilisateur
	 * 
	 * @retval String Prénom de l'utilisateur
	 */
	public function getPrenom(): string
	{
		return $this->prenom;
	}

	/**
	 * Récupération de l'e-mail de l'utilisateur
	 * 
	 * @retval String E-mail de l'utilisateur
	 */
	public function getEmail(): string
	{
		return $this->email;
	}

	/**
	 * Récupération du numéro de téléphone de l'utilisateur
	 * 
	 * @retval String Numéro de l'utilisateur
	 */
	public function getTel(): string
	{
		return $this->tel;
	}

	/**
	 * Modification du nom de l'utilisateur
	 * 
	 * @param String $nom Nouveau nom de l'utilisateur
	 */
	public function setNom(string $nom): void
	{
		$this->nom = $nom;
	}

	/**
	 * Modification du prénom de l'utilisateur
	 * 
	 * @param String $nom Nouveau prénom de l'utilisateur
	 */
	public function setPrenom(string $prenom): void
	{
		$this->prenom = $prenom;
	}

	/**
	 * Modification de l'e-mail de l'utilisateur
	 * 
	 * @param String $nom Nouvel e-mail de l'utilisateur
	 */
	public function setEmail(string $email): void
	{
		$this->email = $email;
	}

	/**
	 * Modification du numéro de téléphone de l'utilisateur
	 * 
	 * @param String $nom Nouveau numéro de téléphone de l'utilisateur
	 */
	public function setTel(string $tel): void
	{
		$this->tel = $tel;
	}

	/**
	 * Modification du mot de passe de l'utilisateur dans la base de données
	 * 
	 * @param String $mdp Nouveau mot de passe de l'utilisateur (en SHA512)
	 */
	public function updateMdp(string $mdp): void
	{
		$update = MyPDO::getInstance()->prepare(<<<SQL
			UPDATE user
			SET mdpSHA512 = :mdp
			WHERE idUser = :id
SQL
		);

		$update->execute(array(":mdp" => hash('sha512', $mdp), ":id" => $this->id));
	}

	/**
	 * Modification du numéro de téléphone de l'utilisateur dans la base de données
	 * 
	 * @param String $tel Nouveau numéro de téléphone de l'utilisateur
	 */
	public function updateTel(string $tel): void
	{
		$update = MyPDO::getInstance()->prepare(<<<SQL
			UPDATE user
			SET tel = :tel
			WHERE idUser = :id
SQL
		);

		$update->execute(array(":tel" => $tel, ":id" => $this->id));

		$this->tel = $tel;
	}


	/**
	 * Affichage du profil
	 * 
	 * @retval String Profil de l'utilisateur
	 */
	public function profile(): string
	{
		$chaine = <<<HTML
			<table>
				<tr>
					<td>Nom</td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td>$this->nom</td>
				</tr>

				<tr>
					<td>Prénom</td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td>$this->prenom</td>
				</tr>

				<tr>
					<td>Login</td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td>$this->email [$this->id]</td>
				</tr>

				<tr>
					<td>Téléphone</td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td>$this->tel</td>
				</tr>
			</table>
HTML;
		return $chaine;
	}
}