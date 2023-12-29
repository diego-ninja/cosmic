<?php

declare(strict_types=1);

namespace Ninja\Cosmic\Parser;

class MarkdownParser
{
    private static array $instances = [];

    protected bool $breaks_enabled         = true;
    protected bool $markup_escaped         = false;
    protected bool $urls_linked            = true;
    protected bool $safe_mode              = false;
    protected string $inline_marker_list   = '!"*_&[:<>`~\\';
    protected string $regex_html_attribute = '[a-zA-Z_:][\w:.-]*(?:\s*=\s*(?:[^"\'=<>`\s]+|"[^"]*"|\'[^\']*\'))?';
    protected array $definition_data;

    protected array $safe_links_whitelist = [
        'http://',
        'https://',
        'ftp://',
        'ftps://',
        'mailto:',
        'data:image/png;base64,',
        'data:image/gif;base64,',
        'data:image/jpeg;base64,',
        'irc:',
        'ircs:',
        'git:',
        'ssh:',
        'news:',
        'steam:',
    ];

    protected array $block_types = [
        '#' => ['Header'],
        '*' => ['Rule', 'List'],
        '+' => ['List'],
        '-' => ['SetextHeader', 'Table', 'Rule', 'List'],
        '0' => ['List'],
        '1' => ['List'],
        '2' => ['List'],
        '3' => ['List'],
        '4' => ['List'],
        '5' => ['List'],
        '6' => ['List'],
        '7' => ['List'],
        '8' => ['List'],
        '9' => ['List'],
        ':' => ['Table'],
        '<' => ['Comment', 'Markup'],
        '=' => ['SetextHeader'],
        '>' => ['Quote'],
        '[' => ['Reference'],
        '_' => ['Rule'],
        '`' => ['FencedCode'],
        '|' => ['Table'],
        '~' => ['FencedCode'],
    ];

    protected array $unmarked_block_types = [
        'Code',
    ];

    protected array $inline_types = [
        '"'  => ['SpecialCharacter'],
        '!'  => ['Image'],
        '&'  => ['SpecialCharacter'],
        '*'  => ['Emphasis'],
        ':'  => ['Url'],
        '<'  => ['UrlTag', 'EmailTag', 'Markup', 'SpecialCharacter'],
        '>'  => ['SpecialCharacter'],
        '['  => ['Link'],
        '_'  => ['Emphasis'],
        '`'  => ['Code'],
        '~'  => ['Strikethrough'],
        '\\' => ['EscapeSequence'],
    ];

    protected array $special_characters = [
        '\\', '`', '*', '_', '{', '}', '[', ']', '(', ')', '>', '#', '+', '-', '.', '!', '|',
    ];

    protected array $strong_regex = [
        '*' => '/^[*]{2}((?:\\\\\*|[^*]|[*][^*]*[*])+?)[*]{2}(?![*])/s',
        '_' => '/^__((?:\\\\_|[^_]|_[^_]*_)+?)__(?!_)/us',
    ];

    protected array $em_regex = [
        '*' => '/^[*]((?:\\\\\*|[^*]|[*][*][^*]+?[*][*])+?)[*](?![*])/s',
        '_' => '/^_((?:\\\\_|[^_]|__[^_]*__)+?)_(?!_)\b/us',
    ];

    protected array $void_elements = [
        'area', 'base', 'br', 'col', 'command', 'embed', 'hr', 'img', 'input', 'link', 'meta', 'param', 'source',
    ];

    protected array $text_level_elements = [
        'a', 'br', 'bdo', 'abbr', 'blink', 'nextid', 'acronym', 'basefont',
        'b', 'em', 'big', 'cite', 'small', 'spacer', 'listing',
        'i', 'rp', 'del', 'code',          'strike', 'marquee',
        'q', 'rt', 'ins', 'font',          'strong',
        's', 'tt', 'kbd', 'mark',
        'u', 'xm', 'sub', 'nobr',
        'sup', 'ruby',
        'var', 'span',
        'wbr', 'time',
    ];

    public function text(string $text): string
    {
        $this->definition_data = [];
        $text                  = str_replace(["\r\n", "\r"], "\n", $text);
        $text                  = trim($text, "\n");
        $lines                 = explode("\n", $text);
        $markup                = $this->lines($lines);

        return trim($markup, "\n");
    }

