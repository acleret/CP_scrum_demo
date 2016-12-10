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
  if (isset($_POST["resterConnecte"])) {
    $_SESSION["expire"] = time() + (60 * 60 * 24 * 365); // 1 an plus tard;
  } else {
    $_SESSION["expire"] = time() + (30 * 60); // 30 mn plus tard
  }

  header("Location: ../web/index.php");
  exit();
} else {
  header("Location: ../web/connexion.php?erreur=KO");
  exit();
}
?>
