<?php
require_once("../web/config.php");

$id_pro = $_COOKIE["id_projet"];
$infos_pro = $db->infosProjet($id_pro);
$row_pro = $infos_pro->fetch_assoc();

if (isset($_POST["modif_commit_us"])) {
    $db->modifUserStoryTracabilite($_POST["id_us"], $_POST["date_commit"], $_POST["num_commit"], $_POST["auteur_commit"]);
}

$s->head("Traçabilité");
$s->header($db);
$s->nav($db);
?>
          <script>
            $(document).ready(function() {
              $('#tableUS').DataTable( {
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
    		  <h2><?php echo $row_pro["PRO_nom"]; ?> - Traçabilité</h2>
              <hr>
              <table id="tableUS" class="table table-striped table-hover">
                <thead>
                  <tr>
                    <th>US</th>
                    <th>Commit</th>
                    <th>Date</th>
                    <th>Auteur</th>
<?php
if (isset($_SESSION["session"]) && $db->estMembreProjet($row_pro["PRO_id"], $_SESSION["id_co"])) {
?>
                    <th>Actions</th>
<?php
}
?>
                  </tr>
                </thead>
                <tbody>
<?php
$result = $db->listeUserStories($id_pro);
while ($row = $result->fetch_assoc()) {
?>
                  <tr>
                    <td><?php echo $row["US_nom"]; ?></td>
                    <td><?php echo $row["US_idDernierCommit"]; ?></td>
				    <td><?php echo $db->ordonnerDate($row["US_dateDernierCommit"]); ?></td>
                    <td><?php echo $row["US_auteurDernierCommit"]; ?></td>
<?php
    if (isset($_SESSION["session"]) && $db->estMembreProjet($row_pro["PRO_id"], $_SESSION["id_co"])) {
?>
                    <td>
         		      <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modificationModal<?php echo $row["US_id"]; ?>">Modifier</button>
                    </td>
                    <!-- Modal Modification -->
                    <div id="modificationModal<?php echo $row["US_id"]; ?>" class="modal fade" role="dialog">
                      <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                          <form style="display: inline;" action="" method="post">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                              <h4 class="modal-title">Modification du commit</h4>
                            </div>
                            <div class="modal-body">
                              <div class="form-group">
                                <label>Numéro du commit</label>
                                <input class="form-control" type="text" name="num_commit" placeholder="d08d49ff98re5d21f3e066ef515430b0c641b308" value="<?php echo $row["US_idDernierCommit"]; ?>" required/>
                              </div>
                              <div class="form-group">
                                <label>Date du commit</label>
                				<input class="form-control" type="date" name="date_commit" placeholder="pseudo" value="<?php echo $row["US_dateDernierCommit"]; ?>" required/>
                              <div class="form-group">
                                <label>Auteur du commit</label>
                				<input class="form-control" type="text" name="auteur_commit" placeholder="pseudo" value="<?php echo $row["US_auteurDernierCommit"]; ?>" required/>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                              <input type="hidden" name="id_us" value="<?php echo $row["US_id"]; ?>"/>
                              <input class="btn btn-primary" name="modif_commit_us" type="submit" value="Valider"/>
                            </form>
                          </div>
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
<?php
$s->footer();
?>
