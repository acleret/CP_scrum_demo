<?php
require_once("../web/config.php");

$pseudo = $_POST["identifiant"];
$mdp = $_POST["motDePasse"];

$result = $db->testDeveloppeurConnexion($pseudo, $mdp);

$donnees = $result->fetch_assoc();

if($result->num_rows == 1) {
  $_SESSION["session"] = true;
  $_SESSION["id_co"] = $donnees["DEV_id"];
  $_SESSION["pseudo_co"] = $donnees["DEV_pseudo"];
  $_SESSION["email_co"] = $donnees["DEV_mail"];
  $_SESSION["image_co"] = $donnees["DEV_urlAvatar"];
  $_SESSION["nom_co"] = $donnees["DEV_nom"];
  $_SESSION["prenom_co"] = $donnees["DEV_prenom"];
	$_SESSION["mdp_co"] = $donnees["DEV_mdp"];
	
  header("Location: ../web/index.php");
  exit();
} else {
  header("Location: ../web/connexion.php?erreur=KO");
  exit();
}
?>