<?php declare(strict_types=1);

/**
 * Classe Session
 */
class Session
{
	/**
	 * Lancement de la session (si elle est inexistante)
	 */
	public static function start(){
		if (session_status() == PHP_SESSION_ACTIVE){
			#On ne fait rien
		}elseif (headers_sent() == true){
			throw new SessionException("Impossible de modifier les entêtes HTTP");
		}elseif (session_status() == PHP_SESSION_DISABLED){
			throw new SessionException("Etat de session incompatible ou incohérent");
		}else{
			session_start();
		}
	}
}