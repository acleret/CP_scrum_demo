<?php
require_once("config.php");

$s->suppressionCookies();

if (isset($_POST["modif_profil"])) {
    if (empty($_POST["url"]))
        $_SESSION["image_co"] = NULL;
    else
        $_SESSION["image_co"] = $_POST["url"];
    $db->modifDeveloppeur($_POST["id_dev"], $_POST["prenom"], $_POST["nom"], $_POST["pseudo_dev"], $_POST["url"]);
}

if (isset($_POST["suppr_profil"])) {
	$res = $db->supprDeveloppeur($_POST["suppr_profil"]);
	if($res) header("Location: deconnexion.php");
	exit();
}

if (isset($_GET["profil"])) { // un visiteur peut voir n'importe quel profil
	$pseudo_demande = $_GET["profil"];

	$id = $db->idDeveloppeur($pseudo_demande); // grâce aux pseudo uniques
	$row_id = $id->fetch_assoc();
	$id_dev = $row_id["DEV_id"];

	$infos = $db->infosDeveloppeur($id_dev);
	$row = $infos->fetch_assoc();

	$s->head("Profil - ".$pseudo_demande);
} else if (isset($_SESSION["session"])) { // le dev connecté peut agir sur son profil
	$id_dev = $_SESSION["id_co"];
	$infos = $db->infosDeveloppeur($id_dev);
	$row = $infos->fetch_assoc();

	$s->head("Mon profil");
} else {
	header("Location: index.php");
	exit();
}

$s->header($db);
$s->nav($db);
?>
	      <article>
            <div class="col-sm-8 text-left">
			  <h2>
<?php if (isset($_GET["profil"]) && isset($_SESSION["session"])
					&& $_GET["profil"]==$_SESSION["pseudo_co"]) {	?>
			Mon profil '<?php echo $row["DEV_pseudo"];?>'
<?php } else if (isset($_GET["profil"])) {	?>
			Profil de '<?php echo $row["DEV_pseudo"];?>'
<?php } else if (isset($_SESSION["session"])) { ?>
			Mon profil '<?php echo $row["DEV_pseudo"];?>'
<?php } ?>
			  </h2>
			  <hr>
<?php
if (isset($_POST["modif_profil"])) {
?>
              <div class="alert alert-success alert-dismissible">
                <a href="" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>OK!</strong> Modification du profil effectuée avec succès !
              </div>
<?php
}
if(isset($_POST['modif_mdp'])){
    $amdp=$_POST['amdp'];
    $nmdp=$_POST['nmdp'];
    $vmdp=$_POST['vmdp'];

    if ($amdp==$_SESSION['mdp_co']){
        if($nmdp==$vmdp){
            if ($db->modifDeveloppeurMDP($_SESSION['id_co'], $nmdp)){
?>
              <div class="alert alert-success alert-dismissible">
                <a href="" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>OK!</strong> Modification du mot de passe effectuée avec succès !
              </div>
<?php
                $_SESSION['mdp_co']=$nmdp;
            } else {
?>
              <div class="alert alert-danger alert-dismissible">
                <a href="" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Erreur!</strong> Modification de votre mot de passe échouée. Veuillez contacter la maintenance.

              </div>
<?php
            }
        } else {
?>
             <div class="alert alert-danger alert-dismissible">
                <a href="" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Erreur!</strong> Problème de confirmation du nouveau mot de passe.
              </div>
<?php
        }
    } else {
?>
              <div class="alert alert-danger alert-dismissible">
                <a href="" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Erreur!</strong> Le mot de passe actuel est incorrect.
              </div>
<?php
    }
}

/* if (isset($_GET["url"])) { */
/*     if (!strcmp($_GET["url"], "OK")) { */
/*         echo "<p>le profil a bien été modifié</p><br>\n"; */
/*     } */
/* } */
?>
			    <div class="paragraphs">
				  <div class="row">
				    <div class="col-sm-3">
