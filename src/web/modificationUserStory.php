<?php
require_once("../web/config.php");

if (isset($_COOKIE["id_projet"])) {
  if (isset($_SESSION["session"])) {
    if ($db->estMembreProjet($_COOKIE["id_projet"], $_SESSION["id_co"])) {
      if (isset($_POST["id_us"]) && isset($_POST["numero_us"]) && isset($_POST["nom_us"]) && isset($_POST["chiffrage_us"])) {
        $id_us = $_POST["id_us"];
        if ($db->testIDUserStory($id_us)) {
          $numero_us = $_POST["numero_us"];
          $nom_us = $_POST["nom_us"];
          $chiffrage = $_POST["chiffrage_us"];
          $id_pro = $_COOKIE["id_projet"];
          if (isset($_POST["priorite_us"])) {
            $priorite = $_POST["priorite_us"];
            if ($db->modifUserStoryProductOwner($id_us, $numero_us, $nom_us, $chiffrage, $priorite)) {
              header("Location: ../web/backlog.php?modif=OK");
              exit();
            } else {
                header("Location: ../web/backlog.php?modif=erreur");
              exit();
            }
          } else {
            if ($db->modifUserStory($id_us, $numero_us, $nom_us, $chiffrage)) {
              header("Location: ../web/backlog.php?modif=OK");
              exit();
            } else {
              header("Location: ../web/backlog.php?modif=erreur");
              exit();
            }
          }
        } else {
          header("Location: ../web/backlog.php?modif=erreurID");
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
