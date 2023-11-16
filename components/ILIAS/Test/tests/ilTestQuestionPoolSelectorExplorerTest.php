<?php

class ilTestQuestionPoolSelectorExplorerTest extends ilTestBaseTestCase
{
    public function testConstruct(): void
    {
        $ilTestQuestionPoolSelectorExplorer = new ilTestQuestionPoolSelectorExplorer(
            $this->createMock(ilTestRandomQuestionSetConfigGUI::class),
            '',
            '',
            $this->createMock(ilObjectDataCache::class)
        );
        $this->assertInstanceOf(ilRepositorySelectorExplorerGUI::class, $ilTestQuestionPoolSelectorExplorer);
    }

    /**
     * @dataProvider getAndSetAvailableQuestionPoolsDataProvider
     */
    public function testGetAndSetAvailableQuestionPools(array $IO): void
    {
        $ilTestQuestionPoolSelectorExplorer = new ilTestQuestionPoolSelectorExplorer(
            $this->createMock(ilTestRandomQuestionSetConfigGUI::class),
            '',
            '',
            $this->createMock(ilObjectDataCache::class)
        );

        $this->assertEquals([], $ilTestQuestionPoolSelectorExplorer->getAvailableQuestionPools());
        $this->assertNull($ilTestQuestionPoolSelectorExplorer->setAvailableQuestionPools($IO));
        $this->assertEquals($IO, $ilTestQuestionPoolSelectorExplorer->getAvailableQuestionPools());
    }

    public function getAndSetAvailableQuestionPoolsDataProvider(): array
    {
        return [
            [[]],
            [[1]],
            [[1, 2]],
            [[1, 2, 3]],
        ];

    }

    public function testIsAvailableQuestionPool(): void
    {
        $this->markTestSkipped();
    }

    public function testIsNodeVisible(): void
    {
        $this->markTestSkipped();
    }
}