<?php
    if (!empty($row["DEV_urlAvatar"])) {
?>
					  <img class="pull-left .img-fluid" src="<?php echo $row["DEV_urlAvatar"]; ?>" alt="Avatar" height="164" width="164" class="img-rounded"/>
<?php
    } else {
?>
					  <img class="pull-left .img-fluid" src="../web/img/avatar-default.jpg" alt="Avatar" height="164" width="164" class="img-rounded"/>
<?php
    }
?>
				    </div>
				    <div class="col-sm-5" style="padding:2%;">
					  <dl class="dl-horizontal" style="float:left;">
					    <dt>Nom :</dt>
					    <dd><?php echo $row["DEV_nom"];?></dd>
					    <dt>Prénom : </dt>
					    <dd><?php echo $row["DEV_prenom"];?></dd>
					    <dt>Mail : </dt>
					    <dd><?php echo $row["DEV_mail"];?></dd>
					  </dl>
					  <br>
					  <br>
<?php
if ((isset($_SESSION["session"]) && empty($_GET["profil"]))
    || ((isset($_GET["profil"])
        && isset($_SESSION["session"])
        && $_GET["profil"]==$_SESSION["pseudo_co"]))) {
?>
					  <div action="" method="post">
					    <input style="display: inline; margin-left:25%;" class="btn btn-link dl-horizontal" data-toggle="modal" data-target="#modalModifMDP" type="submit" value="Changer mon mot de passe"/>
            		    <div id="modalModifMDP" class="modal fade" role="dialog">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <form style="display: inline;" action="" method="post">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                                  <h4 class="modal-title">Changer mon mot de passe</h4>
                                </div>
                                <div class="modal-body">
			                      <div class="form-group">
                                    <label>Mot de passe actuel</label>
				                    <input class="form-control" type="password" name="amdp" placeholder="Mot de passe actuel" required/>
			                      </div>
						          <div class="form-group">
								    <label>Nouveau mot de passe</label>
                                    <input class="form-control" type="password" name="nmdp" placeholder="Nouveau mot de passe" required />
							      </div>
						          <div class="form-group">
								    <label>Confirmer mot de passe</label>
                                    <input class="form-control" type="password" name="vmdp" placeholder="Confirmer mot de passe" required />
							      </div>
						        </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
			                      <input class="btn btn-primary" name="modif_mdp" type="submit" value="Modifier"/>
                                </div>
                              </form>
                            </div>
					      </div>
                        </div>
                      </div>
<?php 				} ?>
				    </div>
			  	  </div>
			    </div>
			    <hr>
			    <div class="col-sm-4 text-left">
			      <h3>Projets :</h3>
			      <h4>(en tant que Product Owner)</h4>
			      <div class="scrollProfil">
			  	    <ul>
<?php
    $projets = $db->listeProjetsDeveloppeurProductOwner($id_dev);
    while ($projet = $projets->fetch_assoc()) {
?>
				      <li>
					    <form style="display: inline;" action="setProjet.php" method="post">
					      <input type="hidden" name="id_projet" value="<?php echo $projet["PRO_id"]; ?>"/>
					      <input class="btn btn-link" type="submit" value="<?php echo $projet["PRO_nom"]; ?>"/>
					    </form><br>Créé le <?php echo $projet["PRO_dateCreation"]; ?> pour <?php echo $projet["PRO_client"]; ?><br>
                      </li>
<?php
    }
?>
				    </ul>
				  </div>
			    </div>
			    <div class="col-sm-4 text-left">
			      <h3>Projets :</h3>
			      <h4>(en tant que Scrum Master)</h4>
			      <div class="scrollProfil">
			   	    <ul>
