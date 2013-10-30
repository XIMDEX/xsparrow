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
ModulesManager::file('/inc/cli/CliParser.class.php');
ModulesManager::file('/inc/cli/CliReader.class.php');
ModulesManager::file('/inc/io/BaseIO.class.php');
ModulesManager::file('/inc/fsutils/FsUtils.class.php');
ModulesManager::file(MODULE_XSPARROW_PATH . '/BuildParser.class.php');
ModulesManager::file('/inc/helper/DebugLog.class.php');
ModulesManager::file('/actions/addximlet/Action_addximlet.class.php');
ModulesManager::file(MODULE_XSPARROW_PATH . '/inc/Theme.class.php');
ModulesManager::file(MODULE_XSPARROW_PATH . '/XSparrowProject.class.php');
ModulesManager::file('/inc/xml/XSLT.class.php');

/**
 * Create a project using XSparrow wizard
 */
class Action_createproject extends ActionAbstract {

    private $project = null;
    public $name ="XSparrow";
    private $schemas = array();
    private $theme = null;

    /**
     *Main function, first step in the creation project process
     */
    public function index() {

        $themes = Theme::getAllThemes();

        $nodeProjectRoot = new Node(10000);
        $arrayChildrens = $nodeProjectRoot->GetChildren();
        $arrayProjectNames = array();
        foreach ($arrayChildrens as $idChildren) {
            $nodeProject = new Node($idChildren);
            $arrayProjectNames[] = $nodeProject->GetNodeName();
        }
        $prefixName = "XSparrow";
        $proposalName = $prefixName;
        $foundValidName = false;
        $cont = 1;
        do {
            if (!in_array($proposalName, $arrayProjectNames)){
                $foundValidName = true;
            }else{
                $proposalName = $prefixName.$cont;
                $cont++;
            }
            
        } while (!$foundValidName);
        
        $arrayTheme = array();
        foreach ($themes as $theme ) {
            $themeDescription["name"] = $theme->_shortname;
            $themeDescription["title"] = $theme->_title;
            $themeDescription["description"] = $theme->_description;
            $themeDescription["configurable"] = $theme->configurable=="1"? true: false;

            $arrayTheme[] = $themeDescription;
        }

        $values = array(
            "go_method" => "createproject",
            "name" => $proposalName,
            "themes" => $arrayTheme
        );



    	$extraPath = "";
    	if (DEBUG_MODE)
    		$extraPath = DEBUG_FOLDER;

        $template = "index";
        $jsFolder = "/modules/XSparrow/actions/createproject/resources/js/$extraPath";
        $cssFolder = "/modules/XSparrow/actions/createproject/resources/css/";

        $this->addJs($jsFolder . "colorpicker.js");
        $this->addJs($jsFolder . "fileupload.js");
        $this->addJs($jsFolder . "fontselector.js");
        $this->addJs($jsFolder . "ximdex.select.js");
        $this->addJs($jsFolder . "slider.js");

        $this->addJs($jsFolder . "projectCreation.js");
        $this->addJs($jsFolder . "init.js");

        $this->addCss($cssFolder . "style.css");
        $this->addCss($cssFolder . "colorpicker.css");
        $this->addCss($cssFolder . "fontselector.css");
        $this->addCss($cssFolder . "ximdex.select.css");

        $this->render($values, $template, 'default-3.0.tpl');
    }

