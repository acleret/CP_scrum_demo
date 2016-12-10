<?php
require_once("../web/config.php");

if (!isset($_SESSION["session"])) {
  $s->suppressionCookies();
  $s->head("Connexion");
  $s->header($db);
  $s->nav($db);
?>
          <article>
            <div class="col-sm-8 text-left">
              <h2>Connexion</h2>
              <hr>
<?php
  $erreurKO = false;
  if (isset($_GET["erreur"])) {
    if (!strcmp($_GET["erreur"], "KO")) {
      $erreurKO = true;
      echo "Erreur : mauvais pseudo ou mot de passe<br>\n";
    }
  }
  if (isset($_GET["url"])) {
    if (!strcmp($_GET["url"], "OK"))
      echo "le profil a été créé";
  }
?>
              <form action="../web/verificationConnexion.php" class="form-horizontal" method="post" accept-charset="utf-8">
                <div <?php if($erreurKO) echo 'class="form-group has-error"'; else echo 'class="form-group"'; ?>>
                  <div class="col-md-8"><input name="identifiant" placeholder="Identifiant" class="form-control" type="text" id="DevPseudo" required autofocus/></div>
                </div>
                <div <?php if($erreurKO) echo 'class="form-group has-error"'; else echo 'class="form-group"'; ?>>
                  <div class="col-md-8"><input name="motDePasse" placeholder="Mot de passe" class="form-control" type="password" id="DevMDP" required /></div>
                </div>
                <div class="checkbox">
                  <label><input type="checkbox" name="resterConnecte" value="1" />Rester connecté</label>
                </div>
                <br />
                <div class="form-group">
                  <div class="col-md-offset-0 col-md-8"><input class="btn btn-primary" type="submit" value="Se connecter"/></div>
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
