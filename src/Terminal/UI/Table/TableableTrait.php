<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\UI\Table;

use Ninja\Cosmic\Exception\MissingInterfaceException;
use Ninja\Cosmic\Exception\UnexpectedValueException;
use Ninja\Cosmic\Serializer\SerializableInterface;
use Ninja\Cosmic\Terminal\Terminal;
use Ninja\Cosmic\Terminal\UI\Table\Column\TableColumn;
use Ramsey\Collection\AbstractCollection;

use function Cosmic\is_nullable;
use function Cosmic\snakeize;

trait TableableTrait
{
    /**
     * @throws MissingInterfaceException
     * @throws UnexpectedValueException
     */
    public function getTableData(): array
    {
        if (!is_subclass_of($this, SerializableInterface::class)) {
            throw MissingInterfaceException::withInterface(SerializableInterface::class, static::class);
        }

        if (!is_subclass_of($this, TableableInterface::class)) {
            throw MissingInterfaceException::withInterface(TableableInterface::class, static::class);
        }

        $nonNullableNormal   = [];
        $nonNullableBooleans = [];
        $nonNullableDates    = [];
        $nullableNormal      = [];
        $nullableBooleans    = [];
        $nullableDates       = [];
        $arraysOrCollections = [];
        $raw                 = $this->toArray();

        foreach ($raw as $key => $value) {
            $isCollection = false;
            $icon         = is_nullable($key, get_class($this)) ?
                Terminal::getTheme()->getIcon("nullable") :
                Terminal::getTheme()->getIcon("mandatory");

            if (is_array($value) || $value instanceof AbstractCollection) {
                $icon         = Terminal::getTheme()->getIcon("collection");
                $isCollection = true;
            }

            $formattedValue = [
                "key" => $isCollection ?
                    sprintf("%s %s [%d] ", $icon, snakeize($key), count($value)) :
                    sprintf("%s %s ", $icon, snakeize($key)),
                "value" => $this->extractValue($value),
            ];

            if (is_array($value) || $value instanceof AbstractCollection) {
                $arraysOrCollections[$key] = $formattedValue;
            } elseif (str_ends_with($key, 'At')) {
                if (is_nullable($key, get_class($this))) {
                    $nullableDates[$key] = $formattedValue;
                } else {
                    $nonNullableDates[$key] = $formattedValue;
                }
            } elseif (str_starts_with($key, 'is')) {
                if (is_nullable($key, get_class($this))) {
                    $nullableBooleans[$key] = $formattedValue;
                } else {
                    $nonNullableBooleans[$key] = $formattedValue;
                }
            } elseif (is_nullable($key, get_class($this))) {
                $nullableNormal[$key] = $formattedValue;
            } else {
                $nonNullableNormal[$key] = $formattedValue;
            }
        }

        ksort($nonNullableNormal);
        ksort($nonNullableBooleans);
        ksort($nonNullableDates);
        ksort($nullableNormal);
        ksort($nullableBooleans);
        ksort($nullableDates);
        ksort($arraysOrCollections);

        return array_merge($nonNullableNormal, $nonNullableBooleans, $nonNullableDates, $nullableNormal, $nullableBooleans, $nullableDates, $arraysOrCollections);
    }

    /**
     * @throws MissingInterfaceException
     * @throws UnexpectedValueException
     */
    public function asTable(): Table
    {
        $config = new TableConfig();
        $config->setShowHeader(false);
        $config->setPadding(1);

        return (new Table(data: $this->getTableData(), columns: [], config: $config, title: $this->getTableTitle()))
            ->addColumn(new TableColumn(name: '', key: 'key', color: 'notice'))
            ->addColumn((new TableColumn(name: '', key: 'value')));
    }

    public function getTableTitle(): ?string
    {
        return null;
    }
    private function extractValue(mixed $value): string
    {
        if (is_bool($value)) {
            return $value ? 'Yes' : 'No';
        }

        if (is_array($value)) {
            return implode(', ', $value);
        }

        return $value ? (string)$value : '';
    }

}
