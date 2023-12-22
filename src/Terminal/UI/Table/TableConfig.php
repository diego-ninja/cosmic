<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\UI\Table;

use Ninja\Cosmic\Terminal\Terminal;
use PHLAK\Config\Config;

class TableConfig extends Config
{
    public const DEFAULT_TABLE_COLOR    = "white";
    public const DEFAULT_HEADER_COLOR   = "white";
    public const DEFAULT_TITLE_COLOR    = "notice";
    public const DEFAULT_FIELD_COLOR    = "white";
    public const DEFAULT_SHOW_HEADER    = true;
    public const DEFAULT_ITEM_NAME      = "row";
    public const DEFAULT_PADDING        = 1;
    public const DEFAULT_CENTER_CONTENT = false;
    public const DEFAULT_CHARSET        = "double";

    public function __construct(array|string $context = null, string $prefix = null)
    {
        if (is_array($context)) {
            $context = array_merge($this->getDefaultConfig(), $context);
        }

        parent::__construct($context, $prefix);
    }

    public function getCharset(): array
    {
        return Terminal::getTheme()->getCharset($this->get("charset", self::DEFAULT_CHARSET))->chars;
    }

    public function getChar(string $char): ?string
    {
        return $this->getCharset()[$char];
    }

    public function hasChar(string $char): bool
    {
        return isset($this->getCharset()[$char]);
    }

    public function setCharset(array $charset): self
    {
        $this->set("charset", $charset);
        return $this;
    }

    public function getItemName(): string
    {
        return $this->get("item_name", self::DEFAULT_ITEM_NAME);
    }

    public function setItemName(string $itemName): self
    {
        $this->set("item_name", $itemName);
        return $this;
    }

    public function getTableColor(): string
    {
        return $this->get("table_color", self::DEFAULT_TABLE_COLOR);
    }

    public function setTableColor(string $tableColor): self
    {
        $this->set("table_color", $tableColor);
        return $this;
    }

    public function getHeaderColor(): string
    {
        return $this->get("header_color", self::DEFAULT_HEADER_COLOR);
    }

    public function setHeaderColor(string $headerColor): self
    {
        $this->set("header_color", $headerColor);
        return $this;
    }

    public function getTitleColor(): string
    {
        return $this->get("title_color", self::DEFAULT_TITLE_COLOR);
    }

    public function setTitleColor(string $titleColor): self
    {
        $this->set("title_color", $titleColor);
        return $this;
    }

    public function getFieldColor(): string
    {
        return $this->get("field_color", self::DEFAULT_FIELD_COLOR);
    }

    public function setFieldColor(string $fieldColor): self
    {
        $this->set("field_color", $fieldColor);
        return $this;
    }

    public function getShowHeader(): bool
    {
        return $this->get("show_header", self::DEFAULT_SHOW_HEADER);
    }

    public function setShowHeader(bool $showHeader): self
    {
        $this->set("show_header", $showHeader);
        return $this;
    }

    public function getPadding(): int
    {
        return $this->get("padding", self::DEFAULT_PADDING);
    }

    public function setPadding(int $padding): self
    {
        $this->set("padding", $padding);
        return $this;
    }

    public function getCenterContent(): bool
    {
        return $this->get("center_content", self::DEFAULT_CENTER_CONTENT);
    }

    public function setCenterContent(bool $centerContent): self
    {
        $this->set("center_content", $centerContent);
        return $this;
    }

    private function getDefaultConfig(): array
    {
        return [
            "charset"        => Terminal::getTheme()->getCharset("double")->name ?? self::DEFAULT_CHARSET,
            "item_name"      => self::DEFAULT_ITEM_NAME,
            "table_color"    => self::DEFAULT_TABLE_COLOR,
            "header_color"   => self::DEFAULT_HEADER_COLOR,
            "title_color"    => self::DEFAULT_TITLE_COLOR,
            "show_header"    => self::DEFAULT_SHOW_HEADER,
            "padding"        => self::DEFAULT_PADDING,
            "center_content" => self::DEFAULT_CENTER_CONTENT,
        ];
    }

}
