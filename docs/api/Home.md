
***

# Documentation



This is an automatically generated documentation for **Documentation**.


## Namespaces


### \Ninja\Cosmic\Application

#### Classes

| Class | Description |
|-------|-------------|
| [`Application`](./classes/Ninja/Cosmic/Application/Application.md) | Class Application|




### \Ninja\Cosmic\Application\Builder

#### Classes

| Class | Description |
|-------|-------------|
| [`ApplicationBuilder`](./classes/Ninja/Cosmic/Application/Builder/ApplicationBuilder.md) | Class ApplicationBuilder|




### \Ninja\Cosmic\Application\Compiler

#### Classes

| Class | Description |
|-------|-------------|
| [`BoxCompiler`](./classes/Ninja/Cosmic/Application/Compiler/BoxCompiler.md) | Class BoxCompiler|



#### Interfaces

| Interface | Description |
|-----------|-------------|
| [`CompilerInterface`](./classes/Ninja/Cosmic/Application/Compiler/CompilerInterface.md) | Interface CompilerInterface|



### \Ninja\Cosmic\Application\Publisher

#### Classes

| Class | Description |
|-------|-------------|
| [`GithubClientPublisher`](./classes/Ninja/Cosmic/Application/Publisher/GithubClientPublisher.md) | Class GithubClientPublisher|



#### Interfaces

| Interface | Description |
|-----------|-------------|
| [`PublisherInterface`](./classes/Ninja/Cosmic/Application/Publisher/PublisherInterface.md) | Interface PublisherInterface|



### \Ninja\Cosmic\Application\Publisher\Asset

#### Classes

| Class | Description |
|-------|-------------|
| [`Asset`](./classes/Ninja/Cosmic/Application/Publisher/Asset/Asset.md) | Class Asset|
| [`AssetCollection`](./classes/Ninja/Cosmic/Application/Publisher/Asset/AssetCollection.md) | Class AssetCollection|
| [`Signature`](./classes/Ninja/Cosmic/Application/Publisher/Asset/Signature.md) | Class Signature|




### \Ninja\Cosmic\Application\Publisher\Release

#### Classes

| Class | Description |
|-------|-------------|
| [`Release`](./classes/Ninja/Cosmic/Application/Publisher/Release/Release.md) | Class Release|
| [`ReleaseCollection`](./classes/Ninja/Cosmic/Application/Publisher/Release/ReleaseCollection.md) | Class ReleaseCollection|




### \Ninja\Cosmic\Command

#### Classes

| Class | Description |
|-------|-------------|
| [`AboutCommand`](./classes/Ninja/Cosmic/Command/AboutCommand.md) | |
| [`BuildCommand`](./classes/Ninja/Cosmic/Command/BuildCommand.md) | |
| [`CompletionCommand`](./classes/Ninja/Cosmic/Command/CompletionCommand.md) | |
| [`CosmicCommand`](./classes/Ninja/Cosmic/Command/CosmicCommand.md) | |
| [`HelpCommand`](./classes/Ninja/Cosmic/Command/HelpCommand.md) | |
| [`InitCommand`](./classes/Ninja/Cosmic/Command/InitCommand.md) | |
| [`InstallCommand`](./classes/Ninja/Cosmic/Command/InstallCommand.md) | |
| [`ListCommand`](./classes/Ninja/Cosmic/Command/ListCommand.md) | |
| [`PublishCommand`](./classes/Ninja/Cosmic/Command/PublishCommand.md) | |
| [`SignCommand`](./classes/Ninja/Cosmic/Command/SignCommand.md) | |



#### Interfaces

| Interface | Description |
|-----------|-------------|
| [`CommandInterface`](./classes/Ninja/Cosmic/Command/CommandInterface.md) | |
| [`EnvironmentAwareInterface`](./classes/Ninja/Cosmic/Command/EnvironmentAwareInterface.md) | |



### \Ninja\Cosmic\Command\Attribute

#### Classes

| Class | Description |
|-------|-------------|
| [`Alias`](./classes/Ninja/Cosmic/Command/Attribute/Alias.md) | |
| [`Argument`](./classes/Ninja/Cosmic/Command/Attribute/Argument.md) | |
| [`Decorated`](./classes/Ninja/Cosmic/Command/Attribute/Decorated.md) | |
| [`Description`](./classes/Ninja/Cosmic/Command/Attribute/Description.md) | |
| [`Environment`](./classes/Ninja/Cosmic/Command/Attribute/Environment.md) | |
| [`Hidden`](./classes/Ninja/Cosmic/Command/Attribute/Hidden.md) | |
| [`Icon`](./classes/Ninja/Cosmic/Command/Attribute/Icon.md) | |
| [`Name`](./classes/Ninja/Cosmic/Command/Attribute/Name.md) | |
| [`Option`](./classes/Ninja/Cosmic/Command/Attribute/Option.md) | |
| [`Signature`](./classes/Ninja/Cosmic/Command/Attribute/Signature.md) | |


