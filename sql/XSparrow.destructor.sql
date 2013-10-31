DELETE FROM Actions where module = "XSparrow";
INSERT INTO Actions VALUES (6011,5012,'Add new project','addfoldernode','create_proyect.png','Create a new node with project type',10,NULL,0,'',0);
INSERT INTO `Actions`(`IdAction`, `IdNodeType`, `Name`, `Command`, `Icon`, `Description`,`Sort`, `Module`, `Multiple`, `Params`)  VALUES (6044,5018,'Add new document','createxmlcontainer','add_xml.png','Create a new document structured in several languages',10,NULL,0,'');

DROP TABLE IF EXISTS  `XSparrowProject`;
DROP TABLE IF EXISTS  `XSparrowRelProjectDocs`;
DROP TABLE IF EXISTS  `XSparrowInstalledProjects`;