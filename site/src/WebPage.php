<?php declare(strict_types=1);

/**
 * Classe WebPage permettant de ne plus écrire l'enrobage HTML lors de la création d'une page Web.
 *
 * @startuml
 *
 *  skinparam defaultFontSize 16
 *  skinparam BackgroundColor transparent
 *
 *  class WebPage {
 *      - $head = ""
 *      - $title = null
 *      - $body = ""
 *      + __construct(string $title=null)
 *      + body() : string
 *      + head() : string
 *      + setTitle(string $title) : void
 *      + appendToHead(string $content) : void
 *      + appendCss(string $css) : void
 *      + appendCssUrl(string $url) : void
 *      + appendJs(string $js) : void
 *      + appendJsUrl(string $url) : void
 *      + appendContent(string $content) : void
 *      + appendNavBar() : void
 *      + appendFooter() : void
 *      + toHTML() : string
 *      + {static} getLastModification() : string
 *      + {static} escapeString(string $string) : string
 *  }
 *
 * @enduml
 */
class WebPage
{
    /**
     * Texte compris entre \<head\> et \</head\>.
     *
     * @var string $head
     */
    private $head = '';

    /**
     * Texte compris entre \<title\> et \</title\>.
     *
     * @var string $title
     */
    private $title = null;

    /**
     * Texte compris entre \<body\> et \</body\>.
     *
     * @var string $body
     */
    private $body = '';

    /**
     * Constructeur.
     *
     * @param string $title Titre de la page
     */
    public function __construct(string $title = null)
    {
        if (!is_null($title)) {
            $this->setTitle($title);
        }
    }

    /**
     * Retourner le contenu de $this->body.
     *
     * @return string
     */
    public function body(): string
    {
        return $this->body;
    }

    /**
     * Retourner le contenu de $this->head.
     *
     * @return string
     */
    public function head(): string
    {
        return $this->head;
    }

    /**
     * Affecter le titre de la page.
     *
     * @param string $title Le titre
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Ajouter un contenu dans $this->head.
     *
     * @param string $content Le contenu à ajouter
     */
    public function appendToHead(string $content): void
    {
        $this->head .= $content;
    }


    /**
     * Ajouter une navbar
     */
    public function appendNavBar(): void 
    {
        $authentication = new UserAuthentication();

        if (!$authentication->isUserConnected()) {
            $form = "";
            $panelButton = "";
        }else{
            $form = $authentication->logoutForm('secure_form.php', "Déconnexion");



            $panelButton = "";
           
            $client = $authentication->getUser()->getId();
            if($client == 1){
                $panelButton = <<<HTML
                <a href="admin.php"><li>PANEL ADMIN</li></a>
                HTML;
            }
            else if($client == 2){
                $panelButton = <<<HTML
                <a href="livreur.php"><li>PANEL LIVREUR</li></a>
            HTML;
            }
        }
        $first = <<<HTML
        <nav class="navbar sticky-top navbar-light bg-light">
            <div class="nav-left">
                <a class="navbar-brand text-light navFirst" href="index.php">
                    <img src="img/logo.png" height="30px">
                </a>
            </div>

            <div class="row">
                <div class="col-6">
        HTML;
        $second = <<<HTML
                </div>
                <div class="col-6">
                    <button id="open-menu" class="menu-btn mt-2 mx-4" type="button">
                              <img src="icon/menu.svg" height="33px">
                    </button>
                </div>

            </div>
            <div class="nav-line container-fluid mt-3"></div>
        </nav>

        <div id="menu" class="menu-close bg-dark"> 
            <div class="row mt-5 justify-content-between">
                <div class="col-2 ml-3">
                    <button id="close-menu" class="menu-btn" type="button">
                        <img src="icon/close.svg" height="25px">
                    </button>
                </div>
                <div class="col-8">
                    <div class="mt-1 pr-5 align-right">
                        <img src="img/logo_white.png"  height="30px">
                    </div>
                </div>
            </div>
            
            <ul class="menu-lst mt-4">
                {$panelButton}
                <a href="index.php#carte"><li>NOTRE CARTE</li></a>
                <a href="panier.php"><li>PANIER</li></a>
                <a href="infoCommande.php"><li>COMMANDER</li></a>
                <a href="historique.php"><li>HISTORIQUE</li></a>
                <a href="compte.php"><li>MON COMPTE</li></a>
                <a href="contact.php"><li>CONTACT</li></a>
                <a href="horaire.php"><li>HORAIRES</li></a>
            </ul>
        </div>
        HTML;
        $this->appendContent($first.$form.$second);
    }

