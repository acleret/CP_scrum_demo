<?php
require_once("../web/config.php");

if (!isset($_POST["id_sprint"]) && isset($_COOKIE["id_sprint"])) {
	$id_sprint = $_COOKIE["id_sprint"];
} else if (isset($_POST["id_sprint"])) {
    $id_sprint = $_POST["id_sprint"];
} else {
	header("Location: ../web/index.php");
    exit();
}
 
$infos_sprint = $db->infosSprint($id_sprint);
$row_sprint = $infos_sprint->fetch_assoc();
$num_sprint = $row_sprint["SPR_numero"];
	
if (isset($_COOKIE["id_projet"])) {
	$id_pro = $_COOKIE["id_projet"];
    $infos_pro = $db->infosProjet($id_pro);
    $row_pro = $infos_pro->fetch_assoc();

    $s->head($row_pro['PRO_nom']." - Kanban");
    $s->header($db);
    $s->nav($db);
?>
	<article>
    <div class="col-sm-8 text-left">
    <h2><?php echo $row_pro["PRO_nom"];?> - Kanban du Sprint n°<?php echo $num_sprint; ?></h2>
    <h5><a href="#suivi">Suivi des tâches</a> | <!--<a href="#interdépendance">Interdépendance des tâches</a> |--> <a href="#liste">Liste détaillée des tâches par user story</a></h5>
    <hr>
    <h3 id="suivi">Suivi des tâches</h3>
    <h4>Informations utiles</h4>
    <p style="font-style:italic;">
    Survolez les tâches pour avoir quelques informations supplémentaires dessus. <br>
<?php  if (isset($_SESSION["session"])) { ?>
    Pour modifier les informations d'une tâche, veuillez cliquer dessus. <br>
				Vous pouvez déplacer une tâche sur sa ligne pour seulement mettre à jour son état (méthode du "drag and drop").
<?php  } ?>
			</p>

<?php
  if (isset($_GET["ajout"])) {
    if (!strcmp($_GET["ajout"], "OK")) {
?>
			<div class="alert alert-success alert-dismissible">
				<a href="" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>OK!</strong> Tâche ajoutée.
			</div>
<?php
    } else if (!strcmp($_GET["ajout"], "erreurNumTache")) {
?>
			<div class="alert alert-danger alert-dismissible">
				<a href="" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>Erreur!</strong> L'ajout de la tâche a échoué car le numéro de la tâche est déjà pris par une autre dans ce sprint.
    </div>
<?php
} else if (!strcmp($_GET["ajout"], "erreur")) {
?>
    <div class="alert alert-danger alert-dismissible">
    <a href="" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Erreur!</strong> L'ajout de la tâche a échoué.
			</div>
<?php
    }
  }
	if (isset($_GET["modif"])) {
    if (!strcmp($_GET["modif"], "OK")) {
?>
			<div class="alert alert-success alert-dismissible">
				<a href="" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>OK!</strong> Tâche modifiée.
			</div>
<?php
    } else if (!strcmp($_GET["modif"], "erreur")) {
?>
			<div class="alert alert-danger alert-dismissible">
				<a href="" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>Erreur!</strong> La modification de la tâche a échoué.
			</div>
<?php
    } else if (!strcmp($_GET["modif"], "erreurID")) {
?>
			<div class="alert alert-danger alert-dismissible">
				<a href="" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>Erreur!</strong> La tâche n'existe pas.
    </div>
<?php
} else if (!strcmp($_GET["modif"], "pasConnecte")) {
?>
    <div class="alert alert-danger alert-dismissible">
    <a href="" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Erreur!</strong> N'étant pas connecté vous n'avez pas les droits pour modifier ce kanban.
    </div>
<?php
} else if (!strcmp($_GET["modif"], "pasMembre")) {
?>
    <div class="alert alert-danger alert-dismissible">
    <a href="" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Erreur!</strong> N'étant pas membre du projet vous n'avez pas les droits pour modifier ce kanban.
    </div>
<?php
}
}
if (isset($_GET["modifEtat"])) {
    if (!strcmp($_GET["modifEtat"], "OK")) {
?>
        <div class="alert alert-success alert-dismissible">
        <a href="" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>OK!</strong> État de la tâche mis à jour.
        </div>
<?php
    }
}
if (isset($_GET["suppr"])) {
    if (!strcmp($_GET["suppr"], "OK")) {
?>
        <div class="alert alert-success alert-dismissible">
        <a href="" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>OK!</strong> Tâche supprimée.
        </div>
<?php
    } else if (!strcmp($_GET["suppr"], "erreur")) {
?>
        <div class="alert alert-danger alert-dismissible">
        <a href="" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>Erreur!</strong> La supression de la tâche a échoué.
        </div>
<?php
    } else if (!strcmp($_GET["suppr"], "erreurID")) {
?>
        <div class="alert alert-danger alert-dismissible">
        <a href="" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>Erreur!</strong> Cette tâche n'existe pas.
			</div>
<?php
    }
  }
?>
			<table id="tableTaches" class="table table-striped table-hover">
				<thead>
					<tr>
						<th>US</th>
<?php			$etats = array("TO DO", "ON GOING", "TO TEST", "DONE");
					foreach ($etats as $etat) {
?>
						<th style="text-align:center;"><?php echo $etat; ?></th>
<?php			}
?>
<?php
				if (isset($_SESSION["session"])) {
					if ($db->estMembreProjet($row_pro["PRO_id"], $_SESSION["id_co"])) {
?>
						<th style="text-align:center;">Actions</th>
<?php
					}
				}
?>
						</tr>
					</thead>
					<tbody>
<?php
			$listeUS = $db->listeUserStorySprint($id_sprint);
			while ($row_us = $listeUS->fetch_assoc()) {
        $id_us = $row_us['US_id'];
?>
						<tr>
							<td><a href="#detailUS<?php echo $row_us["US_numero"] ?>">US#<?php echo ($row_us["US_numero"] < 10) ? '0'.$row_us["US_numero"] : $row_us["US_numero"]; ?></a></td>
<?php
				$result = $db->listeTachesUSEtats($id_us);
				$varHtmlToDo = null;
				$varHtmlOnGoing = null;
				$varHtmlToTest = null;
				$varHtmlDone = null;
				while ($row_etat = $result->fetch_assoc()) { // une ligne = un état
					switch ($row_etat["TAC_etat"]) {
						case "TO DO":
							$varHtmlToDo = "<td class=\"dropzone\" id=\"$id_us-".$row_etat["TAC_etat"]."\">";
							$lesTaches = explode(";", $row_etat["MesTaches"]);
							foreach($lesTaches as $key => $tacheInfo) {
								$infosTrouveesTache = explode("|", $tacheInfo);

								$id_tache = $infosTrouveesTache[0];
								$num_tache = $infosTrouveesTache[1];
								$nom_tache = $infosTrouveesTache[2];
								$etat_tache = $infosTrouveesTache[3];
								$dateDepart_tache = $infosTrouveesTache[6];
								$idResponsable_tache = $infosTrouveesTache[7];
								$infosResponsable_tache = $db->infosDeveloppeur($idResponsable_tache);
								$pseudoResponsable_tache = $infosResponsable_tache->fetch_assoc()['DEV_pseudo'];

								$varHtmlToDo .= "<div class=\"link tooltip-link modifiable draggable\" id=\"tacheDraggableUS$id_us$id_tache\" data-toggle=\"tooltip\" data-placement=\"bottom\" data-original-title=\"Nom de la tâche: $nom_tache - Responsable: $pseudoResponsable_tache - Début: $dateDepart_tache - Etat : $etat_tache \" draggable=\"true\" style=\"text-align:center;\">[Tache#$num_tache]<span id=\"TODO-$id_tache\" style=\"display:none;\">$tacheInfo</span></div>\n";
?>
								<!-- Modal Etat -->
								<div id="modifierEtatModal<?php echo $id_us."-"; echo $id_tache; ?>" class="modal fade" role="dialog">
									<div class="modal-dialog">
										<!-- Modal content-->
										<div class="modal-content">
											<form style="display: inline;" action="../web/modificationTache.php" method="post">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal">&times;</button>
													<h4 class="modal-title">Confirmation du changement d'état de la tâche <?php echo $num_tache; ?></h4>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="document.location.reload(false)">Annuler</button>
        <input type="hidden" name="etat_tache" value=""/>
        <input type="hidden" name="id_tache" value="<?php echo $id_tache; ?>"/>
        <input class="btn btn-primary" type="submit" value="Continuer"/>
        </div>
        </form>
        </div>
        </div>
        </div>
        <!-- Modal Modification -->
             <div id="modifierModal<?php echo $id_us."-".$id_tache; ?>" class="modal fade" role="dialog">
        <div class="modal-dialog">
        <!-- Modal content-->
             <div class="modal-content">
        <form style="display: inline;" action="../web/modificationTache.php" onsubmit="return verifForm(this);" method="post">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modification des informations d'une tâche</h4>
												</div>
												<div class="modal-body">
													<div class="form-group">
														<label for="nom">Nom</label>
														<input type="text" name="nom" class="form-control" id="nom" placeholder="Nom" />
													</div>
													<div class="form-group">
														<label for="etat">Etat</label>
														<input type="text" name="etat" class="form-control" id="etat" size="7" placeholder="Etat" onblur="verifEtat(this);" />
													</div>
													<div class="form-group">
														<label for="description">Description</label>
														<textarea name="description" class="form-control" rows="3" id="description" placeholder="Description..." ></textarea>
													</div>
													<div class="form-group">
														<label for="nbJours">Nombre de jours</label>
														<input type="number" name="nbJours" class="form-control" id="nbJours" placeholder="Nombre de jours" />
													</div>
													<div class="form-group">
														<label for="dateDebut">Date de début</label>
														<input type="date" name="dateDepart" class="form-control" id="dateDepart" placeholder="Date de début" />
													</div>
													<div class="form-group" >
														<label for="responsable">Pseudo du responsable</label>
														<select class="form-control" name="responsable" id="responsable">
						<?php 					$listeDeveloppeurs = $db->listeDeveloppeurs(); 
														while ($row_dev = $listeDeveloppeurs->fetch_assoc()) {							
						?>
															<option value="<?php echo $row_dev["DEV_id"]; ?>"><?php echo $row_dev["DEV_pseudo"]; ?></option>
						<?php 					}		?>
														</select>
													</div>											
													<div class="form-group" >
														<label for="idUS_tache">Rattacher la tâche à une autre user story :</label>
														
														<select class="form-control" name="us" id="us">
						<?php 
														$liste_us = $db->listeUserStorySprint($id_sprint);
														while ($row_us = $liste_us->fetch_assoc()) {
															$infosUS = $db->infosTache($row_us["US_id"]);
						?>
															<option value="<?php echo $row_us["US_id"]; ?>"><?php echo $row_us["US_numero"]." : ".$row_us["US_nom"]; ?></option>
						<?php 					}		?>
														</select>
													</div>											
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
													<input type="hidden" name="id_tache" value=""/>
													<input class="btn btn-primary" type="submit" value="Valider"/>
												</div>
											</form>
										</div>
									</div>
								</div>
								<!-- end ModifierModal -->
<?php
							}
							$varHtmlToDo .= "</td>";
							break;
						case "ON GOING":
							$varHtmlOnGoing = "<td class=\"dropzone\" id=\"$id_us-".$row_etat["TAC_etat"]."\">";
							$lesTaches = explode(";", $row_etat["MesTaches"]);
							foreach($lesTaches as $key => $tacheInfo) {
								$infosTrouveesTache = explode("|", $tacheInfo);

								$id_tache = $infosTrouveesTache[0];
								$num_tache = $infosTrouveesTache[1];
								$nom_tache = $infosTrouveesTache[2];
								$etat_tache = $infosTrouveesTache[3];
								$dateDepart_tache = $infosTrouveesTache[6];
								$idResponsable_tache = $infosTrouveesTache[7];
								$infosResponsable_tache = $db->infosDeveloppeur($idResponsable_tache);
								$pseudoResponsable_tache = $infosResponsable_tache->fetch_assoc()['DEV_pseudo'];

								$varHtmlOnGoing .= "<div class=\"link tooltip-link modifiable draggable\" id=\"tacheDraggableUS$id_us$id_tache\" data-toggle=\"tooltip\" data-placement=\"bottom\" data-original-title=\"Nom de la tâche: $nom_tache - Responsable : $pseudoResponsable_tache - Début : $dateDepart_tache - Etat : $etat_tache \" draggable=\"true\" style=\"text-align:center;\">[Tache#$num_tache]<span id=\"ONGOING-$id_tache\" style=\"display:none;\">$tacheInfo</span></div>\n";
?>
								<!-- Modal Etat -->
								<div id="modifierEtatModal<?php echo $id_us."-"; echo $id_tache; ?>" class="modal fade" role="dialog">
									<div class="modal-dialog">
										<!-- Modal content-->
										<div class="modal-content">
											<form style="display: inline;" action="../web/modificationTache.php" method="post">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal">&times;</button>
													<h4 class="modal-title">Confirmation du changement d'état de la tâche <?php echo $num_tache; ?></h4>
        </div>
        <div class="modal-body">
												
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="document.location.reload(false)">Annuler</button>
        <input type="hidden" name="etat_tache" value=""/>
        <input type="hidden" name="id_tache" value="<?php echo $id_tache; ?>"/>
        <input class="btn btn-primary" type="submit" value="Continuer"/>
        </div>
        </form>
        </div>
        </div>
        </div>
        <!-- Modal Modification -->
             <div id="modifierModal<?php echo $id_us."-".$id_tache; ?>" class="modal fade" role="dialog">
        <div class="modal-dialog">
        <!-- Modal content-->
             <div class="modal-content">
        <form style="display: inline;" action="../web/modificationTache.php" onsubmit="return verifForm(this);" method="post">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modification des informations d'une tâche</h4>
												</div>
												<div class="modal-body">
													<div class="form-group">
														<label for="nom">Nom</label>
														<input type="text" name="nom" class="form-control" id="nom" placeholder="Nom" />
													</div>
													<div class="form-group">
														<label for="etat">Etat</label>
														<input type="text" name="etat" class="form-control" id="etat" size="7" placeholder="Etat" onblur="verifEtat(this);" />
													</div>
													<div class="form-group">
														<label for="description">Description</label>
														<textarea name="description" class="form-control" rows="3" id="description" placeholder="Description..." ></textarea>
													</div>
													<div class="form-group">
														<label for="nbJours">Nombre de jours</label>
														<input type="number" name="nbJours" class="form-control" id="nbJours" placeholder="Nombre de jours" />
													</div>
													<div class="form-group">
														<label for="dateDebut">Date de début</label>
														<input type="date" name="dateDepart" class="form-control" id="dateDepart" placeholder="Date de début" />
													</div>
													<div class="form-group" >
														<label for="responsable">Pseudo du responsable</label>
														<select class="form-control" name="responsable" id="responsable">
						<?php 					$listeDeveloppeurs = $db->listeDeveloppeurs(); 
														while ($row_dev = $listeDeveloppeurs->fetch_assoc()) {							
						?>
															<option value="<?php echo $row_dev["DEV_id"]; ?>"><?php echo $row_dev["DEV_pseudo"]; ?></option>
						<?php 					}		?>
														</select>
													</div>											
													<div class="form-group" >
														<label for="idUS_tache">Rattacher la tâche à une autre user story :</label>
														
														<select class="form-control" name="us" id="us">
						<?php 
														$liste_us = $db->listeUserStorySprint($id_sprint);
														while ($row_us = $liste_us->fetch_assoc()) {
															$infosUS = $db->infosTache($row_us["US_id"]);
						?>
															<option value="<?php echo $row_us["US_id"]; ?>"><?php echo $row_us["US_numero"]." : ".$row_us["US_nom"]; ?></option>
						<?php 					}		?>
														</select>
													</div>											
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
													<input type="hidden" name="id_tache" value=""/>
													<input class="btn btn-primary" type="submit" value="Valider"/>
												</div>
											</form>
										</div>
									</div>
								</div>
								<!-- end ModifierModal -->

<?php						}
							$varHtmlOnGoing .= "</td>";
							break;
						case "TO TEST":
							$varHtmlToTest = "<td class=\"dropzone\" id=\"$id_us-".$row_etat["TAC_etat"]."\">";
							$lesTaches = explode(";", $row_etat["MesTaches"]);
							foreach($lesTaches as $key => $tacheInfo) {
								$infosTrouveesTache = explode("|", $tacheInfo);

								$id_tache = $infosTrouveesTache[0];
								$num_tache = $infosTrouveesTache[1];
								$nom_tache = $infosTrouveesTache[2];
								$etat_tache = $infosTrouveesTache[3];
								$dateDepart_tache = $infosTrouveesTache[6];
								$idResponsable_tache = $infosTrouveesTache[7];
								$infosResponsable_tache = $db->infosDeveloppeur($idResponsable_tache);
								$pseudoResponsable_tache = $infosResponsable_tache->fetch_assoc()['DEV_pseudo'];

								$varHtmlToTest .= "<div class=\"link tooltip-link modifiable draggable\" id=\"tacheDraggableUS$id_us$id_tache\" data-toggle=\"tooltip\" data-placement=\"bottom\" data-original-title=\"Nom de la tâche: $nom_tache - Responsable: $pseudoResponsable_tache - Début: $dateDepart_tache - Etat : $etat_tache \" draggable=\"true\" style=\"text-align:center;\">[Tache#$num_tache]<span id=\"TOTEST-$id_tache\" style=\"display:none;\">$tacheInfo</span></div>\n";
	?>
								<!-- Modal Etat -->
								<div id="modifierEtatModal<?php echo $id_us."-"; echo $id_tache; ?>" class="modal fade" role="dialog">
									<div class="modal-dialog">
										<!-- Modal content-->
										<div class="modal-content">
											<form style="display: inline;" action="../web/modificationTache.php" method="post">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal">&times;</button>
													<h4 class="modal-title">Confirmation du changement d'état de la tâche <?php echo $num_tache; ?></h4>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="document.location.reload(false)">Annuler</button>
        <input type="hidden" name="etat_tache" value=""/>
        <input type="hidden" name="id_tache" value="<?php echo $id_tache; ?>"/>
        <input class="btn btn-primary" type="submit" value="Continuer"/>
        </div>
        </form>
        </div>
        </div>
        </div>
        <!-- Modal Modification -->
             <div id="modifierModal<?php echo $id_us."-".$id_tache; ?>" class="modal fade" role="dialog">
        <div class="modal-dialog">
        <!-- Modal content-->
             <div class="modal-content">
        <form style="display: inline;" action="../web/modificationTache.php" onsubmit="return verifForm(this);" method="post">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modification des informations d'une tâche</h4>
												</div>
												<div class="modal-body">
													<div class="form-group">
														<label for="nom">Nom</label>
														<input type="text" name="nom" class="form-control" id="nom" placeholder="Nom" />
													</div>
													<div class="form-group">
														<label for="etat">Etat</label>
														<input type="text" name="etat" class="form-control" id="etat" size="7" placeholder="Etat" onblur="verifEtat(this);" />
													</div>
													<div class="form-group">
														<label for="description">Description</label>
														<textarea name="description" class="form-control" rows="3" id="description" placeholder="Description..." ></textarea>
													</div>
													<div class="form-group">
														<label for="nbJours">Nombre de jours</label>
														<input type="number" name="nbJours" class="form-control" id="nbJours" placeholder="Nombre de jours" />
													</div>
													<div class="form-group">
														<label for="dateDebut">Date de début</label>
														<input type="date" name="dateDepart" class="form-control" id="dateDepart" placeholder="Date de début" />
													</div>
													<div class="form-group" >
														<label for="responsable">Pseudo du responsable</label>
														<select class="form-control" name="responsable" id="responsable">
						<?php 					$listeDeveloppeurs = $db->listeDeveloppeurs(); 
														while ($row_dev = $listeDeveloppeurs->fetch_assoc()) {							
						?>
															<option value="<?php echo $row_dev["DEV_id"]; ?>"><?php echo $row_dev["DEV_pseudo"]; ?></option>
						<?php 					}		?>
														</select>
													</div>											
													<div class="form-group" >
														<label for="idUS_tache">Rattacher la tâche à une autre user story :</label>
														
														<select class="form-control" name="us" id="us">
						<?php 
														$liste_us = $db->listeUserStorySprint($id_sprint);
														while ($row_us = $liste_us->fetch_assoc()) {
															$infosUS = $db->infosTache($row_us["US_id"]);
						?>
															<option value="<?php echo $row_us["US_id"]; ?>"><?php echo $row_us["US_numero"]." : ".$row_us["US_nom"]; ?></option>
						<?php 					}		?>
														</select>
													</div>											
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
													<input type="hidden" name="id_tache" value=""/>
													<input class="btn btn-primary" type="submit" value="Valider"/>
												</div>
											</form>
										</div>
									</div>
								</div>
								<!-- end ModifierModal -->
<?php					}
							$varHtmlToTest .= "</td>";
							break;
						case "DONE":
							$varHtmlDone = "<td class=\"dropzone\" id=\"$id_us-".$row_etat["TAC_etat"]."\">";
							$lesTaches = explode(";", $row_etat["MesTaches"]);
							foreach($lesTaches as $key => $tacheInfo) {
								$infosTrouveesTache = explode("|", $tacheInfo);

								$id_tache = $infosTrouveesTache[0];
								$num_tache = $infosTrouveesTache[1];
								$nom_tache = $infosTrouveesTache[2];
								$etat_tache = $infosTrouveesTache[3];
								$dateDepart_tache = $infosTrouveesTache[6];
								$idResponsable_tache = $infosTrouveesTache[7];
								$infosResponsable_tache = $db->infosDeveloppeur($idResponsable_tache);
								$pseudoResponsable_tache = $infosResponsable_tache->fetch_assoc()['DEV_pseudo'];

								$varHtmlDone .= "<div class=\"link tooltip-link modifiable draggable\" id=\"tacheDraggableUS$id_us$id_tache\" data-toggle=\"tooltip\" data-placement=\"bottom\" data-original-title=\"Nom de la tâche: $nom_tache - Responsable: $pseudoResponsable_tache - Début: $dateDepart_tache - Etat : $etat_tache \" draggable=\"true\" style=\"text-align:center;\">[Tache#$num_tache]<span id=\"DONE-$id_tache\" style=\"display:none;\">$tacheInfo</span></div>\n";
?>
								<!-- Modal Etat -->
								<div id="modifierEtatModal<?php echo $id_us."-"; echo $id_tache; ?>" class="modal fade" role="dialog">
									<div class="modal-dialog">
										<!-- Modal content-->
										<div class="modal-content">
											<form style="display: inline;" action="../web/modificationTache.php" method="post">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal">&times;</button>
													<h4 class="modal-title">Confirmation du changement d'état de la tâche <?php echo $num_tache; ?></h4>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="document.location.reload(false)">Annuler</button>
        <input type="hidden" name="etat_tache" value=""/>
        <input type="hidden" name="id_tache" value="<?php echo $id_tache; ?>"/>
        <input class="btn btn-primary" type="submit" value="Continuer"/>
        </div>
        </form>
        </div>
        </div>
        </div>
        <!-- Modal Modification -->
             <div id="modifierModal<?php echo $id_us."-".$id_tache; ?>" class="modal fade" role="dialog">
        <div class="modal-dialog">
        <!-- Modal content-->
             <div class="modal-content">
        <form style="display: inline;" action="../web/modificationTache.php" onsubmit="return verifForm(this);" method="post">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modification des informations d'une tâche</h4>
												</div>
												<div class="modal-body">
													<div class="form-group">
														<label for="nom">Nom</label>
														<input type="text" name="nom" class="form-control" id="nom" placeholder="Nom" />
													</div>
													<div class="form-group">
														<label for="etat">Etat</label>
														<input type="text" name="etat" class="form-control" id="etat" size="7" placeholder="Etat" onblur="verifEtat(this);" />
													</div>
													<div class="form-group">
														<label for="description">Description</label>
														<textarea name="description" class="form-control" rows="3" id="description" placeholder="Description..." ></textarea>
													</div>
													<div class="form-group">
														<label for="nbJours">Nombre de jours</label>
														<input type="number" name="nbJours" class="form-control" id="nbJours" placeholder="Nombre de jours" />
													</div>
													<div class="form-group">
														<label for="dateDebut">Date de début</label>
														<input type="date" name="dateDepart" class="form-control" id="dateDepart" placeholder="Date de début" />
													</div>
													<div class="form-group" >
														<label for="responsable">Pseudo du responsable</label>
														<select class="form-control" name="responsable" id="responsable">
						<?php 					$listeDeveloppeurs = $db->listeDeveloppeurs(); 
														while ($row_dev = $listeDeveloppeurs->fetch_assoc()) {							
						?>
															<option value="<?php echo $row_dev["DEV_id"]; ?>"><?php echo $row_dev["DEV_pseudo"]; ?></option>
						<?php 					}		?>
														</select>
													</div>											
													<div class="form-group" >
														<label for="idUS_tache">Rattacher la tâche à une autre user story :</label>
														
														<select class="form-control" name="us" id="us">
						<?php 
														$liste_us = $db->listeUserStorySprint($id_sprint);
														while ($row_us = $liste_us->fetch_assoc()) {
															$infosUS = $db->infosTache($row_us["US_id"]);
						?>
															<option value="<?php echo $row_us["US_id"]; ?>"><?php echo $row_us["US_numero"]." : ".$row_us["US_nom"]; ?></option>
						<?php 					}		?>
														</select>
													</div>											
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
													<input type="hidden" name="id_tache" value=""/>
													<input class="btn btn-primary" type="submit" value="Valider"/>
												</div>
											</form>
										</div>
									</div>
								</div>
								<!-- end ModifierModal -->

<?php					}
							$varHtmlDone .= "</td>";
							break;
					}
				}
				if ($varHtmlToDo == null) $varHtmlToDo = "<td class=\"dropzone\" id=\"$id_us-TO DO\"></td>";
				if ($varHtmlOnGoing == null) $varHtmlOnGoing = "<td class=\"dropzone\" id=\"$id_us-ON GOING\"></td>";
				if ($varHtmlToTest == null) $varHtmlToTest = "<td class=\"dropzone\"  id=\"$id_us-TO TEST\"></td>";
				if ($varHtmlDone == null) $varHtmlDone = "<td class=\"dropzone\"  id=\"$id_us-DONE\"></td>";

				echo $varHtmlToDo.$varHtmlOnGoing.$varHtmlToTest.$varHtmlDone;

				if (isset($_SESSION["session"])) {
					if ($db->estMembreProjet($id_pro, $_SESSION["id_co"])) {
?>
						<td>
							<button type="button" class="btn btn-default" data-toggle="modal" data-target="#ajouterModal<?php echo $id_us; ?>">Ajouter une tâche</button>
<?php        if($db->listeTachesUS($id_us)->num_rows > 0) { ?>
							<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#supprimerModal<?php echo $id_us; ?>">Supprimer une tâche</button>
<?php				} ?>
						</td>
						<!-- Modal Ajout -->
						<div id="ajouterModal<?php echo $id_us; ?>" class="modal fade" role="dialog">
							<div class="modal-dialog">
								<!-- Modal content-->
								<div class="modal-content">
									<form style="display: inline;" action="../web/ajoutTache.php" method="post">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal">&times;</button>
											<h4 class="modal-title">Ajout d'une tâche dans l'US#<?php echo $row_us['US_numero']; ?></h4>
										</div>
										<div class="modal-body">
											<div class="form-group" title="Trouvez un numéro différent de ceux qui sont déjà attribués dans ce sprint car sans cela vous devrez recommencer l'opération">
												<label for="numero">Numéro</label>
                        <input class="form-control" type="number" name="numero" placeholder="Numéro" required />
											</div>
											<div class="form-group">
												<label for="nom">Nom</label>
												<input type="text" class="form-control" id="nom" placeholder="Nom" name="nom" required />
											</div>
											<div class="form-group">
												<label for="description">Description</label>
												<textarea class="form-control" rows="3" id="description" name="description" placeholder="Description..." ></textarea>
											</div>
											<div class="form-group">
												<label for="nbJours">Nombre de jours</label>
                        <input class="form-control" type="number" name="nbJours" placeholder="Nombre de jours" required />
											</div>
											<div class="form-group">
												<label for="dateDebut">Date de début</label>
                        <input class="form-control" type="date" name="dateDepart" placeholder="Date de début" required />
											</div>
											<div class="form-group">
												<label for="responsable">Pseudo du responsable</label>
												
													<select class="form-control" name="responsable">
					<?php 					$listeDeveloppeurs = $db->listeDeveloppeursProjet($id_pro); 
													while ($row_dev = $listeDeveloppeurs->fetch_assoc()) {
														if ($row_dev["DEV_id"] == $_SESSION["id_co"]) {
					?>
														<option value="<?php echo $row_dev["DEV_id"]; ?>" selected>
					<?php							} else { ?>
														<option value="<?php echo $row_dev["DEV_id"]; ?>">
					<?php 						}
														echo $row_dev["DEV_pseudo"];
					?>
														</option>
					<?php						} ?>
													</select>
											</div>											
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
											<input type="hidden" name="id_us" value="<?php echo $id_us; ?>"/>
											<input class="btn btn-primary" type="submit" value="Valider"/>
										</div>
									</form>
								</div>
							</div>
						</div>
						<!-- Modal Suppression -->
						<div id="supprimerModal<?php echo $id_us; ?>" class="modal fade" role="dialog">
							<div class="modal-dialog">
								<!-- Modal content-->
								<div class="modal-content">
									<form style="display: inline;" action="../web/suppressionTache.php" method="post">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal">&times;</button>
											<h4 class="modal-title">Suppression d'une tâche de l'US (action irréversible !)</h4>
										</div>
										<div class="modal-body">
											<div class="form-group" >
				<?php 
												$liste_taches = $db->listeTachesUS($id_us);
												while ($row_tache = $liste_taches->fetch_assoc()) {
				?>
													<input type="radio" name="id_tache" value="<?php echo $row_tache["TAC_id"]; ?>">T#<?php echo $row_tache["TAC_numero"]." : ".$row_tache["TAC_nom"]; ?>&nbsp;
				<?php 					}		?>
											</div>											
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
											<input type="hidden" name="id_us" value="<?php echo $id_us; ?>"/>
											<input class="btn btn-danger" type="submit" value="Supprimer"/>
										</div>
									</form>
								</div>
							</div>
						</div>
<?php
					}
				}
				echo "</tr>";
			}
?>
					</tbody>
				</table>
			<!--<h3 id="interdépendance">Interdépendance des tâches</h3>
			<br> [A VENIR]
			<br>
			<br>-->
			<h3 id="liste">Liste détaillée des tâches par user stories (classées par ordre de priorité)</h3> 
<?php
	$result_us = $db->listeUserStorySprint($id_sprint);
	echo "<ul class=\"list-group\">";
	while ($row_us = $result_us->fetch_assoc()) {
		echo "<li class=\"list-group-item\" id=\"detailUS".$row_us['US_numero']."\"><strong>US#".$row_us['US_numero']." : ".$row_us['US_nom']."</strong></li>";

               echo "<ul class=\"list-group\">";
		$result_taches = $db->listeTachesUS($row_us['US_id']);
		while ($row_tache = $result_taches->fetch_assoc()) {
			$dateDepart_tache = $row_tache['TAC_dateDepart'];
			$pseudoResponsable_tache = $db->infosDeveloppeur($row_tache['DEV_id'])->fetch_assoc()['DEV_pseudo'];
			echo "<li class=\"list-group-item\" id=\tache".$row_tache['TAC_numero']."\">Tache#".$row_tache['TAC_numero']." : Tâche à réaliser par $pseudoResponsable_tache à partir de $dateDepart_tache. <br> &nbsp;&nbsp;".$row_tache['TAC_description']."<br></li>";
		}
		echo "</ul>";
	}
	echo "</ul>";		
?>			
    </div>
    </article>

<?php
    $s->footer();
?>
	<script>
    $(document).ready(function() {
        $('#tableTaches').DataTable( {
            "order": [[ 0, "asc" ]],
				"oLanguage": {
                "sLengthMenu": "Afficher _MENU_ entrées",
					"sSearch": "<span class=\"glyphicon glyphicon-search\"></span> Recherche:",
					"sEmptyTable": "Aucune donnée",
					"sInfo": "Affichage de _START_ à _END_ sur _TOTAL_ entrées",
					"sInfoEmpty": "",
					"oPaginate": {
                    "sPrevious": "Précédent",
						"sNext": "Suivant"
                        }
            }
        });
			
        $('.tooltip-link').tooltip();
			
        $('.modifiable').on("click", function (event) {				
            var str = $(this).find("span").text();
            var infosTache = str.split("|"); //text = $id_tache|$nom_tache|$etat_tache|etc...|$id_us
            var id_tache = infosTache[0]; //ok : id_tache = $id_tache
            var idUS_tache = infosTache[8]; //ok : id_tache = $id_tache
            var nomModalDeclenche = "#modifierModal"+idUS_tache+"-"+id_tache;
								
            $(nomModalDeclenche+' #nom').val(infosTache[2]);
            $(nomModalDeclenche+' #etat').val(infosTache[3]);
            $(nomModalDeclenche+' #description').val(infosTache[4]);
            $(nomModalDeclenche+' #nbJours').val(infosTache[5]);
            $(nomModalDeclenche+' #dateDepart').val(infosTache[6]);
            $(nomModalDeclenche+' #responsable').val(infosTache[7]);
            $(nomModalDeclenche+' #us').val(infosTache[8]);
								
            $(nomModalDeclenche+' input[name=\"id_tache\"]').val(infosTache[0]);
				
            $(nomModalDeclenche).modal('show');
        });
			
        $('.draggable').on("dragstart", {param1:"nice"}, function (event) {
            var dt = event.originalEvent.dataTransfer;
            dt.setData('Text', $(this).text()); //text = [Tache#$numero]$id_tache|$nom_tache|$etat_tache|etc...|$id_us
        });
			
        $('.dropzone').on("dragenter dragover drop", function (event) {	
            event.preventDefault();
            var id_zone = $(this).attr('id'); //ok : id = "$id_us-$etat"
            if (event.type == 'drop') {
                var data = event.originalEvent.dataTransfer.getData('Text',$(this).text()); //ok : text = [Tache#$numero_tache]$id_tache|Tache$id_tache|etat_tache|etc...|$id_us
                var infos_tache = data.split('|');

                var ancienEtat_tache = infos_tache[3];
                var idUS_tache = infos_tache[infos_tache.length-1];
                //alert("idUS_tache: "+idUS_tache);
					
                var result = id_zone.split('-');
                var idUS_zone = result[0]; //ok : idUS_zone = $id_us
                //alert("idUS_zone: "+idUS_zone);

                if(idUS_tache == idUS_zone) {
                    var caseEtId_tache = infos_tache[0];
                    var infosCaseEtId_tache = caseEtId_tache.split("]");
                    var id_tache = infosCaseEtId_tache[1]; //ok : id_tache = $id_tache
					
                    var recompositionIDTacheDragged = "tacheDraggableUS"+idUS_zone+id_tache; //ok : id = "tacheDraggableUS$id_us$id_tache\"
                    de = $('#'+recompositionIDTacheDragged).detach();
                    de.appendTo($(this));


                    var nomModalDeclenche = "#modifierEtatModal"+idUS_zone+"-"+id_tache;
                    var indiceColonneDropzoneus = $(this).parent().children().index($(this));
                    var nomNouvelEtat;
                    switch(indiceColonneDropzoneus) {
                    case 1:
                        nomNouvelEtat = "TO DO";
                        break;
                    case 2:
                        nomNouvelEtat = "ON GOING";
                        break;
                    case 3:
                        nomNouvelEtat = "TO TEST";
                        break;
                    case 4:
                        nomNouvelEtat = "DONE";
                        break;
                    default:
                        nomNouvelEtat = "";
                        alert("Impossible d'arriver ici ou alors la raison est un mystère.");
                        break;
                    }
						
                    $(nomModalDeclenche+' input[name=\"etat_tache\"]').val(nomNouvelEtat);
                    $(nomModalDeclenche+' .modal-body').text(ancienEtat_tache+" --> "+nomNouvelEtat);
                    $(nomModalDeclenche).modal('show');						
                }
                else {
                    alert("Attention: les tâches ne sont pas autorisées à changer d'us !");
                }
            };
        });
    });
		
    function surligne(champ, erreur) {
        if(erreur)
            champ.style.backgroundColor = "#fba";
        else
            champ.style.backgroundColor = "";
    }

    function verifEtat(champ) {
        if(champ.value == "TO DO" || champ.value == "ON GOING" ||
        champ.value == "TO TEST" || champ.value == "DONE") {
            surligne(champ, false);
            return true;
        } else {
            surligne(champ, true);
            return false;
        }
    };
    function verifForm(f) {
        var etatOk = verifEtat(f.etat);
        if(etatOk)
            return true;
        else {
            alert("Veuillez remplir correctement le champs Etat (i.e avec \"TO DO\", \"ON GOING\", \"TO TEST\" ou \"DONE\") !");
            return false;
        }
    }
	</script>

<?php
}  else {
	header("Location: ../web/index.php");
    exit();
}
?>
