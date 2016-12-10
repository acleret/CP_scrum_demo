<?php
require_once("../web/config.php");

$s->head("CDP - Tests des requêtes");
$test = $db;


/*****************************************************************************/
echo ("<h1>/* Tests des fonctions concernant les développeurs */</h1><br>\n");
/*****************************************************************************/

echo "<b>// test infosDeveloppeur</b><br>\n<ul>";
$id_dev = 1;
if ($test->testIDDeveloppeur($id_dev)) {
  $result = $test->infosDeveloppeur($id_dev);
  $row = $result->fetch_assoc();
  echo $row["DEV_id"]." | ".
			$row["DEV_prenom"]." | ".
			$row["DEV_nom"]." | ".
			$row["DEV_pseudo"]." | ".
			$row["DEV_mdp"]." | ".
			$row["DEV_mail"]." | ".
			$row["DEV_urlAvatar"]." | ".
			$row["DEV_dateCreation"]."<br>\n<br>\n\n\n";
} else {
  echo "<li class=\"erreur\">Erreur : le dév ".$id_dev." est inconnu</li>\n";
}
echo "</ul><br>\n";

echo "<b>// test pseudoDeveloppeur</b><br>\n<ul>";
$pseudo_dev1 = $test->pseudoDeveloppeur($id_dev)->fetch_assoc();
if (($res = $pseudo_dev1["DEV_pseudo"]) == "devpseudo01") {
  echo "<li class=\"correct\">Pseudo correct</li>\n";
} else {
  echo "<li class=\"erreur\">Erreur : le pseudo ne correspond pas</li>\n";
}
echo "</ul><br>\n";

echo "<b>// test listeDeveloppeurs</b><br>\n<ul>";
$result = $test->listeDeveloppeurs();
while ($row = $result->fetch_assoc()) {
  echo $row["DEV_id"]." | ".
			$row["DEV_prenom"]." | ".
			$row["DEV_nom"]." | ".
			$row["DEV_pseudo"]." | ".
			$row["DEV_mdp"]." | ".
			$row["DEV_mail"]." | ".
			$row["DEV_urlAvatar"]." | ".
			$row["DEV_dateCreation"]."<br>\n";
}
echo "</ul><br>\n";

echo "<b>// test ajoutNouveauDeveloppeur</b><br>\n<ul>";
if ($test->ajoutNouveauDeveloppeur('ptest', 'ntest', 'pstest', 'mdptest', 'mtest', NULL)) {
  echo "<li class=\"correct\">Développeur ajouté</li>\n"; // attendu la première fois
} else {
  echo "<li class=\"erreur\">Erreur pseudo ou mail déjà pris</li>\n";
}
if ($test->ajoutNouveauDeveloppeur('ptest', 'ntest', 'pstest', 'mdptest', 'mtest2', NULL)) {
  echo "<li class=\"erreur\">Développeur ajouté</li>\n";
} else {
  echo "<li class=\"correct\">Erreur pseudo ou mail déjà pris</li>\n";
}
if ($test->ajoutNouveauDeveloppeur('ptest', 'ntest', 'pstest2', 'mdptest', 'mtest', NULL)) {
  echo "<li class=\"erreur\">Développeur ajouté</li>\n";
} else {
  echo "<li class=\"correct\">Erreur pseudo ou mail déjà pris</li>\n";
}
if ($test->ajoutNouveauDeveloppeur('ptest2', 'ntest2', 'pstest2', 'mdptest2', 'mtest2', NULL)) {
  echo "<li class=\"correct\">Développeur ajouté</li>\n"; // attendu la première fois
} else {
  echo "<li class=\"erreur\">Erreur pseudo ou mail déjà pris</li>\n";
}
echo "</ul><br>\n";

$nbDevs = $test->listeDeveloppeurs()->num_rows; /* id du developpeur 'ptest2' */

echo "<b>// test modifDeveloppeurMDP</b><br>\n<ul>";
if ($test->modifDeveloppeurMDP($nbDevs, "nmpdtest2")) {
  echo "<li class=\"correct\">Le développeur ptest2 a un nouveau de passe !</li>\n";
} else {
  echo "<li class=\"erreur\">Erreur le développeur ptest2 a toujours son ancien mot de passe.</li>\n";
}
echo "</ul><br>\n";

echo "<b>// test supprDeveloppeur</b><br>\n<ul>";
if ($test->supprDeveloppeur($nbDevs)) {
  echo "<li class=\"correct\">Le compte du développeur ptest2 a été supprimé de la base !</li>\n";
} else {
  echo "<li class=\"erreur\">Erreur le compte du développeur ptest2 existe toujours.</li>\n";
}
echo "</ul><br>\n";

