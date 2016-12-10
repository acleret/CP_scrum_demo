<?php
require_once("../web/config.php");

if (isset($_COOKIE["id_projet"]) && isset($_SESSION["session"])) {
	if ($db->estMembreProjet($_COOKIE["id_projet"], $_SESSION["id_co"])) {
		if(isset($_POST["id_us"]) && isset($_POST["id_tache"])) {
			$id_us = $_POST["id_us"];
			$expire = time() + 60 * 60 * 24; // 24 heures
			$us = $db->infosUserStory($id_us);
			$id_sprint_us = $us->fetch_assoc()["SPR_id"];
			setcookie("id_sprint", $id_sprint_us, $expire);

			$id_tache = $_POST["id_tache"];
			
			if ($db->testIDTache($id_tache)) {
				if ($db->suppressionTache($id_tache)) {
					header("Location: ../web/kanban.php?suppr=OK");
					exit();
				} else {
					header("Location: ../web/kanban.php?suppr=erreur");
					exit();
				}
			} else {
					header("Location: ../web/kanban.php?suppr=erreurID");
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
} else {
	header("Location: ../web/index.php");
	exit();
}
?>