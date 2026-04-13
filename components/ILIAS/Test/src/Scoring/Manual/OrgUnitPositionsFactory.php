<?php

/**
 * This file is part of ILIAS, a powerful learning management system
 * published by ILIAS open source e-Learning e.V.
 *
 * ILIAS is licensed with the GPL-3.0,
 * see https://www.gnu.org/licenses/gpl-3.0.en.html
 * You should have received a copy of said license along with the
 * source code, too.
 *
 * If this is not the case or you just want to try ILIAS, you'll find
 * us at:
 * https://www.ilias.de
 * https://github.com/ILIAS-eLearning
 *
 *********************************************************************/

declare(strict_types=1);

namespace ILIAS\Test\Scoring\Manual;

class OrgUnitPositionsFactory extends PositionsFactory
{
    protected function getTestParticipants(): array
    {
        $test_participants = $this->test_obj->getTestParticipants();

        $allowed_participants = $this->access->filterUserIdsByPositionOfCurrentUser(
            \ilOrgUnitOperation::OP_SCORE_PARTICIPANTS,
            $this->test_obj->getRefId(),
            array_column($test_participants, 'usr_id')
        );

        return array_filter(
            $test_participants,
            static fn(array $participant): bool => in_array($participant['usr_id'], $allowed_participants, true)
        );
    }
}