echo "<b>// test testPseudoDeveloppeur</b><br>\n<ul>";
$pseudo = "pstest";
if ($test->testPseudoDeveloppeur($pseudo)) {
  echo "<li class=\"correct\">Le développeur ".$pseudo." existe déjà</li>\n";
} else {
  echo "<li class=\"erreur\">Le développeur ".$pseudo." n'existe pas</li>\n";
}
$pseudo = "rien";
if ($test->testPseudoDeveloppeur($pseudo)) {
  echo "<li class=\"erreur\">Le developpeur ".$pseudo." existe déjà</li>\n";
} else {
  echo "<li class=\"correct\">Le développeur ".$pseudo." n'existe pas</li>\n";
}
echo "</ul><br>\n";

echo "<b>// test testMailDeveloppeur</b><br>\n<ul>";
$mail = "mtest";
if ($test->testMailDeveloppeur($mail)) {
  echo "<li class=\"correct\">Le mail ".$mail." existe bien</li>\n";
} else {
  echo "<li class=\"erreur\">Le mail ".$mail." n'existe pas</li>\n";
}
$mail = "rien";
if ($test->testMailDeveloppeur($mail)) {
  echo "<li class=\"erreur\">Le mail ".$mail." existe bien</li>\n";
} else {
  echo "<li class=\"correct\">Le mail ".$mail." n'existe pas</li>\n";
}
echo "</ul><br>\n";

echo "<b>// test modifDeveloppeur</b><br>\n<ul>";
$newpseudo = "newpstest";
if (!$test->testPseudoDeveloppeur($newpseudo)) {
  $id_dev = $test->maxIDDeveloppeur();
  if ($test->modifDeveloppeur($id_dev, "bob", "bob", $newpseudo, "")) {
    echo "<li class=\"correct\">Développeur modifié</li>\n";
  } else {
    echo "<li class=\"erreur\">Erreur modification dev</li>\n";
  }
}
echo "</ul><br>\n";

echo "<b>// test estDeveloppeurProjet</b><br>\n<ul>";
if ($test->estDeveloppeurProjet(1, 1)) {
  echo "<li class=\"correct\">Le développeur 1 est développeur du projet 1</li>\n";
} else {
    echo "<li class=\"erreur\">Le développeur 1 n'est pas membre du projet 1</li>\n";
}
if ($test->estDeveloppeurProjet(3, 1)) {
  echo "<li class=\"correct\">Le développeur 1 est membre du projet 3</li>\n";
} else {
    echo "<li class=\"erreur\">Le développeur 1 n'est pas membre du projet 3</li>\n";
}
echo "</ul><br>\n";

echo "<b>// test estScrumMaster</b><br>\n<ul>";
$id_dev = 1;
$id_pro = 1;
if ($test->estScrumMaster($id_dev, $id_pro)) {
  echo "<li class=\"correct\">Le développeur ".$id_dev." est Scrum Master sur le projet ".$id_pro."</li>\n";
} else {
  echo "<li class=\"erreur\">Le développeur ".$id_dev." n'est pas Scrum Master sur le projet ".$id_pro."</li>\n";
}
echo "</ul><br>\n";

echo "<b>// test estProductOwner</b><br>\n<ul>";
$id_dev = 1;
$id_pro = 1;
if ($test->estProductOwner($id_dev, $id_pro)) {
  echo "<li class=\"correct\">Le développeur ".$id_dev." est ProductOwner sur le projet ".$id_pro."</li>\n";
} else {
  echo "<li class=\"erreur\">Le développeur ".$id_dev." n'est pas ProductOwner sur le projet ".$id_pro."</li>\n";
}
echo "</ul><br>\n";

echo "<b>// test estMembreProjet</b><br>\n<ul>";
$id_dev = 1;
$id_pro = 1;
if ($test->estMembreProjet($id_dev, $id_pro)) {
  echo "<li class=\"correct\">La personne n°".$id_dev." est bien membre du projet ".$id_pro."</li>\n";
} else {
  echo "<li class=\"erreur\">La personne n°".$id_dev." n'est pas membre du projet ".$id_pro."</li>\n";
}
echo "</ul><br>\n";

echo "<b>// test testIDDeveloppeur</b><br>\n<ul>";
$id = 1;
if ($test->testIDDeveloppeur($id)) {
  echo "<li class=\"correct\">L'id ".$id." existe déjà</li>\n";
} else {
  echo "<li class=\"erreur\">L'id ".$id." n'existe pas</li>\n";
}
$id = 20000000;
if ($test->testIDDeveloppeur($id)) {
  echo "<li class=\"erreur\">L'id ".$id." existe</li>\n";
} else {
  echo "<li class=\"correct\">L'id ".$id." n'existe pas</li>\n";
}
echo "</ul><br>\n";

