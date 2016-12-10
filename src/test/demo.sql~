USE cp_scrum;
ALTER TABLE `burndown_chart`
  DROP FOREIGN KEY `burndown_chart_ibfk_1`,
  DROP FOREIGN KEY `burndown_chart_ibfk_2`;
ALTER TABLE `inter_dev_projet`
  DROP FOREIGN KEY `inter_dev_projet_ibfk_1`,
  DROP FOREIGN KEY `inter_dev_projet_ibfk_2`;
ALTER TABLE `projet`
  DROP FOREIGN KEY `projet_ibfk_1`,
  DROP FOREIGN KEY `projet_ibfk_2`;
ALTER TABLE `sprint`
  DROP FOREIGN KEY `sprint_ibfk_1`;
ALTER TABLE `tache`
  DROP FOREIGN KEY `tache_ibfk_1`,
  DROP FOREIGN KEY `tache_ibfk_2`;
ALTER TABLE `us`
  DROP FOREIGN KEY `us_ibfk_1`,
  DROP FOREIGN KEY `us_ibfk_2`;

TRUNCATE TABLE `burndown_chart`;
TRUNCATE TABLE `projet`;
TRUNCATE TABLE `developpeur`;
TRUNCATE TABLE `inter_dev_projet`;
TRUNCATE TABLE `sprint`;
TRUNCATE TABLE `tache`;
TRUNCATE TABLE `us`;

ALTER TABLE `burndown_chart`
  ADD CONSTRAINT `burndown_chart_ibfk_1` FOREIGN KEY (`SPR_id`) REFERENCES `sprint` (`SPR_id`),
  ADD CONSTRAINT `burndown_chart_ibfk_2` FOREIGN KEY (`PRO_id`) REFERENCES `projet` (`PRO_id`);
ALTER TABLE `inter_dev_projet`
  ADD CONSTRAINT `inter_dev_projet_ibfk_1` FOREIGN KEY (`DEV_id`) REFERENCES `developpeur` (`DEV_id`),
  ADD CONSTRAINT `inter_dev_projet_ibfk_2` FOREIGN KEY (`PRO_id`) REFERENCES `projet` (`PRO_id`);
ALTER TABLE `projet`
  ADD CONSTRAINT `projet_ibfk_1` FOREIGN KEY (`DEV_idProductOwner`) REFERENCES `developpeur` (`DEV_id`),
  ADD CONSTRAINT `projet_ibfk_2` FOREIGN KEY (`DEV_idScrumMaster`) REFERENCES `developpeur` (`DEV_id`);
ALTER TABLE `sprint`
  ADD CONSTRAINT `sprint_ibfk_1` FOREIGN KEY (`PRO_id`) REFERENCES `projet` (`PRO_id`);
ALTER TABLE `tache`
  ADD CONSTRAINT `tache_ibfk_1` FOREIGN KEY (`DEV_id`) REFERENCES `developpeur` (`DEV_id`),
  ADD CONSTRAINT `tache_ibfk_2` FOREIGN KEY (`US_id`) REFERENCES `us` (`US_id`);
ALTER TABLE `us`
  ADD CONSTRAINT `us_ibfk_1` FOREIGN KEY (`PRO_id`) REFERENCES `projet` (`PRO_id`),
  ADD CONSTRAINT `us_ibfk_2` FOREIGN KEY (`SPR_id`) REFERENCES `sprint` (`SPR_id`);

INSERT INTO `developpeur` (`DEV_prenom`, `DEV_nom`, `DEV_pseudo`, `DEV_mdp`, `DEV_mail`, `DEV_urlAvatar`, `DEV_dateCreation`) VALUES
("Thomas", "VIGU&#201", "tvigue", "mdpmdpmdp", "thomas.vigue@etu.u-bordeaux.fr", "https://cdn1.iconfinder.com/data/icons/ninja-things-1/1772/ninja-simple-512.png", Now()),
("Nathalie", "CRAEYE", "ncraeye", "mdpmdpmdp", "nathalie.craeye@etu.u-bordeaux.fr", "https://cdn3.iconfinder.com/data/icons/avatars-9/145/Avatar_Panda-512.png", Now()),
("Anthony", "CL&#201RET", "acleret", "mdpmdpmdp", "anthony.cleret@etu.u-bordeaux.fr", "http://data-cache.abuledu.org/1024/icone-de-coq-5049c8fe.jpg", Now());

