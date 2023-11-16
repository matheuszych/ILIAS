<?php

class ilTestMailNotificationTest extends ilTestBaseTestCase
{
    public function testConstruct(): void
    {
        $ilTestMailNotification = new ilTestMailNotification();
        $this->assertInstanceOf(ilTestMailNotification::class, $ilTestMailNotification);
    }

    public function testSendSimpleNotification(): void
    {
        $this->markTestSkipped();
    }

    public function testSendAdvancedNotification(): void
    {
        $this->markTestSkipped();
    }
}