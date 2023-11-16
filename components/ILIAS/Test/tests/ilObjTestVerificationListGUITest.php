<?php

class ilObjTestVerificationListGUITest extends ilTestBaseTestCase
{
    public function testConstruct(): void
    {
        $ilObjTestVerificationListGUI = new ilObjTestVerificationListGUI();
        $this->assertInstanceOf(ilObjTestVerificationListGUI::class, $ilObjTestVerificationListGUI);
    }

    public function testInit(): void
    {
        $ilObjTestVerificationListGUI = new ilObjTestVerificationListGUI();
        $this->assertNull($ilObjTestVerificationListGUI->init());

        $reflection  = new ReflectionObject($ilObjTestVerificationListGUI);

        $delete_enabled_property = $reflection->getProperty('delete_enabled');
        $this->assertTrue($delete_enabled_property->getValue($ilObjTestVerificationListGUI));

        $cut_enabled_property = $reflection->getProperty('cut_enabled');
        $this->assertTrue($cut_enabled_property->getValue($ilObjTestVerificationListGUI));

        $copy_enabled_property = $reflection->getProperty('copy_enabled');
        $this->assertTrue($copy_enabled_property->getValue($ilObjTestVerificationListGUI));

        $subscribe_enabled_property = $reflection->getProperty('subscribe_enabled');
        $this->assertFalse($subscribe_enabled_property->getValue($ilObjTestVerificationListGUI));

        $link_enabled_property = $reflection->getProperty('link_enabled');
        $this->assertFalse($link_enabled_property->getValue($ilObjTestVerificationListGUI));

        $info_screen_enabled_property = $reflection->getProperty('info_screen_enabled');
        $this->assertFalse($info_screen_enabled_property->getValue($ilObjTestVerificationListGUI));

        $type_property = $reflection->getProperty('type_property');
        $this->assertEquals('tstv', $type_property->getValue($ilObjTestVerificationListGUI));

        $gui_class_name_property = $reflection->getProperty('gui_class_name');
        $this->assertEquals(ilObjTestVerificationGUI::class, $gui_class_name_property->getValue($ilObjTestVerificationListGUI));

        $commands_property = $reflection->getProperty('commands');
        $this->assertEquals(
            ['permission' => 'read', 'cmd' => 'view', 'lang_var' => 'show', 'default' => true],
            $commands_property->getValue($ilObjTestVerificationListGUI),
        );
    }

    public function testGetProperties(): void
    {
        $ilObjTestVerificationListGUI = new ilObjTestVerificationListGUI();

        $this->assertEquals([[
                'alert' => false,
                'property' => 'Type',
                'value' => 'Test Certificate',
            ]],
            $ilObjTestVerificationListGUI->getProperties(),
        );
    }
}