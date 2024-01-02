<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\UI\Element;

use Ninja\Cosmic\Terminal\Terminal;
use Ninja\Cosmic\Terminal\UI\Table\Column\TableColumn;
use Ninja\Cosmic\Terminal\UI\Table\TableConfig;

readonly class Table extends AbstractElement
{
    /**
     * @param string[]|array<string, string> $header
     * @param array<array<string, string>> $data
     */
    public function __invoke(array $header, array $data): void
    {
        $headers = $this->normalizeHeaders($header);
        $data    = $this->normalizeData($data, $headers);

        $table = new \Ninja\Cosmic\Terminal\UI\Table\Table(
            data: $data,
            columns: [],
            config: new TableConfig(Terminal::getTheme()->getConfig("table"))
        );

        foreach ($headers as $key => $value) {
            $table->addColumn(new TableColumn(name: $value, key: $key));
        }

        $this->output->writeln($table->render());

        Terminal::clear(1);
    }

    /**
     * @param string[]|array<string, string> $headers
     * @return array<string, string>
     */
    private function normalizeHeaders(array $headers): array
    {
        if (array_is_list($headers)) {
            return array_combine($headers, array_map(static fn($header): string => ucfirst((string)$header), $headers));
        }

        return $headers;
    }

    /**
     * @param array<array<int|string, string>> $data
     * @param string[]|array<string, string> $headers
     * @return array<array<string, string>>
     */
    private function normalizeData(array $data, array $headers): array
    {
        $normalized = [];
        foreach ($data as $value) {
            $normalized[] = array_is_list($value) ? array_combine(array_keys($headers), array_values($value)) : $value;
        }

        return $normalized;
    }
}
