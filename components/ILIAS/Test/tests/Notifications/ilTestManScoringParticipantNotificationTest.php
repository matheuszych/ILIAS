<?php

class ilTestManScoringParticipantNotificationTest extends ilTestBaseTestCase
{
    public function testConstruct(): void
    {
        $ilTestManScoringParticipantNotification = new ilTestManScoringParticipantNotification(0, 0);
        $this->assertInstanceOf(ilTestManScoringParticipantNotification::class, $ilTestManScoringParticipantNotification);
    }

    public function tetSend(): void
    {
        $this->markTestSkipped();
    }

    public function testBuildSubject(): void
    {
        $this->markTestSkipped();
    }

    public function testBuildBody(): void
    {
        $this->markTestSkipped();
    }

    /**
     * @dataProvider dataProviderGetAndSetRecipient
     */
    public function testGetAndSetRecipient(int $IO): void
    {
        $ilTestManScoringParticipantNotification = new ilTestManScoringParticipantNotification(0, 0);
        $this->assertNull(self::callMethod($ilTestManScoringParticipantNotification, 'setRecipient', [$IO]));
        $this->assertEquals($IO, self::callMethod($ilTestManScoringParticipantNotification, 'getRecipient'));
    }

    public function dataProviderGetAndSetRecipient(): array
    {
        return [
            [-1],
            [0],
            [1],
        ];
    }

    public function testConvertFeedbackForMail(): void
    {
        $this->markTestSkipped();
    }
}