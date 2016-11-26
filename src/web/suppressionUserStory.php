<?php
require_once("../web/config.php");

if (isset($_COOKIE["id_projet"])) {
  if (isset($_SESSION["session"])) {
    if ($db->estMembreProjet($_COOKIE["id_projet"], $_SESSION["id_co"])) {
      if(isset($_POST["id_us"])) {
        if (!$db->testIDUserStory($_POST["id_us"])) {
          header("Location: ../web/backlog.php?suppr=erreurID");
          exit();
        }
        if ($db->suppressionUserStory($_POST["id_us"])) {
          header("Location: ../web/backlog.php?suppr=OK");
          exit();
        } else {
          header("Location: ../web/backlog.php?suppr=erreur");
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
