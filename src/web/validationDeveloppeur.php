<?php
require_once("../web/config.php");

if (isset($_POST["prenom"]) && isset($_POST["nom"]) &&
    isset($_POST["pseudo"]) && isset($_POST["mdp"]) &&
    isset($_POST["repetemdp"]) && isset($_POST["mail"]) &&
    isset($_POST["repetemail"]) && isset($_POST["url"])) {
  if (strcmp($_POST["mdp"], $_POST["repetemdp"]) != 0) {
    header("Location: ../web/inscription.php?erreur=repetemdp".
    "&prenom=".$_POST["prenom"].
    "&nom=".$_POST["nom"].
    "&pseudo=".$_POST["pseudo"].
    "&mail=".$_POST["mail"].
    "&repetemail=".$_POST["repetemail"]);
    exit();
  } else {
  if (strcmp($_POST["mail"], $_POST["repetemail"]) != 0) {
    header("Location: ../web/inscription.php?erreur=repetemail".
    "&prenom=".$_POST["prenom"].
    "&nom=".$_POST["nom"].
    "&pseudo=".$_POST["pseudo"].
    "&mail=".$_POST["mail"]);
    exit();
  } else {
    if ($db->testPseudoDeveloppeur($_POST["pseudo"])) {
        header("Location: ../web/inscription.php?erreur=pseudo".
        "&prenom=".$_POST["prenom"].
        "&nom=".$_POST["nom"].
        "&mail=".$_POST["mail"].
        "&repetemail=".$_POST["repetemail"]);
        exit();
      } else {
        if ($db->testMailDeveloppeur($_POST["mail"])) {
          header("Location: ../web/inscription.php?erreur=mail".
          "&prenom=".$_POST["prenom"].
          "&nom=".$_POST["nom"].
          "&pseudo=".$_POST["pseudo"]);
          exit();
        } else {
          $url = $_POST["url"];
          if ($db->ajoutNouveauDeveloppeur($_POST["prenom"],
              $_POST["nom"], $_POST["pseudo"], $_POST["mdp"],
              $_POST["mail"], $url)) {
            header("Location: ../web/connexion.php?url=OK");
            exit();
          } else {
            header("Location: ../web/inscription.php?erreur=dev".
            "&prenom=".$_POST["prenom"].
            "&nom=".$_POST["nom"].
            "&pseudo=".$_POST["pseudo"].
            "&mail=".$_POST["mail"].
            "&repetemail=".$_POST["repetemail"]);
            exit();
          }
        }
      }
    }
  }
}
else {
  header("Location: ../web/index.php");
  exit();
}
?>