echo "<b>// test listeProjetsDeveloppeurProductOwner</b><br>\n<ul>";
$id_dev = 1; // le dev 1 est lié au projet 1, 2 et 4
$result = $test->listeProjetsDeveloppeurProductOwner($id_dev);
while ($row = $result->fetch_assoc()) {
  echo $row["PRO_id"]." | ".
			$row["PRO_nom"]." | ".
			$row["PRO_client"]." | ".
			$row["PRO_description"]." | ".
			$row["PRO_dateCreation"]." | ".
			$row["DEV_idProductOwner"]." | ".
			$row["DEV_idScrumMaster"]."<br>\n";
}
echo "</ul><br>\n";

echo "<b>// test listeProjetsDeveloppeur</b><br>\n<ul>";
$id_dev = 1; // le dev 1 est lié au projet 1, 2 et 4
$result = $test->listeProjetsDeveloppeur($id_dev);
while ($row = $result->fetch_assoc()) {
  echo $row["PRO_id"]." | ".
			$row["PRO_nom"]." | ".
			$row["PRO_client"]." | ".
			$row["PRO_description"]." | ".
			$row["PRO_dateCreation"]." | ".
			$row["DEV_idProductOwner"]." | ".
			$row["DEV_idScrumMaster"]."<br>\n";
}
echo "</ul><br>\n";

echo "<b>// test nombreProjetsDeveloppeur</b><br>\n<ul>";
echo "<li class=\"correct\">Le dév n°".$id_dev." est lié à ".$test->nombreProjetsDeveloppeur($id_dev)." projets</li>\n";
echo "</ul><br>\n";

echo "<b>// test testDeveloppeurConnexion</b><br>\n<ul>";
$pseudo = "pstest";
$mdp = "mdptest";
$result = $test->testDeveloppeurConnexion($pseudo, $mdp);
$row = $result->fetch_assoc();
if ($result->num_rows == 1) {
  echo $row["DEV_id"]." | ".
			$row["DEV_prenom"]." | ".
			$row["DEV_nom"]." | ".
			$row["DEV_pseudo"]." | ".
			$row["DEV_mdp"]." | ".
			$row["DEV_mail"]." | ".
			$row["DEV_urlAvatar"]." | ".
			$row["DEV_dateCreation"]."<br>\n<br>\n";
} else {
  echo "<li class=\"correct\">Mauvaise combinaison pseudo et mot de passe</li>\n";
}
echo "</ul><br>\n";


/**************************************************************************/
echo ("<h1>/** Tests des fonctions concernant les projets **/</h1><br>\n");
/**************************************************************************/

echo "<b>// test testIDProjet</b><br>\n<ul>";
if ($test->testIDProjet($id_pro)) {
  echo "<li class=\"correct\">Le projet $id_pro existe bien</li>\n";
} else {
  echo "<li class=\"erreur\">Erreur : testIDProjet() ne reconnait pas le projet $id_pro</li>\n";
}
echo "</ul><br>\n";

echo "<b>// test infosProjet</b><br>\n<ul>";
$id_pro = 1;
if ($test->testIDProjet($id_pro)) {
  $result = $test->infosProjet($id_pro);
  $row = $result->fetch_assoc();
  echo $row["PRO_id"]." | ".
			$row["PRO_nom"]." | ".
			$row["PRO_client"]." | ".
			$row["PRO_description"]." | ".
			$row["PRO_dateCreation"]." | ".
			$row["DEV_idProductOwner"]." | ".
			$row["DEV_idScrumMaster"]."<br>\n";
} else {
  echo "<li class=\"erreur\">Erreur : le projet $id_pro est inconnu</li>\n";
}
echo "</ul><br>\n";

echo "<b>// test listeProjets</b><br>\n<ul>";
$result = $test->listeProjets();
while ($row = $result->fetch_assoc()) {
  echo $row["PRO_id"]." | ".
			$row["PRO_nom"]." | ".
			$row["PRO_client"]." | ".
			$row["PRO_description"]." | ".
			$row["PRO_dateCreation"]." | ".
			$row["DEV_idProductOwner"]." | ".
			$row["DEV_idScrumMaster"]."<br>\n";
}
echo "</ul><br>\n";

echo "<b>// test nombreProjets</b><br>\n<ul>";
$nb_projets = $test->nombreProjets();
echo ($nb_projets == $result->num_rows)? "<li class=\"correct\">Bon calcul : $nb_projets projets</li>\n" : "<li class=\"erreur\">Mauvais calcul</li>\n";
echo "</ul><br>\n";

echo "<b>// test ajouterProjetBDD</b><br>\n<ul>";
$devs = array(0 => 1, 1 => 2, 2 => 3);
$idProjet = $test->ajouterProjetBDD("NouveauProjet", "Anonymous", "Projet test dans la BD", 1, 1, $devs);
$idProjetMaxEnVisu = $test->maxIDProjet();
if ($idProjet == $idProjetMaxEnVisu) {
  echo "<li class=\"correct\">Projet $idProjet créé</li>\n";
} else {
  echo "<li class=\"erreur\">Erreur lors de la création du projet ".$idProjet." (!= ".$idProjetMaxEnVisu.")</li>\n";
}
echo "</ul><br>\n";

