<?php
session_start(); // cette fonction est obligatoire dans toutes les pages avant le code html

if (isset($_SESSION["expire"])) {
  if (time() > $_SESSION["expire"]) {
    session_destroy();
  }
}

require_once("../web/requetes.php");
require_once("../web/structure.php");

/******* Configuration BD local *******/
$DBhost  = "localhost";  // serveur de la bdd
$DBowner = "root";  // login SQL
$DBpwd   = "";  // password SQL
$DBname  = "cp_scrum"; // nom de la bdd

/******* Configuration BD au Cremi // à choisir si connexion au Cremi *******/
/*
$DBhost  = "dbserver";  // serveur de la bdd
$DBowner = "<LOGIN DE LA SESSION>";  // login SQL
$DBpwd   = "<MOT DE PASSE DE LA SESSION>";  // password SQL
$DBname  = "tvigue"; // nom de la bdd
*/
/*
$DBhost  = "dbserver";  // serveur de la bdd
$DBowner = "acleret";  // login SQL
$DBpwd   = "azerty";  // password SQL
$DBname  = "acleret"; // nom de la bdd
*/

/******* Etablissement de la connexion SQL *******/
$db = new Requetes($DBhost, $DBowner, $DBpwd, $DBname);

if ($db->verifConnexion()) {
  die ("Connection failed: ".$db->verifConnexion());
}

/******* Creation structure d'une page *******/
$s = new Structure();
?>
