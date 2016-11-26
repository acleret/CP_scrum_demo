<?php
require_once("../web/config.php");

if (isset($_COOKIE["id_projet"])) {
  if (isset($_SESSION["session"])) {
    if ($db->estMembreProjet($_COOKIE["id_projet"], $_SESSION["id_co"])) {
      if (isset($_POST["nom_us"]) && isset($_POST["chiffrage_us"])) {
        $nom_us = $_POST["nom_us"];
        $chiffrage = $_POST["chiffrage_us"];
        $id_pro = $_COOKIE["id_projet"];
        if (isset($_POST["priorite_us"])) {
          $priorite = $_POST["priorite_us"];
        } else {
          $priorite = "NULL";
        }
        if ($db->ajoutUserStory($nom_us, $chiffrage, $priorite, $id_pro)) {
          header("Location: ../web/backlog.php?ajout=OK");
          exit();
        } else {
          header("Location: ../web/backlog.php?ajout=erreur");
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
} else {
  header("Location: ../web/index.php");
  exit();
}
?>
