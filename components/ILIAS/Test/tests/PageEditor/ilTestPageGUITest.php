<?php

namespace PageEditor;

use ilTestBaseTestCase;
use ilTestPageGUI;

class ilTestPageGUITest extends ilTestBaseTestCase
{
    public function testConstruct(): void
    {
        $ilTestPageGUI = new ilTestPageGUI('', 0);
        $this->assertInstanceOf(ilTestPageGUI::class, $ilTestPageGUI);
    }

    /**
     * @dataProvider getTabsDataProvider
     */
    public function testGetTabs(string $input): void
    {
        $ilTestPageGUI = new ilTestPageGUI('', 0);
        $this->assertNull($ilTestPageGUI->getTabs($input));
    }

    public function getTabsDataProvider(): array
    {
        return [
            [''],
            ['test'],
        ];
    }
}