    /**
    *Creating a project from a form.
    *Get all the values from the request params.
    */
    public function createproject() {

        //Creating project        
        $themeName = $this->request->getParam("theme");
        $xml = $this->request->getParam("xml");
        $this->theme = new Theme($themeName);
        $this->name = $this->request->getParam("name");

        //1. Create project
        //2. Add templates and schemas
        //3. Add Server
        //4. Add Css, images and common
        //5. Add ximlet
        //6. Add document

        $nodeTypeName = "Project";
        $nodeType = new NodeType();
        $nodeType->SetByName($nodeTypeName);

        //Creating project
        $data = array(
            'NODETYPENAME' => $nodeTypeName,
            'NAME' => $this->name,
            'NODETYPE' => $nodeType->GetID(),
            'PARENTID' => 10000
            );

        $io = new BaseIO();
        $projectId = $io->build($data);
        if ($projectId < 1) {
            return false;
        }

        $servers = $this->theme->project->getServers();
        $schemas = $this->theme->project->getSchemes();
        $templates = $this->theme->project->getTemplates();
        
        foreach($schemas as $schema){
            $this->schemas = array_merge($this->schemas, $this->insertFiles($projectId, "schemas", array($schema)));
        }

        foreach($templates as $template){
            $this->insertFiles($projectId, "templates", array($template));
        }

        foreach ($servers as $server) {
            $this->insertServer($projectId, $server);
        }
        
        $template = "success";

        $values = array();

        $this->render($values, $template, 'default-3.0.tpl');
    }


    /**
    *Get Possible Channel by default. Giving priority to html or web channel.
    */
    function getChannel() {
        $channels = Channel::GetAllChannels();
        if (is_null($channels)) {
            return false;
        }
        $channelId = false;
        foreach ($channels as $id_channel) {
            $channel = new Channel($id_channel);
            if ($channel->GetName() == "html" or $channel->GetName() == "web") {
                $channelId = $id_channel;
                break;
            }
        }
        if (!$channelId)
            $channelId = $channels[0];
        Module::log(Module::SUCCESS, "Using channel with ID " . $channelId);
        return $channelId;
    }

    function getLanguage() {
        $language = new Language();
        $langs = $language->GetList();
        if (is_null($langs)) {
            return false;
        }
        $langId = $langs[0];
        Module::log(Module::SUCCESS, "Using language with ID " . $langId);
        return $langId;
    }

    /**
     * Create a Server Node and all the descendant: xmldocument, ximlet, images, css and common 
     *
     * @param int $projectId Ximdex id for node project
     * @param  Loader_Server $server Object to create the server
     * @return int Server id.
     */
    private function insertServer($projectId, $server) {

        $nodeType = new NodeType();
        $nodeType->SetByName($server->nodetypename);
        $idNodeType = ($nodeType->get('IdNodeType') > 0) ? $nodeType->get('IdNodeType') : NULL;

        $data = array(
            'NODETYPENAME' => $server->nodetypename,
            'NAME' => $server->name,
            'NODETYPE' => $idNodeType,
            'PARENTID' => $projectId
        );

        $io = new BaseIO();
        $serverId = $io->build($data);
        if ($serverId < 1) {
            return false;
        }

        $server->serverid = $serverId;
        $server->url = preg_replace('/\{URL_ROOT\}/', Config::GetValue('UrlRoot'), $server->url);
        $server->initialDirectory = preg_replace('/\{XIMDEX_ROOT_PATH\}/', XIMDEX_ROOT_PATH, $server->initialDirectory);

        $nodeServer = new Node($serverId);
        $physicalServerId = $nodeServer->class->AddPhysicalServer(
                $server->protocol, $server->login, $server->password, $server->host, $server->port, $server->url, $server->initialDirectory, $server->overrideLocalPaths, $server->enabled, $server->previsual, $server->description, $server->isServerOTF
        );

        $nodeServer->class->AddChannel($physicalServerId, $this->project->channel);
        Module::log(Module::SUCCESS, "Server creation O.K.");        
        
        // common
        $arrayCommon = $server->getCommon();

        $this->createResourceByFolder($server, "common", "CommonFolder", $arrayCommon);


        
        $arrayTemplates = $server->getTemplates();
        foreach($arrayTemplates as $template){
            $this->insertFiles($serverId, "templates", array($template));
        }       

        //images
        $arrayImages = $server->getImages();        
        $this->createResourceByFolder($server, "images", "ImagesFolder", $arrayImages);

        //Css
        $arrayCss = $server->getCSS();
        $this->createResourceByFolder($server, "css", "CssFolder", $arrayCss);
        

        // document
        $docs = $server->getXimdocs();
        $ret = $this->insertDocs($server->serverid, $docs);

        // ximlet
        $let = $server->getXimlet();
        $ret = $this->insertDocs($server->serverid, $let, true);
        
        return $serverId;
    }

