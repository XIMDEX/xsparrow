<?php
/**
 *  \details &copy; 2011  Open Ximdex Evolution SL [http://www.ximdex.org]
 *
 *  Ximdex a Semantic Content Management System (CMS)    							*
 *  Copyright (C) 2011  Open Ximdex Evolution SL <dev@ximdex.org>	      *
 *                                                                            *
 *  This program is free software: you can redistribute it and/or modify      *
 *  it under the terms of the GNU Affero General Public License as published  *
 *  by the Free Software Foundation, either version 3 of the License, or      *
 *  (at your option) any later version.                                       *
 *                                                                            *
 *  This program is distributed in the hope that it will be useful,           *
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of            *
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             *
 *  GNU Affero General Public License for more details.                       *
 *                                                                            *
 * See the Affero GNU General Public License for more details.                *
 * You should have received a copy of the Affero GNU General Public License   *
 * version 3 along with Ximdex (see LICENSE).                                 *
 * If not, see <http://gnu.org/licenses/agpl-3.0.html>.                       *
 *                                                                            *
 * @version $Revision: $                                                      *
 *                                                                            *
 *                                                                            *
 ******************************************************************************/
ModulesManager::file('/modules/XSparrow/BuildParser.class.php');

/**
 * Manage the XSparrow projects. 
 */
class XSparrowProjectManager{

	private $name=null;
	private $version=null;
	private $project = null;
	private $defaultProject = null;

	/**
	*Constructor method
	*@param string $name project name
	*@param string $version version of project
	*/
	public function __construct($name, $version) {
		
		$this->name = $name;
		$this->version = $version;

		$b = new BuildParser($version,$name);
        $defaultParser = new BuildParser($version);

        //Init buildProject property
        $this->project = $b->getProject();

        if ($name == DEFAULT_PROJECT){
            $this->defaultProject = $this->project;
        }else{
            $this->defaultProject = $defaultParser->getProject();
        }
	}


	/**
	*Get the compress Xsl File for the selected project
    *
    * @param boolean $recursive true if this method can be called again
    *
    * @return [type] [description]
	*/
	public function getCompressXslFile($recursive="true"){

		$path = TEMP_FOLDER."/XSparrow/{$this->version}/{$this->name}.xsl";
		$xslContent = false;
		if (file_exists($fullPath)){
			$xslContent = FsUtils::file_get_contents($fullPath);
		}
		else if ($recursive){
			$xslContent = $this->buildCompressFile()->getCompressXslFile(false);				
		}
		
		return $xslContent; 
	}
	

	/**
	* Create a temp file with all xsl templates includes in a file.
    *
    * @param String $themeFolder Name for the theme. It will be a folder in data/tmp/XSparrow
    *
    * @return Object false if error, this object if everything ok.
	*/
	public function buildCompressFile($themeFolder){

		$arrayTemplates = $this->getTemplates();

		
        $xslContent = "";
        
        foreach ($arrayTemplates as $filename => $templateFile) {
        	if ($filename != "docxap"){
        		$templateContent = $templateFile->getContent();
                $filePath = $templateFile->getPath();
                $filePath = str_replace(Config::GetValue("AppRoot"), Config::GetValue("UrlRoot"), $filePath);
                $templateContent = '<xsl:include href="'.$filePath.'"/>';
		    	$xslContent .= $templateContent;
        	}
        }

        if (array_key_exists("docxap", $arrayTemplates)){
            $docxapContent = $arrayTemplates["docxap"]->getContent();
            $docxapContent = preg_replace("/\<xsl:include.*href=\".*templates_include.xsl\".*\>/",$xslContent,$docxapContent);

            $tmpFolder = Config::GetValue("AppRoot")."/data/tmp/XSparrow/{$this->version}/{$this->name}";
            if (!is_dir($tmpFolder)){
                if (!mkdir($tmpFolder,0777,true)){
                    //print in log
                    return false;
                }
            }

            //Replace @@@RMximdex.dotdot()
            $replacement=Config::GetValue("UrlRoot")."/data/tmp/XSparrow/{$themeFolder}/$1";
            $docxapContent = preg_replace("/@@@RMximdex.dotdot\((.+)\)@@@/", $replacement, $docxapContent);

            FsUtils::file_put_contents("$tmpFolder/docxap.xsl", $docxapContent); 
        }
       

        return $this;
	}

