<?php
class Requetes {

  private $conn;


  /******************************************************/
  /** Fonctions pour la connexion à la Base de Données **/
  /******************************************************/

    // crée la connexion
    public function __construct($servername, $username, $password, $dbname) {
        $this->conn = new mysqli($servername, $username, $password, $dbname);
    }

    // ferme la connexion
    public function __destruct() {
        $this->conn->close();
    }

    // vérifie la connexion
    public function verifConnexion() {
        return $this->conn->connect_error;
    }


  /************************************************/
  /** Fonctions pour la gestion des développeurs **/
  /************************************************/

    // retourne les données du développeur $id_dev
    public function infosDeveloppeur($id_dev) {
        $sql = "SELECT * FROM developpeur WHERE DEV_id = ".$id_dev.";";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans infosDeveloppeur(): %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        return $result;
    }

    // retourne l'id du développeur dont le pseudo est $pseudo_dev
    // car comme les id, les pseudo sont uniques
    public function idDeveloppeur($pseudo_dev) {
        $sql = "SELECT DEV_id FROM developpeur
                WHERE DEV_pseudo = '".$pseudo_dev."';";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans idDeveloppeur(): %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        return $result;
    }

    // retourne la liste des développeurs du site web
    public function listeDeveloppeurs() {
        $sql = "SELECT D.* FROM developpeur as D
                ORDER BY D.DEV_pseudo ASC;";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans listeDeveloppeurs(): %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        return $result;
    }

    // ajoute un développeur dans la BD (si le pseudo et le mail sont libres)
  // retourne vrai si il est ajouté
    public function ajoutNouveauDeveloppeur($prenom, $nom, $pseudo, $mdp, $mail, $url_avatar) {
        if ($this->testPseudoDeveloppeur($pseudo) || $this->testMailDeveloppeur($mail)) {
            return false;
        }
        $sql = "INSERT INTO developpeur (DEV_prenom, DEV_nom, DEV_pseudo, DEV_mdp, DEV_mail, DEV_urlAvatar, DEV_dateCreation)
                VALUES ('".$prenom."', '".$nom."', '".$pseudo."', '".$mdp."', '".$mail."', '".$url_avatar."', Now());";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans ajoutNouveauDeveloppeur(): %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        return true;
    }

    // si le pseudo d'un développeur existe retourne vrai
    public function testPseudoDeveloppeur($pseudo) {
        $sql = "SELECT * FROM developpeur WHERE DEV_pseudo = '".$pseudo."';";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans testPseudoDeveloppeur(): %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        $row = $result->fetch_assoc();
        if ($row["DEV_pseudo"] == $pseudo) {
            return true;
        }
        return false;
    }

    // si le mail d'un développeur existe retourne vrai
    public function testMailDeveloppeur($mail) {
        $sql = "SELECT * FROM developpeur WHERE DEV_mail = '".$mail."';";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans testMailDeveloppeur(): %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        $row = $result->fetch_assoc();
        if ($row["DEV_mail"] == $mail) {
            return true;
        }
        return false;
    }

    // modifie les données du développeur connecté
    // retourne vrai quand c'est exécuté
    public function modifDeveloppeur($id, $prenom, $nom, $pseudo, $url_avatar) {
        $sql = "UPDATE developpeur
                SET DEV_prenom='".$prenom."',DEV_nom='".$nom."',DEV_pseudo='".$pseudo."', DEV_urlAvatar='".$url_avatar."'
                WHERE DEV_id=".$id.";";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans modifDeveloppeur(): %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        return true;
    }

    // retourne vrai si le mot de passe de l'id a bien été mis à jour
    public function modifDeveloppeurMDP($id, $nouveauMotDePasse) {
        $sql="UPDATE developpeur
              SET DEV_mdp='$nouveauMotDePasse'
              WHERE DEV_id='$id'";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans modifDeveloppeurMDP(): %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        return true;
    }

    // retourne vrai si le compte du développeur connecté,
    // à l'identifiant $id_dev, est supprimé de la BDD
    public function supprDeveloppeur($id_dev) {
        $sql3="DELETE FROM developpeur
              WHERE DEV_id='$id_dev'";
        $sql2="DELETE FROM inter_dev_projet
              WHERE DEV_id='$id_dev'";
        $sql1="DELETE FROM projet
              WHERE DEV_idProductOwner='$id_dev'";
        /* TODO : Que faire quand il est le ScrumMaster */
        /* TODO : Que faire quand il est le responsable d'une tâche */
        if (!$result = $this->conn->query($sql1)) {
            printf("<b style=\"color:red;\">Message d'erreur dans supprDeveloppeur().1: %s</b><br>\n", $this->conn->error);
            return NULL;
        } else {
            if (!$result = $this->conn->query($sql2)) {
                printf("<b style=\"color:red;\">Message d'erreur dans supprDeveloppeur().2: %s</b><br>\n", $this->conn->error);
                return NULL;
            } else {
                if (!$result = $this->conn->query($sql3)) {
                    printf("<b style=\"color:red;\">Message d'erreur dans supprDeveloppeur().3: %s</b><br>\n", $this->conn->error);
                    return NULL;
                }
            }
            return true;
        }
    }