INSERT INTO `projet`(`PRO_nom`, `PRO_client`, `PRO_description`, `PRO_dateCreation`, `DEV_idProductOwner`, `DEV_idScrumMaster`) VALUES
("projet SCRUM", "Xavier Blanc", "Un site web de gestion de projet qui utilise la m&#233thode Scrum. Les utilisateur pourront : s&#39inscrire sur le site, inscrire un nouveau projet, lui affecter des d&#233veloppeurs (utilisateurs), &#233diter le backlog, organiser les sprints, affecter des user stories &#224 chaque sprint, &#233diter le kanban et la tra&#231abilit&#233 et visualiser l&#39avanc&#233e du projet via le burndown chart. Liens github : https://github.com/acleret/CP_scrum_dev, https://github.com/acleret/CP_scrum_demo.", Now(), 1, 3);

INSERT IGNORE INTO `inter_dev_projet` (`DEV_id`, `PRO_id`) VALUES
(1,1),(2,1),(3,1);

INSERT INTO `sprint` (`SPR_numero` , `SPR_dateDebut` , `SPR_duree` , `PRO_id`) VALUES
(1, '2016-10-24', '14', 1),
(2, '2016-11-07', '14', 1),
(3, '2016-11-21', '14', 1);

INSERT INTO `us` (`US_numero`, `US_nom`, `US_chiffrageAbstrait`, `US_priorite`, `US_dateCreation`, `US_dateDernierCommit`, `US_idDernierCommit`, `US_auteurDernierCommit`, `PRO_id`, `SPR_id`) VALUES
(1, 'En tant que visiteur je souhaite m&#39inscrire en tant que d&#233veloppeur.', '5', '1', '2016-10-24', NULL, NULL, NULL, 1, 1),
(2, 'En tant que visiteur je souhaite m&#39identifier en tant que d&#233veloppeur.', '3', '1', '2016-10-24', NULL, NULL, NULL, 1, 1),
(3, 'En tant que d&#233veloppeur je souhaite visualiser/modifier mon profil.', '2', '5', '2016-10-24', NULL, NULL, NULL, 1, 1),
(4, 'En tant que visiteur/d&#233veloppeur je souhaite visualiser la liste des projets.', '3', '1', '2016-10-24', NULL, NULL, NULL, 1, 1),
(5, 'En tant que d&#233veloppeur je souhaite rep&#233rer et acc&#233der partout sur le site aux projets auxquels je suis associ&#233 et &#224 ceux auxquels je ne le suis pas.', '3', '5', '2016-10-24', NULL, NULL, NULL, 1, 1),
(6, 'En tant que visiteur/d&#233veloppeur je souhaite visualiser la fiche r&#233capitulative d&#39un projet.', '2', '1', '2016-10-24', NULL, NULL, NULL, 1, 1),
(7, 'En tant que d&#233veloppeur je souhaite ajouter/&#233diter/supprimer mes projets.', '8', '1', '2016-10-24', NULL, NULL, NULL, 1, 2),
(8, 'En tant que d&#233veloppeur je souhaite s&#233lectionner/retirer des d&#233veloppeurs &#224 mes projets (via leur identifiant) et identifier un ScrumMaster et un ProductOwner.', '5', '1', '2016-10-24', NULL, NULL, NULL, 1, 3),
(9, 'En tant que visiteur/d&#233veloppeur je souhaite visualiser le backlog d&#39un projet.', '2', '2', '2016-10-24', NULL, NULL, NULL, 1, 2),
(10, 'En tant que d&#233veloppeur je souhaite ajouter/&#233diter/supprimer une US d&#39un sprint d&#39un projet auquel je suis associ&#233.', '8', '2', '2016-10-24', NULL, NULL, NULL, 1, 2),
(11, 'En tant que ProductOwner je souhaite modifier la priorit&#233 des US.', '1', '2', '2016-10-24', NULL, NULL, NULL, 1, 2),
(12, 'En tant que visiteur/d&#233veloppeur je souhaite avoir une vue g&#233n&#233rale des sprints d&#39un projet et une vue sp&#233cifique sur un sprint.', '3', '3', '2016-10-24', NULL, NULL, NULL, 1, 2),
(13, 'En tant que d&#233veloppeur je souhaite ajouter/&#233diter/supprimer un sprint dans un projet auquel je suis associ&#233.', '8', '3', '2016-10-24', NULL, NULL, NULL, 1, 2),
(14, 'En tant que d&#233veloppeur je souhaite s&#233lectionner/retirer une US du backlog dans l&#39&#233dition des sprints d&#39un projet auquel je suis associ&#233.', '3', '3', '2016-10-24', NULL, NULL, NULL, 1, 3),
(15, 'En tant que visiteur/d&#233veloppeur je souhaite visualiser le kanban d&#39un sprint d&#39un projet.', '5', '3', '2016-10-24', NULL, NULL, NULL, 1, 3),
(16, 'En tant que d&#233veloppeur je souhaite ajouter/modifier/supprimer une t&#226che d&#39une US d&#39un projet auquel je suis associ&#233.', '8', '3', '2016-10-24', NULL, NULL, NULL, 1, 3),
(17, 'En tant que visiteur/d&#233veloppeur je souhaite visualiser la tra&#231abilit&#233 d&#39un projet.', '2', '4', '2016-10-24', NULL, NULL, NULL, 1, 3),
(18, 'En tant que d&#233veloppeur je souhaite ajouter le commit et la date d&#39une US d&#39un projet auquel je suis associ&#233.', '3', '4', '2016-10-24', NULL, NULL, NULL, 1, 3),
(19, 'En tant que visiteur/d&#233veloppeur je souhaite visualiser l&#39avancement (burndown chart) d&#39un projet en temps r&#233el.', '5', '4', '2016-10-24', NULL, NULL, NULL, 1, 3);

