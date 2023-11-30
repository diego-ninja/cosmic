<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Tests\Unit\Terminal\Table;

use Ninja\Cosmic\Terminal\Table\Table;
use PHPUnit\Framework\TestCase;

final class TableTest extends TestCase
{
    private Table $table;

    protected function setUp(): void
    {
        $this->table = new Table();
    }

    public function testGetUseColors(): void
    {
        $this->assertTrue($this->table->getUseColors());
    }

    public function testGetCenterContent(): void
    {
        $this->assertFalse($this->table->getCenterContent());
    }

    public function testGetShowHeaders(): void
    {
        $this->assertTrue($this->table->getShowHeaders());
    }

    public function testGetItemName(): void
    {
        $this->assertSame('Row', $this->table->getItemName());
    }

    public function testGetTableColor(): void
    {
        $this->assertSame('reset', $this->table->getTableColor());
    }

    public function testGetHeaderColor(): void
    {
        $this->assertSame('reset', $this->table->getHeaderColor());
    }

    public function testAddField(): void
    {
        $this->table->addField('Test Field', 'test', null, 'blue');
        $fields = $this->table->getFields();
        $this->assertArrayHasKey('test', $fields);
        $this->assertSame('Test Field', $fields['test']['name']);
        $this->assertSame('blue', $fields['test']['color']);
    }

    public function testInjectData(): void
    {
        $data = [
            ['test' => 'value1'],
            ['test' => 'value2'],
        ];
        $this->table->injectData($data);
        $this->assertSame($data, $this->table->getInjectedData());
    }

    public function testGet(): void
    {
        $this->table->addField('Test Field', 'test', null, 'blue');
        $this->table->injectData([
            ['test' => 'value1'],
            ['test' => 'value2'],
        ]);

        $output = $this->table->get();

        $this->assertStringContainsString('Test Field', $output);
        $this->assertStringContainsString('value1', $output);
        $this->assertStringContainsString('value2', $output);
    }

    public function testDisplay(): void
    {
        $this->table->addField('Test Field', 'test', null, 'blue');
        $this->table->injectData([
            ['test' => 'value1'],
            ['test' => 'value2'],
        ]);

        ob_start();
        $this->table->display();
        $output = ob_get_clean();

        $this->assertStringContainsString('Test Field', $output);
        $this->assertStringContainsString('value1', $output);
        $this->assertStringContainsString('value2', $output);
    }
}
