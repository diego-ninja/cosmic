<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\UI\Element;

use Ninja\Cosmic\Terminal\Table\Column\TableColumn;
use Ninja\Cosmic\Terminal\Table\TableConfig;
use Ninja\Cosmic\Terminal\Terminal;

readonly class Summary extends AbstractElement
{
    public function __invoke(array $data, int $width, ?string $title = null): void
    {
        $headers = $this->normalizeHeaders(array_keys($data[0]));
        $data    = $this->normalizeData($data, $headers);

        $table = new \Ninja\Cosmic\Terminal\Table\Table(
            data: $data,
            columns: [],
            config: new TableConfig(Terminal::getTheme()->getConfig("summary")),
            title: $title
        );

        foreach ($headers as $key => $value) {
            $table->addColumn(new TableColumn(name: $value, key: $key));
        }

        $this->output->writeln($table->render());
    }

    private function normalizeHeaders(array $headers): array
    {
        if (array_is_list($headers)) {
            return array_combine($headers, array_map(static fn($header) => ucfirst($header), $headers));
        }

        return $headers;
    }

    private function normalizeData(array $data, array $headers): array
    {
        $normalized = [];
        foreach ($data as $key => $value) {
            if (array_is_list($value)) {
                $normalized[] = array_combine(array_keys($headers), array_values($value));
            } else {
                $normalized[] = $value;
            }
        }

        return array_map(static function ($normalized_value) {
            $normalized_value["key"] = sprintf(
                "%s %s",
                Terminal::getTheme()->getIcon("bullet"),
                $normalized_value["key"]
            );
            return $normalized_value;
        }, $normalized);
    }

}
