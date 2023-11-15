<?php

class ilObjTestVerificationGUITest extends ilTestBaseTestCase
{
    public function testConstruct(): void
    {
        $ilObjTestVerificationGUI = new ilObjTestVerificationGUI(
            0,
            1,
            0,
        );
        $this->assertInstanceOf(ilObjTestVerificationGUI::class, $ilObjTestVerificationGUI);
    }

    public function testGetType(): void
    {
        $ilObjTestVerificationGUI = new ilObjTestVerificationGUI(
            0,
            1,
            0,
        );
        $this->assertEquals('tstv', $ilObjTestVerificationGUI->getType());
    }

    public function testCreate(): void
    {
        $this->markTestSkipped();
    }

    public function testSave(): void
    {
        $this->markTestSkipped();
    }

    public function testDeliver(): void
    {
        $this->markTestSkipped();
    }

    public function testRender(): void
    {
        $this->markTestSkipped();
    }

    public function testDownloadFromPortfolioPage(): void
    {
        $this->markTestSkipped();
    }

    public function test_goto(): void
    {
        $this->markTestSkipped();
    }

    public function testGetRequestValue(): void
    {
        $this->markTestSkipped();
    }
}