    // retourne vrai dès que la personne $id_dev est, au choix :
		//	po, sm, développeur du projet $id_projet
    public function estMembreProjet($id_projet, $id_dev) {
				return $this->estDeveloppeurProjet($id_projet, $id_dev) ||
							$this->estScrumMaster($id_dev, $id_projet) ||
							$this->estProductOwner($id_dev, $id_projet);
    }

		// retourne vrai si le membre $id_dev est développeur du projet $id_projet
    public function estDeveloppeurProjet($id_projet, $id_dev) {
        $sql = "SELECT * FROM inter_dev_projet
                WHERE PRO_id = ".$id_projet." AND DEV_id = ".$id_dev.";";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans estDeveloppeurProjet(): %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        $row = $result->fetch_assoc();
        if ($row["PRO_id"] == $id_projet && $row["DEV_id"] == $id_dev) {
            return true;
        }
        return false;
    }

    // retourne vrai si le développeur $id_dev est le Scrum Master du projet
    public function estScrumMaster($id_dev, $id_pro) {
        $sql = "SELECT * FROM projet
                WHERE PRO_id=".$id_pro." AND DEV_idScrumMaster=".$id_dev.";";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans estScrumMaster(): %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        if ($result->num_rows == 1)
            return true;
        else
            return false;
    }

    // retourne vrai si le développeur $id_dev est le Product Owner du projet
    public function estProductOwner($id_dev, $id_pro) {
      $sql = "SELECT * FROM projet
              WHERE PRO_id=".$id_pro." AND DEV_idProductOwner=".$id_dev.";";
      if (!$result = $this->conn->query($sql)) {
          printf("<b style=\"color:red;\">Message d'erreur dans estProductOwner(): %s</b><br>\n", $this->conn->error);
          return NULL;
      }
      if ($result->num_rows == 1)
          return true;
      else
          return false;
    }

  // si l'id d'un développeur existe retourne vrai
    public function testIDDeveloppeur($id_dev) {
        $sql = "SELECT * FROM developpeur WHERE DEV_id = ".$id_dev.";";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans testIDDeveloppeur(): %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        $row = $result->fetch_assoc();
        if ($row["DEV_id"] == $id_dev) {
            return true;
        }
        return false;
    }

    // retourne la liste des projets dont le développeur $id_dev est Product Owner
    public function listeProjetsDeveloppeurProductOwner($id_dev) {
        $sql = "SELECT * FROM projet AS P
                INNER JOIN inter_dev_projet AS IDP ON P.PRO_id = IDP.PRO_id
                WHERE IDP.DEV_id = ".$id_dev." AND DEV_idProductOwner = ".$id_dev."
                ORDER BY PRO_dateCreation ASC;";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans listeProjetsDeveloppeurProductOwner(): %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        return $result;
    }

    // retourne la liste des projets liés au développeur $id_dev
    public function listeProjetsDeveloppeur($id_dev) {
        $sql = "SELECT P.* FROM projet as P
                INNER JOIN inter_dev_projet AS IDP ON P.PRO_id = IDP.PRO_id
                WHERE IDP.DEV_id = ".$id_dev."
                ORDER BY P.PRO_dateCreation ASC;";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans listeProjetsDeveloppeur(): %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        return $result;
    }

    // retourne le nombre de projets liés au développeur $id_dev
    public function nombreProjetsDeveloppeur($id_dev) {
        $sql = "SELECT * FROM projet as P
                INNER JOIN inter_dev_projet AS IDP ON P.PRO_id = IDP.PRO_id
                WHERE IDP.DEV_id = ".$id_dev.";";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans nombreProjetsDeveloppeur(): %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        return $result->num_rows;
    }

    // retourne les infos d'un développeur ssi les pseudo et mot de passe correspondent
    public function testDeveloppeurConnexion($pseudo, $mdp) {
        $sql = "SELECT * FROM developpeur
                WHERE DEV_pseudo='".$pseudo."' AND DEV_mdp='".$mdp."';";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans testDeveloppeurConnexion(): %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        return $result;
    }