echo "<b>// test modifierProjetBDD</b><br>\n<ul>";
$result = $test->infosProjet($idProjet);
$row = $result->fetch_assoc();
echo "<li>Projet ".$idProjet." avant modif : <br>";
echo $row["PRO_id"]." | ".
			$row["PRO_nom"]." | ".
			$row["PRO_client"]." | ".
			$row["PRO_description"]." | ".
			$row["DEV_idProductOwner"]." | ".
			$row["DEV_idScrumMaster"]."<br></li><br>\n\n";
$devs[] = 10; // on ajoute le dév 10
unset($devs[2]); // on retire le dév 03
if ($test->modifierProjetBDD($idProjet, "MonDernierProjet", "Anonymous", "Projet test dans la BD", 1, 2, $devs)) {
  echo "<li class=\"correct\">Projet $idProjet bien modifié :<br>";
	$result2 = $test->infosProjet($idProjet);
	$row2 = $result2->fetch_assoc();
	echo $row2["PRO_id"]." | ".
			$row2["PRO_nom"]." | ".
			$row2["PRO_client"]." | ".
			$row2["PRO_description"]." | ".
			$row2["DEV_idProductOwner"]." | ".
			$row2["DEV_idScrumMaster"]."</li>\n";
} else {
  echo "<li class=\"erreur\">Erreur lors de la modification des données du projet $idprojet</li>\n";
}
echo "</ul><br>\n";

echo "<b>// test supprimerProjetBDD</b><br>\n<ul>";
if ($test->supprimerProjetBDD($idProjet)) {
	echo "<li class=\"correct\">Projet $idProjet bien supprimé</li>\n";
} else {
  echo "<li class=\"erreur\">Erreur lors de la suppression du projet $idProjet</li>\n";
}
echo "</ul><br>\n";

echo "<b>// test listeProjets</b><br>\n<ul>";
$result = $test->listeProjets();
while ($row = $result->fetch_assoc()) {
  echo $row["PRO_id"]." | ".
			$row["PRO_nom"]." | ".
			$row["PRO_client"]." | ".
			$row["PRO_description"]." | ".
			$row["PRO_dateCreation"]." | ".
			$row["DEV_idProductOwner"]." | ".
			$row["DEV_idScrumMaster"]."<br>\n";
}
echo "</ul><br>\n";

echo "<b>// test listeDeveloppeursProjet</b><br>\n<ul>";
$id_pro = 1; // le projet contient plusieurs développeurs
$result = $test->listeDeveloppeursProjet($id_pro);
while ($row = $result->fetch_assoc()) {
  echo $row["DEV_id"]." | ".
			$row["DEV_prenom"]." | ".
			$row["DEV_nom"]." | ".
			$row["DEV_pseudo"]." | ".
			$row["DEV_mdp"]." | ".
			$row["DEV_mail"]." | ".
			$row["DEV_urlAvatar"]." | ".
			$row["DEV_dateCreation"]."<br>\n";
}
echo "</ul><br>\n";


/*******************************************************************************/
echo ("<h1>/** Tests des fonctions concernant les User Stories **/</h1><br>\n");
/*******************************************************************************/

echo "<b>// test testIDUserStory</b><br>\n<ul>";
$id_us = 1;
if ($test->testIDUserStory($id_us)) {
  echo "<li class=\"correct\">L'US $id_us existe bien</li>\n";
} else {
  echo "<li class=\"erreur\">Erreur : testIDUserStory() ne reconnait pas l'US $id_us</li>\n";
}
echo "</ul><br>\n";

echo "<b>// test infosUserStory</b><br>\n<ul>";
$id_us = 1;
if ($test->testIDUserStory($id_us)) {
  $result = $test->infosUserStory($id_us);
  $row = $result->fetch_assoc();
  echo $row["US_id"]." | ".
       $row["US_numero"]." | ".
       $row["US_nom"]." | ".
       $row["US_chiffrageAbstrait"]." | ".
       $row["US_priorite"]." | ".
       $row["US_dateCreation"]." | ".
       $row["US_dateDernierCommit"]." | ".
       $row["US_idDernierCommit"]." | ".
       $row["US_auteurDernierCommit"]." | ".
       $row["PRO_id"]." | ".
       $row["SPR_id"]."<br>\n";
} else {
  echo "<li class=\"erreur\">Erreur l'US $id_us est inconnue</li>\n";
}
echo "</ul><br>\n";

