<?php
require_once("../web/config.php");

$s->head("CDP - Tests des requêtes");
$test = $db;


/*************************************************************************/
echo ("<h1>/** Tests des fonctions concernant les développeurs **/<br>
/************************************************************/</h1><br>\n");
echo "<br>\n";

// test infosDeveloppeur
echo "<b>// test infosDeveloppeur</b><br>\n<ul>";
$id_dev = 1;
if ($test->testIDDeveloppeur($id_dev)) {
  $result = $test->infosDeveloppeur($id_dev);
  $row = $result->fetch_assoc();
  echo $row["DEV_id"]." | ".$row["DEV_prenom"]." | ".$row["DEV_nom"]." | ".$row["DEV_pseudo"]." | ".$row["DEV_mdp"]." | ".$row["DEV_mail"]." | ".$row["DEV_urlAvatar"]." | ".$row["DEV_dateCreation"]."<br>\n<br>\n\n\n";
} else {
  echo "<li class=\"erreur\">Erreur le dév ".$id_dev." est inconnu</li>\n";
}
echo "</ul><br>\n";

// test pseudoDeveloppeur
echo "<b>// test pseudoDeveloppeur</b><br>\n<ul>";
$pseudo_dev1 = $test->pseudoDeveloppeur($id_dev)->fetch_assoc();
if (($res = $pseudo_dev1["DEV_pseudo"]) == "devpseudo01") {
  echo "<li class=\"correct\">Pseudo correct</li>\n";
} else {
  echo "<li class=\"erreur\">Erreur : le pseudo ne correpond pas</li>\n";
}
echo "</ul><br>\n";

// test listeDeveloppeurs
echo "<b>// test listeDeveloppeurs</b><br>\n<ul>";
$result = $test->listeDeveloppeurs();
while ($row = $result->fetch_assoc()) {
  echo $row["DEV_id"]." | ".$row["DEV_prenom"]." | ".$row["DEV_nom"]." | ".$row["DEV_pseudo"]." | ".$row["DEV_mdp"]." | ".$row["DEV_mail"]." | ".$row["DEV_urlAvatar"]." | ".$row["DEV_dateCreation"]."<br>\n";
}
echo "</ul><br>\n";

// test ajoutNouveauDeveloppeur
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

// test modifDeveloppeurMDP
echo "<b>// test modifDeveloppeurMDP</b><br>\n<ul>";
if ($test->modifDeveloppeurMDP($nbDevs, "nmpdtest2")) {
  echo "<li class=\"correct\">Le développeur ptest2 a un nouveau de passe !</li>\n";
} else {
  echo "<li class=\"erreur\">Erreur le développeur ptest2 a toujours son ancien mot de passe.</li>\n";
}
echo "</ul><br>\n";

// test supprDeveloppeur
echo "<b>// test supprDeveloppeur</b><br>\n<ul>";
if ($test->supprDeveloppeur($nbDevs)) {
  echo "<li class=\"correct\">Le compte du développeur ptest2 a été supprimé de la base !</li>\n";
} else {
  echo "<li class=\"erreur\">Erreur le compte du développeur ptest2 existe toujours.</li>\n";
}
echo "</ul><br>\n";

// test testPseudoDeveloppeur
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

// test testMailDeveloppeur
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

// test modifDeveloppeur
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

// test estDeveloppeurProjet
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

// test estScrumMaster
echo "<b>// test estScrumMaster</b><br>\n<ul>";
$id_dev = 1;
$id_pro = 1;
if ($test->estScrumMaster($id_dev, $id_pro)) {
  echo "<li class=\"correct\">Le développeur ".$id_dev." est Scrum Master sur le projet ".$id_pro."</li>\n";
} else {
  echo "<li class=\"erreur\">Le développeur ".$id_dev." n'est pas Scrum Master sur le projet ".$id_pro."</li>\n";
}
echo "</ul><br>\n";

// test estProductOwner
echo "<b>// test estProductOwner</b><br>\n<ul>";
$id_dev = 1;
$id_pro = 1;
if ($test->estProductOwner($id_dev, $id_pro)) {
  echo "<li class=\"correct\">Le développeur ".$id_dev." est ProductOwner sur le projet ".$id_pro."</li>\n";
} else {
  echo "<li class=\"erreur\">Le développeur ".$id_dev." n'est pas ProductOwner sur le projet ".$id_pro."</li>\n";
}
echo "</ul><br>\n";

// test estMembreProjet
echo "<b>// test estMembreProjet</b><br>\n<ul>";
$id_dev = 1;
$id_pro = 1;
if ($test->estMembreProjet($id_dev, $id_pro)) {
  echo "<li class=\"correct\">La personne n°".$id_dev." est bien membre du projet ".$id_pro."</li>\n";
} else {
  echo "<li class=\"erreur\">La personne n°".$id_dev." n'est pas membre du projet ".$id_pro."</li>\n";
}
echo "</ul><br>\n";

// test testIDDeveloppeur
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

// test listeProjetsDeveloppeurProductOwner
echo "<b>// test listeProjetsDeveloppeurProductOwner</b><br>\n<ul>";
$id_dev = 1; // le dev 1 est lié au projet 1, 2 et 4
$result = $test->listeProjetsDeveloppeurProductOwner($id_dev);
while ($row = $result->fetch_assoc()) {
  echo $row["PRO_id"]." | ".$row["PRO_nom"]." | ".$row["PRO_client"]." | ".$row["PRO_description"]." | ".$row["PRO_dateCreation"]." | ".$row["DEV_idProductOwner"]." | ".$row["DEV_idScrumMaster"]."<br>\n";
}
echo "</ul><br>\n";

// test listeProjetsDeveloppeur
echo "<b>// test listeProjetsDeveloppeur</b><br>\n<ul>";
$id_dev = 1; // le dev 1 est lié au projet 1, 2 et 4
$result = $test->listeProjetsDeveloppeur($id_dev);
while ($row = $result->fetch_assoc()) {
  echo $row["PRO_id"]." | ".$row["PRO_nom"]." | ".$row["PRO_client"]." | ".$row["PRO_description"]." | ".$row["PRO_dateCreation"]." | ".$row["DEV_idProductOwner"]." | ".$row["DEV_idScrumMaster"]."<br>\n";
}
echo "</ul><br>\n";

// test nombreProjetsDeveloppeur
echo "<b>// test nombreProjetsDeveloppeur</b><br>\n<ul>";
echo "<li class=\"correct\">Le dév n°".$id_dev." est lié à ".$test->nombreProjetsDeveloppeur($id_dev)." projets</li>\n";
echo "</ul><br>\n";

// test testDeveloppeurConnexion
echo "<b>// test testDeveloppeurConnexion</b><br>\n<ul>";
$pseudo = "pstest";
$mdp = "mdptest";
$result = $test->testDeveloppeurConnexion($pseudo, $mdp);
$row = $result->fetch_assoc();
if ($result->num_rows == 1) {
  echo $row["DEV_id"]." | ".$row["DEV_prenom"]." | ".$row["DEV_nom"]." | ".$row["DEV_pseudo"]." | ".$row["DEV_mdp"]." | ".$row["DEV_mail"]." | ".$row["DEV_urlAvatar"]." | ".$row["DEV_dateCreation"]."<br>\n<br>\n";
} else {
  echo "<li class=\"correct\">Mauvaise combinaison pseudo et mot de passe</li>\n";
}
echo "</ul><br>\n";


/*****************************************************************/
echo ("<h1>/** Tests des fonctions concernant les projets **/<br>
/****************************************************/</h1><br>\n");
echo "<br>\n";

// test infosProjet
echo "<b>// test infosProjet</b><br>\n<ul>";
$id_pro = 1;
if ($test->testIDProjet($id_pro)) {
  $result = $test->infosProjet($id_pro);
  $row = $result->fetch_assoc();
  echo $row["PRO_id"]." | ".$row["PRO_nom"]." | ".$row["PRO_client"]." | ".$row["PRO_description"]." | ".$row["PRO_dateCreation"]." | ".$row["DEV_idProductOwner"]." | ".$row["DEV_idScrumMaster"]."<br>\n";
} else {
  echo "<li class=\"erreur\">Erreur le projet ".$id_pro." est inconnu</li>\n";
}
echo "</ul><br>\n";

// test listeProjets
echo "<b>// test listeProjets</b><br>\n<ul>";
$result = $test->listeProjets();
while ($row = $result->fetch_assoc()) {
  echo $row["PRO_id"]." | ".$row["PRO_nom"]." | ".$row["PRO_client"]." | ".$row["PRO_description"]." | ".$row["PRO_dateCreation"]." | ".$row["DEV_idProductOwner"]." | ".$row["DEV_idScrumMaster"]."<br>\n";
}
echo "</ul><br>\n";

// test supprimerProjetBDD
echo "<b>// test supprimerProjetBDD</b><br>\n<ul>";
if ($test->supprimerProjetBDD(5)) {
	echo "<li class=\"correct\">Projet 5 bien supprimé</li>\n";
} else {
  echo "<li class=\"erreur\">Erreur lors de la suppression d'un projet</li>\n";
}
echo "</ul><br>\n";

// test nombreProjets
echo "<b>// test nombreProjets</b><br>\n<ul>";
$nb_projets = $test->nombreProjets();
echo ($nb_projets == $test->listeProjets()->num_rows)? "<li class=\"correct\">Bon calcul : "
.$nb_projets." projets</li>\n" : "<li class=\"erreur\">Mauvais calcul</li>\n";
echo "</ul><br>\n";

// test listeProjets
echo "<b>// test listeProjets</b><br>\n<ul>";
$result = $test->listeProjets();
while ($row = $result->fetch_assoc()) {
  echo $row["PRO_id"]." | ".$row["PRO_nom"]." | ".$row["PRO_client"]." | ".$row["PRO_description"]." | ".$row["PRO_dateCreation"]." | ".$row["DEV_idProductOwner"]." | ".$row["DEV_idScrumMaster"]."<br>\n";
}
echo "</ul><br>\n";

// test ajouterProjetBDD
echo "<b>// test ajouterProjetBDD</b><br>\n<ul>";
$idProjetMaxEnVisu = $test->maxIDProjet()+1;
$devs = array(0 => 1, 1 => 2, 2 => 3);
$idProjet = $test->ajouterProjetBDD("NouveauProjet", "Anonymous", "Projet test dans la BD", 1, 1, $devs);
if ($idProjetMaxEnVisu == $idProjet) {
echo $idProjet;
  echo "<li class=\"correct\">Projet créé</li>\n";
} else {
  echo "<li class=\"erreur\">Erreur dans la création du projet</li>\n";
}
echo "</ul><br>\n";

// test modifierProjetBDD
echo "<b>// test modifierProjetBDD</b><br>\n<ul>";
$result = $test->infosProjet($idProjet);
$row = $result->fetch_assoc();
echo "<li>Projet ".$idProjet." avant modif : <br>";
echo $row["PRO_id"]." | ".$row["PRO_nom"]." | ".$row["PRO_client"]." | ".$row["PRO_description"]." | ".$row["DEV_idProductOwner"]." | ".$row["DEV_idScrumMaster"]."<br></li><br>\n\n";
$devs[] = 10; // on rajoute le dév 10
unset($devs[2]); // on retire le dév 03
if ($test->modifierProjetBDD($idProjet, "MonDernierProjet", "Anonymous", "Projet test dans la BD", 1, 2, $devs)) {
  echo "<li class=\"correct\">Projet ".$idProjet." bien modifié : <br>";
	$result2 = $test->infosProjet($idProjet);
	$row2 = $result2->fetch_assoc();
	echo $row2["PRO_id"]." | ".$row2["PRO_nom"]." | ".$row2["PRO_client"]." | ".$row2["PRO_description"]." | ".$row2["DEV_idProductOwner"]." | ".$row2["DEV_idScrumMaster"]."</li>\n";
} else {
  echo "<li class=\"erreur\">Erreur dans la modification des données du projet ".$idprojet."</li>\n";
}
echo "</ul><br>\n";

// test listeDeveloppeursProjet
echo "<b>// test listeDeveloppeursProjet</b><br>\n<ul>";
$id_pro = 1; // le projet contient plusieurs développeurs
$result = $test->listeDeveloppeursProjet($id_pro);
while ($row = $result->fetch_assoc()) {
  echo $row["DEV_id"]." | ".$row["DEV_prenom"]." | ".$row["DEV_nom"]." | ".$row["DEV_pseudo"]." | ".$row["DEV_mdp"]." | ".$row["DEV_mail"]." | ".$row["DEV_urlAvatar"]." | ".$row["DEV_dateCreation"]."<br>\n";
}
echo "</ul><br>\n";

// test testIDProjet
echo "<b>// test testIDProjet</b><br>\n<ul>";
if ($test->testIDProjet(1)) {
  echo "<li class=\"correct\">Le projet 1 existe bien</li>\n";
} else {
  echo "<li class=\"erreur\">Le projet 1 n'existe pas</li>\n";
}
echo "</ul><br>\n";


/************************************************************************/
echo ("<h1>/** Tests des fonctions concernant les User Stories **/<br>
/***********************************************************/</h1><br>\n");
echo "<br>\n";

// test infosUserStory
echo "<b>// test infosUserStory</b><br>\n<ul>";
$id_us = 1;
if ($test->testIDUserStory($id_us)) {
  $result = $test->infosUserStory($id_us);
  $row = $result->fetch_assoc();
  echo $row["US_id"]." | ".$row["US_nom"]." | ".$row["US_chiffrageAbstrait"]." | ".$row["US_priorite"]." | ".$row["US_dateCreation"]." | ".$row["US_dateDernierCommit"]." | ".$row["US_idDernierCommit"]." | ".$row["US_auteurDernierCommit"]." |
  ".$row["PRO_id"]." |
  ".$row["SPR_id"]."<br>\n";
} else {
  echo "<li class=\"erreur\">Erreur l'US ".$id_us." est inconnue</li>\n";
}
echo "</ul><br>\n";

// test listeUserStories
echo "<b>// test listeUserStories</b><br>\n<ul>";
$id_pro = 4;
$result = $test->listeUserStories($id_pro);
while ($row = $result->fetch_assoc()) {
  echo $row["US_id"]." | ".$row["US_nom"]." | ".$row["US_chiffrageAbstrait"]." | ".$row["US_priorite"]." | ".$row["US_dateCreation"]." | ".$row["US_dateDernierCommit"]." | ".$row["US_idDernierCommit"]." | ".$row["US_auteurDernierCommit"]." |
  ".$row["PRO_id"]." |
  ".$row["SPR_id"]."<br>\n";
}
echo "</ul><br>\n";

// test listeUserStorySprint
echo "<b>// test listeUserStorySprint</b><br>\n<ul>";
$id_spr = 1;
$result = $test->listeUserStorySprint($id_spr);
echo "US dans le sprint#".$id_spr."<br>\n";
while ($row = $result->fetch_assoc()) {
  echo $row["US_id"]." | ".$row["US_nom"]." | ".$row["US_chiffrageAbstrait"]." | ".$row["US_priorite"]." | ".$row["US_dateCreation"]." | ".$row["US_dateDernierCommit"]." | ".$row["US_idDernierCommit"]." | ".$row["US_auteurDernierCommit"]." |
  ".$row["PRO_id"]." |
  ".$row["SPR_id"]."<br>\n";
}
echo "</ul><br>\n";

// test listeUserStoryOutOfSprint
echo "<b>// test listeUserStoryOutOfSprint</b><br>\n<ul>";
$id_spr = 1;
$id_pro = 1;
$result = $test->listeUserStoryOutOfSprint($id_spr, $id_pro);
echo "US dans du projet ".$id_pro." or du sprint#".$id_spr."<br>\n";
while ($row = $result->fetch_assoc()) {
  echo $row["US_id"]." | ".$row["US_nom"]." | ".$row["US_chiffrageAbstrait"]." | ".$row["US_priorite"]." | ".$row["US_dateCreation"]." | ".$row["US_dateDernierCommit"]." | ".$row["US_idDernierCommit"]." | ".$row["US_auteurDernierCommit"]." |
  ".$row["PRO_id"]." |
  ".$row["SPR_id"]."<br>\n";
}
echo "</ul><br>\n";

// test ajoutUserStory
echo "<b>// test ajoutUserStory</b><br>\n<ul>";
if ($test->ajoutUserStory("us1", 5, "NULL", 1)) {
  echo "<li class=\"correct\">US créée</li>\n";
} else {
  echo "<li class=\"erreur\">Erreur dans la création d'une US</li>\n";
}
echo "</ul><br>\n";

// test modifUserStory
echo "<b>// test modifUserStory</b><br>\n<ul>";
$id_us = $test->maxIDUserStory();
if ($test->modifUserStory($id_us, "US1Modifiée", 8, "NULL", "NULL")) {
  echo "<li class=\"correct\">US ".$id_us." modifiée</li>\n";
} else {
  echo "<li class=\"erreur\">Erreur dans la modification des données de l'US ".$id_us."</li>\n";
}
echo "</ul><br>\n";

// test modifUserStoryTracabilite
echo "<b>// test modifUserStoryTracabilite</b><br>\n<ul>";
$id_us = $test->maxIDUserStory();
if ($test->modifUserStoryTracabilite($id_us, "Now()", "commit_id", "developpeur responsable du commit")) {
  echo "<li class=\"correct\">US ".$id_us." modifiée</li>\n";
} else {
  echo "<li class=\"erreur\">Erreur dans la modification des données d'une US ".$id_us."</li>\n";
}
echo "</ul><br>\n";

// test affecterUserStorySprint
echo "<b>// test affecterUserStorySprint</b><br>\n<ul>";
$id_us = $test->maxIDUserStory();
$id_spr = 4;
if ($test->affecterUserStorySprint($id_us, $id_spr)) {
  echo "<li class=\"correct\">US ".$id_us." affectée au sprint ".$id_spr."</li>\n";
} else {
  echo "<li class=\"erreur\">Erreur lors de l'affectation d'un sprint à l'US ".$id_us."</li>\n";
}
echo "</ul><br>\n";

// test retirerUserStorySprint
echo "<b>// test retirerUserStorySprint</b><br>\n<ul>";
$id_us = $test->maxIDUserStory();
$id_spr = 4;
if ($test->retirerUserStorySprint($id_us)) {
  echo "<li class=\"correct\">US ".$id_us." retirée du sprint ".$id_spr."</li>\n";
} else {
  echo "<li class=\"erreur\">Erreur dans la modification des données d'une US ".$id_us."</li>\n";
}
echo "</ul><br>\n";

// test suppressionUserStory
echo "<b>// test suppressionUserStory</b><br>\n<ul>";
$id_us = $test->maxIDUserStory();
if ($test->testIDUserStory($id_us)) {
  if ($test->suppressionUserStory($id_us)) {
    echo "<li class=\"correct\">US ".$id_us." supprimée</li>\n";
  } else {
    echo "<li class=\"erreur\">Erreur lors de la suppression d'une US</li>\n";
  }
} else {
  echo "<li class=\"erreur\">L'US ".$id_us." n'existe pas</li>\n";
}
echo "</ul><br>\n";

// test testIDUserStory
echo "<b>// test testIDUserStory</b><br>\n<ul>";
$id_us = 1;
if ($test->testIDUserStory($id_us)) {
  echo "<li class=\"correct\">L'US ".$id_us." existe bien</li>\n";
} else {
  echo "<li class=\"erreur\">L'US ".$id_us." n'existe pas</li>\n";
}
echo "</ul><br>\n";


/*****************************************************************/
echo ("<h1>/** Tests des fonctions concernant les sprints **/<br>
/****************************************************/</h1><br>\n");
echo "<br>\n";

// test listeSprints
echo "<b>// test listeSprints</b><br>\n<ul>";
$id_pro = 1;
$result = $test->listeSprints($id_pro);
while ($row = $result->fetch_assoc()) {
  echo $row["SPR_id"]." | ".$row["SPR_numero"]." | ".$row["SPR_dateDebut"]." | ".$row["SPR_duree"]." | ".$row["PRO_id"]."<br>\n";
}
echo "</ul><br>\n";

// test infosSprint
echo "<b>// test infosSprint</b><br>\n<ul>";
$id_spr = 5;
$result = $test->infosSprint($id_spr);
$row = $result->fetch_assoc();
echo $row["SPR_id"]." | ".$row["SPR_numero"]." | ".$row["SPR_dateDebut"]." | ".$row["SPR_duree"]." | ".$row["PRO_id"]."<br>\n";
echo "</ul><br>\n";

// test ordonnerDate
echo "<b>// test ordonnerDate</b><br>\n<ul>";
$id_pro = 1;
$id_spr = 1;
$result = $test->infosSprint($id_spr);
$date = $row["SPR_dateDebut"];
echo $date." -> ";
echo $test->ordonnerDate($date);
echo "</ul><br>\n";

// test ajoutSprint
echo "<b>// test ajoutSprint</b><br>\n<ul>";
if ($test->ajoutSprint("11", "2011-11-11", 11, 1)) {
  echo "<li class=\"correct\">Sprint ajouté</li>\n";
} else {
  echo "<li class=\"erreur\">Erreur dans la création d'une US</li>\n";
}
echo "</ul><br>\n";

// test supprimerSprint
echo "<b>// test supprimerSprint</b><br>\n<ul>";
$id_spr = 11;
if ($test->supprimerSprint($id_spr)) {
  echo "<li class=\"correct\">Sprint supprimé</li>\n";
} else {
  echo "<li class=\"erreur\">Erreur dans la création d'une US</li>\n";
}
echo "</ul><br>\n";

// test ModifSprint
echo "<b>// test modifSprint</b><br>\n<ul>";
$id_spr = 4;
$result = $test->infosSprint($id_spr);
$row = $result->fetch_assoc();
echo "<li>avant modif : <br>";
echo $row["SPR_id"]." | ".$row["SPR_numero"]." | ".$row["SPR_dateDebut"]." | ".$row["SPR_duree"]." | ".$row["PRO_id"]."<br>\n";
echo "</li><br>\n<li>après modif : <br>";
$test->modifSprint($id_spr, "22", "2222-12-22", 22);
$result2 = $test->infosSprint($id_spr);
$row2 = $result2->fetch_assoc();
echo $row2["SPR_id"]." | ".$row2["SPR_numero"]." | ".$row2["SPR_dateDebut"]." | ".$row2["SPR_duree"]." | ".$row2["PRO_id"]."<br>\n";
echo "</li></ul><br>\n";

?>