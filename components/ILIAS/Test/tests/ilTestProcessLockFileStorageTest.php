<?php

class ilTestProcessLockFileStorageTest extends ilTestBaseTestCase
{
    public function testConstruct(): void
    {
        $ilTestProcessLockFileStorage = new ilTestProcessLockFileStorage(0);
        $this->assertInstanceOf(ilTestProcessLockFileStorage::class, $ilTestProcessLockFileStorage);
    }

    public function testGetPathPrefix(): void
    {
        $ilTestProcessLockFileStorage = new ilTestProcessLockFileStorage(0);
        $this->assertEquals('ilTestProcessLocks', self::callMethod($ilTestProcessLockFileStorage, 'getPathPrefix'));
    }

    public function testGetPathPostfix(): void
    {
        $ilTestProcessLockFileStorage = new ilTestProcessLockFileStorage(0);
        $this->assertEquals('context', self::callMethod($ilTestProcessLockFileStorage, 'getPathPostfix'));
    }

    public function testCreate(): void
    {
        $this->markTestSkipped();
    }
}