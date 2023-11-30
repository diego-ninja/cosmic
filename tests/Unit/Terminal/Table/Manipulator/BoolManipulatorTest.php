<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Tests\Unit\Terminal\Table\Manipulator;

use Ninja\Cosmic\Terminal\Table\Manipulator\BoolManipulator;
use PHPUnit\Framework\TestCase;

final class BoolManipulatorTest extends TestCase
{
    private BoolManipulator $boolManipulator;

    protected function setUp(): void
    {
        $this->boolManipulator = new BoolManipulator();
    }

    public function testManipulate(): void
    {
        $this->assertSame('true', $this->boolManipulator->manipulate(true));
        $this->assertSame('false', $this->boolManipulator->manipulate(false));
    }

    public function testManipulateNonBoolean(): void
    {
        $this->assertSame('1', $this->boolManipulator->manipulate('1'));
        $this->assertSame('0', $this->boolManipulator->manipulate('0'));
        $this->assertSame('test', $this->boolManipulator->manipulate('test'));
    }
}
