# 📝 Question Facade Documentation

The `Question` class is a facade that provides a simple interface require input from the user. It provides a set of methods that delegate the work to other classes and methods. This class is part of the `Ninja\Cosmic\Terminal\Input` namespace.

## Ask

This method asks a question with a given message, default answer, autocomplete options, and a flag indicating whether the message should be decorated.

```php
ask(string $message, ?string $default = null, array $autoComplete = [], bool $decorated = true): ?string
```

**Parameters:**
- `$message`: The message to be displayed in the question.
- `$default`: The default answer to the question.
- `$autoComplete`: The autocomplete options for the question.
- `$decorated`: A flag indicating whether the message should be decorated.

**Example:**
```php
Question::ask('What is your name?', 'John Doe', ['John Doe', 'Jane Doe'], true);
```
## Confirm

This method asks a confirmation question with a given message, default answer, and a flag indicating whether the message should be decorated.

```php
confirm(string $message, bool $default = true, bool $decorated = true): bool
```

**Parameters:**
- `$message`: The message to be displayed in the question.
- `$default`: The default answer to the question.
- `$decorated`: A flag indicating whether the message should be decorated.

**Example:**
```php
Question::confirm('Do you confirm generation of the application?', true);
```
<pre>🚀 <font color="#88C0D0">Do you confirm generation of the application?</font> [<font color="#88C0D0">yes</font>/no]</pre>

## Hidden

This method asks a question with a given message and hides the response of the user.

```php
hidden(string $message, bool $decorated = true): ?string
```

**Parameters:**
- `$message`: The message to be displayed in the question.
- `$decorated`: A flag indicating whether the message should be decorated.

**Example:**
```php
Question::hidden('Enter your password:');
```

## Select

This method displays a list of options in the terminal and returns the user's selection(s).

```php
select(string $message, array $options, bool $allowMultiple = true, ?int $columns = null, ?int $maxWidth = null): array
```

**Parameters:**
- `$message`: The message to be displayed in the question.
- `$options`: The options for the question.
- `$allowMultiple`: A flag indicating whether multiple selections are allowed.
- `$columns`: The number of columns.
- `$maxWidth`: The maximum width.

**Example:**
```php
Question::select('Choose your favorite colors:', ['Red', 'Green', 'Blue'], true, 3, 100);
```
<pre><font color="#E5E9F0"> 🔎 </font><font color="#88C0D0">License</font><font color="#E5E9F0">: </font> [<font color="#88C0D0">ENTER=select</font>]
 <font color="#88C0D0"> ⬡ Apache-2.0                </font> <font color="#88C0D0"> ⬡ BSD-2-Clause              </font> <font color="#88C0D0"> ⬡ BSD-3-Clause              </font>
 <font color="#88C0D0"> ⬡ GPL-2.0-only              </font> <font color="#88C0D0"> ⬡ GPL-2.0-or-later          </font> <font color="#88C0D0"> ⬡ GPL-3.0-only              </font>
 <font color="#88C0D0"> ⬡ GPL-3.0-or-later          </font> <font color="#88C0D0"> ⬡ LGPL-2.1-only             </font> <font color="#88C0D0"> ⬡ LGPL-2.1-or-later         </font>
 <font color="#88C0D0"> ⬡ LGPL-3.0-only             </font> <font color="#88C0D0"> ⬡ LGPL-3.0-or-later         </font> <font color="#88C0D0"> ⬢ MIT                       </font>
 <font color="#88C0D0"> ⬡ MPL-2.0                   </font> <font color="#88C0D0"> ⬡ Proprietary               </font> <font color="#88C0D0"> ⬡ Unlicensed                </font>
</pre>
