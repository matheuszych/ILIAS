<?php

class ilObjAssessmentFolderGUITest extends ilTestBaseTestCase
{
    public function testConstruct(): void
    {
        $ilObjAssessmentFolderGUI = new ilObjAssessmentFolderGUI(
            null,
            0,
            true,
            true,
        );
        $this->assertInstanceOf(ilObjAssessmentFolderGUI::class, $ilObjAssessmentFolderGUI);
    }

    public function testGetAssessmentFolder(): void
    {
        $this->markTestSkipped();
    }

    public function testExecuteCommand(): void
    {
        $this->markTestSkipped();
    }

    public function testSettingsObject(): void
    {
        $this->markTestSkipped();
    }

    public function testBuildSettingsForm(): void
    {
        $this->markTestSkipped();
    }

    public function testSaveSettingsObject(): void
    {
        $this->markTestSkipped();
    }

    public function testShowLogObject(): void
    {
        $this->markTestSkipped();
    }

    public function testExportLogObject(): void
    {
        $this->markTestSkipped();
    }

    public function testGetLogDataOutputForm(): void
    {
        $this->markTestSkipped();
    }

    public function testLogsObject(): void
    {
        $this->markTestSkipped();
    }

    public function testDeleteLogObject(): void
    {
        $this->markTestSkipped();
    }

    public function testLogAdminObject(): void
    {
        $this->markTestSkipped();
    }

    public function testGetAdminTabs(): void
    {
        $this->markTestSkipped();
    }

    public function testGetLogdataSubtabs(): void
    {
        $this->markTestSkipped();
    }

    public function testGetTabs(): void
    {
        $this->markTestSkipped();
    }

    public function testShowLogSettingsObject(): void
    {
        $this->markTestSkipped();
    }

    public function testSaveLogSettingsObject(): void
    {
        $this->markTestSkipped();
    }

    public function testGetLogSettingsForm(): void
    {
        $this->markTestSkipped();
    }
}