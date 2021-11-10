<?php

/**
 * This file is part of ILIAS, a powerful learning management system
 * published by ILIAS open source e-Learning e.V.
 * ILIAS is licensed with the GPL-3.0,
 * see https://www.gnu.org/licenses/gpl-3.0.en.html
 * You should have received a copy of said license along with the
 * source code, too.
 * If this is not the case or you just want to try ILIAS, you'll find
 * us at:
 * https://www.ilias.de
 * https://github.com/ILIAS-eLearning
 */

/**
 * GUI class for learning module editor
 * @author Alexander Killing <killing@leifos.de>
 * @ilCtrl_Calls ilLMEditorGUI: ilObjLearningModuleGUI
 */
class ilLMEditorGUI
{
    protected \ILIAS\GlobalScreen\ScreenContext\ContextServices $tool_context;
    protected ilCtrl $ctrl;
    protected ilRbacSystem $rbacsystem;
    protected ilNavigationHistory $nav_history;
    protected ilHelpGUI $help;
    protected ilGlobalTemplateInterface $tpl;
    protected ilLanguage $lng;
    protected ilObjectDefinition $objDefinition;
    protected int $ref_id;
    protected ilObjLearningModule $lm_obj;
    protected ilLMTree $tree;
    protected int $obj_id;
    protected int $requested_active_node = 0;
    protected bool $to_page = false;

    public function __construct()
    {
        global $DIC;

        $this->rbacsystem = $DIC->rbac()->system();
        $this->nav_history = $DIC["ilNavigationHistory"];
        $this->help = $DIC["ilHelp"];
        $tpl = $DIC["tpl"];
        $lng = $DIC->language();
        $objDefinition = $DIC["objDefinition"];
        $ilCtrl = $DIC->ctrl();
        $rbacsystem = $DIC->rbac()->system();
        $ilNavigationHistory = $DIC["ilNavigationHistory"];

        $lng->loadLanguageModule("content");
        $lng->loadLanguageModule("lm");

        $this->ref_id = (int) ($_GET["ref_id"] ?? 0);
        $this->obj_id = (int) ($_GET["obj_id"] ?? 0);

        // check write permission
        if (!$rbacsystem->checkAccess("write", $this->ref_id)) {
            throw new ilPermissionException($lng->txt("permission_denied"));
        }

        $this->ctrl = $ilCtrl;
        $this->tool_context = $DIC->globalScreen()->tool()->context();

        $this->ctrl->saveParameter($this, array("ref_id", "transl"));

        // initiate variables
        $this->tpl = $tpl;
        $this->lng = $lng;
        $this->objDefinition = $objDefinition;

        /** @var ilObjLearningModule $lm_obj */
        $lm_obj = ilObjectFactory::getInstanceByRefId($this->ref_id);
        $this->lm_obj = $lm_obj;
        $this->tree = new ilLMTree($this->lm_obj->getId());

        $ilNavigationHistory->addItem(
            $this->ref_id,
            "ilias.php?baseClass=ilLMEditorGUI&ref_id=" . $this->ref_id,
            "lm"
        );

        $this->requested_active_node = (int) ($_REQUEST["active_node"] ?? 0);
        $this->to_page = (bool) ($_GET["to_page"] ?? false);

        $this->checkRequestParameters();
    }
    
    /**
     * Check request parameters
     * @throws ilCtrlException
     * @throws ilException
     */
    protected function checkRequestParameters() : void
    {
        $forwards_to_role = $this->ctrl->checkCurrentPathForClass("ilobjrolegui");

        if (!$forwards_to_role && $this->obj_id > 0 && ilLMObject::_lookupContObjID($this->obj_id) != $this->lm_obj->getId()) {
            throw new ilException("Object ID does not match learning module.");
        }
        if ($this->requested_active_node > 0 && ilLMObject::_lookupContObjID($this->requested_active_node) != $this->lm_obj->getId()) {
            throw new ilException("Active node does not match learning module.");
        }
    }
    

    /**
     * @throws ilCtrlException
     * @throws ilException
     */
    public function executeCommand() : void
    {
        global $DIC;

        $this->tool_context->claim()->repository();

        $cmd = "";

        /** @var ilLocatorGUI $loc */
        $loc = $DIC["ilLocator"];
        $loc->addRepositoryItems($this->ref_id);

        if ($this->to_page) {
            $this->ctrl->setParameterByClass("illmpageobjectgui", "obj_id", $this->obj_id);
            $this->ctrl->redirectByClass(array("ilobjlearningmodulegui", "illmpageobjectgui"), "edit");
        }
        
        $this->showTree();

        $next_class = $this->ctrl->getNextClass($this);

        if ($next_class == "" && ($cmd != "explorer")
            && ($cmd != "showImageMap")) {
            $next_class = "ilobjlearningmodulegui";
        }

        // show footer
        $show_footer = !(($cmd == "explorer"));
            
        switch ($next_class) {
            case "ilobjlearningmodulegui":
                $this->main_header();
                $lm_gui = new ilObjLearningModuleGUI("", $this->ref_id, true, false);

                $ret = $this->ctrl->forwardCommand($lm_gui);
                if (strcmp($cmd, "explorer") != 0) {
                    $this->displayLocator();
                }
                // (horrible) workaround for preventing template engine
                // from hiding paragraph text that is enclosed
                // in curly brackets (e.g. "{a}", see ilPageObjectGUI::showPage())
                // @todo 6.0
                /*
                $output =  $this->tpl->getSpecial("DEFAULT", true, true, $show_footer,true);
                $output = str_replace("&#123;", "{", $output);
                $output = str_replace("&#125;", "}", $output);
                header('Content-type: text/html; charset=UTF-8');
                echo $output;*/
                $this->tpl->printToStdout();
                break;

            default:
                $ret = $this->$cmd();
                break;
        }
    }

    /**
     * Show tree
     */
    public function showTree() : void
    {
        $tpl = $this->tpl;

        $this->tool_context->current()->addAdditionalData(ilLMEditGSToolProvider::SHOW_TREE, true);

        $exp = new ilLMEditorExplorerGUI($this, "showTree", $this->lm_obj);
        if (!$exp->handleCommand()) {
//            $tpl->setLeftNavContent($exp->getHTML());
        }
    }
    
    /**
     * output main header (title and locator)
     */
    public function main_header() : void
    {
        $this->tpl->loadStandardTemplate();

        // content style
        $this->tpl->setCurrentBlock("ContentStyle");
        $this->tpl->setVariable(
            "LOCATION_CONTENT_STYLESHEET",
            ilObjStyleSheet::getContentStylePath($this->lm_obj->getStyleSheetId())
        );
        $this->tpl->parseCurrentBlock();

        // syntax style
        $this->tpl->setCurrentBlock("SyntaxStyle");
        $this->tpl->setVariable(
            "LOCATION_SYNTAX_STYLESHEET",
            ilObjStyleSheet::getSyntaxStylePath()
        );
        $this->tpl->parseCurrentBlock();
    }


    /**
     * Display locator
     */
    public function displayLocator() : void
    {
        $this->tpl->setLocator();
    }
}
