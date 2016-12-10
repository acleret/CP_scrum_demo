<?php
require_once("config.php");

if (isset($_POST["suppr_projet"])) {
  $res = $db->supprimerProjetBDD($_POST["suppr_projet"]);
  $s->suppressionCookies();
  if (isset($_POST["pageActuelle"])) {
        if ($res)
            header("Location: listeProjets.php?page=".$_POST["pageActuelle"]);
    }
    else
        header("Location: index.php");
  exit();
}

if (isset($_COOKIE["id_projet"])) {
    $id_pro = $_COOKIE["id_projet"];
    $infos = $db->infosProjet($id_pro);
    $row = $infos->fetch_assoc();
    $s->head("Page projet");
    $s->header($db);
    $s->nav($db);
?>
          <article>
            <div class="col-sm-8 text-left">
              <h2>Projet '<?php echo $row["PRO_nom"];?>'</h2>
              <hr>
              <dl class="dl-horizontal">
                <dt>Date de création</dt>
                <dd><?php echo $row["PRO_dateCreation"];?></dd>
                <br>
                <dt>Client</dt>
                <dd><?php echo $row["PRO_client"];?></dd>
                <dt>Product owner</dt>
                <dd><?php
                $id_PO = $row["DEV_idProductOwner"];
                $infos_PO = $db->infosDeveloppeur($id_PO);
                $row_PO = $infos_PO->fetch_assoc();
                echo "<a href=\"profil.php?profil=".$row_PO["DEV_pseudo"]."\">".$row_PO["DEV_pseudo"]."</a>";
                ?></dd>
                <dt>Scrum master</dt>
                <dd><?php
                $id_SM = $row["DEV_idScrumMaster"];
                $infos_SM = $db->infosDeveloppeur($id_SM);
                $row_SM = $infos_SM->fetch_assoc();
                echo "<a href=\"profil.php?profil=".$row_SM["DEV_pseudo"]."\">".$row_SM["DEV_pseudo"]."</a>";
                ?></dd>
              </dl>
              <dl class="dl-horizontal">
                <dt>Développeurs</dt>
                <dd>
                  <ul class="list-inline">
<?php
    $result = $db->listeDeveloppeursProjet($id_pro);
    while ($row_dev = $result->fetch_assoc()) {
        echo "                    <li><a href=\"profil.php?profil=".$row_dev["DEV_pseudo"]."\">".$row_dev["DEV_pseudo"]."</a></li>\n";
    }
?>
                  </ul>
                </dd>
              </dl>
              <hr>
              <p class="text-justify"><?php echo $row["PRO_description"]; ?></p>
              <br>
              <br>
            </div>
          </article>
<?php
    if (isset($_SESSION["session"])) {
        if ($db->estMembreProjet($row["PRO_id"], $_SESSION["id_co"])) {
?>
          <aside>
            <div class="col-sm-2 sidenav">
              <form style="display: inline;" action="../web/formulaireProjet.php" method="post">
                <input type="hidden" name="action" value="éditer"/>
                <input type="hidden" name="idProjet" value="<?php echo $row["PRO_id"];?>"/>
                <input type="hidden" name="nom" value="<?php echo $row["PRO_nom"];?>"/>
                <input type="hidden" name="client" value="<?php echo $row["PRO_client"];?>"/>
                <input type="hidden" name="descr" value="<?php echo $row["PRO_description"];?>"/>
                <input type="hidden" name="id_po" value="<?php echo $row["DEV_idProductOwner"];?>"/>
                <input type="hidden" name="id_sm" value="<?php echo $row["DEV_idScrumMaster"];?>"/>
                <input class="btn btn-default" type="submit" value="Modifier"/>
              </form>
              <br>
              <br>
                      <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#supprimerModal<?php echo $_COOKIE["id_projet"]; ?>">Supprimer</button>

                      <!-- Modal Suppression -->
                      <div style="text-align: left" id="supprimerModal<?php echo $_COOKIE["id_projet"]; ?>" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                          <!-- Modal content-->
                          <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                              <h4 class="modal-title">Confirmation de suppression d'un projet</h4>
                            </div>
                            <div class="modal-body">
                              <p>Attention action irréversible</p>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                              <form style="display: inline;" action="" method="post">
                                <input type="hidden" name="suppr_projet" value="<?php echo $_COOKIE["id_projet"]; ?>"/>
                                <input class="btn btn-danger" type="submit" value="Supprimer"/>
                              </form>
                            </div>
                          </div>
                        </div>
                      </div>
            </div>
          </aside>
<?php
        }
    }
    $s->footer();
} else {
  $url = $_SERVER["REQUEST_URI"];
  header("Location: ../web/index.php?url=".$url);
  exit();
}
?>
