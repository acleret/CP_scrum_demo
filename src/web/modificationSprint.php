<?php
require_once("../web/config.php");

$id_pro = $_COOKIE["id_projet"];
$id_spr = $_POST["id_sprint"];

$num = "";
$dateDebut = "";
$duree = "";

$s->head("Modification");
$s->header($db);
$s->nav($db);
?>
          <article>
            <div class="col-sm-8 text-left">
              <h2>Modification de <?php echo $_POST["nom_sprint"]; ?></h2>
              <hr>
                <form  class="form-horizontal" action="../web/listeSprints.php" method="post">
                  <div class="form-group">
                    <div class="col-md-offset-0 col-md-8">
                      <input class="form-control" type="number" name="num_sprint" placeholder="<?php echo $_POST["num_sprint"]; ?>" value="<?php echo $num; ?>"/>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-offset-0 col-md-8">
                      <input class="form-control" type="date" name="date_sprint" value="<?php echo $dateDebut; ?>"/>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-offset-0 col-md-8">
                      <input class="form-control" type="number" name="duree_sprint" placeholder="<?php echo $_POST["duree_sprint"]; ?>" value="<?php echo $duree; ?>"/>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-offset-0 col-md-1">
                      <input type="hidden" name="modif_sprint" value="<?php echo $id_spr; ?>">
                      <input class="btn btn-primary" type="submit" value="Modifier"/>
                    </div>
                    <div class="col-md-offset-0 col-md-10">
                      <a href="../web/listeSprints.php"><button type="button" class="btn btn-default">Annuler</button></a>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </article>           
<?php
  $s->footer();
?>