UPDATE `us` SET `US_dateDernierCommit` = "16/11/04", `US_idDernierCommit` = "6177e9ff92e3d0da4726c17c17ac86f06e43c5be", `US_auteurDernierCommit` = "", `US_auteurDernierCommit` = "Nathalie" WHERE `US_id` = 1;
UPDATE `us` SET `US_dateDernierCommit` = "16/11/04", `US_idDernierCommit` = "fd8975c1550ae74ab9934d900ada299a941d985c", `US_auteurDernierCommit` = "", `US_auteurDernierCommit` = "Thomas" WHERE `US_id` = 2;
UPDATE `us` SET `US_dateDernierCommit` = "16/11/04", `US_idDernierCommit` = "fd8975c1550ae74ab9934d900ada299a941d985c", `US_auteurDernierCommit` = "", `US_auteurDernierCommit` = "Thomas" WHERE `US_id` = 3;
UPDATE `us` SET `US_dateDernierCommit` = "16/11/04", `US_idDernierCommit` = "d08d49ff98126941f3e066ef515430b0c641b308", `US_auteurDernierCommit` = "", `US_auteurDernierCommit` = "Anthony" WHERE `US_id` = 4;
UPDATE `us` SET `US_dateDernierCommit` = "16/11/04", `US_idDernierCommit` = "d08d49ff98126941f3e066ef515430b0c641b308", `US_auteurDernierCommit` = "", `US_auteurDernierCommit` = "Anthony" WHERE `US_id` = 5;
UPDATE `us` SET `US_dateDernierCommit` = "16/11/04", `US_idDernierCommit` = "6177e9ff92e3d0da4726c17c17ac86f06e43c5be", `US_auteurDernierCommit` = "", `US_auteurDernierCommit` = "Nathalie" WHERE `US_id` = 6;
UPDATE `us` SET `US_dateDernierCommit` = "16/11/22", `US_idDernierCommit` = "4d2e68d9147a6f693d54ea11d0da5451a134abe2", `US_auteurDernierCommit` = "", `US_auteurDernierCommit` = "Thomas" WHERE `US_id` = 7;
UPDATE `us` SET `US_dateDernierCommit` = "16/11/25", `US_idDernierCommit` = "da0912316e04563585279ae1365dd3e941ceaaa1", `US_auteurDernierCommit` = "", `US_auteurDernierCommit` = "Thomas" WHERE `US_id` = 8;
UPDATE `us` SET `US_dateDernierCommit` = "16/11/18", `US_idDernierCommit` = "dd147b8765632e2f6fec2434c71f82bb1920215b", `US_auteurDernierCommit` = "", `US_auteurDernierCommit` = "Anthony" WHERE `US_id` = 9;
UPDATE `us` SET `US_dateDernierCommit` = "16/11/18", `US_idDernierCommit` = "dd147b8765632e2f6fec2434c71f82bb1920215b", `US_auteurDernierCommit` = "", `US_auteurDernierCommit` = "Anthony" WHERE `US_id` = 10;
UPDATE `us` SET `US_dateDernierCommit` = "16/11/18", `US_idDernierCommit` = "dd147b8765632e2f6fec2434c71f82bb1920215b", `US_auteurDernierCommit` = "", `US_auteurDernierCommit` = "Anthony" WHERE `US_id` = 11;
UPDATE `us` SET `US_dateDernierCommit` = "16/11/22", `US_idDernierCommit` = "de40c1b3c4ee124817645796056137b95a04f6b6", `US_auteurDernierCommit` = "", `US_auteurDernierCommit` = "Nathalie" WHERE `US_id` = 12;
UPDATE `us` SET `US_dateDernierCommit` = "16/11/22", `US_idDernierCommit` = "de40c1b3c4ee124817645796056137b95a04f6b6", `US_auteurDernierCommit` = "", `US_auteurDernierCommit` = "Nathalie" WHERE `US_id` = 13;
UPDATE `us` SET `US_dateDernierCommit` = "16/12/05", `US_idDernierCommit` = "cba77d8d4a4ce4be90abd0c1cbf4b309df226d1d", `US_auteurDernierCommit` = "", `US_auteurDernierCommit` = "Nathalie" WHERE `US_id` = 14;
UPDATE `us` SET `US_dateDernierCommit` = "16/12/05", `US_idDernierCommit` = "9a5e6f7caf3ecaa65208ad3ffb53718e2558e5e5", `US_auteurDernierCommit` = "", `US_auteurDernierCommit` = "Thomas" WHERE `US_id` = 15;
UPDATE `us` SET `US_dateDernierCommit` = "16/12/05", `US_idDernierCommit` = "478aa9ad837ef226279f472a1dd315a40ae0c00f", `US_auteurDernierCommit` = "", `US_auteurDernierCommit` = "Thomas" WHERE `US_id` = 16;
UPDATE `us` SET `US_dateDernierCommit` = "16/12/02", `US_idDernierCommit` = "5098ff70dd5fc1868a24b1ef2d03d32c4b0eb448", `US_auteurDernierCommit` = "", `US_auteurDernierCommit` = "Nathalie" WHERE `US_id` = 17;
UPDATE `us` SET `US_dateDernierCommit` = "16/12/02", `US_idDernierCommit` = "5098ff70dd5fc1868a24b1ef2d03d32c4b0eb448", `US_auteurDernierCommit` = "", `US_auteurDernierCommit` = "Nathalie" WHERE `US_id` = 18;
UPDATE `us` SET `US_dateDernierCommit` = "16/12/03", `US_idDernierCommit` = "a73b5f5277d70703b1e752cb1eaccafbfb9574aa", `US_auteurDernierCommit` = "", `US_auteurDernierCommit` = "Anthony" WHERE `US_id` = 19;