    public function line(string $text, array $non_nestables = []): string
    {
        $markup = '';

        // $excerpt is based on the first occurrence of a marker

        while ($excerpt = strpbrk($text, $this->inline_marker_list)) {
            $marker = $excerpt[0];

            $marker_position = strpos($text, $marker);

            $new_excerpt = ['text' => $excerpt, 'context' => $text];

            foreach ($this->inline_types[$marker] as $inline_type) {
                // check to see if the current inline type is nestable in the current context

                if ($non_nestables !== [] && in_array($inline_type, $non_nestables, true)) {
                    continue;
                }

                $new_inline = $this->{'inline' . $inline_type}($new_excerpt);

                if (!isset($new_inline)) {
                    continue;
                }

                // makes sure that the inline belongs to "our" marker

                if (isset($new_inline['position']) && $new_inline['position'] > $marker_position) {
                    continue;
                }

                // sets a default inline position

                if (!isset($new_inline['position'])) {
                    $new_inline['position'] = $marker_position;
                }

                // cause the new element to 'inherit' our non nestables

                foreach ($non_nestables as $non_nestable) {
                    $new_inline['element']['non_nestables'][] = $non_nestable;
                }

                // the text that comes before the inline
                $unmarked_text = substr($text, 0, $new_inline['position']);

                // compile the unmarked text
                $markup .= $this->unmarkedText($unmarked_text);

                // compile the inline
                $markup .= $new_inline['markup'] ?? $this->element($new_inline['element']);

                // remove the examined text
                $text = substr($text, $new_inline['position'] + $new_inline['extent']);

                continue 2;
            }

            // the marker does not belong to an inline

            $unmarked_text = substr($text, 0, $marker_position + 1);

            $markup .= $this->unmarkedText($unmarked_text);

            $text = substr($text, $marker_position + 1);
        }

        return $markup . $this->unmarkedText($text);
    }

    public function setBreaksEnabled(bool $breaks_enabled): static
    {
        $this->breaks_enabled = $breaks_enabled;
        return $this;
    }

    public function setMarkupEscaped(bool $markup_escaped): static
    {
        $this->markup_escaped = $markup_escaped;
        return $this;
    }

    public function setUrlsLinked(bool $urls_linked): static
    {
        $this->urls_linked = $urls_linked;
        return $this;
    }

    public function setSafeMode(bool $safe_mode): static
    {
        $this->safe_mode = $safe_mode;
        return $this;
    }

    protected function lines(array $lines): string
    {
        $current_block = null;

        foreach ($lines as $line) {
            if (rtrim((string) $line) === '') {
                if (isset($current_block)) {
                    $current_block['interrupted'] = true;
                }

                continue;
            }

            if (str_contains((string) $line, "\t")) {
                $parts = explode("\t", (string) $line);

                $line = $parts[0];

                unset($parts[0]);

                foreach ($parts as $part) {
                    $shortage = 4 - mb_strlen($line, 'utf-8') % 4;

                    $line .= str_repeat(' ', $shortage);
                    $line .= $part;
                }
            }

            $indent = 0;

            while (isset($line[$indent]) && $line[$indent] === ' ') {
                $indent++;
            }

            $text = $indent > 0 ? substr((string) $line, $indent) : $line;

            $new_line = ['body' => $line, 'indent' => $indent, 'text' => $text];

            if (isset($current_block['continuable'])) {
                $block = $this->{'block' . $current_block['type'] . 'Continue'}($new_line, $current_block);

                if (isset($block)) {
                    $current_block = $block;
                    continue;
                }

                if ($this->isBlockCompletable($current_block['type'])) {
                    $current_block = $this->{'block' . $current_block['type'] . 'Complete'}($current_block);
                }
            }

            $marker = $text[0];

            $block_types = $this->unmarked_block_types;

            if (isset($this->block_types[$marker])) {
                foreach ($this->block_types[$marker] as $block_type) {
                    $block_types[] = $block_type;
                }
            }

            foreach ($block_types as $block_type) {
                $block = $this->{'block' . $block_type}($new_line, $current_block);

                if (isset($block)) {
                    $block['type'] = $block_type;

                    if (!isset($block['identified'])) {
                        $blocks[] = $current_block;

                        $block['identified'] = true;
                    }

                    if ($this->isBlockContinuable($block_type)) {
                        $block['continuable'] = true;
                    }

                    $current_block = $block;

                    continue 2;
                }
            }

            if (isset($current_block) && !isset($current_block['type']) && !isset($current_block['interrupted'])) {
                $current_block['element']['text'] .= "\n" . $text;
            } else {
                $blocks[] = $current_block;

                $current_block = $this->paragraph($new_line);

                $current_block['identified'] = true;
            }
        }

        if (isset($current_block['continuable']) && $this->isBlockCompletable($current_block['type'])) {
            $current_block = $this->{'block' . $current_block['type'] . 'Complete'}($current_block);
        }

        $blocks[] = $current_block;

        unset($blocks[0]);

        $markup = '';

        foreach ($blocks as $block) {
            if (isset($block['hidden'])) {
                continue;
            }

            $markup .= "\n";
            $markup .= $block['markup'] ?? $this->element($block['element']);
        }

        // ~

        return $markup . "\n";
    }