    private function createResourceByFolder($server, $rootFolderName, $rootFolderNodeType, $arrayXimFiles){

        $this->server=$server->serverid;
        $nodeServer = new Node($server->serverid);
        $rootFolderId = $nodeServer->GetChildByName($rootFolderName);
        $this->$rootFolderName = $rootFolderId;
        $newFolderNodeType = new NodeType();
        $newFolderNodeType->SetByName($rootFolderNodeType);        
        $this->createResource($rootFolderId, $arrayXimFiles, $newFolderNodeType->GetID());

    }

    private function createResource($rootFolderId, $arrayXimFiles, $idFolderNodeType){

        $createdFolders = $this->createFolders($rootFolderId, array_keys($arrayXimFiles),$idFolderNodeType);
        foreach ($arrayXimFiles as $filePath => $ximFileObject) {
            $lastSlash = strrpos($filePath, "/");
            $folderPath = substr($filePath, 0, $lastSlash+1);
            if ($createdFolders[$folderPath]){

                $folderNode = new Node($createdFolders[$folderPath]);
                $folderName = $folderNode->GetNodeName();
                $idParent = $folderNode->get("IdParent");
                $this->insertFiles($idParent, $folderName, array($ximFileObject));
            }else{
                //Any error message here
            }            
            
        }
    }

    private function createFolders($rootFolderId, $arrayNames, $idNodeType){
        $createdFolders = array("/" => $rootFolderId);
        foreach ($arrayNames as $name) {
            $folderId = $rootFolderId;
            $arrayNews = explode("/", $name);
            $currentFolderName = "/";
            for($i = 1; $i < count($arrayNews)-1; $i++){
                $currentFolderName.= $arrayNews[$i]."/";
                if (!array_key_exists($currentFolderName, $createdFolders)){
                    $folder = new Node();
                    $idFolder = $folder->CreateNode($arrayNews[$i], $folderId, $idNodeType, null);
                    $createdFolders[$currentFolderName] = $idFolder;
                }
                $folderId = $createdFolders[$currentFolderName];
            }

        }

        return $createdFolders;
    }

