<?php declare(strict_types=1);

/**
 * Classe Panier
 */
class Panier{

    /**
     * Ajoute un élément dans la base de données
     * 
     * @param int       $idUser         ID de l'article
     * @param String    $nom            Nom de l'article
     * @param String    $description    Description de l'article
     * @param float     $prix           Prix de l'article
     */
    static public function addElement(int $idUser, string $nom, string $description, float $prix){
        $req = MyPDO::getInstance()->prepare(<<<SQL
        INSERT INTO `panier` (`idUser`, `nom`, `description`, `prix`)
        VALUES (:idUser, :nom, :description, :prix);
SQL
    	);
    	$req->execute(array(":idUser" => $idUser, ":nom" => $nom, ":description" => $description, ":prix" => $prix));
    }

    /**
     * Récupération du panier
     * 
     * @param int $idUser ID du client
     * 
     * @retval Array Panier du client
     */
    static public function getElement(int $idUser): array{
        $req = MyPDO::getInstance()->prepare(<<<SQL
        SELECT nom, description, prix, id
        FROM panier
        WHERE idUser = :idUser
SQL
    	);
    	$req->execute(array(":idUser" => $idUser));
    	$tabToReturn = [];
    	while ($tab = $req->fetch()) {
    		$tabToReturn[] = $tab;
    	}
    	return $tabToReturn;
    }

    /**
     * Retourne le prix total du panier du client
     * 
     * @param int $idUser ID du client
     * 
     * @retval float Prix total du panier
     */
    static public function getPrixTotal(int $idUser): float{
        $req = MyPDO::getInstance()->prepare(<<<SQL
        SELECT prix
        FROM panier
        WHERE idUser = :idUser
SQL
    	);
    	$req->execute(array(":idUser" => $idUser));
    	$prix = 0;
    	while ($n = $req->fetch()) {
    		$prix += $n["prix"];
    	}
    	return $prix;
    }

    /**
     * Supprime un élément du panier
     * 
     * @param $id ID de l'article à supprimer
     */
    static public function delElement($id)
    {
        $req = MyPDO::getInstance()->prepare(<<<SQL
        DELETE FROM panier
        WHERE id = :idArticle
SQL
        );
        $req->execute([":idArticle"=>$id]);
    }

    /**
     * Vide le panier
     * 
     * @param $idUser ID du client
     */
    static public function viderPanier($idUser)
    {
        $req = MyPDO::getInstance()->prepare(<<<SQL
        DELETE FROM panier
        WHERE idUser = :idUser
SQL
        );
        $req->execute([":idUser"=>$idUser]);
    }
}