    protected function isBlockContinuable(string $type): bool
    {
        return method_exists($this, 'block' . $type . 'Continue');
    }

    protected function isBlockCompletable(string $type): bool
    {
        return method_exists($this, 'block' . $type . 'Complete');
    }

    protected function blockCode(array $line, ?array $block = null): ?array
    {
        if (isset($block) && !isset($block['type']) && !isset($block['interrupted'])) {
            return null;
        }

        if ($line['indent'] >= 4) {
            $text = substr((string) $line['body'], 4);

            return [
                'element' => [
                    'name'    => 'pre',
                    'handler' => 'element',
                    'text'    => [
                        'name' => 'code',
                        'text' => $text,
                    ],
                ],
            ];
        }

        return null;
    }

    protected function blockCodeContinue(array $line, array $block): ?array
    {
        if ($line['indent'] >= 4) {
            if (isset($block['interrupted'])) {
                $block['element']['text']['text'] .= "\n";

                unset($block['interrupted']);
            }

            $block['element']['text']['text'] .= "\n";
            $text = substr((string) $line['body'], 4);
            $block['element']['text']['text'] .= $text;

            return $block;
        }

        return null;
    }

    protected function blockCodeComplete(array $block): array
    {
        $text                             = $block['element']['text']['text'];
        $block['element']['text']['text'] = $text;

        return $block;
    }

    protected function blockComment(array $line): ?array
    {
        if ($this->markup_escaped || $this->safe_mode) {
            return null;
        }

        if (isset($line['text'][3]) && $line['text'][3] === '-' && $line['text'][2] === '-' && $line['text'][1] === '!') { //phpcs:ignore
            $block = [
                'markup' => $line['body'],
            ];

            if (str_ends_with((string) $line['text'], '-->')) {
                $block['closed'] = true;
            }

            return $block;
        }

        return null;
    }

    protected function blockCommentContinue(array $line, array $block): ?array
    {
        if (isset($block['closed'])) {
            return null;
        }

        $block['markup'] .= "\n" . $line['body'];

        if (str_ends_with((string) $line['text'], '-->')) {
            $block['closed'] = true;
        }

        return $block;
    }

    protected function blockFencedCode(array $line): ?array
    {
        if (preg_match('/^[' . $line['text'][0] . ']{3,} *([^`]+)? *$/', (string) $line['text'], $matches)) {
            $element = [
                'name'     => 'pre',
                'text'     => '',
                'disabled' => true,
            ];

            if (isset($matches[1])) {
                $language = substr($matches[1], 0, strcspn($matches[1], " \t\n\f\r"));

                $class = 'language-' . $language;

                $element['attributes'] = [
                    'class' => $class,
                ];
            }

            return [
                'char'    => $line['text'][0],
                'element' => [
                    'name'       => 'code',
                    'handler'    => 'element',
                    'attributes' => [
                        'class' => $class ?? "language",
                    ],
                    'text' => $element,
                ],
            ];
        }

        return null;
    }

    protected function blockFencedCodeContinue(array $line, array $block): ?array
    {
        if (isset($block['complete'])) {
            return null;
        }

        if (isset($block['interrupted'])) {
            $block['element']['text']['text'] .= "\n";

            unset($block['interrupted']);
        }

        if (preg_match('/^' . $block['char'] . '{3,} *$/', (string) $line['text'])) {
            $block['element']['text']['text'] = substr((string) $block['element']['text']['text'], 1);

            $block['complete'] = true;

            return $block;
        }

        $block['element']['text']['text'] .= "\n" . $line['body'];

        return $block;
    }

    protected function blockFencedCodeComplete(array $block): array
    {
        $text                             = $block['element']['text']['text'];
        $block['element']['text']['text'] = $text;

        return $block;
    }

    protected function blockHeader(array $line): ?array
    {
        if (isset($line['text'][1])) {
            $level = 1;

            while (isset($line['text'][$level]) && $line['text'][$level] === '#') {
                $level++;
            }

            if ($level > 6) {
                return null;
            }

            $text = trim((string) $line['text'], '# ');

            return [
                'element' => [
                    'name'       => 'h' . min(6, $level),
                    'attributes' => [
                        'class' => $level === 1 ? 'h1' : 'heading',
                    ],
                    'text'    => $text,
                    'handler' => 'line',
                ],
            ];
        }

        return null;
    }

