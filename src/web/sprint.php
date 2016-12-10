<?php
require_once("../web/config.php");

if (isset($_COOKIE["id_projet"])) {

  $id_pro = $_COOKIE["id_projet"];
  $infos = $db->infosProjet($id_pro);
  $row = $infos->fetch_assoc();

  if (isset($_POST["ret_us"])) {
    $retirer_us_sprint = $db->retirerUserStorySprint($_POST["ret_us"]);
  }

  if (isset($_POST["inser_us"])) {
    $retirer_us_sprint = $db->affecterUserStorySprint($_POST["inser_us"], $_POST["id_sprint"]);
    if($db->listeUserStoriesAvecCommit($id_pro)->num_rows == 0) {
      if (($db->listeUserStoryOutOfSprints($id_pro)->num_rows == 0) && ($db->listeUserStories($id_pro)->num_rows > 0)) {
          $db->modifChiffragePlanifie($id_pro);
      }
    }
  }

  if (isset($_POST["modif_sprint"])) {
    $id_sprint_modif = $_POST["modif_sprint"];
    $infos_sprint_modif = $db->infosSprint($id_sprint_modif);
    $row_sprint_modif = $infos_sprint_modif->fetch_assoc();
    if($_POST["num_sprint"] == NULL) $modif_num = $row_sprint_modif["SPR_numero"]; else $modif_num = $_POST["num_sprint"];
    if($_POST["date_sprint"] == NULL) $modif_date = $row_sprint_modif["SPR_dateDebut"]; else $modif_date = $_POST["date_sprint"];
    if($_POST["duree_sprint"] == NULL) $modif_duree = $row_sprint_modif["SPR_duree"]; else $modif_duree = $_POST["duree_sprint"];
    $db->modifSprint($id_sprint_modif, $modif_num, $modif_date, $modif_duree);
  }

  // le numero a t-il été modifié
  $nom_spr = (isset($modif_num)) ? "Sprint#0".$modif_num : $_POST["nom_sprint"] ;

  if (!isset($_POST["id_sprint"]) || !isset($_POST["nom_sprint"])) {
    header("Location: ../web/index.php");
    exit();
  }

  $id_spr = $_POST["id_sprint"];
  $infos_spr = $db->infosSprint($id_spr);
  $row2 = $infos_spr->fetch_assoc();

  $expire = time() + 60 * 60 * 24; // 24 heures
  setcookie("id_sprint", $row2["SPR_id"], $expire);


  $s->head("Sprints");
  $s->header($db);
  $s->nav($db);
?>
          <script>
            $(document).ready(function() {
              $('#tableSprint').DataTable( {
                "order": [[ 3, "asc" ]],
                "oLanguage": {
                  "sLengthMenu": "Afficher _MENU_ entrées",
                  "sSearch": "<span class=\"glyphicon glyphicon-search\"></span> Recherche:",
                  "sEmptyTable": "Aucunes données",
                  "sInfo": "Affichage de _START_ à _END_ sur _TOTAL_ entrées",
                  "sInfoEmpty": "",
                  "oPaginate": {
                    "sPrevious": "Précédent",
                    "sNext": "Suivant"
                  }
                }
              } );
              $('#tableAddUS').DataTable( {
                "order": [[ 3, "asc" ]],
                "oLanguage": {
                  "sLengthMenu": "Afficher _MENU_ entrées",
                  "sSearch": "<span class=\"glyphicon glyphicon-search\"></span> Recherche:",
                  "sEmptyTable": "Aucunes données",
                  "sInfo": "Affichage de _START_ à _END_ sur _TOTAL_ entrées",
                  "sInfoEmpty": "",
                  "oPaginate": {
                    "sPrevious": "Précédent",
                    "sNext": "Suivant"
                  }
                }
              } );
            } );
          </script>
          <article>
            <div class="col-sm-8 text-left">
              <h2><?php echo $row["PRO_nom"]." - ".$nom_spr;?></h2>
              <hr>
              <div class="col-sm-8 text-left">
                <dl class="dl-horizontal">
                  <dt>Début</dt>
                  <dd><?php echo $db->ordonnerDate($row2["SPR_dateDebut"]); ?></dd>
                  <dt>Durée</dt>
                  <dd><?php echo $row2["SPR_duree"]." jours"; ?></dd>
                  <dt>Chiffrage total</dt>
                  <dd>
<?php
    $cout_sprint = $db->sommeChiffrageSprint($id_spr);
    if (empty($cout_sprint))
      echo "-\n";
    else
      echo $cout_sprint."\n";
?>
                  </dd>
                </dl>
              </div>
              <div class="col-sm-4 text-right">
                <form style="display: inline;" action="../web/kanban.php" method="post">
                  <input type="hidden" name="id_sprint" value="<?php echo $id_spr; ?>"/>
                  <input class="btn btn-primary" type="submit" value="Kanban"/>
                </form>
              </div>
              <br>
              <br>
              <br>
              <hr>
              <table id="tableSprint" class="table table-striped table-hover">
                <thead>
                  <tr>
                    <th>Numéro US</th>
                    <th>Nom</th>
                    <th>Chiffrage abstrait</th>
                    <th>Priorité</th>
<?php
  if (isset($_SESSION["session"]) && $db->estMembreProjet($row["PRO_id"], $_SESSION["id_co"])) {
?>
                    <th>Actions</th>
<?php
  }
?>
                  </tr>
                </thead>
                <tbody>
<?php
  $listeUS = $db->listeUserStorySprint($id_spr);
  while ($row3 = $listeUS->fetch_assoc()) {
    $id_us = $row3["US_id"];
    $infos_us = $db->infosUserStory($id_us);
    $row3 = $infos_us->fetch_assoc();
?>
                <tr>
                  <td><?php echo 'US#'.$row3["US_numero"]; ?></td>
                  <td><?php echo $row3["US_nom"]; ?></td>
                  <td><?php echo $row3["US_chiffrageAbstrait"]; ?></td>
                  <td><?php echo $row3["US_priorite"]; ?></td>
<?php
    if (isset($_SESSION["session"]) && $db->estMembreProjet($row["PRO_id"], $_SESSION["id_co"])) {
?>
                  <td>
                    <form style="display: inline;" action="" method="post">
                      <input type="hidden" name="ret_us" value="<?php echo $id_us; ?>"/>
                      <input type="hidden" name="id_sprint" value="<?php echo $id_spr;?>"/>
                      <input type="hidden" name="nom_sprint" value="<?php echo $_POST["nom_sprint"];?>"/>
                      <input class="btn btn-default" type="submit" value="Retirer"/>
                    </form>
                  </td>
<?php
    }
?>
                </tr>
<?php
  }
?>
              </tbody>
            </table>
<?php
  if (isset($_SESSION["session"]) && $db->estMembreProjet($row["PRO_id"], $_SESSION["id_co"])) {
?>
            <hr>
            <h3>Ajouter user stories</h3>
            <br>
            <table id="tableAddUS" class="table table-striped table-hover">
              <thead>
                <tr>
                  <th>Numéro US</th>
                  <th>Nom</th>
                  <th>Chiffrage abstrait</th>
                  <th>Priorité</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
<?php
    $listeUS2 = $db->listeUserStoryOutOfSprints($id_pro);
    while ($row4 = $listeUS2->fetch_assoc()) {
      $id_us = $row4["US_id"];
      $infos_us = $db->infosUserStory($id_us);
      $row4 = $infos_us->fetch_assoc();
?>
                <tr>
                  <td><?php echo 'US#'.$row4["US_numero"]; ?></td>
                  <td><?php echo $row4["US_nom"]; ?></td>
                  <td><?php echo $row4["US_chiffrageAbstrait"]; ?></td>
                  <td><?php echo $row4["US_priorite"]; ?></td>
                  <td>
                    <form style="display: inline;" action="" method="post">
                      <input type="hidden" name="inser_us" value="<?php echo $id_us; ?>"/>
                      <input type="hidden" name="id_sprint" value="<?php echo $id_spr; ?>"/>
                      <input type="hidden" name="nom_sprint" value="<?php echo $_POST["nom_sprint"];?>"/>
                      <input class="btn btn-default" type="submit" value="Ajouter"/>
                    </form>
                  </td>
                </tr>
<?php
    }
?>
              </tbody>
            </table>
          </div>
          <aside>
            <div class="col-sm-2 sidenav">
              <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modalModif<?php echo $row2["SPR_id"];?>">Modifier</button><br><br>
              <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#supprimerModal<?php echo $id_spr; ?>">Supprimer</button>

              <!-- Modal Suppression -->
              <div style="text-align: left" id="supprimerModal<?php echo $id_spr; ?>" class="modal fade" role="dialog">
                <div class="modal-dialog">
                  <!-- Modal content-->
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Confirmation de suppression d'un sprint</h4>
                    </div>
                    <div class="modal-body">
                      <p>Attention action irréversible</p>
                    </div>
                    <div class="modal-footer">
                      <form  action="../web/listeSprints.php" method="post">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                        <input type="hidden" name="suppr_sprint" value="<?php echo $id_spr; ?>"/>
                        <input class="btn btn-danger" type="submit" value="Supprimer"/>
                      </form>
                    </div>
                  </div>
                </div>
              </div>


                    <!-- Modal Modification -->
                    <div id="modalModif<?php echo $row2["SPR_id"];?>" class="modal fade" role="dialog" style="text-align:left">
                      <div class="modal-dialog">
                      <!-- Modal content-->
                      <div class="modal-content">
                        <form style="display: inline;" action="" method="post">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Modifier un sprint</h4>
                          </div>
                          <div class="modal-body">
                            <div class="form-group">
                              <label>Numéro</label>
                              <input class="form-control" type="number" name="num_sprint" placeholder="Numéro" value="<?php echo $row2["SPR_numero"];?>"/>
                            </div>
                            <div class="form-group">
                              <label>Date de début</label>
                              <input class="form-control" type="date" name="date_sprint" value="<?php echo $row2["SPR_dateDebut"];?>"/>
                            </div>
                            <div class="form-group">
                              <label>Durée</label>
                              <input class="form-control" type="number" name="duree_sprint" placeholder="Nombre de jours" value="<?php echo $row2["SPR_duree"]; ?>"/>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                            <input type="hidden" name="modif_sprint" value="<?php echo $id_spr; ?>">
                            <input type="hidden" name="id_sprint" value="<?php echo $id_spr; ?>">
                            <input type="hidden" name="nom_sprint" value="<?php echo "Sprint#0".$row2["SPR_numero"]; ?>">
                            <input class="btn btn-primary" type="submit" value="Modifier"/>
                          </div>
                        </form>
                        </div>
                      </div>
                    </div>
            </div>
          </aside>
<?php
  }
?>
<?php
  $s->footer();
} else {
  header("Location: ../web/index.php");
  exit();
}
?>