    /**
     * Ajouter un footer
     */
    public function appendFooter(): void
    {
        $this->appendContent( <<<HTML
            <div class="container mt-5 mn-line-height">
                <footer class="row justify-content-center pt-4">
                        <div class="col-6 col-md-3">
                            <h6 class="font-weight-bold mb-3">INFORMATIONS</h6>
                            <a href="horaire.html"><p>Notre restaurant</p></a>
                            <a href="contact.html"><p>Nous rejoindre</p></a>
                        </div>
                        <div class="col-6 col-md-3">
                            <h6 class="font-weight-bold mb-3">CONTACT</h6>
                            <a href="https://twitter.com"><img src="icon/twitter.svg" height="40px"></a>
                            <a href="https://facebook.com"><img src="icon/facebook.svg" height="40px"></a>
                            <a href="https://www.instagram.com"><img src="icon/instagram.svg" height="40px"></a>
                        </div>
                        <div class="col-6 col-md-3 mt-5 mt-md-0">
                            <h6 class="font-weight-bold mb-3">NOS SERVICES</h6>
                            <p><a href="../dodwish/index.php" target="_self">Se faire livrer</a></p>
                            <p><a href="https://www.doctissimo.fr/html/dossiers/allergies.htm" target="_blank">Informations allergènes</a></p>
                        </div>
                        <div class="col-6 col-md-3 mt-5 mt-md-0">
                            <h6 class="font-weight-bold mb-3">ELEMENTS LEGAUX</h6>
                            <p><a href="../dodwish/mentions_legales.php" target="_blank">Mentions légales</a></p>
                            <p><a href="../dodwish/cg.php" target="_blank">Conditions d'utilisation</a></p>
                        </div>

                </footer>
                <p class="text-center mt-4">Pour votre santé, évitez de manger trop gras, trop sucré, trop salé.</p>
                <p class="text-center"><a href="https://www.mangerbouger.fr/" target="_blank">www.mangerbouger.fr</a></p>
                <p class="text-center">Do-Dwich® n’est pas une marque déposée.</p>
                <p class="text-center">© 2020 Do-Dwich.</p>
                <p class="text-center">Tous droits réservés. Photos non contractuelles.</p>
                <p class="text-center">Paramètres des témoins</p>
            </div>

            <script type="text/javascript">
                var status = 0;
                document.getElementById('open-menu').addEventListener('click', menuChange);
                document.getElementById('close-menu').addEventListener('click', menuChange);
                function menuChange(){
                    console.log(status)
                     if(status == 0){
                        document.getElementById('menu').className = 'menu-open';
                        status = 1
                     }
                     else{
                        document.getElementById('menu').className = 'menu-close';
                        status = 0;
                     }
                }
            </script>
            HTML);
    }

    /**
     * Ajouter un contenu CSS dans head.
     *
     * @param string $css Le contenu CSS à ajouter
     *@see WebPageEnhanced::appendToHead(string $content) : void
     *
     */
    public function appendCss(string $css): void
    {
        $this->appendToHead(<<<HTML
    <style type='text/css'>
    {$css}
    </style>

HTML
        );
    }

    /**
     * Ajouter l'URL d'un script CSS dans head.
     *
     * @param string $url L'URL du script CSS
     *@see WebPageEnhanced::appendToHead(string $content) : void
     *
     */
    public function appendCssUrl(string $url): void
    {
        $this->appendToHead(<<<HTML
    <link rel="stylesheet" type="text/css" href="{$url}">

HTML
        );
    }

    /**
     * Ajouter un contenu JavaScript dans head.
     *
     * @param string $js Le contenu JavaScript à ajouter
     *@see WebPageEnhanced::appendToHead(string $content) : void
     *
     */
    public function appendJs(string $js): void
    {
        $this->appendToHead(<<<HTML
    <script type='text/javascript'>
    {$js}
    </script>

HTML
        );
    }

    /**
     * Ajouter l'URL d'un script JavaScript dans head.
     *
     * @param string $url L'URL du script JavaScript
     *@see WebPageEnhanced::appendToHead(string $content) : void
     *
     */
    public function appendJsUrl(string $url): void
    {
        $this->appendToHead(<<<HTML
    <script type='text/javascript' src='{$url}'></script>

HTML
        );
    }

    /**
     * Ajouter un contenu dans body.
     *
     * @param string $content Le contenu à ajouter
     */
    public function appendContent(string $content): void
    {
        $this->body .= $content;
    }

    /**
     * Produire la page Web complète.
     *
     * @return string
     *
     * @throws Exception si title n'est pas défini
     */
    public function toHTML(): string
    {
        if (is_null($this->title)) {
            throw new Exception(__CLASS__.': title not set');
        }

        $lastModification = self::getLastModification();

        return <<<HTML
<!doctype html>
<html lang="fr">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>{$this->title}</title>
{$this->head()}
    </head>
    <body>
        <div id='page'>
{$this->body()}
        <!--<div id='lastmodified'>{$lastModification}</div>-->
        </div>
    </body>
</html>
HTML;
    }

    /**
     * Donner la date et l'heure de la dernière modification du script principal.
     *
     * @return string
     *
     * @see http://php.net/manual/en/function.getlastmod.php
     * @see http://php.net/manual/en/function.strftime.php
     */
    public static function getLastModification(): string
    {
        return strftime('Dernière modification de cette page le %d/%m/%Y à %Hh%M', getlastmod());
    }

    /**
     * Protéger les caractères spéciaux pouvant dégrader la page Web.
     *
     * @param string $string La chaîne à protéger
     *
     * @return string La chaîne protégée
     *
     * @see https://www.php.net/manual/en/function.htmlspecialchars.php
     */
    public static function escapeString(string $string): string
    {
        return htmlspecialchars($string, ENT_QUOTES | ENT_HTML5, 'utf-8');
    }
}
