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

/**
 * Class ilBuddySystemUnlinkedStateRelationTest
 * @author Michael Jansen <mjansen@databay.de>
 */
class ilBuddySystemUnlinkedStateRelationTest extends ilBuddySystemBaseStateTest
{
    public function getInitialState(): ilBuddySystemRelationState
    {
        return new ilBuddySystemUnlinkedRelationState();
    }

    public function testIsUnlinked(): void
    {
        $this->assertTrue($this->relation->isUnlinked());
    }

    public function testIsLinked(): void
    {
        $this->assertFalse($this->relation->isLinked());
    }

    public function testIsRequested(): void
    {
        $this->assertFalse($this->relation->isRequested());
    }

    public function testIsIgnored(): void
    {
        $this->assertFalse($this->relation->isIgnored());
    }

    public function testCanBeUnlinked(): void
    {
        $this->expectException(ilBuddySystemRelationStateException::class);
        $this->relation->unlink();
    }

    public function testCanBeLinked(): void
    {
        $this->expectException(ilBuddySystemRelationStateException::class);
        $this->relation->link();
    }

    public function testCanBeRequested(): void
    {
        $this->relation->request();
        $this->assertTrue($this->relation->isRequested());
        $this->assertTrue($this->relation->wasUnlinked());
    }

    public function testCanBeIgnored(): void
    {
        $this->expectException(ilBuddySystemRelationStateException::class);
        $this->relation->ignore();
    }

    public function testPossibleTargetStates(): void
    {
        $this->assertTrue(
            $this->relation->getState()
                ->getPossibleTargetStates()
                ->equals(new ilBuddySystemRelationStateCollection([
                    new ilBuddySystemRequestedRelationState(),
                ]))
        );
    }
}