    protected function blockList(array $line): ?array
    {
        [$name, $pattern] = $line['text'][0] <= '-' ? ['ul', '[*+-]'] : ['ol', '[0-9]+[.]'];

        if (preg_match('/^(' . $pattern . '[ ]+)(.*)/', (string) $line['text'], $matches)) {
            $block = [
                'indent'  => $line['indent'],
                'pattern' => $pattern,
                'element' => [
                    'name'    => $name,
                    'handler' => 'elements',
                ],
            ];

            if ($name === 'ol') {
                $list_start = strstr($matches[0], '.', true);

                if ($list_start !== '1') {
                    $block['element']['attributes'] = ['start' => $list_start];
                }
            }

            $block['li'] = [
                'name'    => 'li',
                'handler' => 'li',
                'text'    => [
                    $matches[2],
                ],
            ];

            $block['element']['text'][] = &$block['li'];

            return $block;
        }

        return null;
    }

    protected function blockListContinue(array $line, array $block): ?array
    {
        if ($block['indent'] === $line['indent'] && preg_match('/^' . $block['pattern'] . '(?:[ ]+(.*)|$)/', (string) $line['text'], $matches)) { //phpcs:ignore
            if (isset($block['interrupted'])) {
                $block['li']['text'][] = '';

                $block['loose'] = true;

                unset($block['interrupted']);
            }

            unset($block['li']);

            $text = $matches[1] ?? '';

            $block['li'] = [
                'name'    => 'li',
                'handler' => 'li',
                'text'    => [
                    $text,
                ],
            ];

            $block['element']['text'][] = &$block['li'];

            return $block;
        }

        if ($line['text'][0] === '[' && $this->blockReference($line)) {
            return $block;
        }

        if (!isset($block['interrupted'])) {
            $text = preg_replace('/^[ ]{0,4}/', '', (string) $line['body']);

            $block['li']['text'][] = $text;

            return $block;
        }

        if ($line['indent'] > 0) {
            $block['li']['text'][] = '';

            $text = preg_replace('/^[ ]{0,4}/', '', (string) $line['body']);

            $block['li']['text'][] = $text;

            unset($block['interrupted']);

            return $block;
        }

        return null;
    }

    protected function blockListComplete(array $block): array
    {
        if (isset($block['loose'])) {
            foreach ($block['element']['text'] as &$li) {
                if (end($li['text']) !== '') {
                    $li['text'][] = '';
                }
            }
        }

        return $block;
    }

    protected function blockQuote(array $line): ?array
    {
        if (preg_match('/^>[ ]?(.*)/', (string) $line['text'], $matches)) {
            return [
                'element' => [
                    'name'    => 'blockquote',
                    'handler' => 'lines',
                    'text'    => (array)$matches[1],
                ],
            ];
        }

        return null;
    }

    protected function blockQuoteContinue(array $line, array $block): ?array
    {
        if ($line['text'][0] === '>' && preg_match('/^>[ ]?(.*)/', (string) $line['text'], $matches)) {
            if (isset($block['interrupted'])) {
                $block['element']['text'][] = '';

                unset($block['interrupted']);
            }

            $block['element']['text'][] = $matches[1];

            return $block;
        }

        if (!isset($block['interrupted'])) {
            $block['element']['text'][] = $line['text'];

            return $block;
        }

        return null;
    }

    protected function blockRule(array $line): ?array
    {
        if (preg_match('/^([' . $line['text'][0] . '])([ ]*\1){2,}[ ]*$/', (string) $line['text'])) {
            return [
                'element' => [
                    'name'       => 'hr',
                    'attributes' => [
                        'class' => 'hr',
                    ],
                ],
            ];
        }

        return null;
    }

    protected function blockSetextHeader(array $line, array $block = null): ?array
    {
        if (!isset($block) || isset($block['type']) || isset($block['interrupted'])) {
            return null;
        }

        if (rtrim((string) $line['text'], $line['text'][0]) === '') {
            $block['element']['name'] = $line['text'][0] === '=' ? 'h1' : 'h2';

            return $block;
        }

        return null;
    }

