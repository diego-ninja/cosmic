***

# MarkdownParser





* Full name: `\Ninja\Cosmic\Parser\MarkdownParser`



## Properties


### instances



```php
private static array $instances
```



* This property is **static**.


***

### breaks_enabled



```php
protected bool $breaks_enabled
```






***

### markup_escaped



```php
protected bool $markup_escaped
```






***

### urls_linked



```php
protected bool $urls_linked
```






***

### safe_mode



```php
protected bool $safe_mode
```






***

### inline_marker_list



```php
protected string $inline_marker_list
```






***

### regex_html_attribute



```php
protected string $regex_html_attribute
```






***

### definition_data



```php
protected array $definition_data
```






***

### safe_links_whitelist



```php
protected array $safe_links_whitelist
```






***

### block_types



```php
protected array $block_types
```






***

### unmarked_block_types



```php
protected array $unmarked_block_types
```






***

### inline_types



```php
protected array $inline_types
```






***

### special_characters



```php
protected array $special_characters
```






***

### strong_regex



```php
protected array $strong_regex
```






***

### em_regex



```php
protected array $em_regex
```






***

### void_elements



```php
protected array $void_elements
```






***

### text_level_elements



```php
protected array $text_level_elements
```






***

## Methods


### text



```php
public text(string $text): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$text` | **string** |  |





***

### line



```php
public line(string $text, array $non_nestables = []): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$text` | **string** |  |
| `$non_nestables` | **array** |  |





***

### setBreaksEnabled



```php
public setBreaksEnabled(bool $breaks_enabled): static
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$breaks_enabled` | **bool** |  |





***

### setMarkupEscaped



```php
public setMarkupEscaped(bool $markup_escaped): static
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$markup_escaped` | **bool** |  |





***

### setUrlsLinked



```php
public setUrlsLinked(bool $urls_linked): static
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$urls_linked` | **bool** |  |





***

### setSafeMode



```php
public setSafeMode(bool $safe_mode): static
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$safe_mode` | **bool** |  |





***

### lines



```php
protected lines(array $lines): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$lines` | **array** |  |





***

### isBlockContinuable



```php
protected isBlockContinuable(string $type): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$type` | **string** |  |





***

### isBlockCompletable



```php
protected isBlockCompletable(string $type): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$type` | **string** |  |





***

### blockCode



```php
protected blockCode(array $line, ?array $block = null): ?array
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$line` | **array** |  |
| `$block` | **?array** |  |





***

### blockCodeContinue



```php
protected blockCodeContinue(array $line, array $block): ?array
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$line` | **array** |  |
| `$block` | **array** |  |





***

### blockCodeComplete



```php
protected blockCodeComplete(array $block): array
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$block` | **array** |  |





***

### blockComment



```php
protected blockComment(array $line): ?array
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$line` | **array** |  |





***

### blockCommentContinue



```php
protected blockCommentContinue(array $line, array $block): ?array
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$line` | **array** |  |
| `$block` | **array** |  |





***

### blockFencedCode



```php
protected blockFencedCode(array $line): ?array
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$line` | **array** |  |





***

### blockFencedCodeContinue



```php
protected blockFencedCodeContinue(array $line, array $block): ?array
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$line` | **array** |  |
| `$block` | **array** |  |





***

### blockFencedCodeComplete



```php
protected blockFencedCodeComplete(array $block): array
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$block` | **array** |  |





***

### blockHeader



```php
protected blockHeader(array $line): ?array
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$line` | **array** |  |





***

### blockList



```php
protected blockList(array $line): ?array
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$line` | **array** |  |





***

### blockListContinue



```php
protected blockListContinue(array $line, array $block): ?array
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$line` | **array** |  |
| `$block` | **array** |  |





***

### blockListComplete



```php
protected blockListComplete(array $block): array
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$block` | **array** |  |





***

### blockQuote



```php
protected blockQuote(array $line): ?array
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$line` | **array** |  |





***

### blockQuoteContinue



```php
protected blockQuoteContinue(array $line, array $block): ?array
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$line` | **array** |  |
| `$block` | **array** |  |





***

### blockRule



```php
protected blockRule(array $line): ?array
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$line` | **array** |  |





***

### blockSetextHeader



```php
protected blockSetextHeader(array $line, array $block = null): ?array
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$line` | **array** |  |
| `$block` | **array** |  |





***

### blockMarkup



```php
protected blockMarkup(array $line): ?array
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$line` | **array** |  |





***

### blockMarkupContinue



```php
protected blockMarkupContinue(array $line, array $block): ?array
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$line` | **array** |  |
| `$block` | **array** |  |





***

### blockReference



```php
protected blockReference(array $line): ?array
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$line` | **array** |  |





***

### blockTable



```php
protected blockTable(array $line, ?array $block = null): ?array
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$line` | **array** |  |
| `$block` | **?array** |  |





***

### blockTableContinue



```php
protected blockTableContinue(array $line, array $block): ?array
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$line` | **array** |  |
| `$block` | **array** |  |





***

### paragraph



```php
protected paragraph(array $line): array
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$line` | **array** |  |





***

### inlineCode



```php
protected inlineCode(array $excerpt): ?array
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$excerpt` | **array** |  |





***

### inlineEmailTag



```php
protected inlineEmailTag(array $excerpt): ?array
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$excerpt` | **array** |  |





***

### inlineEmphasis



```php
protected inlineEmphasis(array $excerpt): ?array
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$excerpt` | **array** |  |





***

### inlineEscapeSequence



```php
protected inlineEscapeSequence(array $excerpt): ?array
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$excerpt` | **array** |  |





***

### inlineImage



```php
protected inlineImage(array $excerpt): ?array
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$excerpt` | **array** |  |





***

### inlineLink



```php
protected inlineLink(array $excerpt): ?array
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$excerpt` | **array** |  |





***

### inlineMarkup



```php
protected inlineMarkup(array $excerpt): ?array
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$excerpt` | **array** |  |





***

### inlineSpecialCharacter



```php
protected inlineSpecialCharacter(array $excerpt): ?array
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$excerpt` | **array** |  |





***

### inlineStrikethrough



```php
protected inlineStrikethrough(array $excerpt): ?array
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$excerpt` | **array** |  |





***

### inlineUrl



```php
protected inlineUrl(array $excerpt): ?array
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$excerpt` | **array** |  |





***

### inlineUrlTag



```php
protected inlineUrlTag(array $excerpt): ?array
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$excerpt` | **array** |  |





***

### unmarkedText



```php
protected unmarkedText(string $text): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$text` | **string** |  |





***

### element



```php
protected element(array $element): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$element` | **array** |  |





***

### elements



```php
protected elements(array $elements): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$elements` | **array** |  |





***

### li



```php
protected li(array $lines): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$lines` | **array** |  |





***

### sanitiseElement



```php
protected sanitiseElement(array $element): array
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$element` | **array** |  |





***

### filterUnsafeUrlInAttribute



```php
protected filterUnsafeUrlInAttribute(array $element, string $attribute): array
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$element` | **array** |  |
| `$attribute` | **string** |  |





***

### escape



```php
protected static escape(string $text, bool $allow_quotes = false): string
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$text` | **string** |  |
| `$allow_quotes` | **bool** |  |





***

### striAtStart



```php
protected static striAtStart(string $string, string $needle): bool
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$string` | **string** |  |
| `$needle` | **string** |  |





***

### instance



```php
public static instance(mixed $name = &#039;default&#039;): self
```



* This method is **static**.




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **mixed** |  |





***


***
> Automatically generated on 2023-12-21
