<?php

class ilObjTestListGUITest extends ilTestBaseTestCase
{
    public function testConstruct(): void
    {
        $ilObjTestListGUI = new ilObjTestListGUI(1);
        $this->assertInstanceOf($ilObjTestListGUI::class, $ilObjTestListGUI);
    }

    public function testInit(): void
    {
        $this->markTestSkipped();
    }

    public function testGetCommandFrame(): void
    {
        $this->markTestSkipped();
    }

    public function testGetProperties(): void
    {
        $this->markTestSkipped();
    }

    public function testGetCommandLink(): void
    {
        $this->markTestSkipped();
    }

    public function testGetCommands(): void
    {
        $this->markTestSkipped();
    }

    public function testHandleUserResultsCommand(): void
    {
        $this->markTestSkipped();
    }

    public function testRemoveUserResultsCommand(): void
    {
        $this->markTestSkipped();
    }

    /**
     * @dataProvider createDefaultCommandDataProvider
     */
    public function testCreateDefaultCommand(array $IO): void
    {
        $ilObjTestListGUI = new ilObjTestListGUI(1);
        $this->assertEquals($IO, $ilObjTestListGUI->createDefaultCommand($IO));
    }

    public function createDefaultCommandDataProvider()
    {
        return [
            [[]],
            [[1]],
            [[1, 2]],
            [[1, 2, 3]],
        ];
    }

    public function testAddCommandLinkParameter(): void
    {
        $this->markTestSkipped();
    }

    public function tesModifyTitleLink(): void
    {
        $this->markTestSkipped();
    }
}