<?php
require_once("../web/config.php");

if (isset($_SESSION["session"])) {
  session_destroy();
}

header("Location: ../web/index.php");
exit();
?>