echo "<b>// test listeUserStories</b><br>\n<ul>";
$id_pro = 4;
$result = $test->listeUserStories($id_pro);
while ($row = $result->fetch_assoc()) {
  echo $row["US_id"]." | ".
       $row["US_numero"]." | ".
			$row["US_nom"]." | ".
			$row["US_chiffrageAbstrait"]." | ".
			$row["US_priorite"]." | ".
			$row["US_dateCreation"]." | ".
			$row["US_dateDernierCommit"]." | ".
			$row["US_idDernierCommit"]." | ".
			$row["US_auteurDernierCommit"]." | ".
      $row["PRO_id"]." | ".
      $row["SPR_id"]."<br>\n";
}
echo "</ul><br>\n";

echo "<b>// test listeUserStorySprint</b><br>\n<ul>";
$id_spr = 1;
$result = $test->listeUserStorySprint($id_spr);
echo "US dans le sprint#$id_spr<br>\n";
while ($row = $result->fetch_assoc()) {
  echo $row["US_id"]." | ".
       $row["US_numero"]." | ".
			$row["US_nom"]." | ".
			$row["US_chiffrageAbstrait"]." | ".
			$row["US_priorite"]." | ".
			$row["US_dateCreation"]." | ".
			$row["US_dateDernierCommit"]." | ".
			$row["US_idDernierCommit"]." | ".
			$row["US_auteurDernierCommit"]." |".
      $row["PRO_id"]." | ".
      $row["SPR_id"]."<br>\n";
}
echo "</ul><br>\n";

echo "<b>// test listeUserStoryOutOfSprints</b><br>\n<ul>";
$id_spr = 1;
$id_pro = 1;
$result = $test->listeUserStoryOutOfSprints($id_pro);
echo "US du projet $id_pro hors de tous les sprints<br>\n";
while ($row = $result->fetch_assoc()) {
  echo $row["US_id"]." | ".
       $row["US_numero"]." | ".
			$row["US_nom"]." | ".
			$row["US_chiffrageAbstrait"]." | ".
			$row["US_priorite"]." | ".
			$row["US_dateCreation"]." | ".
			$row["US_dateDernierCommit"]." | ".
			$row["US_idDernierCommit"]." | ".
			$row["US_auteurDernierCommit"]." |".
      $row["PRO_id"]." | ".
      $row["SPR_id"]."<br>\n";
}
echo "</ul><br>\n";

echo "<b>// test ajoutUserStory</b><br>\n<ul>";
if ($test->ajoutUserStory(1, "us1", 5, "NULL", 1)) {
	$id_us = $test->maxIDUserStory();
  echo "<li class=\"correct\">US $id_us créée</li>\n";
} else {
  echo "<li class=\"erreur\">Erreur lors de la création d'une US</li>\n";
}
echo "</ul><br>\n";

echo "<b>// test modifUserStory</b><br>\n<ul>";
if ($test->modifUserStory($id_us, 4, "USModifiée", 8)) {
  echo "<li class=\"correct\">US $id_us modifiée</li>\n";
} else {
  echo "<li class=\"erreur\">Erreur lors de la modification des données de l'US $id_us</li>\n";
}
echo "</ul><br>\n";

echo "<b>// test modifUserStoryProductOwner</b><br>\n<ul>";
if ($test->modifUserStoryProductOwner($id_us, 8, "USModifiée", 13, 5)) {
  echo "<li class=\"correct\">US $id_us modifiée</li>\n";
} else {
  echo "<li class=\"erreur\">Erreur lors de la modification des données de l'US $id_us</li>\n";
}
echo "</ul><br>\n";

echo "<b>// test modifUserStoryTracabilite</b><br>\n<ul>";
if ($test->modifUserStoryTracabilite($id_us, "2016-09-30", "commit_id", "developpeur responsable du commit")) {
  echo "<li class=\"correct\">US $id_us modifiée</li>\n";
} else {
  echo "<li class=\"erreur\">Erreur dans la modification des données d'une US $id_us</li>\n";
}
echo "</ul><br>\n";

echo "<b>// test affecterUserStorySprint</b><br>\n<ul>";
$id_spr = 4;
if ($test->affecterUserStorySprint($id_us, $id_spr)) {
  echo "<li class=\"correct\">US $id_us affectée au sprint $id_spr</li>\n";
} else {
  echo "<li class=\"erreur\">Erreur lors de l'affectation d'un sprint à l'US $id_us</li>\n";
}
echo "</ul><br>\n";

echo "<b>// test retirerUserStorySprint</b><br>\n<ul>";
$id_spr = 4;
if ($test->retirerUserStorySprint($id_us)) {
  echo "<li class=\"correct\">US $id_us retirée du sprint $id_spr</li>\n";
} else {
  echo "<li class=\"erreur\">Erreur lors de la modification des données de l'US $id_us</li>\n";
}
echo "</ul><br>\n";