INSERT INTO `tache` (`TAC_numero`, `TAC_nom`, `TAC_description`, `TAC_dateDepart`, `TAC_etat`, `DEV_id`, `US_id`) VALUES
(1, "T1", "menu import&#233 depuis un fichier s&#233par&#233 (on devra utiliser infosDeveloppeur(id_dev) et listeProjetsDeveloppeur(id_dev))", "2016-10-24", "DONE", 1, 1),
(2, "T2", "MySql : ajoutNouveauDeveloppeur(prenom, nom, pseudo, mail, mdp, url_avatar)", "2016-10-24", "DONE", 3, 1),
(3, "T3", "Web : page d\'inscription", "2016-10-24", "DONE", 3, 1),
(4, "T4", "Test E2E US#1", "2016-10-24", "DONE", 2, 1),
(5, "T5", "MySql : v&#233rifications de la correspondance et de la pr&#233sence du couple (pseudo,mdp) dans la table Developpeur avec estDeveloppeur(pseudo, mdp)", "2016-10-24", "DONE", 2, 2),
(6, "T6", "Web : page de connexion", "2016-10-24", "DONE", 2, 2),
(7, "T7", "Test E2E US#2", "2016-10-24", "DONE", 1, 2),
(8, "T8", "MySql : infosDeveloppeur(id_dev)", "2016-10-24", "DONE", 2, 3),
(9, "T9", "HTML : mailerInfosModifiees(id_dev, prenom, nom, mdp, url_avatar)", "2016-10-24", "DONE", 2, 3),
(10, "T10", "Web : page de profil et de modification du profil", "2016-10-24", "DONE", 2, 3),
(11, "T11", "Test E2E US#3", "2016-10-24", "DONE", 1, 3),
(12, "T12", "MySql : listeProjets()", "2016-10-24", "DONE", 1, 4),
(13, "T13", "Web : page d\'accueil", "2016-10-24", "DONE", 1, 4),
(14, "T14", "Test E2E US#4", "2016-10-24", "DONE", 3, 4),
(15, "T15", "MySql : listeProjetsDev(id_dev)", "2016-10-24", "DONE", 1, 5),
(16, "T16", "Web : volet de navigation avec distinction des projets gr&#226ce &#224 listeProjetsDev(id_dev) et listeProjets()", "2016-10-24", "DONE", 1, 5),
(17, "T17", "Test E2E US#5", "2016-10-24", "DONE", 3, 5),
(18, "T18", "MySql : infosProjet(id_projet)", "2016-10-24", "DONE", 3, 6),
(19, "T19", "Web : page projet", "2016-10-24", "DONE", 3, 6),
(20, "T20", "Test E2E US#6", "2016-10-24", "DONE", 2, 6),