    // retourne le plus grand des id de développeur
    public function maxIDDeveloppeur() {
        $sql = "SELECT MAX(DEV_id) FROM developpeur;";
        if (!$res = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans maxIDDeveloppeur(): %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        $row = $res->fetch_assoc();
        return $row["MAX(DEV_id)"];
    }

    // retourne le pseudo du développeur
    public function pseudoDeveloppeur($id_dev) {
        $sql = "SELECT DEV_pseudo FROM developpeur
								WHERE DEV_id ='".$id_dev."';";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans pseudoDeveloppeur(): %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        return $result;
    }


  /*******************************************/
  /** Fonctions pour la gestion des projets **/
  /*******************************************/

    // retourne les données du projet $id_pro
    public function infosProjet($id_pro) {
        $sql = "SELECT * FROM projet WHERE PRO_id = ".$id_pro.";";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans infosProjet(): %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        return $result;
    }

    // retourne la liste de tous les projets enregistrés sur le site web
    // les paramètres sont optionnels car mis en place pour la pagination
    public function listeProjets($id_premiere_ligne = 0, $nb_projets_par_pages = 200000) {
        $sql = "SELECT * FROM projet
                ORDER BY PRO_dateCreation ASC
                LIMIT ".$id_premiere_ligne.", ".$nb_projets_par_pages.";";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans listeProjets(): %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        return $result;
    }

    // retourne le nombre de projets enregistrés sur le site web
    public function nombreProjets() {
        $sql = "SELECT * FROM projet";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans nombreProjets(): %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        return $result->num_rows;
    }

		// démarche pour ajouter un projet dans la base de données
		// retourne vrai ssi :
		//  - le projet est rajouté
		//  - le lien entre membres et projet sont créés
		public function ajouterProjetBDD(){ // Les arguments ne sont pas définis car la piste d'un po ou d'un sm null est envisageable : $nom, $client, $description, $idPO, $idSM, $idDevs
				$numargs = func_num_args();
				$arg_list = func_get_args();

				if ($numargs == 6) {
					$idProjet = $this->ajoutNouveauProjet($arg_list[0], $arg_list[1], $arg_list[2], $arg_list[3], $arg_list[4]);

					if ($idProjet==0)
						return false;

					foreach($arg_list[5] as $valeur_idDev) {
						if (!$this->estDeveloppeurProjet($idProjet, $valeur_idDev)) {
							$sql = "INSERT INTO inter_dev_projet (DEV_id, PRO_id)
											VALUES ('".$valeur_idDev."', '".$idProjet."');";
							if (!$result = $this->conn->query($sql)) {
								printf("<b style=\"color:red;\">Message d'erreur dans ajouterProjetBDD(): %s</b><br>\n", $this->conn->error);
								return NULL;
							}
						}
					}
					return $idProjet;
				}
				return false;
		}

    // ajoute un objet de type projet et retourne vrai si il est ajouté
    // sa date de création est celle du jour où il est enregistré
    public function ajoutNouveauProjet($nom, $client, $description, $idPO, $idSM){
        $sql = "INSERT INTO projet (PRO_nom, PRO_client, PRO_description, 		PRO_dateCreation, DEV_idProductOwner, DEV_idScrumMaster)
                VALUES ('".$nom."', '".$client."', '".$description."', Now(), '".$idPO."', '".$idSM."');";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans ajoutNouveauProjet(): %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        return $this->conn->insert_id;
    }

    // démarche pour modifier les informations concernant un projet dans la base de données
		// retourne vrai ssi :
		// 	- les infos sont toutes mises à jour
		//  - les mises à jour des membres sont effectuées
    public function modifierProjetBDD(){ // Les arguments ne sont pas définis car la piste d'un po ou d'un sm null est envisageable : $id_pro, $nom, $client, $description, $idPO, $idSM, $idDevs
				$numargs = func_num_args();
				$arg_list = func_get_args();

				if ($numargs == 7) {
					$idProjet = $this->modifProjet($arg_list[0], $arg_list[1], $arg_list[2], $arg_list[3], $arg_list[4], $arg_list[5]);

					if (!$idProjet)
						return false;

					// retrait des liens développeur-projet
					$actuelsdevsProjet = $this->listeDeveloppeursProjet($arg_list[0]);
					while ($row_dev = $actuelsdevsProjet->fetch_assoc()) {
						$this->suppressionDeveloppeurProjet($row_dev['DEV_id'], $arg_list[0]);
					}

					// rajout des liens développeur-projet dans la table 'inter_dev_projet'
					foreach($arg_list[6] as $valeur_idDev) {
							$sql = "INSERT INTO inter_dev_projet (DEV_id, PRO_id)
											VALUES ('".$valeur_idDev."', '".$arg_list[0]."');";
							if (!$result = $this->conn->query($sql)) {
								printf("<b style=\"color:red;\">Message d'erreur dans modifierProjetBDD(): %s</b><br>\n", $this->conn->error);
								return NULL;
							}
					}
					return true;
				}
				return false;
    }

    // modifie les données du projet $id_pro dans la table 'projet'
		// retourne vrai quand c'est fait
    public function modifProjet($id_pro, $nom, $client, $description, $idPO, $idSM){
        $sql = "UPDATE projet
                SET PRO_nom='".$nom."', PRO_client='".$client."', PRO_description='".$description."', DEV_idProductOwner='".$idPO."', DEV_idScrumMaster='".$idSM."'
                WHERE PRO_id=".$id_pro.";";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans modifProjet(): %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        return true;
    }

    // démarche pour supprimer un projet de la base de données 'cp_scrum'
		// retourne vrai ssi :
		//  - les liens entre le projet, les sprints, les us et les tâches sont supprimées
		//  - les liens entre le projet et les développeurs sont supprimés
		//  - le projet est supprimé
		public function supprimerProjetBDD($idPro){
				$liste_idSprints = $this->listeSprints($idPro);
				while ($row_sprint = $liste_idSprints->fetch_assoc()) {

					$liste_idUS = $this->listeUserStorySprint($row_sprint["SPR_id"]);
					while ($row_us = $liste_idUS->fetch_assoc()) {
						$liste_idTaches = $this->listeTachesUS($row_us["US_id"]);
						while ($row_tache = $liste_idTaches->fetch_assoc()) {
							if(!$this->suppressionTache($row_tache["TAC_id"]))
								return false;
						}
						if(!$this->suppressionUserStory($row_us["US_id"]))
								return false;
					}

					$liste_idUS_out = $this->listeUserStoryOutOfSprints($idPro);
					while ($row_us_out = $liste_idUS_out->fetch_assoc()) {
						$liste_idTaches_out = $this->listeTachesUS($row_us_out["US_id"]);
						while ($row_tache_out = $liste_idTaches_out->fetch_assoc()) {
							if(!$this->suppressionTache($row_tache_out["TAC_id"]))
								return false;
						}
						if(!$this->suppressionUserStory($row_us_out["US_id"]))
								return false;
					}

					if(!$this->supprimerSprint($row_sprint["SPR_id"]))
						return false;
				}

				$liste_idDevs = $this->listeDeveloppeursProjet($idPro);
				while ($row_dev = $liste_idDevs->fetch_assoc()) {
					if (!$this->suppressionDeveloppeurProjet($row_dev["DEV_id"], $idPro))
						return false;
				}

				if (!$this->suppressionProjet($idPro))
					return false;

        return true;
		}

		// retourne vrai après suppression du lien développeur-projet dans la
		// table 'inter_dev_projet'
		public function suppressionDeveloppeurProjet($id_dev, $id_pro){
				$sql = "DELETE FROM inter_dev_projet
								WHERE DEV_id=".$id_dev." AND PRO_id=".$id_pro.";";
        if (!$result = $this->conn->query($sql)) {
          printf("<b style=\"color:red;\">Message d'erreur dans suppressionDeveloppeurProjet(): %s</b><br>\n", $this->conn->error);
          return NULL;
        }
				return true;
		}

    // retourne vrai après avoir retiré un projet, sinon faux
    public function suppressionProjet($id_pro){
        $sql = "DELETE FROM projet
                WHERE PRO_id=".$id_pro.";";
				if (!$result = $this->conn->query($sql)) {
					printf("<b style=\"color:red;\">Message d'erreur dans suppressionProjet(): %s</b><br>\n", $this->conn->error);
					return NULL;
				}
				return true;
    }

    // retourne la liste des développeurs du projet $id_pro
    public function listeDeveloppeursProjet($id_pro) {
        $sql = "SELECT D.* FROM developpeur as D
                INNER JOIN inter_dev_projet AS IDP ON D.DEV_id = IDP.DEV_id
                WHERE IDP.PRO_id = ".$id_pro."
                ORDER BY D.DEV_pseudo ASC;";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans listeDeveloppeursProjet(): %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        return $result;
    }

    // retourne vrai si l'id du projet $id_pro existe déjà
    public function testIDProjet($id_pro) {
        $sql = "SELECT * FROM projet WHERE PRO_id = ".$id_pro.";";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans testIDProjet(): %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        $row = $result->fetch_assoc();
        if ($row["PRO_id"] == $id_pro) {
            return true;
        }
        return false;
    }

    // retourne le plus grand des id de projet
    public function maxIDProjet() {
        $sql = "SELECT MAX(PRO_id) FROM projet;";
        if (!$res = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans maxIDProjet(): %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        $row = $res->fetch_assoc();
        return $row["MAX(PRO_id)"];
    }


	/************************************************/
  /** Fonctions pour la gestion des User Stories **/
  /************************************************/

    //retourne les données de l'us $id_us
    public function infosUserStory($id_us) {
        $sql = "SELECT * FROM us WHERE US_id = ".$id_us.";";
        if (!$res = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans infosUserStory(): %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        return $res;
    }

    // retourne la liste des us du projet $id_pro
    public function listeUserStories($id_pro, $id_premiere_ligne = 0, $nb_projets_par_pages = 200000) {
        $sql = "SELECT * FROM us
                WHERE PRO_id = ".$id_pro."
                ORDER BY US_id ASC
                LIMIT ".$id_premiere_ligne.", ".$nb_projets_par_pages.";";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans listeUserStories(): %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        return $result;
    }

    // retourne les US du backlog qui ne sont pas dans un sprint
    public function listeUserStoryOutOfSprints($id_pro) {
        $sql = "SELECT * FROM us WHERE (SPR_id IS NULL && PRO_id = ".$id_pro.") ORDER BY US_priorite;";
         if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans listeUserStoryOutOfSprints(): %s</b><br>\n", $this->conn->error);
        }
        return $result;
    }

    // ajout US
    public function ajoutUserStory($numero_us, $nom_us, $chiffrage, $priorite, $id_pro) {
        $sql = "INSERT INTO us (US_numero, US_nom, US_chiffrageAbstrait, US_priorite, US_dateCreation, PRO_id)
                VALUES (".$numero_us.", '".$nom_us."', ".$chiffrage.", ".$priorite.", Now(), ".$id_pro.");";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans ajoutUserStory(): %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        return true;
    }


    // modif US
    public function modifUserStory($id_us, $numero_us, $nom_us, $chiffrage) {
        $sql = "UPDATE us
                SET US_numero = ".$numero_us.", US_nom = '".$nom_us."', US_chiffrageAbstrait = ".$chiffrage."
                WHERE US_id = ".$id_us.";";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans modifUserStory(): %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        return true;
    }

    // modif US en tant que ProductOwner
    public function modifUserStoryProductOwner($id_us, $numero_us, $nom_us, $chiffrage, $priorite) {
        $sql = "UPDATE us
                SET US_numero = ".$numero_us.", US_nom = '".$nom_us."', US_chiffrageAbstrait = ".$chiffrage.", US_priorite = ".$priorite."
                WHERE US_id = ".$id_us.";";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans modifUserStoryProductOwner(): %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        return true;
    }

    public function modifUserStoryTracabilite($id_us, $date_commit, $id_commit, $auteur_commit) {
        $sql = "UPDATE us
        SET US_dateDernierCommit = '".$date_commit."', US_idDernierCommit = '".$id_commit."', US_auteurDernierCommit = '".$auteur_commit."'
        WHERE US_id=".$id_us.";";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans modifUserStoryTracabilite(): %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        return true;
    }

    // affecter US à un sprint
    public function affecterUserStorySprint($id_us, $id_sprint) {
        $sql = "UPDATE us
        SET SPR_id = ".$id_sprint."
        WHERE US_id=".$id_us.";";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans affecterUserStorySprint(): %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        return true;
    }

    // retirer US à un sprint
    public function retirerUserStorySprint($id_us) {
        $sql = "UPDATE us
        SET SPR_id = NULL
        WHERE US_id=".$id_us.";";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans retirerUserStorySprint(): %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        return true;
    }

    // suppr US
    public function suppressionUserStory($id_us) {
        $sql_us = "DELETE FROM us WHERE US_id = ".$id_us.";";
        $sql_tache = "DELETE FROM tache WHERE US_id = ".$id_us.";";
        if (!$result = $this->conn->query($sql_tache)) {
            printf("<b style=\"color:red;\">Message d'erreur dans suppressionUserStory().1: %s</b><br>\n", $this->conn->error);
						return NULL;
        } else if (!$result = $this->conn->query($sql_us)) {
            printf("<b style=\"color:red;\">Message d'erreur dans suppressionUserStory().2: %s</b><br>\n", $this->conn->error);
						return NULL;
        }
        return true;
    }


    // si l'id de l'us existe déjà retourne vrai
    public function testIDUserStory($id_us) {
        $sql = "SELECT * FROM us WHERE US_id = ".$id_us.";";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans testIDUserStory(): %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        $row = $result->fetch_assoc();
        if ($row["US_id"] == $id_us) {
            return true;
        }
        return false;
    }

    // retourne le plus grand des id de us
    public function maxIDUserStory() {
        $sql = "SELECT MAX(US_id) FROM us;";
        if (!$res = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans maxIDUserStory(): %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        $row = $res->fetch_assoc();
        return $row["MAX(US_id)"];
    }

    // retourne les tâches par US
    public function listeTachesUS($id_us) {
        $sql = "SELECT * FROM tache WHERE US_id = ".$id_us."
								ORDER BY TAC_dateDepart;";
         if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans listeTachesUS(): %s</b><br>\n", $this->conn->error);
						return NULL;
        }
        return $result;
    }

    // retourne les tâches par US et par état
    public function listeTachesUSEtat($id_us, $etat) {
        $sql = "SELECT * FROM tache
								WHERE US_id = ".$id_us." AND TAC_etat = '".$etat."';";
         if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans listeTachesUSEtat(): %s</b><br>\n", $this->conn->error);
						return NULL;
        }
        return $result;
    }

    // retourne la somme du backlog d'un projet
    public function sommeChiffrageBacklog($id_pro) {
        $sql = "SELECT SUM(US_chiffrageAbstrait) FROM us
                WHERE PRO_id = ".$id_pro.";";
        if (!$res = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans sommeChiffrageBacklog(): %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        $row = $res->fetch_assoc();
        return (empty($row["SUM(US_chiffrageAbstrait)"])) ? 0 : $row["SUM(US_chiffrageAbstrait)"];
    }

    // retourne la somme du chiffrage d'un sprint
    public function sommeChiffrageSprint($id_sprint) {
        $sql = "SELECT SUM(US_chiffrageAbstrait) FROM us
                WHERE SPR_id = ".$id_sprint.";";
        if (!$res = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans sommeChiffrageSprint(): %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        $row = $res->fetch_assoc();
        return (empty($row["SUM(US_chiffrageAbstrait)"])) ? 0 : $row["SUM(US_chiffrageAbstrait)"];
    }

    // retourne la liste des US commitées
    public function listeUserStoriesAvecCommit($id_pro) {
        $sql = "SELECT * FROM us
                WHERE PRO_id = ".$id_pro." AND US_idDernierCommit is not NULL;";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans listeUserStoriesAvecCommit(): %s</b><br>\n", $this->conn->error);
						return NULL;
       }
       return $result;
    }

    // retourne la liste des US d'un sprint qui n'ont pas été commitées
    public function listeUserStoriesSprintSansCommit($id_spr, $id_pro) {
        $sql = "SELECT * FROM us
                WHERE SPR_id = ".$id_spr." AND PRO_id = ".$id_pro." AND US_idDernierCommit is NULL;";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans listeUserStoriesSprintSansCommit(): %s</b><br>\n", $this->conn->error);
						return NULL;
       }
       return $result;
    }


	/*******************************************/
  /** Fonctions pour la gestion des sprints **/
  /*******************************************/

    //retourne les données du sprint $id_spr
    public function infosSprint($id_spr) {
        $sql = "SELECT * FROM sprint WHERE SPR_id = ".$id_spr.";";
        if (!$res = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans infosSprint(): %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        return $res;
    }

    // retourne la liste des sprints du projet $id_pro
    public function listeSprints($id_pro) {
        $sql = "SELECT * FROM sprint
                WHERE PRO_id = ".$id_pro."
                ORDER BY SPR_numero ASC;";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans listeSprints(): %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        return $result;
    }

    // si l'id du sprint existe déjà retourne vrai
    public function testIDSprint($id_spr) {
        $sql = "SELECT * FROM sprint WHERE SPR_id = ".$id_spr.";";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans testIDSprint(): %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        $row = $result->fetch_assoc();
        if ($row["SPR_id"] == $id_spr) {
            return true;
        }
        return false;
    }

   // retourne vrai après avoir ajouté un sprint, sinon faux
    public function ajoutSprint($numero, $dateDebut, $duree, $id_pro) {
        $sql = "INSERT INTO sprint (SPR_numero, SPR_dateDebut, SPR_duree, PRO_id)
                VALUES ('".$numero."', '".$dateDebut."', '".$duree."', '".$id_pro."');";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans ajoutSprint(): %s</b><br>", $this->conn->error);
            return NULL;
        }
        return true;
    }

    // modifie les données du sprint et retourne vrai quand c'est fait
    public function modifSprint($id_spr, $num, $date, $duree){
        $sql = "UPDATE sprint
                SET SPR_numero='".$num."', SPR_dateDebut='".$date."', SPR_duree='".$duree."'
                WHERE SPR_id=".$id_spr.";";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans modifSprint(): %s</b><br>\n", $this->conn->error);
            return NULL;
       }
        return true;
    }

   // retourne vrai après avoir retiré un sprint, sinon faux
    public function supprimerSprint($id_spr) {
        $sql_ret_us = "UPDATE us SET SPR_id = NULL WHERE SPR_id = ".$id_spr.";";
        $sql_ret_bdc = "DELETE FROM burndown_chart WHERE SPR_id = ".$id_spr.";";
        $sql_ret_spr = "DELETE FROM sprint WHERE SPR_id = ".$id_spr.";";
        if (!$result = $this->conn->query($sql_ret_us)) {
            printf("<b style=\"color:red;\">Message d'erreur dans supprimerSprint().1: %s</b><br>", $this->conn->error);
            return NULL;
        } else if (!$result = $this->conn->query($sql_ret_bdc)) {
            printf("<b style=\"color:red;\">Message d'erreur dans supprimerSprint().2: %s</b><br>", $this->conn->error);
            return NULL;
        } else if (!$result = $this->conn->query($sql_ret_spr)) {
            printf("<b style=\"color:red;\">Message d'erreur dans supprimerSprint().3: %s</b><br>", $this->conn->error);
            return NULL;
        }
        return true;
    }

    // retourne les US d'un sprint
    public function listeUserStorySprint($id_spr) {
        $sql = "SELECT * FROM us WHERE SPR_id = ".$id_spr." ORDER BY US_priorite;";
         if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans listeUserStorySprint(): %s</b><br>\n", $this->conn->error);
        }
        return $result;
    }

    // ordonne une date : 2000-10-01 -> 01/10/00
    public function ordonnerDate($date) {
        if (empty($date))
            return NULL;
        return $date[8].$date[9]."/".$date[5].$date[6]."/".$date[2].$date[3] ;
    }

    // retourne le numéro du sprint à partir de son id
    public function numeroSprint($id_spr) {
        if (!empty($id_spr)) {
            $sql = "SELECT SPR_numero FROM sprint WHERE SPR_id = ".$id_spr.";";
            if (!$result = $this->conn->query($sql)) {
                printf("<b style=\"color:red;\">Message d'erreur dans numeroSprint(): %s</b><br>\n", $this->conn->error);
                return NULL;
            }
            $row = $result->fetch_assoc();
            return $row["SPR_numero"];
        } else {
            return "";
        }
    }

    // retourne le plus grand des id de sprint
    public function maxIDSprint() {
        $sql = "SELECT MAX(SPR_id) FROM sprint;";
        if (!$res = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans maxIDSprint(): %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        $row = $res->fetch_assoc();
        return $row["MAX(SPR_id)"];
    }


  /******************************************/
  /** Fonctions pour la gestion des tâches **/
  /******************************************/

    //retourne les données de la tâche $id_tac
    public function infosTache($id_tac) {
        $sql = "SELECT * FROM tache WHERE TAC_id = ".$id_tac.";";
        if (!$res = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans infosTache(): %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        return $res;
    }

    // retourne les tâches du sprint $id_spr qui ont pour état $etat
		public function listeTachesEtatSprint($id_spr, $etat) {
				$sql = "SELECT *	FROM tache
								WHERE US_id IN (
									SELECT US_id FROM us
									WHERE SPR_id = ".$id_spr."
									ORDER BY `US_id` ASC
								)	AND TAC_etat = \"".$etat."\"
								ORDER BY US_id ASC;";
        if (!$res = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans listeTachesEtatSprint(): %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        return $res;
		}

    // retourne les tâches de l'us $id_us regroupées selon leur état
		public function listeTachesUSEtats($id_us) {
				//SET SESSION group_concat_max_len = 1000000;
				$sql = "SELECT  TAC_etat,
											 GROUP_CONCAT(
													DISTINCT CONCAT(
														CAST(TAC_id AS CHAR), \"|\", 
														CAST(TAC_numero AS CHAR), \"|\", 
														TAC_nom, \"|\", 
														TAC_etat, \"|\", 
														TAC_description, \"|\",
														TAC_nbJours, \"|\",
														CAST(TAC_dateDepart AS CHAR), \"|\", 
														CAST(DEV_id AS CHAR), \"|\", 
														CAST(US_id AS CHAR)
													)
													ORDER BY TAC_numero ASC
													SEPARATOR \";\"
												) AS MesTaches
								FROM tache WHERE US_id = ".$id_us."
								GROUP BY TAC_etat;";
        if (!$res = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans listeTachesUSEtats(): %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        return $res;
		}

		// retourne vrai après avoir ajouté une tâche, sinon faux
    public function ajoutTache($numero, $nom, $description, $nbJours, $dateDepart, $id_dev, $id_us) {
				$etat = "TO DO";
        $sql = "INSERT INTO tache (TAC_numero, TAC_nom, TAC_description, TAC_nbJours, TAC_dateDepart, TAC_etat, DEV_id, US_id)
                VALUES ('".$numero."', '".$nom."', '".$description."', '".$nbJours."', '".$dateDepart."', '".$etat."', '".$id_dev."', '".$id_us."');";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans ajoutTache(): %s</b><br>", $this->conn->error);
            return NULL;
        }
        return true;
    }
		
		// retourne vrai si le numero de tâche $numero_tac est non présente dans les tâches du sprint $id_spr, sinon faux
		public function estNumeroTache($id_spr, $numero_tac) {
        $listeUS = $this->listeUserStorySprint($id_spr);
				while($row_us = $listeUS->fetch_assoc()) {
					$listeTaches = $this->listeTachesUS($row_us["US_id"]);
					while($row_tache = $listeTaches->fetch_assoc()) {
						if($numero_tac == $row_tache["TAC_numero"]) {
							return false;
						}
					}
				}
        return true;		
		}
		// retourne vrai après avoir retiré une tâche, sinon faux
		public function suppressionTache($id_tac) {
			$sql = "DELETE FROM tache WHERE TAC_id = ".$id_tac.";";
			if (!$result = $this->conn->query($sql)) {
					printf("<b style=\"color:red;\">Message d'erreur dans suppressionTache(): %s<br></b>", $this->conn->error);
					return NULL;
			}
			return true;
		}

    // modifie les données de la tâche $id_tac et retourne vrai quand c'est fait
    public function modifTache($id_tac, $nom, $description, $nbJours, $dateDepart, $etat, $id_dev, $id_us){
        $sql = "UPDATE tache
                SET TAC_nom='".$nom."', TAC_description='".$description."', TAC_nbJours='".$nbJours."',	TAC_dateDepart='".$dateDepart."', TAC_etat='".$etat."', DEV_id='".$id_dev."', US_id='".$id_us."'
                WHERE TAC_id=".$id_tac.";";
				if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans modifTache(): %s</b><br>\n", $this->conn->error);
            return NULL;
       }
        return true;
    }

		// modifie l'état de la tâche $id_tac et retourne vrai quand c'est fait
    public function modifEtatTache($id_tac, $etat){
        $sql = "UPDATE tache
                SET TAC_etat='".$etat."'
                WHERE TAC_id=".$id_tac.";";
				if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans modifEtatTache(): %s</b><br>\n", $this->conn->error);
            return NULL;
       }
        return true;
    }

		// modifie l'id de l'us à laquelle appartient la tâche $id_tac et retourne vrai quand c'est fait
    public function modifUSTache($id_tac, $id_us){
        $sql = "UPDATE tache
                SET US_id='".$id_us."'
                WHERE TAC_id=".$id_tac.";";
				if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans modifUSTache(): %s</b><br>\n", $this->conn->error);
            return NULL;
       }
        return true;
    }

    // retourne le plus grand des id de la table tache
    public function maxIDTache() {
        $sql = "SELECT MAX(TAC_id) FROM tache;";
        if (!$res = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans maxIDTache(): %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        $row = $res->fetch_assoc();
        return $row["MAX(TAC_id)"];
    }

    // retourne vrai si l'id de la tâche existe déjà
    public function testIDTache($id_tac) {
        $sql = "SELECT * FROM tache WHERE TAC_id = ".$id_tac.";";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans testIDTache(): %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        $row = $result->fetch_assoc();
        if ($row["TAC_id"] == $id_tac) {
            return true;
        }
        return false;
    }

    /*************************************************/
    /** Fonctions pour la gestion du Burndown Chart **/
    /*************************************************/

    public function listeChiffragePlanifie($id_pro) {
        $sql = "SELECT * FROM burndown_chart
                WHERE PRO_id = ".$id_pro.";";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans listeChiffragePlanifie(): %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        return $result;
    }

    public function listeChiffrageReel($id_pro) {
        $result = $this->listeSprints($id_pro);
        while ($row = $result->fetch_assoc()) {
            $id_spr = $row['SPR_id'];
            if($this->listeUserStoriesSprintSansCommit($id_spr, $id_pro)->num_rows == 0) {
                $tab[$id_spr] = $this->sommeChiffrageSprint($id_spr);
            }
        }
        return $tab;
    }

    public function modifChiffragePlanifie($id_pro) {
        $sql = "DELETE FROM burndown_chart WHERE PRO_id = ".$id_pro.";";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur dans modifChiffragePlanifie().1: %s<br></b>", $this->conn->error);
            return NULL;
        }
        $liste_sprints = $this->listeSprints($id_pro);
        while ($row = $liste_sprints->fetch_assoc()) {
            $sql = "INSERT INTO burndown_chart (BDC_chargePlanifie, SPR_id, PRO_id)
                    VALUES (".$this->sommeChiffrageSprint($row['SPR_id']).", ".$row['SPR_id'].", ".$id_pro.");";
            if (!$result = $this->conn->query($sql)) {
                printf("<b style=\"color:red;\">Message d'erreur dans modifChiffragePlanifie().2: %s</b><br>", $this->conn->error);
                return NULL;
            }
        }
        return true;
    }

    public function sommeChiffragePlanifie($id_pro) {
      $sql = "SELECT SUM(BDC_chargePlanifie) FROM burndown_chart
              WHERE PRO_id = ".$id_pro.";";
      if (!$res = $this->conn->query($sql)) {
          printf("<b style=\"color:red;\">Message d'erreur dans sommeChiffragePlanifie(): %s</b><br>\n", $this->conn->error);
          return NULL;
      }
      $row = $res->fetch_assoc();
      return (empty($row["SUM(BDC_chargePlanifie)"])) ? 0 : $row["SUM(BDC_chargePlanifie)"];
    }

}
?>