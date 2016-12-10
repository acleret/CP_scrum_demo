<?php
require_once("../web/config.php");

$s->suppressionCookies();
$s->head("Liste des Projets");
$s->header($db);
$s->nav($db);
?>
          <script>
            $(document).ready(function() {
              $('#tableProjet').DataTable( {
                "order": [[ 1, "asc" ]],
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
              <h2>Projets recensés sur le site</h2>
              <hr>
              <table id="tableProjet" class="table table-striped table-hover">
                <thead>
                  <tr>
                    <th>Titre</th>
                    <th>Date de création</th>
<?php
if (isset($_SESSION["session"])) {
?>
                    <th>Actions</th>
<?php
}
?>
                  </tr>
                </thead>
                <tbody>
<?php
$result = $db->listeProjets();
while ($row = $result->fetch_assoc()) {
?>
                  <tr>
                    <td>
                      <form id="<?php echo "lien_projet".$row["PRO_id"];?>" style="display: inline;" action="../web/setProjet.php" method="post">
                        <input type="hidden" name="id_projet" value="<?php echo $row["PRO_id"]; ?>"/>
                      </form>
                      <a href="#" onclick=<?php echo 'document.getElementById("lien_projet'.$row["PRO_id"].'").submit()' ;?>>
                        <?php echo $row["PRO_nom"]."\n"; ?>
                      </a>
                    </td>
                    <td><?php echo $row["PRO_dateCreation"]; ?></td>
<?php
  if (isset($_SESSION["session"])) {
?>
                    <td>
<?php
    if ($db->estMembreProjet($row["PRO_id"], $_SESSION["id_co"])) {
?>
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
                      <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#supprimerModal<?php echo $row["PRO_id"]; ?>">Supprimer</button>

                      <!-- Modal Suppression -->
                      <div id="supprimerModal<?php echo $row["PRO_id"]; ?>" class="modal fade" role="dialog">
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
                              <form style="display: inline;" action="projet.php" method="post">
                                <input type="hidden" name="suppr_projet" value="<?php echo $row["PRO_id"]; ?>"/>
                                <input class="btn btn-danger" type="submit" value="Supprimer"/>
                              </form>
                            </div>
                          </div>
                        </div>
                      </div>
<?php
    }
?>
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
            </div>
          </article>
          <aside>
<?php
if (isset($_SESSION["session"])) {
?>
            <div class="col-sm-2 sidenav">
              <form style="display: inline;" action="formulaireProjet.php" method="post">
                <input type="hidden" name="action" value="ajouter"/>
                <input class="btn btn-primary btn-lg" type="submit" value="Ajouter un projet"/>
              </form>
            </div>
<?php
}
?>
          </aside>
<?php $s->footer(); ?>