(1, "T1", "MySql : ajouterProjet(#infos), modifierProjet(id_pro, #infos), supprimerProjet(id_pro),", "07/11/16", "DONE", 2, 7),
(2, "T2", "Web : page de modification d\'un projet (formulaireProjet.php) et actions ajout/suppression", "07/11/16", "DONE", 2, 7),
(3, "T3", "MySql : listerDevs(), selectionnerScrumMaster(id_pro, id_dev), selectionnerProductOwner(id_pro, id_dev), selectionnerDev(id_pro, id_dev)", "07/11/16", "DONE", 2, 8),
(4, "T4", "Web : liste de d&#233veloppeurs (menu d&#233roulant ou non, &#224 cocher ou non, s&#233lection multiple ou non ...) dans la page formulaireProjet.php", "07/11/16", "DONE", 2, 8),
(5, "T5", "MySql : listerUS(id_pro)", "07/11/16", "DONE", 1, 9),
(6, "T6", "Web : page backlog.php (mise en page du tableau)", "07/11/16", "DONE", 1, 9),
(7, "T7", "MySql : ajouterUS(#infos), modifierUS(id_us, #infos), supprimerUS(id_us)", "07/11/16", "DONE", 1, 10),
(8, "T8", "Web : page de modification d\'une US (formulaireUS.php) et actions ajout/suppression", "07/11/16", "DONE", 1, 10),
(9, "T9", "MySql : modifierPrioriteUS(id_us, priorite)", "07/11/16", "DONE", 1, 11),
(10, "T10", "Web : pour un PO connect&#233 : modification de la prio dans la page formulaireUS.php", "07/11/16", "DONE", 1, 11),
(11, "T11", "MySql : listerSprint(id_pro), infosSprint(id_sprint)", "07/11/16", "DONE", 3, 12),
(12, "T12", "Web : pages listeSprints.php et sprint.php", "07/11/16", "DONE", 3, 12),
(13, "T13", "MySql : ajouterSprint(#infos), modifierSprint(id_sprint, #infos), supprimerSprint(id_sprint)", "07/11/16", "DONE", 3, 13),
(14, "T14", "Web : page de modification d\'un sprint (formulaireSprint.php) et actions ajout/suppression", "07/11/16", "DONE", 3, 13),
(15, "T15", "Test E2E US#7", "07/11/16", "DONE", 1, 7),
(16, "T16", "Test E2E US#8", "07/11/16", "DONE", 1, 8),
(17, "T17", "Test E2E US#9", "07/11/16", "DONE", 3, 9),
(18, "T18", "Test E2E US#10", "07/11/16", "DONE", 3, 10),
(19, "T19", "Test E2E US#11", "07/11/16", "DONE", 3, 11),
(20, "T20", "Test E2E US#12", "07/11/16", "DONE", 2, 12),
(21, "T21", "Test E2E US#13", "07/11/16", "DONE", 2, 13),

