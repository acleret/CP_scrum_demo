<?php
require_once("../web/config.php");

if (isset($_COOKIE["id_projet"])) {
  $id_pro = $_COOKIE["id_projet"];
  $infos = $db->infosProjet($id_pro);
  $row = $infos->fetch_assoc();

  $liste_sprints = $db->listeSprints($id_pro);
  $row_sprints = $liste_sprints->fetch_assoc();

  if (isset($_POST["ajout_sprint"])) {
    $db->ajoutSprint($_POST["numero"], $_POST["dateDebut"], $_POST["duree"], $id_pro);
    header('Location: ../web/listeSprints.php');
  }

  if (isset($_POST["suppr_sprint"])) {
    $db->supprimerSprint($_POST["suppr_sprint"]);
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

  $s->head($row['PRO_nom']);
  $s->header($db);
  $s->nav($db);
?>
          <script>
            $(document).ready(function() {
              $('#tableSprint').DataTable( {
                "order": [[ 0, "asc" ]],
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
              <h2><?php echo $row["PRO_nom"];?> - Sprints</h2>
              <hr>
              <dl class="dl-horizontal">
                <dt>Durée des sprints</dt>
                <dd><?php
  if ($row_sprints["SPR_duree"] == NULL)
    echo "-\n";
  else
    echo $row_sprints["SPR_duree"]." jours";
            ?></dd>
              </dl>
              <hr>
              <table id="tableSprint" class="table table-striped table-hover">
                <thead>
                  <tr>
                    <th>Numéro</th>
                    <th>Chiffrage</th>
                    <th>Début</th>
                    <th>Kanban</th>
<?php
  if (isset($_SESSION["session"]) && $db->estMembreProjet($row["PRO_id"], $_SESSION["id_co"]))
      echo "              <th>Actions</th>\n" ;
?>
                  </tr>
                </thead>
                <tbody>
<?php
  $liste_sprints = $db->listeSprints($id_pro);  // on réinitialiste liste_sprints
  while ($row_sprints = $liste_sprints->fetch_assoc()) {
    $id_spr = $row_sprints["SPR_id"];
    $infos_spr = $db->infosSprint($id_spr);
    $row_sprints = $infos_spr->fetch_assoc();
    $nom_spr = ($row_sprints["SPR_numero"] < 10) ? "Sprint#0".$row_sprints["SPR_numero"] : "Sprint#".$row_sprints["SPR_numero"];
?>
                  <tr>
                    <td>
                      <form id="<?php echo "lien_sprint".$id_spr;?>" style="display: inline;" action="../web/sprint.php" method="post">
                        <input type="hidden" name="id_sprint" value="<?php echo $id_spr; ?>"/>
                        <input type="hidden" name="nom_sprint" value="<?php echo $nom_spr; ?>"/>
                      </form>
                      <a href="#" onmouseover="this.style.cursor='pointer'" onclick=<?php echo 'document.getElementById("lien_sprint'.$id_spr.'").submit()' ;?>>
                        <?php echo $nom_spr."\n"; ?>
                      </a>
                    </td>
                    <td><?php
    $cout_sprint = $db->sommeChiffrageSprint($id_spr);
    if(!empty($cout_sprint))
      echo $cout_sprint;
                  ?></td>
                    <td><?php echo $db->ordonnerDate($row_sprints["SPR_dateDebut"]); ?></td>
                    <td>
                      <form style="display: inline;" action="kanban.php" method="post">
                        <input type="hidden" name="id_sprint" value="<?php echo $id_spr; ?>"/>
                        <input class="btn btn-link" type="submit" value="Kanban"/>
                      </form>
                    </td>
<?php
    if (isset($_SESSION["session"]) && $db->estMembreProjet($row["PRO_id"], $_SESSION["id_co"])) {
?>
                    <td>
                      <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modalModif<?php echo $row_sprints["SPR_id"];?>">Modifier</button>



                      <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#supprimerModal<?php echo $id_spr; ?>">Supprimer</button>

                      <!-- Modal Suppression -->
                      <div id="supprimerModal<?php echo $id_spr; ?>" class="modal fade" role="dialog">
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
                              <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                              <form style="display: inline;" action="" method="post">
                                <input type="hidden" name="suppr_sprint" value="<?php echo $id_spr;?>"/>
                                <input class="btn btn-danger" type="submit" value="Supprimer"/>
                              </form>
                            </div>
                          </div>
                        </div>
                      </div>
                    </td>
                    <!-- Modal Modification -->
                    <div id="modalModif<?php echo $row_sprints["SPR_id"];?>" class="modal fade" role="dialog">
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
                              <input class="form-control" type="number" name="num_sprint" placeholder="Numéro" value="<?php echo $row_sprints["SPR_numero"];?>"/>
                            </div>
                            <div class="form-group">
                              <label>Date de début</label>
                              <input class="form-control" type="date" name="date_sprint" value="<?php echo $row_sprints["SPR_dateDebut"];?>"/>
                            </div>
                            <div class="form-group">
                              <label>Durée</label>
                              <input class="form-control" type="number" name="duree_sprint" placeholder="Nombre de jours" value="<?php echo $row_sprints["SPR_duree"]; ?>"/>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                            <input type="hidden" name="modif_sprint" value="<?php echo $id_spr; ?>">
                            <input class="btn btn-primary" type="submit" value="Modifier"/>
                          </div>
                        </form>
                        </div>
                      </div>
                    </div>
<?php
    }
?>
                  </tr>
<?php
  }
?>
                </tbody>
              </table>
            </div>
          </article>
          <aside>
<?php
  if (isset($_SESSION["session"]) && $db->estMembreProjet($row["PRO_id"], $_SESSION["id_co"])) {
?>
            <div class="col-sm-2 sidenav">
              <!-- Trigger the modal with a button -->
              <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#ajouterSprint">Ajouter un sprint</button>
              <!-- Modal Ajout-->
              <div id="ajouterSprint" class="modal fade text-left" role="dialog">
                <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                  <form style="display: inline;" action="" method="post">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Ajouter un sprint</h4>
                    </div>
                    <div class="modal-body">
                      <div class="form-group">
                        <label>Numéro</label>
                        <input class="form-control" type="number" name="numero" placeholder="Numéro" required autofocus/>
                      </div>
                      <div class="form-group">
                        <label>Date de début</label>
                        <input class="form-control" type="date" name="dateDebut" placeholder="Date de début" required/>
                      </div>
                      <div class="form-group">
                        <label>Durée</label>
                        <input class="form-control" type="number" name="duree" placeholder="Nombre de jours" required/>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                      <input class="btn btn-primary" name="ajout_sprint" type="submit" value="Valider"/>
                    </div>
                  </form>
                  </div>
                </div>
              </div>
            </div>
<?php
  }
?>
          </aside>
<?php
  $s->footer();
} else {
  header("Location: ../web/index.php");
  exit();
}
?>
