<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Terminal\Theme\Element;

use Ninja\Cosmic\Terminal\Theme\Element\Charset\Charset;
use Ninja\Cosmic\Terminal\Theme\Element\Color\Color;
use Ninja\Cosmic\Terminal\Theme\Element\Icon\Icon;
use Ninja\Cosmic\Terminal\Theme\Element\Spinner\Spinner;
use Ninja\Cosmic\Terminal\Theme\Element\Style\AbstractStyle;
use RuntimeException;
use JsonException;
use Ninja\Cosmic\Terminal\Theme\Element\Charset\CharsetCollection;
use Ninja\Cosmic\Terminal\Theme\Element\Color\ColorCollection;
use Ninja\Cosmic\Terminal\Theme\Element\Icon\IconCollection;
use Ninja\Cosmic\Terminal\Theme\Element\Spinner\SpinnerCollection;
use Ninja\Cosmic\Terminal\Theme\Element\Style\StyleCollection;
use Ninja\Cosmic\Terminal\Theme\Theme;

class CollectionFactory
{
    /**
     * @throws JsonException
     * @return AbstractElementCollection<Charset>|AbstractElementCollection<Color>|AbstractElementCollection<AbstractStyle>|AbstractElementCollection<Icon>|AbstractElementCollection<Spinner>
     */
    public static function loadFile(string $file): AbstractElementCollection
    {
        $content = file_get_contents($file);
        if ($content === false) {
            throw new RuntimeException(sprintf('Unable to load file: %s', $file));
        }

        $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        $type = array_keys($data)[0];

        return match ($type) {
            Theme::THEME_SECTION_CHARSETS => CharsetCollection::fromFile($file),
            Theme::THEME_SECTION_COLORS   => ColorCollection::fromFile($file),
            Theme::THEME_SECTION_STYLES   => StyleCollection::fromFile($file),
            Theme::THEME_SECTION_ICONS    => IconCollection::fromFile($file),
            Theme::THEME_SECTION_SPINNERS => SpinnerCollection::fromFile($file),
            default                       => throw new RuntimeException(sprintf('Unknown collection type: %s', $type)),
        };
    }
}
