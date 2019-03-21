<?php
/**
 * Class Controler
 * Gère les requêtes HTTP
 * 
 * @author Jonathan Martel
 * @version 1.0
 * @update 2019-01-21
 * @license Creative Commons BY-NC 3.0 (Licence Creative Commons Attribution - Pas d’utilisation commerciale 3.0 non transposé)
 * @license http://creativecommons.org/licenses/by-nc/3.0/deed.fr
 * 
 */

session_start();

class Controler 
{

    /**
     * Traite la requête
     * @return void
     */
    public function gerer()
    {

        switch ($_GET['requete']) {
            case 'listeBouteilleCellier':
                $this->listeBouteilleCellier();
                break;
            case 'autocompleteBouteille':
                $this->autocompleteBouteille();
                break;
            case 'ajouterNouvelleBouteilleCellier':
                $this->ajouterNouvelleBouteilleCellier();
                break;
            case 'ajouterBouteilleCellier':
                $this->ajouterBouteilleCellier();
                break;
            case 'modifierBouteilleCellier':
                $this->modifierBouteilleCellier();
                break;
            case 'boireBouteilleCellier':
                $this->boireBouteilleCellier();
                break;
            case 'afficheCellier':
                $this->afficheCellier();
                break;
            case 'uploadPage':
                $this->uploadPage();
                break;
            case 'compte':
                $this->compte();
                break;
            case 'login':
                $this->login();
                break;
            case 'deconnexion':
                $this->deconnexion();
                break;
            case 'inscription':
                $this->inscription();
                break;
            case 'creerCompteUsager':
                $this->creerCompteUsager();
                break;
            case 'modificationCompte':
                $this->modificationCompte();
                break;
            default:
                $this->accueil();
                break;
        }
    }


        /**
         * affiche le page accueil le trier par nom by default
         *
         */
        private function accueil()
        {  
            include("vues/entete.php");
            include("vues/accueil.php");
            include("vues/pied.php");
        }
    
     /**
         * Affiche la liste des bouteilles d'un cellier
         *
         * ///////////TEMPORAIRE/////////////
         */
    	private function afficheCellier()
		{
			$bte = new Bouteille();
            $data = $bte->getListeBouteilleCellier();
			include("vues/entete.php");
			include("vues/cellier.php");
			include("vues/pied.php");
                  
		}
    
    
    //affiche le page accueil apres choisir le trier(par select box)
    private function uploadPage()
    {
        $bte = new Bouteille();
        include("vues/entete.php");
        $data = $bte->getListeBouteilleCellier($_GET['trierCellier']); 
        include("vues/cellier.php");
        include("vues/pied.php");

    }
    /**
     * Affiche la liste complète de l'inventaire des bouteilles listées
     *
     */
    /////////////AJOUTÉ FUNCTION POUR AFFICHER TOUT LES BOUTEILLES ICI//////////////







    /**
     * Affiche le liste de bouteille dans un cellier
     *
     * @param int $id id du cellier à afficher
     *
     * /////////DOIT ÊTRE MODIFIER POUR RÉCUPÉRER UN ID DE CELLIER////////
     */
    private function listeBouteilleCellier()
    {
        $bte = new Bouteille();
        $cellier = $bte->getListeBouteilleCellier();

        echo json_encode($cellier);

    }

     /**
     * 
     *
     * /////////DOIT ÊTRE DOCUMENTÉ ET TESTÉ////////
     */
    private function autocompleteBouteille()
    {
        $bte = new Bouteille();
        $body = json_decode(file_get_contents('php://input'));
        $listeBouteille = $bte->autocomplete($body->nom);

        echo json_encode($listeBouteille);

    }

    /**
     * 
     *
     * /////////DOIT ÊTRE DOIT ÊTRE DOCUMENTÉ ET TESTÉ////////
     */
    private function ajouterNouvelleBouteilleCellier()
    {
        $body = json_decode(file_get_contents('php://input'));
        if(!empty($body)){
            $bte = new Bouteille();
            $resultat = $bte->ajouterBouteilleCellier($body);
            echo json_encode($resultat);
        }
        else{
            include("vues/entete.php");
            include("vues/ajouter.php");
            include("vues/pied.php");
        }


    }       


    /**
     * Retirer une bouteille du cellier
     * ?? ajout d'une note dans historique pour les statistiques
     *
     */
    private function boireBouteilleCellier()
    {
        $body = json_decode(file_get_contents('php://input'));
        $bte = new Bouteille();
        //retire une bouteille du cellier et récupère la nouvelle quantité
        $resultat = $bte->modifierQuantiteBouteilleCellier($body->id, -1);
        $resultat = $bte->obtenirQuantiteBouteilleCellier($body->id);
        echo json_encode($resultat);
    }