	/**
	* Build temp resources from the current project.
    * @return void
	*/
	public function buildTempResources($themeFolder){

		//Build parser for default and current project
		$servers = $this->getServers();
		foreach ($servers as $server) {

			$commons = $server->getCommon();
            $this->buildTempFiles($commons, "common", $themeFolder);
			
            $css = $server->getCSS();
            $this->buildTempFiles($css, "css", $themeFolder);
			
            $images = $server->getImages();
            $this->buildTempFiles($images, "images", $themeFolder);
            
            
		}
	}

    public function buildTempFiles($arrayFiles, $folderName, $themeFolder){

        $tmpFolder = Config::GetValue("AppRoot")."/data/tmp/XSparrow/{$themeFolder}/{$folderName}";
        foreach ($arrayFiles as $path => $fileObject) {
            $content = $fileObject->getContent();
            //$path = substr($path, 0, stripos("/", $path));
            
            $fullFolderPath = $tmpFolder.substr($path, 0, strrpos($path,"/"));
            $fileName = substr($path,strrpos($path,"/")+1);
            
            if (!is_dir($fullFolderPath)){
                if (!mkdir($fullFolderPath,0777,true)){
                    
                }
            }
            FsUtils::file_put_contents("$fullFolderPath/$fileName", $content);  
        }
    }

	/**
	*Get all templates for default and specific projects
	*@return array Loader_ximfile .
	*/
	public function getTemplates(){

		$result = array();

		$templates = $this->project->getTemplates();
        $defaultTemplates = array();
        if ($this->defaultProject && ($this->defaultProject !== $this->project)){
            $defaultTemplates = $this->defaultProject->getTemplates();
        }

        foreach ($defaultTemplates as $template){
            if ($template->__get("filename")){
                $result[$template->__get("filename")] = $template;
            }
        }

        foreach ($templates as $template){
            if ($template->__get("filename")){
                $result[$template->__get("filename")] = $template;
            }
        }

        return $result;
	}


	/**
    * Get the match Servers from default and specific project
    * Defaults are mandatory, but specific can overload it.
    * @return array with all Loader_Server objects.
    */
    public function getServers(){

        $result = array();
        $servers = $this->project->getServers();
        $defaultServers = array();
        if ($this->defaultProject && ($this->defaultProject !== $this->project)){
            $defaultServers = $this->defaultProject->getServers();
        }

        foreach ($defaultServers as $server) {
            if ($server->__get("name")){
                $result[$server->__get("name")] = $server;
            }
        }

        foreach ($servers as $server) {
            if ($server->__get("name")){
                $result[$server->__get("name")] = $server;
            }
        }

        return $result;
    }

    /**
    *Get the match Schemes from default and specific project
    *Defaults are mandatory, but specific can overload it.
    *@return array with all Loader_XimFile objects.
    */
    public function getSchemes(){
        $result = array();

        $schemes = $this->project->getSchemes();
        if ($this->defaultProject && ($this->defaultProject !== $this->project)){
            $defaultSchemes = $this->defaultProject->getSchemes();
        }

        foreach ($defaultSchemes as $scheme){
            if ($scheme->__get("filename")){
                $result[$scheme->__get("filename")] = $scheme;
            }
        }

        foreach ($schemes as $scheme){
            if ($scheme->__get("filename")){
                $result[$scheme->__get("filename")] = $scheme;
            }
        }

        return $result;
    }


    public function setProjectId($idProject){
    	$this->project->$projectid=$idProject;
    }

    public function getBuildProject(){
        return $this->project;
    }


    /**
	*Static method Get all XSparrow Projects under project folder
    *@return array With all the XSparrowProject. array[version][projectName] = XSparrowProject;
	*/
	public static function getAllXSparrowProjects(){
		
		//Returned array if everything is ok.
		//It's something like $result["1.0"]["bootstrap"] == XSparrowProject Object.
		$result = array();
		
		$projectRootFolder = Config::GetValue("AppRoot").MODULE_XSPARROW_PATH.XSPARROW_PROJECT_FOLDER;
		//Getting all theme folders
		$projectVersionFolders = FsUtils::readFolder($projectRootFolder,false);

		//For every Version
		foreach ($projectVersionFolders as $projectVersionFolder ) {

			//For every project in the version folder
			$projectFolders = FsUtils::readFolder($projectRootFolder."/".$projectVersionFolder,false);
			foreach ($projectFolders as $projectFolder) {
				$result[$projectVersionFolder][$projectFolder] = new XSparrowProjectManager($projectFolder, $projectVersionFolder);
			}
		}

		return $result;
	}

}