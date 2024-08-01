<?php

namespace Logging;

use ILIAS\Test\Logging\AdditionalInformationGenerator;
use ILIAS\TestQuestionPool\Questions\GeneralQuestionPropertiesRepository;
use ilTestBaseTestCase;

class AdditionalInformationGeneratorTest extends ilTestBaseTestCase
{
    private AdditionalInformationGenerator $additionalInformationGenerator;

    protected function setUp(): void
    {
        parent::setUp();
        global $DIC;
        $mustacheEngine = $this->createMock(\Mustache_Engine::class);
        $questionsRepo = $this->createMock(GeneralQuestionPropertiesRepository::class);

        $this->additionalInformationGenerator = new AdditionalInformationGenerator($mustacheEngine, $DIC['lng'], $DIC['ui.factory'], $DIC['refinery'], $questionsRepo);
    }

    public function test_getTrueFalseTagForBool(): void
    {
        $this->assertSame('{{ true }}', $this->additionalInformationGenerator->getTrueFalseTagForBool(true));
        $this->assertSame('{{ false }}', $this->additionalInformationGenerator->getTrueFalseTagForBool(false));
    }

    public function test_getEnabledDisabledTagForBool(): void
    {
        $this->assertSame('{{ enabled }}', $this->additionalInformationGenerator->getEnabledDisabledTagForBool(true));
        $this->assertSame('{{ disabled }}', $this->additionalInformationGenerator->getEnabledDisabledTagForBool(false));
    }

    public function test_getNoneTag(): void
    {
        $this->assertSame('{{ none }}', $this->additionalInformationGenerator->getNoneTag());
    }

    public function test_getTagForLangVar(): void
    {
        $this->assertSame('{{ testvar }}', $this->additionalInformationGenerator->getTagForLangVar("testvar"));
        $this->assertSame('{{ testvar2 }}', $this->additionalInformationGenerator->getTagForLangVar("testvar2"));
    }

    public function test_getCheckedUncheckedTagForBool(): void
    {
        $this->assertSame('{{ checked }}', $this->additionalInformationGenerator->getCheckedUncheckedTagForBool(true));
        $this->assertSame('{{ unchecked }}', $this->additionalInformationGenerator->getCheckedUncheckedTagForBool(false));
    }


}
