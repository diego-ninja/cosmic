# 🛠️ Commands

### Step 1: Implementing and extending

To create a new command, you need to create a class that implements the `CommandInterface`, you can leverage the `CosmicCommand` class by extending it. This class implements the required interface and provides a set of methods and properties that are common to all commands.

```php  
namespace Ninja\Cosmic\Command;  
  
final class MyCommand extends CosmicCommand  
{  
    // Command implementation goes here  
}  
```  

### Step 2: Use Attributes to Define Command Metadata

Attributes are used to define metadata about the command. Here are the attributes you can use:

```php  
namespace Ninja\Cosmic\Command;  
  
#[Icon('👽')]  
#[Name('my_command')]  
#[Description('This is my command')]  
#[Signature('my_command [--argument=] [--option]')]  
#[Argument('argument', 'This is an argument')]  
#[Option('--option', 'This is an option', 'default_value')]
#[Alias("mycommand:alias")]
#[Env("DEV")]
#[Env("STAGING")]
#[Hidden]  
#[Decorated(false)]  
final class MyCommand extends CosmicCommand implements CommandInterface  
{  
    // CommandInterface methods implementation goes here  
}  
```  


#### Name
Defines the command name, can be name-spaced, the namespace separator is the colon character. (:)

#### Alias (Repeatable)
Defines the possible aliases for the command, the command will work the same if called by its main name or any of the defined aliases.
#### Icon
Defines the icon of the command that will be shown in the command list. It supports any unicode defined emoji, but, depending on the terminal/console capabilities, some icons, the simpler ones, displays better than the rest.

#### Description
Defines the description of the command that will appear in the command list. The description supports styling via custom defined tags like `<comment></comment>` or `<info></info>`. Besides styling, description support replacements, See `Replacer` documentation to see how this feature works.

#### Signature
Defines the command's signature, which includes the command name and its arguments. Every option or argument defined in the signature should have a correspondent option attribute defining it. Below you can check for the different types and combinations of options and arguments that can be defined in the signature.

##### Arguments

A command can take arguments:

| Description                    | Example         |
|--------------------------------|-----------------|
| Required argument              | `greet name`    |
| Optional argument              | `greet [name]`  |
| Array argument with 0-n values | `greet [name]*` |
| Array argument with 1-n values | `greet name*`   |

##### Options

A command can take options:

| Description                                     | Example                  |
|-------------------------------------------------|--------------------------|
| Simple flag (boolean value)                     | `greet [--yell]`         |
| Option with an mandatory value                  | `greet [--iterations=]`  |
| Option that can be used 0-n times (array value) | `greet [--iterations=]*` |
| Option with a shortcut                          | `greet [-y\|--yell]`      |

Options are always optional. If an option is required, then it should be an argument.

##### Hyphens

Arguments and options containing hyphens (`-`) are matched to camel case variables:

```php  
namespace Ninja\Cosmic\Command;  
  
#[Signature('my_command [--argument-with-hyppen=]')]  
#[Option('--argument-with-hypen', 'This is an argument with hypen']  
final class MyCommand extends CosmicCommand implements CommandInterface  
{  
    public function __invoke(string ·argumentWithHypen)
}  
```  

#### Option (Repeatable)
Defines an option for the command. **Options** are always optional by default, and  they start with a double dash in its name, if an option should be required, then it should be an argument. Options can have a description and a default value, that can be defined as the second and third parameter of the attribute.

#### Argument (Repeatable)
Defines an argument for the command. Arguments can have a description and a default value, that can be defined as the second and third parameter of the attribute.

#### Env (Repeatable)
Defines the environments where the command is enabled, if this attribute is not present the command will be always enabled. This is useful if you need to define commands that should be disabled in certain environments, for example, a command that flushes databases could be disabled in PROD environment.

Commands that are suitable to be enabled or disabled in a certain environment should implement the `EnvironmentAwareInterface'. CosmicCommand` class, via the `CommandAttributeTrait` already implements the methods exposed by the interface.
#### Hidden
If this attribute is present, the command will not appear in the command list, this attribute doesn't disable the command, just hide it from the list. If you need to disable a command, you can use the `Env` attribute to completely enable or disable a command depending on the running environment.

#### Decorated
If this attribute is present and set to false, the command output will be stripped for any ANSI, escape sequence and any other eye-candy decoration.
### Step 4: Implement the `NotifiableInterface` (Optional)

The `NotifiableInterface` provides a contract for commands that need to display success or error OS notification. It defines two methods that your command class can implement:  If the interface is defined and the messages are set, the command will display a OS notification, using the Notifier class, with the configured messages for the success and failure results of the command.

The messages defined in this methods support placeholder replacements, for more information on how to use this feature, please refer to `Replacer` documentation.

- `getSuccessMessage()`: Returns the success message.
- `getErrorMessage()`: Returns the error message.

```php  
namespace Ninja\Cosmic\Command;  
  
final class MyCommand extends CosmicCommand implements NotifiableInterface  
{  
    // CommandInterface methods implementation goes here  
  
    public function getSuccessMessage(): string  
    {  
        return 'Command {{ command.name }} executed successfully';  
    }  
  
    public function getErrorMessage(): string  
    {  
        return 'Command {{ command.name }} execution failed';  
    }  
}  
```  

### Step 5: Implement the Command Logic

The command logic should be implemented in the `__invoke()` method. This method is called when the command is executed.  The method will receive the options and parameters defined in the command signature, check the following example for more clarity.


```php  
namespace Ninja\Cosmic\Command;  

#[Icon("📚")]  
#[Name('quote')]  
#[Signature('quote [--book=] [--author=] [--all]')]  
#[Description('Display a random quote from a book or an author')]  
#[Alias("tell")]  
#[Option("--book", "The book to get a quote from")]  
#[Option("--author", "The author to get a quote from")]  
#[Option("--all", "Get all the quotes from the author or book")]
final class MyCommand extends CosmicCommand  
{  
    // CommandInterface methods implementation goes here  
  
    public function __invoke(?string $book, ?string $author, bool $all = false): int
    {  
        // Command logic goes here  
  
        return $this->success();  
    }  
}  
```  

That's it! You've created a new command. If you place the command inside the `src/Command` folder then it will be auto-magically registered in the application, if not you need to register it in a manual way, to learn how to register commands in your application, please refer to `Application` documentation.