#### Traits

| Trait | Description |
|-------|-------------|
| [`CommandAttributeTrait`](./classes/Ninja/Cosmic/Command/Attribute/CommandAttributeTrait.md) | Trait CommandAttributeTrait|




### \Ninja\Cosmic\Command\Exception

#### Classes

| Class | Description |
|-------|-------------|
| [`InvalidCommandExpressionException`](./classes/Ninja/Cosmic/Command/Exception/InvalidCommandExpressionException.md) | |




### \Ninja\Cosmic\Command\Finder

#### Classes

| Class | Description |
|-------|-------------|
| [`CommandFinder`](./classes/Ninja/Cosmic/Command/Finder/CommandFinder.md) | |




### \Ninja\Cosmic\Command\Input

#### Classes

| Class | Description |
|-------|-------------|
| [`Argument`](./classes/Ninja/Cosmic/Command/Input/Argument.md) | |
| [`Option`](./classes/Ninja/Cosmic/Command/Input/Option.md) | |




### \Ninja\Cosmic\Command\Parser

#### Classes

| Class | Description |
|-------|-------------|
| [`ExpressionParser`](./classes/Ninja/Cosmic/Command/Parser/ExpressionParser.md) | |




### \Ninja\Cosmic\Crypt

#### Classes

| Class | Description |
|-------|-------------|
| [`AbstractKey`](./classes/Ninja/Cosmic/Crypt/AbstractKey.md) | Class AbstractKey|
| [`KeyCollection`](./classes/Ninja/Cosmic/Crypt/KeyCollection.md) | Class KeyCollection|
| [`KeyRing`](./classes/Ninja/Cosmic/Crypt/KeyRing.md) | Class KeyRing|
| [`PrivateKey`](./classes/Ninja/Cosmic/Crypt/PrivateKey.md) | Class PrivateKey|
| [`PublicKey`](./classes/Ninja/Cosmic/Crypt/PublicKey.md) | Class AbstractKey|
| [`Uid`](./classes/Ninja/Cosmic/Crypt/Uid.md) | |
| [`Verifier`](./classes/Ninja/Cosmic/Crypt/Verifier.md) | |



#### Interfaces

| Interface | Description |
|-----------|-------------|
| [`CypherInterface`](./classes/Ninja/Cosmic/Crypt/CypherInterface.md) | Interface CypherInterface|
| [`KeyInterface`](./classes/Ninja/Cosmic/Crypt/KeyInterface.md) | Interface KeyInterface|
| [`SignerInterface`](./classes/Ninja/Cosmic/Crypt/SignerInterface.md) | |
| [`VerifierInterface`](./classes/Ninja/Cosmic/Crypt/VerifierInterface.md) | |



### \Ninja\Cosmic\Crypt\Exception

#### Classes

| Class | Description |
|-------|-------------|
| [`PGPNotInstalledException`](./classes/Ninja/Cosmic/Crypt/Exception/PGPNotInstalledException.md) | |
| [`SignatureFileNotFoundException`](./classes/Ninja/Cosmic/Crypt/Exception/SignatureFileNotFoundException.md) | |




### \Ninja\Cosmic\Crypt\Parser

#### Classes

| Class | Description |
|-------|-------------|
| [`KeyParser`](./classes/Ninja/Cosmic/Crypt/Parser/KeyParser.md) | |




### \Ninja\Cosmic\Environment

#### Classes

| Class | Description |
|-------|-------------|
| [`Env`](./classes/Ninja/Cosmic/Environment/Env.md) | Class Env|
| [`EnvironmentBuilder`](./classes/Ninja/Cosmic/Environment/EnvironmentBuilder.md) | Class EnvironmentBuilder|




### \Ninja\Cosmic\Environment\Exception

#### Classes

| Class | Description |
|-------|-------------|
| [`EnvironmentNotFoundException`](./classes/Ninja/Cosmic/Environment/Exception/EnvironmentNotFoundException.md) | |




### \Ninja\Cosmic\Event

#### Classes