echo "<b>// test suppressionUserStory</b><br>\n<ul>";
if ($test->testIDUserStory($id_us)) {
  if ($test->suppressionUserStory($id_us)) {
    echo "<li class=\"correct\">US $id_us supprimée</li>\n";
  } else {
    echo "<li class=\"erreur\">Erreur lors de la suppression de l'US $id_us</li>\n";
  }
} else {
  echo "<li class=\"erreur\">L'US $id_us n'existe pas</li>\n";
}
echo "</ul><br>\n";

echo "<b>// test sommeChiffrageBacklog</b><br>\n<ul>";
if (!empty($cout = $test->sommeChiffrageBacklog($id_pro))) {
  echo "<li class=\"correct\">Chiffrage = $cout</li>\n";
} else {
  echo "<li class=\"erreur\">Chiffrage = 0</li>\n";
}
echo "</ul><br>\n";

echo "<b>// test sommeChiffrageSprint</b><br>\n<ul>";
if (!empty($cout = $test->sommeChiffrageSprint($id_spr))) {
  echo "<li class=\"correct\">Chiffrage = $cout</li>\n";
} else {
  echo "<li class=\"erreur\">Chiffrage = 0</li>\n";
}
echo "</ul><br>\n";

echo "<b>// test listeUserStoriesAvecCommit</b><br>\n<ul>";
$id_pro = 1;
$result = $test->listeUserStoriesAvecCommit($id_pro);
echo "US avec commit dans le projet $id_pro<br>\n";
while ($row = $result->fetch_assoc()) {
  echo $row["US_id"]." | ".
       $row["US_numero"]." | ".
			$row["US_nom"]." | ".
			$row["US_chiffrageAbstrait"]." | ".
			$row["US_priorite"]." | ".
			$row["US_dateCreation"]." | ".
			$row["US_dateDernierCommit"]." | ".
			$row["US_idDernierCommit"]." | ".
			$row["US_auteurDernierCommit"]." |".
      $row["PRO_id"]." | ".
      $row["SPR_id"]."<br>\n";
}
echo "</ul><br>\n";


/************************************************************************/
echo ("<h1>/* Tests des fonctions concernant les sprints */</h1><br>\n");
/************************************************************************/

echo "<b>// test infosSprint</b><br>\n<ul>";
$id_spr = 1;
$result = $test->infosSprint($id_spr);
$row = $result->fetch_assoc();
echo $row["SPR_id"]." | ".
			$row["SPR_numero"]." | ".
			$row["SPR_dateDebut"]." | ".
			$row["SPR_duree"]." | ".
			$row["PRO_id"]."<br>\n";
echo "</ul><br>\n";

echo "<b>// test listeSprints</b><br>\n<ul>";
$id_pro = 1;
$result = $test->listeSprints($id_pro);
while ($row = $result->fetch_assoc()) {
  echo $row["SPR_id"]." | ".
			$row["SPR_numero"]." | ".
			$row["SPR_dateDebut"]." | ".
			$row["SPR_duree"]." | ".
			$row["PRO_id"]."<br>\n";
}
echo "</ul><br>\n";

echo "<b>// test ordonnerDate</b><br>\n<ul>";
$result = $test->infosSprint($id_spr)->fetch_assoc();
$date = $result["SPR_dateDebut"];
echo $date." -> ";
echo $test->ordonnerDate($date);
echo "</ul><br>\n";

echo "<b>// test ajoutSprint</b><br>\n<ul>";
if ($test->ajoutSprint(11, "2011-11-11", 11, 1)) {
  echo "<li class=\"correct\">Sprint ajouté</li>\n";
} else {
  echo "<li class=\"erreur\">Erreur lors de l'ajout d'un sprint</li>\n";
}
echo "</ul><br>\n";

echo "<b>// test modifSprint</b><br>\n<ul>";
$id_spr = $test->maxIDSprint();
$result = $test->infosSprint($id_spr);
$row = $result->fetch_assoc();
echo "<li>avant modif : <br>";
echo $row["SPR_id"]." | ".
			$row["SPR_numero"]." | ".
			$row["SPR_dateDebut"]." | ".
			$row["SPR_duree"]." | ".
			$row["PRO_id"]."<br>\n";
echo "</li><br>\n<li>après modif : <br>";
$test->modifSprint($id_spr, 22, "2222-12-22", 22);
$result2 = $test->infosSprint($id_spr);
$row2 = $result2->fetch_assoc();
echo $row2["SPR_id"]." | ".
			$row2["SPR_numero"]." | ".
			$row2["SPR_dateDebut"]." | ".
			$row2["SPR_duree"]." | ".
			$row2["PRO_id"]."<br>\n";
echo "</li></ul><br>\n";

echo "<b>// test supprimerSprint</b><br>\n<ul>";
$id_spr = $test->maxIDSprint();
if ($test->supprimerSprint($id_spr)) {
  echo "<li class=\"correct\">Sprint $id_spr supprimé</li>\n";
} else {
  echo "<li class=\"erreur\">Erreur lors de la suppression d'un sprint</li>\n";
}
echo "</ul><br>\n";

