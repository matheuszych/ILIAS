<?php

class ilObjTestVerificationGUITest extends ilTestBaseTestCase
{
    private ilObjTestVerificationGUI $testObj;

    protected function setUp(): void
    {
        parent::setUp();

        $this->addGlobal_ilLoggerFactory();

        $this->testObj = new ilObjTestVerificationGUI(
            0,
            1,
            0,
        );
    }

    public function testConstruct(): void
    {
        $this->assertInstanceOf(ilObjTestVerificationGUI::class, $this->testObj);
    }

    public function testGetType(): void
    {
        $this->assertEquals('tstv', $this->testObj->getType());
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