| Class | Description |
|-------|-------------|
| [`Lifecycle`](./classes/Ninja/Cosmic/Event/Lifecycle.md) | Class Lifecycle|


#### Traits

| Trait | Description |
|-------|-------------|
| [`LifecycleTrait`](./classes/Ninja/Cosmic/Event/LifecycleTrait.md) | Trait LifecycleTrait|



#### Interfaces

| Interface | Description |
|-----------|-------------|
| [`LifecycleAwareInterface`](./classes/Ninja/Cosmic/Event/LifecycleAwareInterface.md) | Interface LifecycleAwareInterface|
| [`LifecycleEventListenerInterface`](./classes/Ninja/Cosmic/Event/LifecycleEventListenerInterface.md) | Interface LifecycleEventListenerInterface|



### \Ninja\Cosmic\Event\Dto

#### Classes

| Class | Description |
|-------|-------------|
| [`LifecycleEventArgs`](./classes/Ninja/Cosmic/Event/Dto/LifecycleEventArgs.md) | Class LifecycleEventArgs|




### \Ninja\Cosmic\Exception

#### Classes

| Class | Description |
|-------|-------------|
| [`BinaryNotFoundException`](./classes/Ninja/Cosmic/Exception/BinaryNotFoundException.md) | |
| [`ConfigFileNotFound`](./classes/Ninja/Cosmic/Exception/ConfigFileNotFound.md) | |
| [`CosmicException`](./classes/Ninja/Cosmic/Exception/CosmicException.md) | |
| [`MissingInterfaceException`](./classes/Ninja/Cosmic/Exception/MissingInterfaceException.md) | |
| [`UnexpectedValueException`](./classes/Ninja/Cosmic/Exception/UnexpectedValueException.md) | |




### \Ninja\Cosmic\Installer

#### Classes

| Class | Description |
|-------|-------------|
| [`AbstractInstaller`](./classes/Ninja/Cosmic/Installer/AbstractInstaller.md) | Class AbstractInstaller|
| [`AptInstaller`](./classes/Ninja/Cosmic/Installer/AptInstaller.md) | Class AptInstaller|
| [`PhiveInstaller`](./classes/Ninja/Cosmic/Installer/PhiveInstaller.md) | Class PhiveInstaller|



#### Interfaces

| Interface | Description |
|-----------|-------------|
| [`InstallerInterface`](./classes/Ninja/Cosmic/Installer/InstallerInterface.md) | Interface InstallerInterface|



### \Ninja\Cosmic\Notifier

#### Classes

| Class | Description |
|-------|-------------|
| [`Notifier`](./classes/Ninja/Cosmic/Notifier/Notifier.md) | Class Notifier|



#### Interfaces

| Interface | Description |
|-----------|-------------|
| [`NotifiableInterface`](./classes/Ninja/Cosmic/Notifier/NotifiableInterface.md) | Interface NotifiableInterface|



### \Ninja\Cosmic\Parser

#### Classes

| Class | Description |
|-------|-------------|
| [`MarkdownParser`](./classes/Ninja/Cosmic/Parser/MarkdownParser.md) | |




### \Ninja\Cosmic\Reflector

#### Classes

| Class | Description |
|-------|-------------|
| [`CallableReflector`](./classes/Ninja/Cosmic/Reflector/CallableReflector.md) | |




### \Ninja\Cosmic\Replacer

#### Classes

| Class | Description |
|-------|-------------|
| [`AbstractReplacer`](./classes/Ninja/Cosmic/Replacer/AbstractReplacer.md) | |
| [`CommandReplacer`](./classes/Ninja/Cosmic/Replacer/CommandReplacer.md) | |
| [`EnvironmentReplacer`](./classes/Ninja/Cosmic/Replacer/EnvironmentReplacer.md) | |
| [`ReplacerFactory`](./classes/Ninja/Cosmic/Replacer/ReplacerFactory.md) | |



#### Interfaces

| Interface | Description |
|-----------|-------------|
| [`ReplacerInterface`](./classes/Ninja/Cosmic/Replacer/ReplacerInterface.md) | |



### \Ninja\Cosmic\Replacer\Exception

#### Classes

| Class | Description |
|-------|-------------|
| [`UndefinedReplacerPrefixException`](./classes/Ninja/Cosmic/Replacer/Exception/UndefinedReplacerPrefixException.md) | |




### \Ninja\Cosmic\Resolver

#### Classes

| Class | Description |
|-------|-------------|
| [`HyphenatedInputResolver`](./classes/Ninja/Cosmic/Resolver/HyphenatedInputResolver.md) | |




