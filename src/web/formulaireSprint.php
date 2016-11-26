<?php
require_once("config.php");

$id_pro = $_COOKIE["id_projet"];

$num = "";
$dateDebut = "";
$duree = "";

$s->head("Ajouter un Sprint");
$s->header($db);
$s->nav($db);
?>
          <article>
            <div class="col-sm-8 text-left">
              <h2>Ajouter un Sprint</h2>
              <hr>
              <form class="form-horizontal" action="../web/listeSprints.php"  method="post">
                <div class="form-group">
                  <div class="col-md-offset-0 col-md-8">
                    <input class="form-control" type="number" name="numero" placeholder="Numéro" value="<?php echo $num; ?>" required/>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-md-offset-0 col-md-8">
                    <input class="form-control" type="date" name="dateDebut" placeholder="Date de début" value="<?php echo $dateDebut; ?>" required/>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-md-offset-0 col-md-8">
                    <input class="form-control" type="number" name="duree" placeholder="Durée" value="<?php echo $duree; ?>" required/>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-md-offset-0 col-md-1">
                    <input type="hidden" name="ajout_sprint">
                    <input class="btn btn-primary" type="submit" value="Ajouter">
                  </div>
                  <div class="col-md-offset-0 col-md-10">
                    <a href="../web/listeSprints.php"><button type="button" class="btn btn-default">Annuler</button></a>
                  </div>
                </div>
              </form>
             
            </div>
          </article>           
<?php
  $s->footer();
?>