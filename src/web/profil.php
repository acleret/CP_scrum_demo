<?php
require_once("config.php");

$s->suppressionCookies();

if (isset($_POST["suppr_profil"])) {
	$res = $db->supprDeveloppeur($_POST["suppr_profil"]);
	if($res) header("Location: deconnexion.php");
	exit();
}

if (isset($_GET["profil"])) { // un visiteur peut voir n'importe quel profil
	$pseudo_demande = $_GET["profil"];

	$id = $db->idDeveloppeur("\"".$pseudo_demande."\""); // grâce aux pseudo uniques
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
			if (isset($_GET["url"])) {
				if (!strcmp($_GET["url"], "OK")) {
					echo "<p>le profil a bien été modifié</p><br>\n";
				}
			}
?>
			<div class="paragraphs">
				<div class="row">
					<div class="col-sm-3">
<?php
					if (empty($_SESSION["image_co"])) {
?>
						<img class="pull-left .img-fluid" src="../web/img/avatar-default.jpg" alt="Avatar" height="164" width="164" class="img-rounded"/>
<?php
					} else {
?>
						<img class="pull-left .img-fluid" src="<?php echo $_SESSION["image_co"]; ?>" alt="Avatar" height="164" width="164" class="img-rounded"/>
<?php
					}
?>
					</div>
					<div class="col-sm-5" style="border: 2px dashed #333; padding:2%;">
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
		if ((isset($_SESSION["session"]) && empty($_GET["profil"])) ||
				((isset($_GET["profil"]) && isset($_SESSION["session"])
					&& $_GET["profil"]==$_SESSION["pseudo_co"]))) {
?>
							<form style="display: inline; margin-left:25%;" action="modificationMotDePasse.php" method="post">
								<input class="btn btn-link" type="submit" value="Changer mon mot de passe"/>
							</form>
<?php 				} ?>
						</div>
					</div>
				</div>

				<hr>

				<div class="col-sm-4 text-left">
					<h3>Projets :</h3>
					<h4>(en tant que Product Owner)</h4>
					<ul>
<?php
					$projets = $db->listeProjetsDeveloppeurProductOwner($id_dev);
					while ($projet = $projets->fetch_assoc()) {
?>
						<li>
							<form style="display: inline;" action="setProjet.php" method="post">
								<input type="hidden" name="id_projet" value="<?php echo $projet["PRO_id"]; ?>"/>
								<input class="btn btn-link" type="submit" value="<?php echo $projet["PRO_nom"]; ?>"/>
							</form><br>
							Créé le <?php echo $projet["PRO_dateCreation"]; ?> pour <?php echo $projet["PRO_client"]; ?>
						</li>
<?php
					}
?>
					</ul>
				</div>
				<div class="col-sm-4 text-left" style="border-right: 1px dashed #333; border-left: 1px dashed #333;">
					<h3>Projets :</h3>
					<h4>(en tant que Scrum Master)</h4>
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
							</form><br>
							Créé le <?php echo $projet["PRO_dateCreation"]; ?> pour <?php echo $projet["PRO_client"]; ?>
						</li>
<?php
						}
					}
?>
					</ul>
				</div>
				<div class="col-sm-4">
					<h3>Collaborations :</h3>
					<h4>(en tant que développeur)</h4>
					<ul>
<?php
					$projets = $db->listeProjetsDeveloppeur($id_dev);
					while ($projet = $projets->fetch_assoc()) {
?>
						<li>
							<form style="display: inline;" action="setProjet.php" method="post">
								<input type="hidden" name="id_projet" value="<?php echo $projet["PRO_id"]; ?>"/>
								<input class="btn btn-link" type="submit" value="<?php echo $projet["PRO_nom"]; ?>"/>
							</form><br>
							Créé le <?php echo $projet["PRO_dateCreation"]; ?> pour <?php echo $projet["PRO_client"]; ?><br>
						</li>
<?php
					}
?>
					</ul><br>
				</div>
			</div>
		</article>
<?php
		if ((isset($_SESSION["session"]) && empty($_GET["profil"])) ||
				((isset($_GET["profil"]) && isset($_SESSION["session"])
					&& $_GET["profil"]==$_SESSION["pseudo_co"]))) {
?>
		<aside>
			<div class="col-sm-2 sidenav">
				<form style="display: inline;" action="formulaireProfil.php" method="post">
					<input class="btn btn-default" type="submit" value="Modifier"/>
				</form>
				<br><br>
				<form style="display: inline;" action="" method="post">
					<input type="hidden" name="suppr_profil" value="<?php echo $_SESSION["id_co"]; ?>"/>
					<input class="btn btn-danger" type="submit" value="Supprimer"/>
				</form>
			</div>
		</aside>
<?php
		}
?>

<?php
	$s->footer();
  exit();
?>