### \Ninja\Cosmic\Serializer



#### Traits

| Trait | Description |
|-------|-------------|
| [`DeserializableTrait`](./classes/Ninja/Cosmic/Serializer/DeserializableTrait.md) | |
| [`GetterSetterTrait`](./classes/Ninja/Cosmic/Serializer/GetterSetterTrait.md) | |
| [`SerializableTrait`](./classes/Ninja/Cosmic/Serializer/SerializableTrait.md) | |



#### Interfaces

| Interface | Description |
|-----------|-------------|
| [`DeserializableInterface`](./classes/Ninja/Cosmic/Serializer/DeserializableInterface.md) | |
| [`SerializableInterface`](./classes/Ninja/Cosmic/Serializer/SerializableInterface.md) | |



### \Ninja\Cosmic\Terminal

#### Classes

| Class | Description |
|-------|-------------|
| [`Terminal`](./classes/Ninja/Cosmic/Terminal/Terminal.md) | Class Terminal|



#### Interfaces

| Interface | Description |
|-----------|-------------|
| [`RenderableInterface`](./classes/Ninja/Cosmic/Terminal/RenderableInterface.md) | |



### \Ninja\Cosmic\Terminal\Descriptor

#### Classes

| Class | Description |
|-------|-------------|
| [`AbstractDescriptor`](./classes/Ninja/Cosmic/Terminal/Descriptor/AbstractDescriptor.md) | |
| [`TextDescriptor`](./classes/Ninja/Cosmic/Terminal/Descriptor/TextDescriptor.md) | |




### \Ninja\Cosmic\Terminal\Input

#### Classes

| Class | Description |
|-------|-------------|
| [`Question`](./classes/Ninja/Cosmic/Terminal/Input/Question.md) | Class Question|




### \Ninja\Cosmic\Terminal\Input\Select\Handler

#### Classes

| Class | Description |
|-------|-------------|
| [`SelectHandler`](./classes/Ninja/Cosmic/Terminal/Input/Select/Handler/SelectHandler.md) | |




### \Ninja\Cosmic\Terminal\Input\Select\Input

#### Classes

| Class | Description |
|-------|-------------|
| [`AbstractSelect`](./classes/Ninja/Cosmic/Terminal/Input/Select/Input/AbstractSelect.md) | |
| [`CheckboxInput`](./classes/Ninja/Cosmic/Terminal/Input/Select/Input/CheckboxInput.md) | |
| [`RadioInput`](./classes/Ninja/Cosmic/Terminal/Input/Select/Input/RadioInput.md) | |
| [`SelectInput`](./classes/Ninja/Cosmic/Terminal/Input/Select/Input/SelectInput.md) | |



#### Interfaces

| Interface | Description |
|-----------|-------------|
| [`ColumnAwareInterface`](./classes/Ninja/Cosmic/Terminal/Input/Select/Input/ColumnAwareInterface.md) | |
| [`SelectInputInterface`](./classes/Ninja/Cosmic/Terminal/Input/Select/Input/SelectInputInterface.md) | |



### \Ninja\Cosmic\Terminal\Input\Select\Input\Exception

#### Classes

| Class | Description |
|-------|-------------|
| [`IndexOutOfRangeException`](./classes/Ninja/Cosmic/Terminal/Input/Select/Input/Exception/IndexOutOfRangeException.md) | |
| [`UnknownOptionException`](./classes/Ninja/Cosmic/Terminal/Input/Select/Input/Exception/UnknownOptionException.md) | |




### \Ninja\Cosmic\Terminal\Input\Select\Input\Trait



#### Traits

| Trait | Description |
|-------|-------------|
| [`ColumnableOptionTrait`](./classes/Ninja/Cosmic/Terminal/Input/Select/Input/Trait/ColumnableOptionTrait.md) | |




### \Ninja\Cosmic\Terminal\Renderer

#### Classes

| Class | Description |
|-------|-------------|
| [`CommandHelpRenderer`](./classes/Ninja/Cosmic/Terminal/Renderer/CommandHelpRenderer.md) | |




### \Ninja\Cosmic\Terminal\Spinner

#### Classes

| Class | Description |
|-------|-------------|
| [`Spinner`](./classes/Ninja/Cosmic/Terminal/Spinner/Spinner.md) | |
| [`SpinnerFactory`](./classes/Ninja/Cosmic/Terminal/Spinner/SpinnerFactory.md) | |




### \Ninja\Cosmic\Terminal\Spinner\Exception

#### Classes

