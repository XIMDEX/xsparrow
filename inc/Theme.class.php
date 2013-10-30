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

ModulesManager::file('/conf/xsparrow.conf', 'XSparrow');
ModulesManager::file('/inc/fsutils/FsUtils.class.php');
ModulesManager::file('/inc/xml/validator/XMLValidator.class.php');
ModulesManager::file('/inc/xml/validator/XMLValidator_RNG.class.php');
ModulesManager::file('/modules/XSparrow/inc/XSparrowProject.class.php');

/**
 * Manage a XSparrow theme. 
 * 
 * A theme is based on a project.
 */
class Theme {

	/**
	 * Xml content for the config file in the theme
	 * @var String
	 */
	public $xml;
	/**
	 * XSparrow Project Version. it has float format.
	 * @var String
	 */
	public $version;
	public $configurable;

	public $projectName;
	public $project=false;

	/**
	*Constructor
	*
	*@param Object $theme. It could be the id of DB table, content xml, relative path or full path to xml.
	*/
	public function __construct($theme){

		$xml = false;
		if (is_string($theme)){

			//if valid xml
			if ($this->isValidXml($theme)){
				$xml = $theme;
			}else{
				$themesFolderPath = $theme;

				if (substr($theme,-4)==".xml"){//if is filename with or without full path
					$themesFolderPath = substr($theme,0,-4);
				}

				$xml = $this->getXmlFromFolderName($themesFolderPath);
			}

		}else if (is_int($theme)){ //If index in DB. Futurible

		}

		$this->xml = $xml;

		if ($theme and !$xml){
			//Param not valid. Log Message
			XMD_Log::warning(LOG_PREFIX."Unable to load $theme theme.");
		}else if ($xml){
			$this->getThemeProperties();
			$this->project = new XSparrowProject($this->projectName,$this->version);
		}
	}

	/**
	* Indicate if this theme object is a valid one.
	* @return boolean True if exists 
	*/
	public function isValid(){

		if ($this->xml)
			return true;
		return false;
	}

	/**
	 * 
	 */
	public function buildTempResources(){		
		$this->project->buildTempResources($this->_shortname);

		$sourceFolder = Config::GetValue("AppRoot").MODULE_XSPARROW_PATH.THEMES_FOLDER."/{$this->_shortname}";
		$tmpFolder = Config::GetValue("AppRoot")."/data/tmp/XSparrow/{$this->_shortname}";
		if (!is_dir($tmpFolder."/images")){
            if (!mkdir($tmpFolder."/images/",0777,true)){
			
            }

        }
        
        $arrayImages = FsUtils::readFolder("$sourceFolder/images");
        if ($arrayImages){
        	foreach ($arrayImages as $image) {
        		copy("$sourceFolder/images/$image", "$tmpFolder/images/$image");
        	}	
        }
        

        if (!is_dir($tmpFolder."/css")){
            if (!mkdir($tmpFolder."/css",0777,true)){
				                
            }
        }

        $arrayCss = FsUtils::readFolder("$sourceFolder/css");
        if ($arrayCss){
       		foreach ($arrayImages as $image) {
        		copy("$sourceFolder/css/$image", "$tmpFolder/css/$image");
        	}	
        }

        //Build a tmp file with docxap.
       	$configuration = str_replace('<?xml version="1.0" ?>', '<docxap transformer="XSparrow" tipo_documento="configuration.xml">', $this->xml);
        $configuration.="</docxap>"; 
       	FsUtils::file_put_contents("$tmpFolder/configuration.xml", $configuration);


	}

	/**
	*Get a xml content from path
	*@param string $themeFolderName. Path to theme folder
	*@return xml content
	*/
	private function getXmlFromFolderName($themesFolderPath){

		$result = false;
		$lastSlash = strrpos($themesFolderPath,"/");
		if ($lastSlash !== FALSE){ //It should be relative path
			$themesFolderPath = substr($themesFolderPath,$lastSlash+1);
		}

		$fullPath = Config::GetValue("AppRoot").MODULE_XSPARROW_PATH.THEMES_FOLDER."/$themesFolderPath/$themesFolderPath.xml";
		if (file_exists($fullPath)){
			$xmlContent = FsUtils::file_get_contents($fullPath);
			$result = $this->isValidXml($xmlContent)? $xmlContent : false;
		}
		return $result;
	}



