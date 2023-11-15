<?php

namespace PageEditor;

use ilTestBaseTestCase;
use ilTestPage;

class ilTestPageTest extends ilTestBaseTestCase
{
    public function testConstruct(): void
    {
        $ilTestPage = new ilTestPage();
        $this->assertInstanceOf(ilTestPage::class, $ilTestPage);
    }

    public function testGetParentType(): void
    {
        $ilTestPage = new ilTestPage();
        $this->assertEquals('tst', $ilTestPage->getParentType());
    }

    /**
     * @dataProvider createPageWithNextIdDataProvider
     */
    public function testCreatePageWithNextId(int $IO): void
    {
        $ilTestPage = new ilTestPage();
        $ilTestPageReflection = new \ReflectionClass(ilTestPage::class);
        $property = $ilTestPageReflection->getProperty('db');
        $property->setValue($ilTestPage, $this->createConfiguredMock(\ilDBInterface::class, [
            'query' => $this->createConfiguredMock(\ilDBStatement::class, [
                'fetchAssoc' => [
                    'last_id' => $IO,
                ],
            ]),
        ]));

        $this->assertEquals($IO, $ilTestPage->createPageWithNextId());
    }

    public function createPageWithNextIdDataProvider(): array
    {
        return [
            [-1],
            [0],
            [1],
        ];
    }
}