| Class | Description |
|-------|-------------|
| [`SpinnerStyleFileNotFoundException`](./classes/Ninja/Cosmic/Terminal/Spinner/Exception/SpinnerStyleFileNotFoundException.md) | |
| [`SpinnerStyleFileParsingException`](./classes/Ninja/Cosmic/Terminal/Spinner/Exception/SpinnerStyleFileParsingException.md) | |




### \Ninja\Cosmic\Terminal\Table

#### Classes

| Class | Description |
|-------|-------------|
| [`Table`](./classes/Ninja/Cosmic/Terminal/Table/Table.md) | |
| [`TableConfig`](./classes/Ninja/Cosmic/Terminal/Table/TableConfig.md) | |


#### Traits

| Trait | Description |
|-------|-------------|
| [`TableableTrait`](./classes/Ninja/Cosmic/Terminal/Table/TableableTrait.md) | |



#### Interfaces

| Interface | Description |
|-----------|-------------|
| [`TableableInterface`](./classes/Ninja/Cosmic/Terminal/Table/TableableInterface.md) | |



### \Ninja\Cosmic\Terminal\Table\Column

#### Classes

| Class | Description |
|-------|-------------|
| [`ColumnCollection`](./classes/Ninja/Cosmic/Terminal/Table/Column/ColumnCollection.md) | |
| [`TableColumn`](./classes/Ninja/Cosmic/Terminal/Table/Column/TableColumn.md) | |



#### Interfaces

| Interface | Description |
|-----------|-------------|
| [`TableColumnInterface`](./classes/Ninja/Cosmic/Terminal/Table/Column/TableColumnInterface.md) | |



### \Ninja\Cosmic\Terminal\Table\Manipulator

#### Classes

| Class | Description |
|-------|-------------|
| [`BoolManipulator`](./classes/Ninja/Cosmic/Terminal/Table/Manipulator/BoolManipulator.md) | |
| [`DateManipulator`](./classes/Ninja/Cosmic/Terminal/Table/Manipulator/DateManipulator.md) | |
| [`DatelongManipulator`](./classes/Ninja/Cosmic/Terminal/Table/Manipulator/DatelongManipulator.md) | |
| [`DatetimeManipulator`](./classes/Ninja/Cosmic/Terminal/Table/Manipulator/DatetimeManipulator.md) | |
| [`DollarManipulator`](./classes/Ninja/Cosmic/Terminal/Table/Manipulator/DollarManipulator.md) | |
| [`DuetimeManipulator`](./classes/Ninja/Cosmic/Terminal/Table/Manipulator/DuetimeManipulator.md) | |
| [`ManipulatorCollection`](./classes/Ninja/Cosmic/Terminal/Table/Manipulator/ManipulatorCollection.md) | |
| [`ManipulatorFactory`](./classes/Ninja/Cosmic/Terminal/Table/Manipulator/ManipulatorFactory.md) | |
| [`MonthManipulator`](./classes/Ninja/Cosmic/Terminal/Table/Manipulator/MonthManipulator.md) | |
| [`NicetimeManipulator`](./classes/Ninja/Cosmic/Terminal/Table/Manipulator/NicetimeManipulator.md) | |
| [`NumberManipulator`](./classes/Ninja/Cosmic/Terminal/Table/Manipulator/NumberManipulator.md) | |
| [`PercentManipulator`](./classes/Ninja/Cosmic/Terminal/Table/Manipulator/PercentManipulator.md) | |
| [`TextManipulator`](./classes/Ninja/Cosmic/Terminal/Table/Manipulator/TextManipulator.md) | |
| [`TimeManipulator`](./classes/Ninja/Cosmic/Terminal/Table/Manipulator/TimeManipulator.md) | |
| [`YearManipulator`](./classes/Ninja/Cosmic/Terminal/Table/Manipulator/YearManipulator.md) | |



#### Interfaces

| Interface | Description |
|-----------|-------------|
| [`TableManipulatorInterface`](./classes/Ninja/Cosmic/Terminal/Table/Manipulator/TableManipulatorInterface.md) | |



### \Ninja\Cosmic\Terminal\Theme

#### Classes

| Class | Description |
|-------|-------------|
| [`Theme`](./classes/Ninja/Cosmic/Terminal/Theme/Theme.md) | |
| [`ThemeLoader`](./classes/Ninja/Cosmic/Terminal/Theme/ThemeLoader.md) | |



#### Interfaces

