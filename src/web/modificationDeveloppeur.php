<?php
require_once("../web/config.php");
if (isset($_SESSION["session"])) {
  if (isset($_POST["prenom"]) && isset($_POST["nom"]) /*&& isset($_POST["pseudo"])*/ && isset($_POST["url"])) {
    if (empty($_POST["prenom"])) {
      $prenom = $_SESSION["prenom_co"];
    } else {
      $prenom = $_POST["prenom"];
    }
    if (empty($_POST["nom"])) {
      $nom = $_SESSION["nom_co"];
    } else {
      $nom = $_POST["nom"];
    }
    if (empty($_POST["pseudo"])) {
      $pseudo = $_SESSION["pseudo_co"];
    } else {
      $pseudo = $_POST["pseudo"];
      if ($db->testPseudoDeveloppeur($pseudo)) {
        header("Location: ../web/formulaireProfil.php?erreur=pseudo".
        "&prenom=".$_POST["prenom"].
        "&nom=".$_POST["nom"]);
        exit();
      }
    }
    if (empty($_POST["url"])) {
      $url = $_SESSION["image_co"];
    } else {
      $url = $_POST["url"];
    }
    if ($db->modifDeveloppeur($_SESSION["id_co"], $prenom, $nom, $pseudo, $url)) {
    /*
      //Variables du formulaire
      $mail = $_SESSION["email_co"];

      // Mail
      $objet = 'Confirmation de la modification de votre profil' ;
      $contenu = '<html>'.
      "\t".'<body>'."\r\n".
      "\t\t".'<p>Bonjour Mr/Mme '.$nom.'</p>'."\r\n".
      "\t\t".'<p>blablablabla</p>'."\r\n".
      "\t".'</body>'."\r\n".
      '</html>'."\r\n";
      $entetes = 'Content-type: text/html; charset=utf-8'."\r\n".
      'From: noreply@u-bordeaux.fr'."\r\n".
      'Reply-To: noreply@u-bordeaux.fr'."\r\n".
      'X-Mailer: PHP/'.phpversion();

      //Envoi d'un mail de confirmation
      mail($mail, $objet, $contenu, $entetes);
    */
      $_SESSION["prenom_co"] = $prenom;
      $_SESSION["nom_co"] = $nom;
      $_SESSION["pseudo_co"] = $pseudo;
      $_SESSION["image_co"] = $url;
      header("Location: ../web/profil.php?url=OK");
      exit();
    } else {
      header("Location: ../web/formulaireProfil.php?erreur=dev");
      exit();
    }
  } else {
    header("Location: ../web/profil.php");
    exit();
  }
} else {
  header("Location: ../web/index.php");
  exit();
}
?>