    protected function blockMarkup(array $line): ?array
    {
        if ($this->markup_escaped || $this->safe_mode) {
            return null;
        }

        if (preg_match('/^<(\w[\w-]*)(?:[ ]*' . $this->regex_html_attribute . ')*[ ]*(\/)?>/', (string) $line['text'], $matches)) { //phpcs:ignore
            $element = strtolower($matches[1]);

            if (in_array($element, $this->text_level_elements, true)) {
                return null;
            }

            $block = [
                'name'   => $matches[1],
                'depth'  => 0,
                'markup' => $line['text'],
            ];

            $length = strlen($matches[0]);

            $remainder = substr((string) $line['text'], $length);

            if (trim($remainder) === '') {
                if (isset($matches[2]) || in_array($matches[1], $this->void_elements, true)) {
                    $block['closed'] = true;

                    $block['void'] = true;
                }
            } else {
                if (isset($matches[2]) || in_array($matches[1], $this->void_elements, true)) {
                    return null;
                }

                if (preg_match('/<\/' . $matches[1] . '>[ ]*$/i', $remainder)) {
                    $block['closed'] = true;
                }
            }

            return $block;
        }

        return null;
    }

    protected function blockMarkupContinue(array $line, array $block): ?array
    {
        if (isset($block['closed'])) {
            return null;
        }

        if (preg_match('/^<' . $block['name'] . '(?:[ ]*' . $this->regex_html_attribute . ')*[ ]*>/i', (string) $line['text'])) { //phpcs:ignore
            $block['depth']++;
        }

        if (preg_match('/(.*?)<\/' . $block['name'] . '>[ ]*$/i', (string) $line['text'], $matches)) { // close
            if ($block['depth'] > 0) {
                $block['depth']--;
            } else {
                $block['closed'] = true;
            }
        }

        if (isset($block['interrupted'])) {
            $block['markup'] .= "\n";

            unset($block['interrupted']);
        }

        $block['markup'] .= "\n" . $line['body'];

        return $block;
    }

    protected function blockReference(array $line): ?array
    {
        if (preg_match('/^\[(.+?)\]:[ ]*<?(\S+?)>?(?:[ ]+["\'(](.+)["\')])?[ ]*$/', (string) $line['text'], $matches)) {
            $id = strtolower($matches[1]);

            $data = [
                'url'   => $matches[2],
                'title' => null,
            ];

            if (isset($matches[3])) {
                $data['title'] = $matches[3];
            }

            $this->definition_data['Reference'][$id] = $data;

            return [
                'hidden' => true,
            ];
        }

        return null;
    }

    protected function blockTable(array $line, ?array $block = null): ?array
    {
        if (!isset($block) || isset($block['type']) || isset($block['interrupted'])) {
            return null;
        }

        if (str_contains((string) $block['element']['text'], '|') && rtrim((string) $line['text'], ' -:|') === '') {
            $alignments = [];

            $divider = $line['text'];

            $divider = trim((string) $divider);
            $divider = trim($divider, '|');

            $divider_cells = explode('|', $divider);

            foreach ($divider_cells as $divider_cell) {
                $divider_cell = trim($divider_cell);

                if ($divider_cell === '') {
                    continue;
                }

                $alignment = null;

                if ($divider_cell[0] === ':') {
                    $alignment = 'left';
                }

                if (str_ends_with($divider_cell, ':')) {
                    $alignment = $alignment === 'left' ? 'center' : 'right';
                }

                $alignments[] = $alignment;
            }

            $header_elements = [];

            $header = $block['element']['text'];

            $header = trim((string) $header);
            $header = trim($header, '|');

            $header_cells = explode('|', $header);

            foreach ($header_cells as $index => $header_cell) {
                $header_cell = trim($header_cell);

                $header_element = [
                    'name'    => 'th',
                    'text'    => $header_cell,
                    'handler' => 'line',
                ];

                if (isset($alignments[$index])) {
                    $alignment = $alignments[$index];

                    $header_element['attributes'] = [
                        'style' => 'text-align: ' . $alignment . ';',
                    ];
                }

                $header_elements[] = $header_element;
            }

            // ~

            $block = [
                'alignments' => $alignments,
                'identified' => true,
                'element'    => [
                    'name'    => 'table',
                    'handler' => 'elements',
                ],
            ];

            $block['element']['text'][] = [
                'name'    => 'thead',
                'handler' => 'elements',
            ];

            $block['element']['text'][] = [
                'name'    => 'tbody',
                'handler' => 'elements',
                'text'    => [],
            ];

            $block['element']['text'][0]['text'][] = [
                'name'    => 'tr',
                'handler' => 'elements',
                'text'    => $header_elements,
            ];

            return $block;
        }

        return null;
    }