    function insertDocs($parentId, $files,$isXimlet=false) {

        if ($isXimlet){
            $xFolderName = 'ximlet';
            $nodeTypeName = 'XIMLET';
            $nodeTypeContainer = "XIMLETCONTAINER";
        }else{
            $xFolderName = 'documents';
            $nodeTypeName = 'XMLDOCUMENT';
            $nodeTypeContainer = "XMLCONTAINER";
        }
        $ret = array();
        if (count($files) == 0)
            return $ret;

        $project = new Node($parentId);
        $xFolderId = $project->GetChildByName($xFolderName);

        if (empty($xFolderId)) {
            Module::log(Module::ERROR, $xFolderName . ' folder not found');
            return false;
        }

        $nodeType = new NodeType();
        $nodeType->SetByName($nodeTypeName);
        $idNodeType = $nodeType->get('IdNodeType') > 0 ? $nodeType->get('IdNodeType') : NULL;

        $io = new BaseIO();

      /*$title = $this->request->getParam("title");
        $principalColor = $this->request->getParam("principal_color");
        $secundaryColor = $this->request->getParam("secundary_color");
        $fontColor = $this->request->getParam("font_color");*/


        foreach ($files as $file) {            
            $idSchema = $this->schemas[$file->templatename];
            $file->channel = $file->channel == '{?}' ? $this->getChannel() : $file->channel;
            $file->language = $file->language == '{?}' ? $this->getLanguage() : $file->language;


            $data = array(
                'NODETYPENAME' => $nodeTypeContainer,
                'NAME' => $file->name,
                'PARENTID' => $xFolderId,
                'CHILDRENS' => array(
                    array(
                        'NODETYPENAME' => 'VISUALTEMPLATE',
                        'ID' => $idSchema
                    )
                )
            );

            $containerId = $io->build($data);

            if (!($containerId > 0)) {
                Module::log(Module::ERROR, "document " . $file->name . " couldn't be created ($containerId)");
                continue;
            }

            $data = array(
                'NODETYPENAME' => $nodeTypeName,
                'NAME' => $file->name,
                'NODETYPE' => $idNodeType,
                'PARENTID' => $containerId,
                'CHILDRENS' => array(
                    array('NODETYPENAME' => 'VISUALTEMPLATE', 'ID' => $idSchema),
                    array('NODETYPENAME' => 'CHANNEL', 'ID' => $file->channel),
                    array('NODETYPENAME' => 'LANGUAGE', 'ID' => $file->language),
                    array('NODETYPENAME' => 'PATH', 'SRC' => $file->getPath())
                )
            );

            $docId = $io->build($data);
            if ($docId > 0  && $isXimlet && $file->name == "config") {
                
                $docNode = new Node($docId);
                $queries = array("//header", "//body");
                $content = $this->setBackgroundContent($queries);
                $content = str_replace('<?xml version="1.0"?>', '', $content);
                $docNode->SetContent($content);
                $ret[$file->filename] = $docId;
                Module::log(Module::SUCCESS, "Importing " . $file->name);
            } else if (!($docId > 0))  {
//              debug::log($project, $file, $data);
                Module::log(Module::ERROR, "XML document " . $file->name . " couldn't be created ($docId)");
            }


        	if ($isXimlet){
	            $actionAddximlet = new Action_addximlet();
        	    $actionAddximlet->createRelXimletSection($parentId, $containerId, 1);
	        }
	}
        if (count($ret) == 0)
            $ret = false;
        return $ret;
    }

    private function setBackgroundContent($queries){

        $content = $this->request->getParam("xml");
        $domDoc = DOMDocument::loadXML($content);
        $xpath = new DOMXPath($domDoc);
        foreach ($queries as $query) {
            $list = $xpath->query($query);                
            if ($list->length){

                $nodeBackground = $list->item(0);
                $backgroundImage = $nodeBackground->getAttribute("background-image");
                $imagePath = Config::GetValue("AppRoot")."/data/tmp/XSparrow/{$backgroundImage}";
                if (file_exists($imagePath)){

                    $pos = strpos($backgroundImage,"_");
                    $name = substr($backgroundImage, $pos+1);
                    $nodeBackground->setAttribute("background-image","images/{$name}");

                    $nodeTypeName = "IMAGEFILE";
                    $nodeType = new NodeType();
                    $nodeType->SetByName($nodeTypeName);
                    $idNodeType = $nodeType->get('IdNodeType') > 0 ? $nodeType->get('IdNodeType') : NULL;

                    $data = array(
                      'NODETYPENAME' => $nodeTypeName,
                      'NAME' => $name,
                      'NODETYPE' => $idNodeType,
                      'PARENTID' => $this->images,
                      'CHILDRENS' => array(
                        array(
                            'NODETYPENAME' => 'PATH',
                            'SRC' => $imagePath
                        ))
                    );

                    $io = new BaseIO();
                    $id = $io->build($data);
                }
                
            }
            
        }
        $content = $domDoc->saveXML();
        return $content;
    }