(1, "T", "MySql : infosSprint(spr), listeUserStorySprint(spr), listeUserStoryOutOfSprint(spr, pro), affecterUserStorySprint(us, $id_sprint), retirerUserStorySprint(us)", "21/11/16", "DONE", 3, 14),
(2, "T", "Web : Sprint.php (2 tableaux)", "21/11/16", "DONE", 3, 14),
(3, "T", "test E2E US#14", "21/11/16", "DONE", 2, 14),
(4, "T", "MySql : listeTachesUS(id_us), listeTachesSprint(id_spr)", "21/11/16", "DONE", 2, 15),
(5, "T", "Web : Kanban.php", "21/11/16", "DONE", 2, 15),
(6, "T", "test E2E US#15", "21/11/16", "DONE", 1, 15),
(7, "T", "MySql : ajouterTache(#infos) modifierTache(id_tache, #infos) supprimerTache(id_tache)", "21/11/16", "DONE", 2, 16),
(8, "T", "Web : Kanban.php", "21/11/16", "DONE", 2, 16),
(9, "T", "test E2E US#16", "21/11/16", "DONE", 1, 16),
(10, "T", "MySql : listeUS(id_projet)", "21/11/16", "DONE", 1, 17),
(11, "T", "Web : tracabilite.php (au gout du dev pour l\'affichage des noms d\'US)", "21/11/16", "DONE", 3, 17),
(12, "T", "test E2E US#17", "21/11/16", "DONE", 2, 17),
(13, "T", "MySql : modifUserStoryTracabilite(...))", "21/11/16", "DONE", 1, 18),
(14, "T", "Web : tracabilite.php", "21/11/16", "DONE", 3, 18),
(15, "T", "test E2E US#18", "21/11/16", "DONE", 2, 18),
(16, "T", "MySql : sommeChiffrageBacklog(id_pro) sommeChiffrageSprint(id_spr)Web", "21/11/16", "DONE", 1, 19),
(17, "T", "ajouter dans listeSprints.php/backlog.php le chiffrage total de chaque sprint/backlog", "21/11/16", "DONE", 1, 19),
(18, "T", "burndownChart.php", "21/11/16", "DONE", 1, 19),
(19, "T", "test E2E US#19", "21/11/16", "DONE", 3, 19);

INSERT INTO `burndown_chart` (`BDC_id`, `BDC_chargePlanifie`, `SPR_id`, `PRO_id`) VALUES
(1, 18, 1, 1),
(2, 35, 2, 1),
(3, 26, 3, 1);