    protected function blockTableContinue(array $line, array $block): ?array
    {
        if (isset($block['interrupted'])) {
            return null;
        }

        if ($line['text'][0] === '|' || strpos((string) $line['text'], '|')) {
            $elements = [];

            $row = $line['text'];

            $row = trim((string) $row);
            $row = trim($row, '|');

            preg_match_all('/(?:(\\\\[|])|[^|`]|`[^`]+`|`)+/', $row, $matches);

            foreach ($matches[0] as $index => $cell) {
                $cell = trim((string) $cell);

                $element = [
                    'name'    => 'td',
                    'handler' => 'line',
                    'text'    => $cell,
                ];

                if (isset($block['alignments'][$index])) {
                    $element['attributes'] = [
                        'style' => 'text-align: ' . $block['alignments'][$index] . ';',
                    ];
                }

                $elements[] = $element;
            }

            $element = [
                'name'    => 'tr',
                'handler' => 'elements',
                'text'    => $elements,
            ];

            $block['element']['text'][1]['text'][] = $element;

            return $block;
        }

        return null;
    }

    protected function paragraph(array $line): array
    {
        return [
            'element' => [
                'name'    => 'p',
                'text'    => $line['text'],
                'handler' => 'line',
            ],
        ];
    }

    protected function inlineCode(array $excerpt): ?array
    {
        $marker = $excerpt['text'][0];

        if (preg_match('/^(' . $marker . '+)[ ]*(.+?)[ ]*(?<!' . $marker . ')\1(?!' . $marker . ')/s', (string) $excerpt['text'], $matches)) { //phpcs:ignore
            $text = $matches[2];
            $text = preg_replace("/[ ]*\n/", ' ', $text);

            return [
                'extent'  => strlen($matches[0]),
                'element' => [
                    'name' => 'code',
                    'text' => $text,
                ],
            ];
        }

        return null;
    }

    protected function inlineEmailTag(array $excerpt): ?array
    {
        if (str_contains((string) $excerpt['text'], '>') && preg_match('/^<((mailto:)?\S+?@\S+?)>/i', (string) $excerpt['text'], $matches)) { //phpcs:ignore
            $url = $matches[1];

            if (!isset($matches[2])) {
                $url = 'mailto:' . $url;
            }

            return [
                'extent'  => strlen($matches[0]),
                'element' => [
                    'name'       => 'a',
                    'text'       => $matches[1],
                    'attributes' => [
                        'href' => $url,
                    ],
                ],
            ];
        }

        return null;
    }

    protected function inlineEmphasis(array $excerpt): ?array
    {
        if (!isset($excerpt['text'][1])) {
            return null;
        }

        $marker = $excerpt['text'][0];

        if ($excerpt['text'][1] === $marker && preg_match($this->strong_regex[$marker], (string) $excerpt['text'], $matches)) {
            $emphasis = 'strong';
        } elseif (preg_match($this->em_regex[$marker], (string) $excerpt['text'], $matches)) {
            $emphasis = 'em';
        } else {
            return null;
        }

        return [
            'extent'  => strlen($matches[0]),
            'element' => [
                'name'       => $emphasis,
                'handler'    => 'line',
                'attributes' => [
                    'class' => $emphasis === 'strong' ? 'strong' : 'emphasis',
                ],
                'text' => $matches[1],
            ],
        ];
    }

    protected function inlineEscapeSequence(array $excerpt): ?array
    {
        if (isset($excerpt['text'][1]) && in_array($excerpt['text'][1], $this->special_characters, true)) {
            return [
                'markup' => $excerpt['text'][1],
                'extent' => 2,
            ];
        }

        return null;
    }

    protected function inlineImage(array $excerpt): ?array
    {
        if (!isset($excerpt['text'][1]) || $excerpt['text'][1] !== '[') {
            return null;
        }

        $excerpt['text'] = substr((string) $excerpt['text'], 1);

        $link = $this->inlineLink($excerpt);

        if ($link === null) {
            return null;
        }

        $inline = [
            'extent'  => $link['extent'] + 1,
            'element' => [
                'name'       => 'img',
                'attributes' => [
                    'src' => $link['element']['attributes']['href'],
                    'alt' => $link['element']['text'],
                ],
            ],
        ];

        $inline['element']['attributes'] += $link['element']['attributes'];

        unset($inline['element']['attributes']['href']);

        return $inline;
    }