echo "<b>// test numeroSprint</b><br>\n<ul>";
$id_spr = $test->maxIDSprint();
if (!empty($test->numeroSprint($id_spr))) {
  $sprint_num = $test->numeroSprint($id_spr);
  echo "<li class=\"correct\">numéro Sprint $sprint_num</li>\n";
} else {
  echo "<li class=\"erreur\">Erreur numéro Sprint</li>\n";
}
echo "</ul><br>\n";


/***********************************************************************/
echo ("<h1>/* Tests des fonctions concernant les tâches */</h1><br>\n");
/***********************************************************************/

echo "<b>// test testIDTache</b><br>\n<ul>";
$id_tac = 1;
if ($test->testIDProjet($id_pro)) {
  echo "<li class=\"correct\">La tâche $id_tac existe bien</li>\n";
} else {
  echo "<li class=\"erreur\">Erreur : testIDTache() ne reconnait pas la tâche $id_tac</li>\n";
}
echo "</ul><br>\n";

echo "<b>// test infosTache</b><br>\n<ul>";
if ($test->testIDProjet($id_pro)) {
	$result = $test->infosTache($id_tac);
	$row = $result->fetch_assoc();
	echo $row["TAC_id"]." | ".
			$row["TAC_numero"]." | ".
			$row["TAC_nom"]." | ".
			$row["TAC_description"]." | ".
			$row["TAC_nbJours"]." | ".
			$row["TAC_dateDepart"]." | ".
			$row["TAC_etat"]." | ".
			$row["DEV_id"]." | ".
			$row["US_id"]."<br>\n";
} else {
  echo "<li class=\"erreur\">Erreur : la tâche $id_tac est inconnue</li>\n";
}
echo "</ul><br>\n";

echo "<b>// test estNumeroTache</b><br>\n<ul>";
$numeroTache = 12;
$us = 141;
$numSprint = $db->infosUserStory($us)->fetch_assoc()["SPR_id"];
if ($test->estNumeroTache($numSprint, $numeroTache)) {
    echo "<li class=\"correct\">Correct : le numéro peut être utilisé dans le cadre de la création d'une nouvelle tâche</li>\n";
} else {
    echo "<li class=\"erreur\">Erreur lors du repérage d'un numéro de tâche libre</li>\n";
}
echo "</ul><br>\n";

echo "<b>// test ajoutTache</b><br>\n<ul>";
if ($test->ajoutTache($numeroTache, "DernièreTache", "Dernière tâche", 1, "2016-11-26", 1, $us)) {
	$id_tac = $test->maxIDTache();
  echo "<li class=\"correct\">Tâche $id_tac créée</li>\n";
} else {
  echo "<li class=\"erreur\">Erreur lors de la création d'une tâche</li>\n";
}
echo "</ul><br>\n";

echo "<b>// test modifTache</b><br>\n<ul>";
if ($test->testIDTache($id_tac)) {
	$result = $test->infosTache($id_tac);
	$row = $result->fetch_assoc();
	echo "<li>Tâche ".$id_tac." avant modif : <br>";
	echo $row["TAC_id"]." | ".
			$row["TAC_numero"]." | ".
			$row["TAC_nom"]." | ".
			$row["TAC_description"]." | ".
			$row["TAC_nbJours"]." | ".
			$row["TAC_dateDepart"]." | ".
			$row["TAC_etat"]." | ".
			$row["DEV_id"]." | ".
			$row["US_id"]."<br>\n";
	if ($test->modifTache($id_tac, "MaTacheModifiee", "Description de la dernière tâche", 1, "2016-11-26", "ON GOING", 2, 141)) {
		echo "<li class=\"correct\">Tâche $id_tac bien modifiée : <br>";
		$result2 = $test->infosTache($id_tac);
		$row2 = $result2->fetch_assoc();
		echo $row2["TAC_id"]." | ".
			$row2["TAC_num"]." | ".
			$row2["TAC_nom"]." | ".
			$row2["TAC_description"]." | ".
			$row2["TAC_nbJours"]." | ".
			$row2["TAC_dateDepart"]." | ".
			$row2["TAC_etat"]." | ".
			$row2["DEV_id"]." | ".
			$row2["US_id"]."<br>\n";
	} else {
		echo "<li class=\"erreur\">Erreur lors de la modification des données de la tâche $id_tac</li>\n";
	}
} else {
  echo "<li class=\"erreur\">La tâche $id_tac est inconnue</li>\n";
}
echo "</ul><br>\n";

