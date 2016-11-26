<?php
require_once("../web/config.php");

if (!isset($_SESSION["session"])) {
  $s->suppressionCookies();
  $s->head("Inscription");
  $s->header($db);
  $s->nav($db);

  $erreurDev = false;
  if (isset($_GET["erreur"])) {
    if (!strcmp($_GET["erreur"], "dev")) {
      $erreurDev = true;
      echo "Erreur : le developpeur n'a pas été créé";
    }
  }
  $erreurPseudo = false;
  if (isset($_GET["erreur"])) {
    if (!strcmp($_GET["erreur"], "pseudo")) {
      $erreurPseudo = true;
    }
  }
  $erreurRepetemdp = false;
  if (isset($_GET["erreur"])) {
    if (!strcmp($_GET["erreur"], "repetemdp")) {
      $erreurRepetemdp = true;
    }
  }
  $erreurMail = false;
  if (isset($_GET["erreur"])) {
    if (!strcmp($_GET["erreur"], "mail")) {
      $erreurMail = true;
    }
  }
  $erreurRepetemail = false;
  if (isset($_GET["erreur"])) {
    if (!strcmp($_GET["erreur"], "repetemail")){
      $erreurRepetemail = true;
    }
  }

  $prenom = "";
  if (isset($_GET["prenom"])) {
    $prenom = $_GET["prenom"];
  }
  $nom = "";
  if (isset($_GET["nom"])) {
    $nom = $_GET["nom"];
  }
  $pseudo = "";
  if (isset($_GET["pseudo"])) {
    $pseudo = $_GET["pseudo"];
  }
  $mail = "";
  if (isset($_GET["mail"])) {
    $mail = $_GET["mail"];
  }
  $repetemail = "";
  if (isset($_GET["repetemail"])) {
    $repetemail = $_GET["repetemail"];
  }
?>
          <article>
            <div class="col-sm-8 text-left">
              <h2>Inscription</h2>
              <hr>
<?php
  if ($erreurDev) {
    echo "Erreur : le developpeur n'a pas été créé";
  }
?>
              <form class="form-horizontal" action="../web/validationDeveloppeur.php"  method="post">
                <div class="form-group">
                  <div class="col-md-offset-0 col-md-8"><input class="form-control" type="text" name="prenom" maxlength="255" placeholder="Prénom" value="<?php echo $prenom; ?>" required/></div>
                </div>
                <div class="form-group">
                  <div class="col-md-offset-0 col-md-8"><input class="form-control" type="text" name="nom" maxlength="255" placeholder="Nom" value="<?php echo $nom; ?>" required/></div>
                </div>
                <div <?php if($erreurPseudo) echo 'class="form-group has-error"'; else echo 'class="form-group"'; ?>>
                  <div class="col-md-offset-0 col-md-8"><input class="form-control" type="text" name="pseudo" maxlength="20" placeholder="Pseudo" value="<?php echo $pseudo; ?>" required/></div>
<?php
  if ($erreurPseudo) {
    echo "Erreur : le pseudo existe déjà";
  }
?>
                </div>
                <div class="form-group">
                  <div class="col-md-offset-0 col-md-8"><input class="form-control" type="password" name="mdp" maxlength="255" placeholder="Mot de passe" required/></div>
                </div>
                <div <?php if($erreurRepetemdp) echo 'class="form-group has-error"'; else echo 'class="form-group"'; ?>>
                  <div class="col-md-offset-0 col-md-8"><input class="form-control" type="password" name="repetemdp" maxlength="255" placeholder="Répéter le mot de passe" required/></div>
<?php
  if ($erreurRepetemdp) {
    echo "Erreur : les mots de passe sont différents";
  }
?>
                </div>
                <div <?php if($erreurMail) echo 'class="form-group has-error"'; else echo 'class="form-group"'; ?>>
                  <div class="col-md-offset-0 col-md-8"><input class="form-control" type="email" name="mail" maxlength="255" placeholder="Adresse Email" value="<?php echo $mail; ?>" required/></div>
<?php
  if ($erreurMail) {
    echo "Erreur : le mail existe déjà";
  }
?>
                </div>
                <div <?php if($erreurRepetemail) echo 'class="form-group has-error"'; else echo 'class="form-group"'; ?>>
                  <div class="col-md-offset-0 col-md-8"><input class="form-control" type="email" name="repetemail"  maxlength="255" placeholder="Répéter l'adresse Email" value="<?php echo $repetemail; ?>" required/></div>
<?php
  if ($erreurRepetemail) {
    echo "Erreur : les mails sont différents";
  }
?>
                </div>
                <div class="form-group">
                  <div class="col-md-offset-0 col-md-8"><input class="form-control" type="url" name="url" maxlength="500" placeholder="URL image avatar"/></div>
                </div>
                <div class="form-group">
                  <div class="col-md-offset-0 col-md-8"><input class="btn btn-primary" type="submit" value="S'inscrire"></div>
                </div>
              </form>
            </div>
          </article>
<?php
  $s->footer();
} else {
  header("Location: ../web/index.php");
  exit();
}
?>
