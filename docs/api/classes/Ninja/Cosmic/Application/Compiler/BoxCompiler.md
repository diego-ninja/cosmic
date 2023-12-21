***

# BoxCompiler

Class BoxCompiler

Responsible for compiling the application into a Phar binary using Box.

* Full name: `\Ninja\Cosmic\Application\Compiler\BoxCompiler`
* This class implements:
[`\Ninja\Cosmic\Application\Compiler\CompilerInterface`](./CompilerInterface.md)




## Methods


### compile

Compile the application into a Phar binary using Box.

```php
public compile(): bool
```









**Return Value:**

True if the compilation process is successful, false otherwise.



**Throws:**

- [`ReflectionException`](../../../../ReflectionException.md)
<p>If unable to install or find the Box binary.</p>

- [`\RuntimeException|\Ninja\Cosmic\Exception\BinaryNotFoundException`](../../../../RuntimeException|/Ninja/Cosmic/Exception/BinaryNotFoundException.md)



***

### installBoxBinary

Install the Box binary using Phive.

```php
private installBoxBinary(): bool
```









**Return Value:**

True if the installation process is successful, false otherwise.



**Throws:**

- [`BinaryNotFoundException`](../../Exception/BinaryNotFoundException.md)



***


***
> Automatically generated on 2023-12-21