| Interface | Description |
|-----------|-------------|
| [`ThemeInterface`](./classes/Ninja/Cosmic/Terminal/Theme/ThemeInterface.md) | |
| [`ThemeLoaderInterface`](./classes/Ninja/Cosmic/Terminal/Theme/ThemeLoaderInterface.md) | |



### \Ninja\Cosmic\Terminal\Theme\Element

#### Classes

| Class | Description |
|-------|-------------|
| [`AbstractElementCollection`](./classes/Ninja/Cosmic/Terminal/Theme/Element/AbstractElementCollection.md) | |
| [`AbstractThemeElement`](./classes/Ninja/Cosmic/Terminal/Theme/Element/AbstractThemeElement.md) | |
| [`CollectionFactory`](./classes/Ninja/Cosmic/Terminal/Theme/Element/CollectionFactory.md) | |




### \Ninja\Cosmic\Terminal\Theme\Element\Charset

#### Classes

| Class | Description |
|-------|-------------|
| [`Charset`](./classes/Ninja/Cosmic/Terminal/Theme/Element/Charset/Charset.md) | |
| [`CharsetCollection`](./classes/Ninja/Cosmic/Terminal/Theme/Element/Charset/CharsetCollection.md) | |




### \Ninja\Cosmic\Terminal\Theme\Element\Color

#### Classes

| Class | Description |
|-------|-------------|
| [`Color`](./classes/Ninja/Cosmic/Terminal/Theme/Element/Color/Color.md) | |
| [`ColorCollection`](./classes/Ninja/Cosmic/Terminal/Theme/Element/Color/ColorCollection.md) | |




### \Ninja\Cosmic\Terminal\Theme\Element\Icon

#### Classes

| Class | Description |
|-------|-------------|
| [`Icon`](./classes/Ninja/Cosmic/Terminal/Theme/Element/Icon/Icon.md) | |
| [`IconCollection`](./classes/Ninja/Cosmic/Terminal/Theme/Element/Icon/IconCollection.md) | |




### \Ninja\Cosmic\Terminal\Theme\Element\Spinner

#### Classes

| Class | Description |
|-------|-------------|
| [`Spinner`](./classes/Ninja/Cosmic/Terminal/Theme/Element/Spinner/Spinner.md) | |
| [`SpinnerCollection`](./classes/Ninja/Cosmic/Terminal/Theme/Element/Spinner/SpinnerCollection.md) | |




### \Ninja\Cosmic\Terminal\Theme\Element\Style

#### Classes

| Class | Description |
|-------|-------------|
| [`AbstractStyle`](./classes/Ninja/Cosmic/Terminal/Theme/Element/Style/AbstractStyle.md) | |
| [`StyleCollection`](./classes/Ninja/Cosmic/Terminal/Theme/Element/Style/StyleCollection.md) | |
| [`SymfonyStyle`](./classes/Ninja/Cosmic/Terminal/Theme/Element/Style/SymfonyStyle.md) | |
| [`TermwindStyle`](./classes/Ninja/Cosmic/Terminal/Theme/Element/Style/TermwindStyle.md) | |




### \Ninja\Cosmic\Terminal\UI

#### Classes

| Class | Description |
|-------|-------------|
| [`UI`](./classes/Ninja/Cosmic/Terminal/UI/UI.md) | Class UI|




### \Ninja\Cosmic\Terminal\UI\Element

#### Classes

| Class | Description |
|-------|-------------|
| [`AbstractElement`](./classes/Ninja/Cosmic/Terminal/UI/Element/AbstractElement.md) | |
| [`AbstractList`](./classes/Ninja/Cosmic/Terminal/UI/Element/AbstractList.md) | |
| [`Header`](./classes/Ninja/Cosmic/Terminal/UI/Element/Header.md) | |
| [`OrderedList`](./classes/Ninja/Cosmic/Terminal/UI/Element/OrderedList.md) | |
| [`Paragraph`](./classes/Ninja/Cosmic/Terminal/UI/Element/Paragraph.md) | |
| [`Rule`](./classes/Ninja/Cosmic/Terminal/UI/Element/Rule.md) | |
| [`Summary`](./classes/Ninja/Cosmic/Terminal/UI/Element/Summary.md) | |
| [`Table`](./classes/Ninja/Cosmic/Terminal/UI/Element/Table.md) | |
| [`Title`](./classes/Ninja/Cosmic/Terminal/UI/Element/Title.md) | |
| [`UnorderedList`](./classes/Ninja/Cosmic/Terminal/UI/Element/UnorderedList.md) | |




***
> Automatically generated on 2023-12-21
