<?php

class ilTestExpressPageObjectGUITest extends ilTestBaseTestCase
{
    public function testConstruct(): void
    {
        $ilTestExpressPageObjectGUI = new ilTestExpressPageObjectGUI(
            0,
            0,
            null,
        );
        $this->assertInstanceOf(ilTestExpressPageObjectGUI::class, $ilTestExpressPageObjectGUI);
    }

    public function testNextQuestion(): void
    {
        $this->markTestSkipped();
    }

    public function testPrevQuestion(): void
    {
        $this->markTestSkipped();
    }

    public function testExecuteCommand(): void
    {
        $this->markTestSkipped();
    }

    public function testAddPageOfQuestions(): void
    {
        $this->markTestSkipped();
    }

    public function testHandleToolbarCommand(): void
    {
        $this->markTestSkipped();
    }

    public function testAddQuestion(): void
    {
        $this->markTestSkipped();
    }

    public function testQuestions(): void
    {
        $this->markTestSkipped();
    }

    public function testRedirectToQuestionEditPage(): void
    {
        $this->markTestSkipped();
    }

    public function testRedirectToQuestionPoolSelectionPage(): void
    {
        $this->markTestSkipped();
    }

    public function testInsertQuestions(): void
    {
        $this->markTestSkipped();
    }
}