	/**
	*Get all attributes from xml content.
	*/
	private function getThemeProperties(){

		$result = "";
		if ($this->xml){
			$domDoc = new DOMDocument();
			$domDoc->preserveWhiteSpace = false;
			$domDoc->validateOnParse = true;
			$domDoc->formatOutput = true;
			if ($domDoc->loadXML($this->xml)){

				$xpathObj = new DOMXPath($domDoc);
				$nodeList0 = $xpathObj->query('/xsparrow-theme/theme-properties');
				if ($nodeList0->length){
					$nodeThemeProperty = $nodeList0->item(0);
					$this->configurable = $nodeThemeProperty->getAttribute("configurable");
					foreach ($nodeThemeProperty->childNodes as $child) {
						$nodeName = "_".$child->nodeName;
						$shortNodeName = str_replace("theme-", "", $nodeName);
						$this->$nodeName = $child->nodeValue;
						//Creating short attribute just for accesibility
						$this->$shortNodeName = $child->nodeValue;
					}
				}else{//if not exists theme-properties
					XMD_Log::error(LOG_PREFIX."/xsparrow-theme/theme-properties node not found. Please check the xml.");
				}

			}else { //if error on load
				XMD_Log::error(LOG_PREFIX."Unable to load xml document to get its properties".$this->xml );
			}
		}
	}

	/**
	*Check if is a valid xml and parse with Relax-NG
	*@param String $xml content to check. It could be a path. but it isnt valid.
	*It would return false value.
	*@param boolean $laxy. If true parse with Relax-NG
	*@return boolean. True if is valid the xml.
	*/
	private function isValidXml($xml, $lazy = false){

		$result = false;

		if ($xml){
			//if valid xml
			$domDocument = new DomDocument();
			//Avoid warnings. $xml can be a path and we know it.
			$result = @$domDocument->loadXML($xml);
		}

		if (!$result){
			//xml could be a path
			return false;
		}

		$xpathObj = new DOMXPath($domDocument);
		$xsparrowThemeNodes = $xpathObj->query('/xsparrow-theme');

		//If doesnt exist /xsparrow-theme node
		if (!$xsparrowThemeNodes->length){
			XMD_Log::warning(LOG_PREFIX."The theme has not version number in xsparrow-theme node");
			return false;
		}

		$xsparrowThemeNode = $xsparrowThemeNodes->item(0);
		//If doesnt exists version attribute.
		if (!$xsparrowThemeNode->hasAttribute("version")){
			XMD_Log::warning(LOG_PREFIX."The theme has not version number in xsparrow-theme node");
			return false;
		}

		$this->version = $xsparrowThemeNode->getAttribute("version");
		$this->projectName = $xsparrowThemeNode->hasAttribute("project")? $xsparrowThemeNode->getAttribute("project") : DEFAULT_PROJECT;
		$rngFilePath = Config::GetValue("AppRoot").MODULE_XSPARROW_PATH.SCHEMES_FOLDER."/".SCHEME_BASENAME.$this->version.".xml";

		//if doesnt exist rng file.
		if (!file_exists($rngFilePath)){
			XMD_Log::warning(LOG_PREFIX."scheme $rngFilePath not found");
			return false;
		}

		$rngFileContent = FsUtils::file_get_contents($rngFilePath);

		//If everything ok
		if ($result && !$lazy){
			$rngValidator = new XMLValidator_RNG();
			if(!@$rngValidator->validate($rngFileContent,$xml)){
				XMD_Log::error(LOG_PREFIX."The theme doesn't validate the relaxng $rngFilePath");
				return false;
			}
		}

		return $result;
	}



	/******************STATIC METHODS******************/
	/**
	*Get all themes in Theme folder
	*@param int $limit number of themes to get.
	*@param int $offset first theme to get
	*/
	public static function getAllThemes($limit=null, $offset=null){

		$result = array();

		if (!$offset || !is_int($offset)){
			$offset=0;
		}

		//Root theme folder
		$themesRootFolder = Config::GetValue("AppRoot").MODULE_XSPARROW_PATH.THEMES_FOLDER;
		//Getting all theme folders
		$themesFolders = FsUtils::readFolder($themesRootFolder,false);
		$excluded = array();

		foreach ($themesFolders as $themeFolder ) {

			if (!is_dir($themesRootFolder."/".$themeFolder)){
				$excluded[] = $themeFolder;
			}
		}

		$themesFolders = array_values(array_diff($themesFolders, $excluded));
		$i = $offset;
		$numFound = 0;
		$numThemesFolders = count($themesFolders);

		while($i < $numThemesFolders){

			if ($limit && is_int($limit) && $limit <= $numFound){
				break;
			}

			$themeName = $themesFolders[$i];

			if (is_dir($themesRootFolder."/".$themeName)){
				$fileXml = "$themesRootFolder/$themeName/$themeName.xml";

				if (file_exists($fileXml)){
					$content = FsUtils::file_get_contents($fileXml);
					$theme = new Theme($fileXml);

					if ($theme->isValid()){
						$result[] = $theme;
						$numFound++;
					}
				}
			}

			$i++;
		}

		return $result;
	}
}
