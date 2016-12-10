<?php
require_once("../web/config.php");

if (isset($_COOKIE["id_projet"]) && isset($_SESSION["session"])) {
	if ($db->estMembreProjet($_COOKIE["id_projet"], $_SESSION["id_co"])) {
		if (isset($_POST["id_us"])) {
			$id_us = $_POST["id_us"];
				
			$expire = time() + 60 * 60 * 24; //24 heures
			$us = $db->infosUserStory($id_us);
			$id_sprint_us = $us->fetch_assoc()["SPR_id"];
			setcookie("id_sprint", $id_sprint_us, $expire);
			
			$num_tache = $_POST["numero"];
			if (!$db->estNumeroTache($id_sprint_us, $num_tache)) {
				header("Location: ../web/kanban.php?ajout=erreurNumTache");
				exit();
			} else {
				$nom_tache = htmlspecialchars($_POST["nom"]);
				$description_tache = isset($_POST["description"]) ?htmlspecialchars($_POST["description"]) : ""; //peut être vide à l'ajout
				$nbJours = $_POST["nbJours"];
				$dateDepart = $_POST["dateDepart"];
				$id_dev = $_POST["responsable"];
			
				if ($db->ajoutTache($num_tache, $nom_tache, $description_tache, $nbJours, $dateDepart, $id_dev, $id_us)) {
					header("Location: ../web/kanban.php?ajout=OK");
					exit();
				} else {
					header("Location: ../web/kanban.php?ajout=erreur");
					exit();
				}
			}
		} else {
			header("Location: ../web/index.php");
			exit();
		}
	} else {
		header("Location: ../web/index.php");
		exit();
	}
} else {
	header("Location: ../web/index.php");
	exit();
}
?>