echo "<b>// test modifEtatTache</b><br>\n<ul>";
if ($test->testIDTache($id_tac)) {
	if ($test->modifEtatTache($id_tac, "To Test")) {
		echo "<li class=\"correct\">Tâche ".$id_tac." après modification de son état : <br>";
		$result = $test->infosTache($id_tac);
		$row = $result->fetch_assoc();
		echo $row["TAC_id"]." | ".
			$row["TAC_num"]." | ".
			$row["TAC_nom"]." | ".
			$row["TAC_description"]." | ".
			$row["TAC_nbJours"]." | ".
			$row["TAC_dateDepart"]." | ".
			$row["TAC_etat"]." | ".
			$row["DEV_id"]." | ".
			$row["US_id"]."<br>\n";
	} else {
		echo "<li class=\"erreur\">Erreur lors de la modification de l'état de la tâche $id_tac</li>\n";
	}
} else {
  echo "<li class=\"erreur\">La tâche $id_tac est inconnue</li>\n";
}
echo "</ul><br>\n";

echo "<b>// test modifUSTache</b><br>\n<ul>";
if ($test->testIDTache($id_tac)) {
	if ($test->modifUSTache($id_tac, 1)) {
		echo "<li class=\"correct\">Tâche $id_tac après modification de l'id de l'us à laquelle elle est rattachée : <br>";
		$result = $test->infosTache($id_tac);
		$row = $result->fetch_assoc();
		echo $row["TAC_id"]." | ".
			$row["TAC_num"]." | ".
			$row["TAC_nom"]." | ".
			$row["TAC_description"]." | ".
			$row["TAC_nbJours"]." | ".
			$row["TAC_dateDepart"]." | ".
			$row["TAC_etat"]." | ".
			$row["DEV_id"]." | ".
			$row["US_id"]."<br>\n";
	} else {
		echo "<li class=\"erreur\">Erreur lors de la modification de l'id de l'us à laquelle la tâche $id_tac est rattachée</li>\n";
	}
} else {
  echo "<li class=\"erreur\">La tâche $id_tac est inconnue</li>\n";
}
echo "</ul><br>\n";

echo "<b>// test suppressionTache</b><br>\n<ul>";
if ($test->testIDTache($id_tac)) {
  if ($test->suppressionTache($id_tac)) {
    echo "<li class=\"correct\">Tâche $id_tac supprimée</li>\n";
  } else {
    echo "<li class=\"erreur\">Erreur lors de la suppression de la tâche $id_tac</li>\n";
  }
} else {
  echo "<li class=\"erreur\">La tâche $id_tac est inconnue</li>\n";
}
echo "</ul><br>\n";

echo "<b>// test listeTachesUSEtats</b><br>\n<ul>";
$id_us = 13;
$result = $test->listeTachesUSEtats($id_us);
while ($row_etat = $result->fetch_assoc()) { // une ligne = un état
	echo "<li><strong>Ligne de résultat pour l'us $id_us et l'état \"".$row_etat["TAC_etat"]."\"</strong> : [";
  echo "(".$row_etat["TAC_etat"].") , (".$row_etat["MesTaches"].")]<br>\n";

	$lesTaches = explode(";", $row_etat["MesTaches"]);
	// count($lesTaches); // nb de tâches par état
	echo "L'us $id_us a ".count($lesTaches)." tâches dans l'état ".$row_etat["TAC_etat"]." !<br>\n";
	foreach($lesTaches as $key => $tacheInfo) {
		echo "Tâche - info récupérée depuis le concat : ($tacheInfo)<br>\n";
		$infosTrouveesTache = explode("|", $tacheInfo);
		echo "Tâche - infos séparées : ( ";
		for($i=0; $i<count($infosTrouveesTache); $i++) {	// count($iTT) : nb d'infos trouvées
			echo $infosTrouveesTache[$i]." ";
		}
		echo ")<br>\n";
  }
	echo "</li><br>\n";
}
echo "</ul><br>\n";


/****************************************************************************/
echo ("<h1>/* Tests des fonctions concernant le burndown_chart */</h1><br>\n");
/*****************************************************************************/

echo "<b>// test listeChiffragePlanifie</b><br>\n<ul>";
$result = $test->listeChiffragePlanifie(1);
while($row = $result->fetch_assoc()) {
  echo $row["BDC_id"]." | ".
    $row["BDC_chargePlanifie"]." | ".
    $row["SPR_id"]." | ".
    $row["PRO_id"]."<br>\n";
}
echo "</ul><br>\n";

echo "<b>// test listeChiffrageReel</b><br>\n<ul>";
$tab = $test->listeChiffrageReel(1);
foreach ($tab as $key => $value) {
  echo $key.' : '.$value.'<br/>'."\n";
}
echo "</ul><br>\n";

echo "<b>// test modifChiffragePlanifie</b><br>\n<ul>";
$test->modifChiffragePlanifie(1);
echo "</ul><br>\n";

echo "<b>// test sommeChiffragePlanifie</b><br>\n<ul>";
echo $test->sommeChiffragePlanifie(1)."\n";
echo "</ul><br>\n";
?>