    protected function inlineLink(array $excerpt): ?array
    {
        $element = [
            'name'          => 'a',
            'handler'       => 'line',
            'non_nestables' => ['Url', 'Link'],
            'text'          => null,
            'attributes'    => [
                'href'  => null,
                'title' => null,
                'class' => 'link',
            ],
        ];

        $extent = 0;

        $remainder = $excerpt['text'];

        if (preg_match('/\[((?:[^][]++|(?R))*+)\]/', (string) $remainder, $matches)) {
            $element['text'] = $matches[1];

            $extent += strlen($matches[0]);

            $remainder = substr((string) $remainder, $extent);
        } else {
            return null;
        }

        if (preg_match('/^[(]\s*+((?:[^ ()]++|[(][^ )]+[)])++)(?:[ ]+("[^"]*"|\'[^\']*\'))?\s*[)]/', $remainder, $matches)) { //phpcs:ignore
            $element['attributes']['href'] = $matches[1];

            if (isset($matches[2])) {
                $element['attributes']['title'] = substr($matches[2], 1, -1);
            }

            $extent += strlen($matches[0]);
        } else {
            if (preg_match('/^\s*\[(.*?)\]/', $remainder, $matches)) {
                $definition = $matches[1] !== '' ? $matches[1] : $element['text'];
                $definition = strtolower($definition);

                $extent += strlen($matches[0]);
            } else {
                $definition = strtolower($element['text']);
            }

            if (!isset($this->definition_data['Reference'][$definition])) {
                return null;
            }

            $definition = $this->definition_data['Reference'][$definition];

            $element['attributes']['href']  = $definition['url'];
            $element['attributes']['title'] = $definition['title'];
        }

        return [
            'extent'  => $extent,
            'element' => $element,
        ];
    }

    protected function inlineMarkup(array $excerpt): ?array
    {
        if ($this->markup_escaped || $this->safe_mode || !str_contains((string) $excerpt['text'], '>')) {
            return null;
        }

        if ($excerpt['text'][1] === '/' && preg_match('/^<\/\w[\w-]*[ ]*>/s', (string) $excerpt['text'], $matches)) {
            return [
                'markup' => $matches[0],
                'extent' => strlen($matches[0]),
            ];
        }

        if ($excerpt['text'][1] === '!' && preg_match('/^<!---?[^>-](?:-?[^-])*-->/s', (string) $excerpt['text'], $matches)) { //phpcs:ignore
            return [
                'markup' => $matches[0],
                'extent' => strlen($matches[0]),
            ];
        }

        if ($excerpt['text'][1] !== ' ' && preg_match('/^<\w[\w-]*(?:[ ]*' . $this->regex_html_attribute . ')*[ ]*\/?>/s', (string) $excerpt['text'], $matches)) { //phpcs:ignore
            return [
                'markup' => $matches[0],
                'extent' => strlen($matches[0]),
            ];
        }

        return null;
    }

    protected function inlineSpecialCharacter(array $excerpt): ?array
    {
        if ($excerpt['text'][0] === '&' && !preg_match('/^&#?\w+;/', (string) $excerpt['text'])) {
            return [
                'markup' => '&amp;',
                'extent' => 1,
            ];
        }

        $special_character = ['>' => 'gt', '<' => 'lt', '"' => 'quot'];

        if (isset($special_character[$excerpt['text'][0]])) {
            return [
                'markup' => '&' . $special_character[$excerpt['text'][0]] . ';',
                'extent' => 1,
            ];
        }

        return null;
    }

    protected function inlineStrikethrough(array $excerpt): ?array
    {
        if (!isset($excerpt['text'][1])) {
            return null;
        }

        if ($excerpt['text'][1] === '~' && preg_match('/^~~(?=\S)(.+?)(?<=\S)~~/', (string) $excerpt['text'], $matches)) {
            return [
                'extent'  => strlen($matches[0]),
                'element' => [
                    'name'       => 's',
                    'text'       => $matches[1],
                    'handler'    => 'line',
                    'attributes' => [
                        'class' => 'strike',
                    ],
                ],
            ];
        }

        return null;
    }

    protected function inlineUrl(array $excerpt): ?array
    {
        if (!$this->urls_linked || !isset($excerpt['text'][2]) || $excerpt['text'][2] !== '/') {
            return null;
        }

        if (preg_match('/\bhttps?:[\/]{2}[^\s<]+\b\/*/ui', (string) $excerpt['context'], $matches, PREG_OFFSET_CAPTURE)) {
            $url = $matches[0][0];

            return [
                'extent'   => strlen($matches[0][0]),
                'position' => $matches[0][1],
                'element'  => [
                    'name'       => 'a',
                    'text'       => $url,
                    'attributes' => [
                        'href'  => $url,
                        'class' => 'link',
                    ],
                ],
            ];
        }

        return null;
    }

    protected function inlineUrlTag(array $excerpt): ?array
    {
        if (str_contains((string) $excerpt['text'], '>') && preg_match('/^<(\w+:\/{2}[^ >]+)>/i', (string) $excerpt['text'], $matches)) {
            $url = $matches[1];

            return [
                'extent'  => strlen($matches[0]),
                'element' => [
                    'name'       => 'a',
                    'text'       => $url,
                    'attributes' => [
                        'href'  => $url,
                        'class' => 'link',
                    ],
                ],
            ];
        }

        return null;
    }