    function insertFiles($parentId, $xFolderName, $files) {

        $ret = array();
        if (count($files) == 0)
            return $ret;

        $project = new Node($parentId);
        $xFolderId = $project->GetChildByName($xFolderName);

        if (empty($xFolderId)) {
            Module::log(Module::ERROR, $xFolderName . ' folder not found');
            return false;
        }

        $io = new BaseIO();

        foreach ($files as $file) {

            $nodeType = new NodeType();
            $nodeType->SetByName($file->nodetypename);
            $idNodeType = $nodeType->get('IdNodeType') > 0 ? $nodeType->get('IdNodeType') : NULL;

            $data = array(
                'NODETYPENAME' => $file->nodetypename,
                'NAME' => $file->basename,
                'NODETYPE' => $idNodeType,
                'PARENTID' => $xFolderId,
                'CHILDRENS' => array(
                    array(
                        'NODETYPENAME' => 'PATH',
                        'SRC' => $file->path
                    )
                )
            );

            $id = $io->build($data);
            $this->specialCase($id, $file);

            if ($id > 0) {
                $ret[$file->filename] = $id;
                Module::log(Module::SUCCESS, "Importing " . $file->basename);
            } else {
                Module::log(Module::ERROR, "Error ($id) importing " . $file->basename);
                Module::log(Module::ERROR, print_r($io->messages->messages, true));
            }
        }

        if (count($ret) == 0)
            $ret = false;
        return $ret;
    }

    /**
    *Process file if its a special one.
    */
    private function specialCase($idNode, &$file){

        $node = new Node($idNode);
        if ($file->basename == "docxap.xsl"){
            $docxapContent = $node->GetContent();
            $urlPath = Config::GetValue("UrlRoot");
            $docxapContent = str_replace("{URL_PATH}", $urlPath, $docxapContent);
            $docxapContent = str_replace("{PROJECT_NAME}", $this->name, $docxapContent);
            $node->SetContent($docxapContent);
        }
    }

    function updateXsl($parentId, $files) {

        if (count($files) == 0)
            return false;

        $project = new Node($parentId);
        $ptdFolderId = $project->GetChildByName('templates');

        $nodePtds = new Node($ptdFolderId);
        if (empty($ptdFolderId)) {
            Module::log(Module::ERROR, 'Ptd folder not found');
            return false;
        }

        $nodeType = new NodeType();
        $nodeType->SetByName('XSLTEMPLATE');
        $idNodeType = ($nodeType->get('IdNodeType') > 0) ? $nodeType->get('IdNodeType') : NULL;


        $node = new Node($ptdFolderId);
        $io = new BaseIO();

        $ximdexUrl = Config::getValue('UrlRoot');
        $projectUrl = Config::getValue('UrlRoot') . '/data/nodes/' . $this->projectName;
        $servers = $this->project->getServers();
        $serverUrl = $projectUrl . '/' . $servers[0]->name;

        foreach ($files as $file) {

            $content = $file->getContent();

            if (preg_match('/\{URL_ROOT\}/', $content)) {
                $content = preg_replace('/\{URL_ROOT\}/', $ximdexUrl, $content);
            }
            if (preg_match('/\{URL_PROJECT\}/', $content)) {
                $content = preg_replace('/\{URL_PROJECT\}/', $projectUrl, $content);
            }
            if (preg_match('/\{URL_SERVER\}/', $content)) {
                $content = preg_replace('/\{URL_SERVER\}/', $serverUrl, $content);
            }

            $children = $nodePtds->GetChildByName($file->basename);
            $ch = new Node($children);
            if (!($ch->get('IdNode') > 0)) {
                Module::log(Module::ERROR, "Updated xsl not O.K. Cannot find the file " . $file->basename);
                continue;
            }

            $result = $ch->setContent($content);

            if (!$result) {
                Module::log(Module::SUCCESS, "Updated xsl O.K. " . $file->basename);
            } else {
                Module::log(Module::ERROR, "Updated xsl not O.K. " . $file->basename);
            }
        }
    }