<?php
    $projets = $db->listeProjetsDeveloppeur($id_dev);
    while ($projet = $projets->fetch_assoc()) {
        if ($db->estScrumMaster($id_dev, $projet["PRO_id"])) {
?>
				      <li>
					    <form style="display: inline;" action="setProjet.php" method="post">
					      <input type="hidden" name="id_projet" value="<?php echo $projet["PRO_id"]; ?>"/>
					      <input class="btn btn-link" type="submit" value="<?php echo $projet["PRO_nom"]; ?>"/>
					    </form><br>Créé le <?php echo $projet["PRO_dateCreation"]; ?> pour <?php echo $projet["PRO_client"]; ?><br>
                      </li>
<?php
						}
					}
?>
				    </ul>
				  </div>
			    </div>
			    <div class="col-sm-4">
			      <h3>Collaborations :</h3>
			      <h4>(en tant que développeur)</h4>
			      <div class="scrollProfil">
				    <ul>
<?php
    $projets = $db->listeProjetsDeveloppeur($id_dev);
    while ($projet = $projets->fetch_assoc()) {
?>
				      <li>
					    <form style="display: inline;" action="setProjet.php" method="post">
						  <input type="hidden" name="id_projet" value="<?php echo $projet["PRO_id"]; ?>"/>
						  <input class="btn btn-link" type="submit" value="<?php echo $projet["PRO_nom"]; ?>"/>
					    </form><br>Créé le <?php echo $projet["PRO_dateCreation"]; ?> pour <?php echo $projet["PRO_client"]; ?><br>
                      </li>
<?php
    }
?>
				    </ul>
				  </div>
                  <br>
			    </div>
			  </div>
		    </article>
<?php
    if ((isset($_SESSION["session"]) && empty($_GET["profil"]))
		 ||	((isset($_GET["profil"])
             && isset($_SESSION["session"])
			 && $_GET["profil"]==$_SESSION["pseudo_co"]))) {
?>
		  <aside>
			<div class="col-sm-2 sidenav">
			  <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modalModifProfil">Modifier</button>
			    <div id="modalModifProfil" class="modal fade text-left" role="dialog">
			      <div class="modal-dialog">
				    <div class="modal-content">
                      <form style="display: inline;" action="" method="post">
				        <div class="modal-header">
				          <button type="button" class="close" data-dismiss="modal">&times;</button>
				          <h4 class="modal-title">Modifier mon profil</h4>
				        </div>
				        <div class="modal-body">
			              <div class="form-group">
                            <label>Prénom</label>
				            <input class="form-control" type="text" name="prenom" value="<?php echo $row["DEV_prenom"]; ?>" required/>
			              </div>
						  <div class="form-group">
						    <label>Nom</label>
                            <input class="form-control" type="text" name="nom" value="<?php echo $row["DEV_nom"]; ?>" required />
						  </div>
						  <div class="form-group">
						    <label>URL avatar</label>
                            <input class="form-control" type="url" name="url" placeholder="URL image avatar" value="<?php echo $row["DEV_urlAvatar"]; ?>" />
						  </div>
				        </div>
				        <div class="modal-footer">
				          <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
			              <input type="hidden" name="id_dev" value="<?php echo $id_dev; ?>"/>
			              <input type="hidden" name="pseudo_dev" value="<?php echo $row["DEV_pseudo"]; ?>"/>
	                      <input class="btn btn-primary" name="modif_profil" type="submit" value="Modifier"/>
				        </div>
                      </form>
                    </div>
			      </div>
			    </div>
			  </form>
			  <br>
              <br>
              <!-- <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#supprimerModal">Supprimer</button>-->
              <!-- Modal Suppression -->
              <div style="text-align: left" id="supprimerModal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                  <!-- Modal content-->
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Confirmation de suppression du profil</h4>
                    </div>
                    <div class="modal-body">
                      <p>Attention action irréversible</p>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                      <form style="display: inline;" action="" method="post">
        			    <input type="hidden" name="suppr_profil" value="<?php echo $_SESSION["id_co"]; ?>"/>
        			    <input class="btn btn-danger" type="submit" value="Supprimer"/>
        		  	  </form>
                    </div>
                  </div>
                </div>
              </div>
		    </div>
		  </aside>
<?php
    }
	$s->footer();
  exit();
?>
