<?php
require_once("../web/config.php");

if(isset($_POST["id_projet"])) {
  if (!$db->testIDProjet($_POST["id_projet"])) {
    header("Location: ../web/index.php");
    exit();
  }
  $expire = time() + 60 * 60 * 24; // 24 heures
  setcookie("id_projet", $_POST["id_projet"], $expire);
  header("Location: ../web/projet.php");
  exit();
} else {
  header("Location: ../web/index.php");
  exit();
}
?>
