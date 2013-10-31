DELETE from Actions where idnodetype=5018 and command="createxmlcontainer";
INSERT INTO Actions (IdAction,Idnodetype,name, command, icon, description, sort, module) VALUES(9000,5012, "Create a new wizard project", "createproject", "create_proyect.png", "Create a new wizard project", 10, "XSparrow");
INSERT INTO Actions (IdAction, IdNodeType, Name, Command, Icon, Description,Sort, Module) VALUES (9001,5018,"Add new document","createxmlcontainer","add_xml.png","Create a new document structured in several languages", 10,"XSparrow");
INSERT INTO RelRolesActions (IdRel,IdRol,IdAction,IdState,IdContext,IdPipeline) VALUES (NULL,201,9000,0,1,3);
INSERT INTO RelRolesActions (IdRel,IdRol,IdAction,IdState,IdContext,IdPipeline) VALUES (NULL,201,9001,0,1,3);

DROP TABLE IF EXISTS  `XSparrowProject`;

CREATE TABLE `XSparrowProject` (
  `idproject` int(12) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default "",
  `version`varchar(6) NOT NULL default "0.0",
   PRIMARY KEY  (`idproject`)
);

CREATE TABLE `XSparrowRelProjectDocs` (
  `idrel` int(12) unsigned NOT NULL  auto_increment,
  `idproject` int(12) unsigned NOT NULL default "0",
  `doc` varchar(255) NOT NULL default "",
  `description`varchar(255) NOT NULL default "",
  `schema`varchar(255) NOT NULL default "",
   PRIMARY KEY  (`idrel`)
);

CREATE TABLE `XSparrowInstalledProjects` (
  `idnode` int(12) unsigned NOT NULL default 0,
  `idproject` int(12) unsigned NOT NULL default "0",
   PRIMARY KEY  (`idnode`)
);

DELETE from Actions where idnodetype=5012 and command="addfoldernode";
