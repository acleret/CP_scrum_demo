<?php
require_once("config.php");

if (isset($_SESSION["session"])) {
	if (isset($_POST["nom"]) && isset($_POST["client"]) && isset($_POST["descr"])) {
		$nom = $_POST["nom"];
		$client = $_POST["client"];
		$description = $_POST["descr"];
	}
	
	if (isset($_POST["idPO"]))
		$id_PO = $_POST["idPO"];
	else
		$id_PO = $_SESSION["id_co"];
	
	if (isset($_GET["action"]) && $_GET["action"]=="ajouter") {
		//TODO ATTENTION aux apostrophes dans DESCRIPTION
		
		//$devs = array(0 => $_SESSION['id_co'], 1 => 2, 2 => 3);
		$id_projet = $db->ajouterProjetBDD($nom, $client, $description, $_POST['PO'], $_POST['SM'], $_POST['devs']); //TODO
		$expire = time() + 60 * 60 * 24; // 24 heures
		setcookie("id_projet", $id_projet, $expire);
		header("Location: projet.php");
		exit();
	}
	else if (isset($_GET["action"]) && $_GET["action"]=="éditer") {
		if(isset($_POST['PO'])) $po = $_POST['PO'];// : $po = null;
		if(isset($_POST['SM'])) $sm = $_POST['SM'];// : $sm = null;
		
		// depuis le bouton "Modifier" de listeProjets.php
		if (isset($_POST["idProjet"]) && isset($_POST["pageActuelle"])) { 
			if ($db->modifierProjetBDD($_POST["idProjet"], $nom, $client, $description, $po, $sm, $_POST['devs'])) {
				header("Location: listeProjets.php?page=".$_POST["pageActuelle"]);
				exit();
			}
		} 
		// depuis le bouton "Modifier" de projet.php
		else {
			if ($db->modifierProjetBDD($_COOKIE["id_projet"], $nom, $client, $description, $po, $sm, $_POST['devs'])) {
				header("Location: projet.php");
				exit();
			}
		}
	}
	else {
		header("Location: index.php");
		exit();
	}
} else {
	header("Location: index.php");
	exit();
}
?>