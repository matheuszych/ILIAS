<?php

class ilECSTestSettingsTest extends ilTestBaseTestCase
{
    public function testConstruct(): void
    {
        $ilECSObjectSettings = new ilECSTestSettings($this->createMock(ilObject::class));
        $this->assertInstanceOf(ilECSTestSettings::class, $ilECSObjectSettings);
    }

    public function testGetECSObjectType(): void
    {
        $ilECSObjectSettings = new ilECSTestSettings($this->createMock(ilObject::class));
        $this->assertEquals('/campusconnect/tests', self::callMethod($ilECSObjectSettings, 'setECSObjectType'));
    }

    public function testBuildJson(): void
    {
        $this->markTestSkipped();
    }
}