    protected function unmarkedText(string $text): string
    {
        if ($this->breaks_enabled) {
            $text = preg_replace('/[ ]*\n/', "<br />\n", $text);
        } else {
            $text = preg_replace('/(?:[ ][ ]+|[ ]*\\\\)\n/', "<br />\n", $text);
            $text = str_replace(" \n", "\n", $text);
        }

        return $text;
    }

    protected function element(array $element): string
    {
        if (isset($element["disabled"])) {
            return $element["text"];
        }

        if ($this->safe_mode) {
            $element = $this->sanitiseElement($element);
        }

        $markup = '<' . $element['name'];

        if (isset($element['attributes'])) {
            foreach ($element['attributes'] as $name => $value) {
                if ($value === null) {
                    continue;
                }

                $markup .= ' ' . $name . '="' . self::escape($value) . '"';
            }
        }

        $permit_raw_html = false;

        if (isset($element['text'])) {
            $text = $element['text'];
        } elseif (isset($element['rawHtml'])) {
            $text                        = $element['rawHtml'];
            $allow_raw_html_in_safe_mode = isset($element['allow_raw_html_in_safe_mode']) && $element['allow_raw_html_in_safe_mode']; //phpcs:ignore
            $permit_raw_html             = !$this->safe_mode || $allow_raw_html_in_safe_mode;
        }

        if (isset($text)) {
            $markup .= '>';

            if (!isset($element['non_nestables'])) {
                $element['non_nestables'] = [];
            }

            if (isset($element['handler'])) {
                $markup .= $this->{$element['handler']}($text, $element['non_nestables']);
            } elseif (!$permit_raw_html) {
                $markup .= self::escape($text, true);
            } else {
                $markup .= $text;
            }

            $markup .= '</' . $element['name'] . '>';
        } else {
            $markup .= ' />';
        }

        return $markup;
    }

    protected function elements(array $elements): string
    {
        $markup = '';

        foreach ($elements as $element) {
            $markup .= "\n" . $this->element($element);
        }

        return $markup . "\n";
    }

    protected function li(array $lines): string
    {
        $markup = $this->lines($lines);

        $trimmed_markup = trim($markup);

        if (!in_array('', $lines, true) && str_starts_with($trimmed_markup, '<p>')) {
            $markup = $trimmed_markup;
            $markup = substr($markup, 3);

            $position = strpos($markup, "</p>");

            $markup = substr_replace($markup, '', $position, 4);
        }

        return $markup;
    }

    protected function sanitiseElement(array $element): array
    {
        static $good_attribute             = '/^[a-zA-Z0-9][a-zA-Z0-9-_]*+$/';
        static $safe_url_name_to_attribute = [
            'a'   => 'href',
            'img' => 'src',
        ];

        if (isset($safe_url_name_to_attribute[$element['name']])) {
            $element = $this->filterUnsafeUrlInAttribute($element, $safe_url_name_to_attribute[$element['name']]);
        }

        if (!empty($element['attributes'])) {
            foreach ($element['attributes'] as $att => $val) {
                if (!preg_match($good_attribute, (string) $att)) {
                    unset($element['attributes'][$att]);
                } elseif (self::striAtStart($att, 'on')) {
                    unset($element['attributes'][$att]);
                }
            }
        }

        return $element;
    }

    protected function filterUnsafeUrlInAttribute(array $element, string $attribute): array
    {
        foreach ($this->safe_links_whitelist as $scheme) {
            if (self::striAtStart($element['attributes'][$attribute], $scheme)) {
                return $element;
            }
        }

        $element['attributes'][$attribute] = str_replace(':', '%3A', (string) $element['attributes'][$attribute]);

        return $element;
    }

    protected static function escape(string $text, bool $allow_quotes = false): string
    {
        return htmlspecialchars($text, $allow_quotes ? ENT_NOQUOTES : ENT_QUOTES, 'UTF-8');
    }

    protected static function striAtStart(string $string, string $needle): bool
    {
        $len = strlen($needle);

        if ($len > strlen($string)) {
            return false;
        }

        return stripos($string, strtolower($needle)) === 0;
    }

    public static function instance($name = 'default'): self
    {
        if (isset(self::$instances[$name])) {
            return self::$instances[$name];
        }

        $instance = new self();

        self::$instances[$name] = $instance;

        return $instance;
    }
}
