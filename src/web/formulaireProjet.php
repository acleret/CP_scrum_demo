<?php
require_once("config.php");

if (isset($_SESSION["session"])) {

	if (isset($_POST["action"])) {
		$nom = "";
		$client = "";
		$description = "";
		$idPO = "";
		$idSM = "";
				
		if ($_POST["action"] == "ajouter") {
			$s->suppressionCookies();
		  $s->head("Page projet - Création");
		}
		else if ($_POST["action"] == "éditer") {
			$s->head("Page projet - Édition");
			if (isset($_POST["nom"])) {
				$nom = $_POST["nom"];
			}
			if (isset($_POST["client"])) {
				$client = $_POST["client"];
			}
			if (isset($_POST["descr"])) {
				$description = $_POST["descr"];
			}
			if (isset($_POST["id_po"])) {
				$idPO = $_POST["id_po"];
				$PO = $db->pseudoDeveloppeur($idPO)->fetch_assoc();
				$nomPO = $PO["DEV_pseudo"];
			}
			if (isset($_POST["id_sm"])) {
				$idSM = $_POST["id_sm"];
				$SM = $db->pseudoDeveloppeur($idSM)->fetch_assoc();
				$nomSM = $SM["DEV_pseudo"];
			}
		}
		$s->header($db);
		$s->nav($db);
?>
		<article>
			<div class="col-sm-8 text-left">
				<h2>
<?php 	if ($_POST["action"] == "ajouter") { ?>
					Nouveau projet
<?php 	} else if ($_POST["action"] == "éditer") { ?>
					Édition du projet '<?php echo $nom; ?>'
<?php 	} ?>
				</h2>
				<hr>
				
<?php 	if ($_POST["action"] == "ajouter") { ?>
				<form class="form-horizontal" action="modificationProjet.php?action=ajouter" method="post">
<?php   } else if ($_POST["action"] == "éditer") { ?>
				<form class="form-horizontal" action="modificationProjet.php?action=éditer" method="post">
<?php 	} ?>
				<!--onsubmit="VerifFormulaireProjet()"-->
					<div class="form-group">
						<div class="col-md-offset-0 col-md-8">
							<label class="control-label" for="nom">Nom du projet :</label>
							<input class="form-control" type="text" id="nom" name="nom" maxlength="255" placeholder="Nom du projet" value="<?php echo $nom; ?>" required />
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-0 col-md-8">
							<label class="control-label" for="client">Client :</label>
							<input class="form-control" type="text" id="client" name="client" maxlength="255" placeholder="Nom du client" value="<?php echo $client; ?>" required />
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-0 col-md-8">
							<label class="control-label" for="comment">Description :</label>
							<textarea class="form-control" rows="5" id="comment" name="descr" placeholder="Description du projet" ><?php echo $description; ?></textarea>
						</div>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" id="changerPO" onclick="changeVisibilite()"> 
<?php 				if ($_POST["action"] == "ajouter") {  
								echo "Ajouter un product owner</label>";
							} else if ($_POST["action"] == "éditer") {  
								echo "Changer de product owner</label><br>\n
								(Actuellement, c'est : ".$nomPO.")";
							}
?>
					</div>
					<div class="form-group" id="nouveauPO" style="display:none;">
						<div class="col-md-offset-0 col-md-8">
							<select class="form-control" name="PO">
<?php 					$listeDeveloppeurs = $db->listeDeveloppeurs(); 
								while ($row_dev = $listeDeveloppeurs->fetch_assoc()) {							
									if ($_POST["action"] == "ajouter") {
										if($row_dev["DEV_id"] == $_SESSION["id_co"]) {
?>						
											<option selected value="<?php echo $_SESSION["id_co"]; ?>"><?php echo $_SESSION["pseudo_co"]; ?></option>
<?php								} else { ?>
											<option value="<?php echo $row_dev["DEV_id"]; ?>"><?php echo $row_dev["DEV_pseudo"]; ?></option>
<?php 							}
									} else if ($_POST["action"] == "éditer") {
										if($row_dev["DEV_id"] == $idPO) {
?>						
											<option selected value="<?php echo $idPO; ?>"><?php echo $nomPO; ?></option>
<?php								} else { ?>
											<option value="<?php echo $row_dev["DEV_id"]; ?>"><?php echo $row_dev["DEV_pseudo"]; ?></option>
<?php 							}
									} else { ?>
											<option value="<?php echo $row_dev["DEV_id"]; ?>"><?php echo $row_dev["DEV_pseudo"]; ?></option>
<?php 						}
								}
?>
							</select>
						</div>
					</div>
					<br>
					<div class="checkbox">
						<label>
							<input type="checkbox" id="changerSM" onclick="changeVisibilite()"> 
<?php 				if ($_POST["action"] == "ajouter") {  
								echo "Ajouter un scrum master</label>";
							} else if ($_POST["action"] == "éditer") {  
								echo "Changer de scrum master</label><br>\n
								(Actuellement, c'est : ".$nomSM.")";
							}
?>
					</div>
					<div class="form-group" id="nouveauSM" style="display:none;">
						<div class="col-md-offset-0 col-md-8">
							<select class="form-control" name="SM">
<?php 					$listeDeveloppeurs = $db->listeDeveloppeurs(); 
								while ($row_dev = $listeDeveloppeurs->fetch_assoc()) {					
									if ($_POST["action"] == "ajouter") {
										if($row_dev["DEV_id"] == $_SESSION["id_co"]) {
?>						
											<option selected value="<?php echo $_SESSION["id_co"]; ?>"><?php echo $_SESSION["pseudo_co"]; ?></option>
<?php								} else { ?>
											<option value="<?php echo $row_dev["DEV_id"]; ?>"><?php echo $row_dev["DEV_pseudo"]; ?></option>
<?php 							}
									} else if ($_POST["action"] == "éditer") {
										if($row_dev["DEV_id"] == $idSM) {
?>						
											<option selected value="<?php echo $idSM; ?>"><?php echo $nomSM; ?></option>
<?php								} else { ?>
											<option value="<?php echo $row_dev["DEV_id"]; ?>"><?php echo $row_dev["DEV_pseudo"]; ?></option>
<?php 							}
									} else { ?>
											<option value="<?php echo $row_dev["DEV_id"]; ?>"><?php echo $row_dev["DEV_pseudo"]; ?></option>
<?php 						}
								}
?>
							</select>
						</div>
					</div>
					<br>
					<div class="checkbox">
						<label>
							<input type="checkbox" id="choisirDevs" onclick="changeVisibilite()"> 
<?php 				if ($_POST["action"] == "ajouter") {  
								echo "Associer des développeurs</label>";
							} else if ($_POST["action"] == "éditer") {  
								echo "Ajouter/Retirer des développeurs</label><br>\n
								(Actuellement, il y a : ";
								if (isset($_POST["idProjet"])) {
									$search = $db->listeDeveloppeursProjet($_POST["idProjet"]);
									while ($row_devProjet = $search->fetch_assoc()) {
										echo $row_devProjet["DEV_pseudo"]." ; ";
									}
								}
								echo ")";
							}
?>
					</div>
					<div class="form-group" id="mesDevs" style="display:none;">
						<div class="col-md-offset-0 col-md-8">
							<select multiple class="form-control" name="devs[]" size="6">
<?php 					$listeDeveloppeurs = $db->listeDeveloppeurs(); 
								while ($row_dev = $listeDeveloppeurs->fetch_assoc()) {
									if ($_POST["action"] == "ajouter") {
										if($row_dev["DEV_id"] == $_SESSION["id_co"]) {
?>						
											<option selected value="<?php echo $_SESSION["id_co"]; ?>"><?php echo $_SESSION["pseudo_co"]; ?></option>
<?php								} else { ?>
											<option value="<?php echo $row_dev["DEV_id"]; ?>"><?php echo $row_dev["DEV_pseudo"]; ?></option>
<?php 							}
									} else if ($_POST["action"] == "éditer") {
										if ($db->estDeveloppeurProjet($_COOKIE["id_projet"], $row_dev["DEV_id"])) {
?>						
											<option selected value="<?php echo $row_dev["DEV_id"]; ?>"><?php echo $row_dev["DEV_pseudo"]; ?></option>
<?php								} else { ?>				
											<option value="<?php echo $row_dev["DEV_id"]; ?>"><?php echo $row_dev["DEV_pseudo"]; ?></option>
<?php								}
									}	else { ?>						
										<option value="<?php echo $row_dev["DEV_id"]; ?>"><?php echo $row_dev["DEV_pseudo"]; ?></option>
<?php							}
								}
?>
							</select>
						</div>
					</div>
					<br><br>
					<p class="information" style="font-style: italic;">
                                <span style="text-decoration: underline">NB :</span>
                                Surtout, pas d'inquiétudes ! Si vous ne savez pas encore qui affecter à un (ou aux 3) poste(s), ne sélectionnez personne. <br> 
                                &emsp;&emsp;&nbsp;Par défaut, nous vous attribuons ce(s) poste(s). <br>
                                &emsp;&emsp;&nbsp;Évidemment, vous pourrez modifier ces informations dans la page d'édition de ce nouveau projet.</p>
					<br>
					<div class="form-group">
						<div class="col-md-offset-0 col-md-8">
<?php 			// depuis le bouton "Modifier" de listeProjets.php
						if (isset($_POST["idProjet"]) && isset($_POST["pageActuelle"])) { 
?>												
							<input type="hidden" name="idProjet" value="<?php echo $_POST["idProjet"]; ?>"/>
							<input type="hidden" name="pageActuelle" value="<?php echo $_POST["pageActuelle"]; ?>"/>
<?php 			} ?>
							<input class="btn btn-primary" type="submit" value="Valider">
						</div>
					</div>
				</form>
			</div>
		</article>
		
   <script>
       jQuery(document).ready(function(){ });
			 
			 function changeVisibilite() {
				if($('#changerPO').is(':checked')) {
					$('#nouveauPO').css({'display':'block'});
				}
				else {
					$('#nouveauPO').css({'display':'none'});
				}
				if($('#changerSM').is(':checked')) {
					$('#nouveauSM').css({'display':'block'});
				}
				else {
					$('#nouveauSM').css({'display':'none'});
				}
				if($('#choisirDevs').is(':checked')) {
					$('#mesDevs').css({'display':'block'});
				}
				else {
					$('#mesDevs').css({'display':'none'});
				}
			}
			function VerifFormulaireProjet() {
				return true;
			}
		</script>
<?php
		$s->footer();
	}
}
else {
	header("Location: index.php");
	exit();
}
?>
