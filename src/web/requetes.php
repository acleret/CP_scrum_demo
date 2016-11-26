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
            printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        return $result;
    }

    // retourne l'id du développeur dont le pseudo est $pseudo_dev
    // car comme les id, les pseudo sont uniques
    public function idDeveloppeur($pseudo_dev) {
        $sql = "SELECT DEV_id FROM developpeur
                WHERE DEV_pseudo = ".$pseudo_dev.";";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        return $result;
    }

    // retourne la liste des développeurs du site web
    public function listeDeveloppeurs() {
        $sql = "SELECT D.* FROM developpeur as D
                ORDER BY D.DEV_pseudo ASC;";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
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
            printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        return true;
    }

    // si le pseudo d'un développeur existe retourne vrai
    public function testPseudoDeveloppeur($pseudo) {
        $sql = "SELECT * FROM developpeur WHERE DEV_pseudo = '".$pseudo."';";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
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
            printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
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
            printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
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
            printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
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
            printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
            return NULL;
        } else {
            if (!$result = $this->conn->query($sql2)) {
                printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
                return NULL;
            } else {
                if (!$result = $this->conn->query($sql3)) {
                    printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
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
            printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
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
            printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
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
          printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
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
            printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
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
            printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
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
            printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
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
            printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        return $result->num_rows;
    }

    // retourne les infos d'un développeur ssi les pseudo et mot de passe correspondent
    public function testDeveloppeurConnexion($pseudo, $mdp) {
        $sql = "SELECT * FROM developpeur
                WHERE DEV_pseudo='".$pseudo."' AND DEV_mdp='".$mdp."';";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        return $result;
    }

    // retourne le plus grand des id de développeur
    public function maxIDDeveloppeur() {
        $sql = "SELECT MAX(DEV_id) FROM developpeur;";
        if (!$res = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
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
            printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
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
            printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
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
            printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        return $result;
    }

    // retourne le nombre de projets enregistrés sur le site web
    public function nombreProjets() {
        $sql = "SELECT * FROM projet";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
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
								printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
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
            printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
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
								printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
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
            printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
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
							if(!$this->suppressionTache($row_tache["Tache_id"]))
								return false;
						}
						if(!$this->suppressionUserStory($row_us["US_id"]))
								return false;
					}

					$liste_idUS_out = $this->listeUserStoryOutOfSprint($row_sprint["SPR_id"], $idPro);
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
          printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
          return NULL;
        }
				return true;
		}

    // retourne vrai après avoir retiré un projet, sinon faux
    public function suppressionProjet($id_pro){
        $sql = "DELETE FROM projet
                WHERE PRO_id=".$id_pro.";";
				if (!$result = $this->conn->query($sql)) {
					printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
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
            printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        return $result;
    }

    // retourne vrai si l'id du projet $id_pro existe déjà
    public function testIDProjet($id_pro) {
        $sql = "SELECT * FROM projet WHERE PRO_id = ".$id_pro.";";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
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
            printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        $row = $res->fetch_assoc();
        return $row["MAX(PRO_id)"];
    }


	/**********************************************/
  /** Fonctions pour la gestion des User Story **/
  /**********************************************/

    //retourne les données de l'us $id_us
    public function infosUserStory($id_us) {
        $sql = "SELECT * FROM us WHERE US_id = ".$id_us.";";
        if (!$res = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
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
            printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        return $result;
    }

    // retourne les US d'un sprint
    public function listeUserStorySprint($id_spr) {
        $sql = "SELECT * FROM us WHERE SPR_id = ".$id_spr." ORDER BY US_priorite;";
         if (!$result = $this->conn->query($sql)) {
            printf("Message d'erreur: %s<br>", $this->conn->error);
        }
        return $result;
    }

    // retourne les US du backlog qui ne sont pas dans le sprint
    public function listeUserStoryOutOfSprint($id_spr, $id_pro) {
        $sql = "SELECT * FROM us WHERE (SPR_id IS NULL && PRO_id = ".$id_pro.") ORDER BY US_priorite;";
         if (!$result = $this->conn->query($sql)) {
            printf("Message d'erreur: %s<br>", $this->conn->error);
        }
        return $result;
    }

    // ajout US
    public function ajoutUserStory($nom_us, $chiffrage, $priorite, $id_pro) {
        $sql = "INSERT INTO us (US_nom, US_chiffrageAbstrait, US_priorite, US_dateCreation, PRO_id)
                VALUES ('".$nom_us."', ".$chiffrage.", ".$priorite.", Now(), ".$id_pro.");";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        return true;
    }


    // modif US
    public function modifUserStory($id_us, $nom_us, $chiffrage, $id_pro) {
        $sql = "UPDATE us
                SET US_nom = '".$nom_us."', US_chiffrageAbstrait = ".$chiffrage.", PRO_id = ".$id_pro."
                WHERE US_id = ".$id_us.";";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        return true;
    }

    // modif US en tant que ProductOwner
    public function modifUserStoryProductOwner($id_us, $nom_us, $chiffrage, $priorite, $id_pro) {
        $sql = "UPDATE us
                SET US_nom = '".$nom_us."', US_chiffrageAbstrait = ".$chiffrage.", US_priorite = ".$priorite.", PRO_id = ".$id_pro."
                WHERE US_id = ".$id_us.";";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        return true;
    }

    public function modifUserStoryTracabilite($id_us, $date_commit, $id_commit, $auteur_commit) {
        $sql = "UPDATE us
        SET US_dateDernierCommit = '".$date_commit."', US_idDernierCommit = '".$id_commit."', US_auteurDernierCommit = '".$auteur_commit."'
        WHERE US_id=".$id_us.";";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
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
            printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
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
            printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        return true;
    }

    // suppr US
    public function suppressionUserStory($id_us) {
        $sql_us = "DELETE FROM us WHERE US_id = ".$id_us.";";
        $sql_tache = "DELETE FROM tache WHERE US_id = ".$id_us.";";
        if (!$result = $this->conn->query($sql_tache)) {
            printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
						return NULL;
        } else if (!$result = $this->conn->query($sql_us)) {
            printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
						return NULL;
        }
        return true;
    }


    // si l'id de l'us existe déjà retourne vrai
    public function testIDUserStory($id_us) {
        $sql = "SELECT * FROM us WHERE US_id = ".$id_us.";";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
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
            printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
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
            printf("Message d'erreur: %s<br>", $this->conn->error);
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
            printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
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
            printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
            return NULL;
        }
        return $result;
    }

    // si l'id du sprint existe déjà retourne vrai
    public function testIDSprint($id_spr) {
        $sql = "SELECT * FROM sprint WHERE SPR_id = ".$id_spr.";";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
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
            printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>", $this->conn->error);
            return NULL;
        }
        return true;
    }

   // retourne vrai après avoir retiré un sprint, sinon faux
    public function supprimerSprint($id_spr) {
        $sql_ret_us = "UPDATE us SET SPR_id = NULL WHERE SPR_id = ".$id_spr.";";
        $sql_ret_spr = "DELETE FROM sprint WHERE SPR_id = ".$id_spr.";";
        if (!$result = $this->conn->query($sql_ret_us)) {
            printf("<b style=\"color:red;\"><b style=\"color:red;\">Message d'erreur: %s</b><br>", $this->conn->error);
            return NULL;
        } else if (!$result = $this->conn->query($sql_ret_spr)) {
            printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>", $this->conn->error);
            return NULL;
        }
        return true;
    }

    // modifie les données du projet et retourne vrai quand c'est fait
    public function modifSprint($id_spr, $num, $date, $duree){
        $sql = "UPDATE sprint
                SET SPR_numero='".$num."', SPR_dateDebut='".$date."', SPR_duree='".$duree."'
                WHERE SPR_id=".$id_spr.";";
        if (!$result = $this->conn->query($sql)) {
            printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
            return NULL;
       }
        return true;
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
                printf("<b style=\"color:red;\">Message d'erreur: %s</b><br>\n", $this->conn->error);
                return NULL;
            }
            $row = $result->fetch_assoc();
            return $row["SPR_numero"];
        } else {
            return "";
        }
    }


  /******************************************/
  /** Fonctions pour la gestion des tâches **/
  /******************************************/

		// retourne vrai après avoir retiré une tâche, sinon faux
		public function suppressionTache($id_tache) {
			$sql = "DELETE FROM tache WHERE TAC_id = ".$id_tache.";";
			if (!$result = $this->conn->query($sql)) {
					printf("<b style=\"color:red;\">Message d'erreur: %s<br></b>", $this->conn->error);
					return NULL;
			}
			return true;
		}

}
?>