    public function GetTypeOfNewNode($nodeID) {

        $node = new Node($nodeID);
        if (!$node->get('IdNode') > 0) {
            return null;
        }
        $nodeTypeName = $node->nodeType->GetName();

        switch ($nodeTypeName) {
            case "Projects":
                $newNodeTypeName = "Project";
                $friendlyName = "Project";
                break;


            case "Project":
                $newNodeTypeName = "Server";
                $friendlyName = "Server";
                break;
        }
        $result = array();
        $result["name"] = $newNodeTypeName;
        $result["friendlyName"]=$friendlyName;
        $resukt["idNodeType"]=$node->GetNodeType();
        return $result;
    }


    /**
    *<p>Set channel, languages and transformer for a project</p>
    *@param $idProject node object, id for project to set.
    *@return nothing
    */
    private function setAdvancedSettings($project){

        if ($project->GetID()){
            $channel = $this->project->channel;
            $channel = $channel == '{?}' ? $this->getChannel() : $channel;
            $this->project->channel = $channel;

            $lang = $this->project->language;
            $lang = $lang == '{?}' ? $this->getLanguage() : $lang;
            $this->project->language = $lang;

            $project->setProperty('Transformer', $this->project->Transformer);
            $project->setProperty('channel', $this->project->channel);
            $project->setProperty('language', $this->project->lang);
        }

    }

    /**
     * Upload a file from the customize project
     */
    public function uploadImage(){
        
        $filename = (isset($_SERVER['HTTP_X_FILENAME']) ? $_SERVER['HTTP_X_FILENAME'] : false);
        $result["status"] = "error";
        if ($filename) {            

            $tmpFolder = Config::GetValue("AppRoot")."/data/tmp/XSparrow";
            $tmpFile= FsUtils::getUniqueFile($tmpFolder, "_$filename");
            if (file_put_contents("$tmpFolder/{$tmpFile}_{$filename}", file_get_contents('php://input'))){
                $result["status"] ="ok";
                $result["url"] = Config::GetValue("UrlRoot")."/data/tmp/XSparrow/{$tmpFile}_{$filename}";
                $result["resource"] = "{$tmpFile}_{$filename}";
            }   
            
        }else{
            error_log("Filename doesnt exist");   
        }
        print json_encode($result);
        die();
    }


	/**
	*Load the iframe content from the transformation.
	*/
    public function loadPreview(){

        $values = array();
        $themeName = $this->request->getParam("theme");

        $theme = new Theme($themeName);
        $configXmlPath = Config::GetValue("AppRoot")."/data/tmp/XSparrow/{$theme->_shortname}/configuration.xml";
        $docxapXslPath = Config::GetValue("AppRoot")."/data/tmp/XSparrow/{$theme->version}/{$theme->projectName}/docxap.xsl";
        
        $domDoc = new DOMDocument();
        $domDoc->preserveWhiteSpace = false;
        $domDoc->validateOnParse = true;
        $domDoc->formatOutput = true;

        $xsltHandler = new XSLT();

        error_log("$configXmlPath ====== $docxapXslPath");
        $xsltHandler->setXML($configXmlPath);
        $xsltHandler->setXSL($docxapXslPath);

        $content = $xsltHandler->process();
        $domDoc->loadHTML($content);
        $document = $domDoc->saveHTML();

        $replacement=Config::GetValue("UrlRoot")."/data/tmp/XSparrow/{$theme->_shortname}/$1";
        $content = preg_replace("/@@@RMximdex.dotdot\((.+)\)@@@/", $replacement, $content);
        if ($content){
            print_r($content);
            die();           
        }else{
            $template = "preview";
            $this->render($values, $template, 'basic_html.tpl');
        }

    }

    public function getTheme(){

        $themeName = $this->request->getParam("theme");
        $theme = new Theme($themeName);
        header('Content-type: text/xml');
        if ($theme){            
            print trim($theme->xml);
        }
        else
            print "<error/>";

        exit();
    }
}

?>