    /**
     * Ajoute une bouteille du cellier
     * ?? ajout d'une note dans historique pour les statistiques
     *
     */
    private function ajouterBouteilleCellier()
    {
        $body = json_decode(file_get_contents('php://input'));

        $bte = new Bouteille();
        //ajoute une bouteille au cellier et récupère la nouvelle quantité
        $resultat = $bte->modifierQuantiteBouteilleCellier($body->id, 1);
        $resultat = $bte->obtenirQuantiteBouteilleCellier($body->id);
        echo json_encode($resultat);
    }

    /**
     * redirige vers le formulaire de modification d'une bouteille dans un cellier
     * ?? traitement du formulaire à venir .....
     *
     *  ///////////////////////////////NON COMPLETÉ//////////////////////////////////
     */
    /*
    private function modifierBouteilleCellier()
    {	
        //$body = json_decode(file_get_contents('php://input'));

        $bte = new Bouteille();
        $resultat['bouteille'] = $bte->getBouteille($_GET['id']);
        $resultat['pays'] = $bte->getPays();
        $resultat['type'] = $bte->getType();

        //echo json_encode($resultat);

        include("vues/entete.php");
        include("vues/formBout.php");
        include("vues/pied.php");

    }
    */

    /**
     * Affiche différentes pages concernant le login selon
     *  si l'utilisateur est connecté ou pas.
     *
     */
    private function compte()
    {
        //Si l'utilisateur est connecté
        if(isset($_SESSION["idUtilisateur"]) && $_SESSION["idUtilisateur"] != ""){
            //Afficher informations de l'utilisateur
            $monCpt = new Login();
            $donnees = $monCpt->getCompte($_SESSION["idUtilisateur"]);
            include("vues/entete.php");
            include("vues/monCompte.php");
            include("vues/pied.php");
        }
        else{
            //Afficher la page de login
            include("vues/entete.php");
            include("vues/login.php");
            include("vues/pied.php");
        }
    }

    /**
     * Vérifie l'authentification de l'utilisateur puis le redirige vers
     *  la page "monCompte.php" si l'authentification est acceptée. Dans le
     *  cas inverse, l'utilisateur reste sur la page de login.
     *
     */
    private function login()
    {
        $body = json_decode(file_get_contents('php://input'));
        if(!empty($body)){
            if($body->courrielCo == "" && $body->motPassCo == ""){
                echo json_encode(false);
            }
            else{
                $log = new Login();
                $correcteInfos = $log->authentification($body);

                echo json_encode($correcteInfos);

                //Création de la variable session lorsque la connexion réussie
                if($correcteInfos == true)
                {
                    $_SESSION["idUtilisateur"] = $body->courrielCo;
                }
            } 
        }
        else
        {
            $varreturn = false;
            echo json_encode($varreturn);
        }
        
    }

    /**
     * Ferme la session en cours afin de déconnecter l'utilisateur
     *  puis le redirige vers la page de connexion.
     *
     */
    private function deconnexion()
    {
        $_SESSION = array();

        if(isset($_COOKIE[session_name()]))
        {
            setcookie(session_name(), '', time() - 3600);
        }

        session_destroy();

        $msgConfirmation = "Votre session à bien été fermée.";
        include("vues/entete.php");
        include("vues/login.php");
        include("vues/pied.php");
    }

    /**
     * Redirige l'utilisateur vers la page d'inscription.
     *
     */
    private function inscription()
    {
        //Si l'utilisateur est connecté
        if(isset($_SESSION["idUtilisateur"]) && $_SESSION["idUtilisateur"] != "")
        {
            include("vues/entete.php");
            include("vues/monCompte.php");
            include("vues/pied.php");
        }
        else
        {
            include("vues/entete.php");
            include("vues/inscription.php");
            include("vues/pied.php");
        }
    }

    /**
     * Ferme la session en cours afin de déconnecter l'utilisateur
     *  puis le redirige vers la page de connexion.
     *
     */
    private function creerCompteUsager()
    {
        $body = json_decode(file_get_contents('php://input'));
        if(!empty($body)){
            $cpt = new Login();
            $ajoutFonctionel = $cpt->nouveauCompte($body);
            
            if($ajoutFonctionel == true){
                $return1 = true;
                echo json_encode($return1);
                $_SESSION["idUtilisateur"] = $body->courrielInscri;
            }
            else{
                $return1 = false;
                echo json_encode($return1);
            }
        }
        else
        {
            echo json_encode(false);   
        }
    }

/*
    /**
     * affiche le formulaire de modification et affectues les 
     *  modifications.
     *
    *//*
    private function modificationCompte()
    {  
        $body = json_decode(file_get_contents('php://input'));
        if(!empty($body)){

        }
    }

*/
}
?>















