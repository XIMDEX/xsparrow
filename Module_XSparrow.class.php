<?php
/**
 *  \details &copy; 2011  Open Ximdex Evolution SL [http://www.ximdex.org]
 *
 *  Ximdex a Semantic Content Management System (CMS)
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published
 *  by the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  See the Affero GNU General Public License for more details.
 *  You should have received a copy of the Affero GNU General Public License
 *  version 3 along with Ximdex (see LICENSE file).
 *
 *  If not, visit http://gnu.org/licenses/agpl-3.0.html.
 *
 *  @author Ximdex DevTeam <dev@ximdex.com>
 *  @version $Revision$
 */




ModulesManager::file('/inc/modules/Module.class.php');
ModulesManager::file('/modules/XSparrow/conf/xsparrow.conf');
ModulesManager::file('/modules/XSparrow/inc/Theme.class.php');
ModulesManager::file('/modules/XSparrow/inc/model/XSparrowProject.class.php');
ModulesManager::file('/modules/XSparrow/inc/model/XSparrowRelProjectDocs.class.php');
ModulesManager::file('/modules/XSparrow/BuildParser.class.php');

class Module_XSparrow extends Module {


	function Module_XSparrow() {

		// Call Module constructor.
		parent::Module('XSparrow', dirname(__FILE__));

		// Initialization stuff.

	}

	function install() {

    // Install logic.

    // get module from ftp, webdav, subversion, etc...?
    // need to be extracted?
    // extract and copy files to modules location.

    // get constructor SQL
		$this->loadConstructorSQL("XSparrow.constructor.sql");
		$install_ret = parent::install();

		//Save all projects
		$arrayProjects = XSparrowProjectManager::getAllXSparrowProjects();
		foreach ($arrayProjects as $version => $projectsByVersion) {
			foreach ($projectsByVersion as $projectName => $projectObject) {
				$xsparrowProject = new XSparrowProject();
				$xsparrowProject->set("name",$projectName);
				$xsparrowProject->set("version", $version);
				$xsparrowProject->add();
				$idProject = $xsparrowProject->get("idproject");
				$servers = $projectObject->getServers();
				foreach ($servers as $server) {
					$arrayXimDocs = $server->getXimDocs();
					foreach ($arrayXimDocs as $ximDoc) {
						$relProjectDoc = new XSparrowRelProjectDocs();
						$relProjectDoc->set("idproject", $idProject);
						$relProjectDoc->set("doc", $ximDoc->__get("name"));
						$relProjectDoc->set("description", $ximDoc->__get("description"));
						$relProjectDoc->set("relaxng", $ximDoc->__get("templatename"));						
						$relProjectDoc->set("filepath", $ximDoc->getPath());
						$relProjectDoc->add();
					}
				}
			}
		}

		//Create a temp file for the templates of every projects
		//It will ease the theme previews.		
		$this->buildTempXslForThemes();	


	}


	/**
	*For every project build a xsl file in tmp/Xsparrow/ with all the xsl definitions
	*/
	private function buildTempXslForThemes(){
		$arrayThemes = Theme::getAllThemes();
		foreach ($arrayThemes as $theme) {
			$theme->buildTempResources();
			$theme->project->buildCompressFile($theme->_shortname);
			echo($theme->_shortname." theme installed\r\n");
		}		
	}

	function uninstall() {

		// Uninstall logic.

        // get destructor SQL
		$this->loadDestructorSQL("XSparrow.destructor.sql");

        // Uninstall !
		parent::uninstall();

	}

}

?>
