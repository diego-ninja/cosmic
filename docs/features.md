## 🚀 Features

Cosmic is a PHP framework and a CLI application for generating PHP applications. Here are some of its main features:

### Command Line Interface
Cosmic provides a robust and flexible command line interface for building and managing your PHP applications. Using the CLI, you can quickly generate a new application, build and install it, and run it from the command line.

### Application Generation
With Cosmic, you can quickly generate a new PHP application with a simple command. The generated application includes a `composer.json` file, a `src` directory, and a `tests` directory. The `composer.json` file contains the minimum dependencies required by the application. In the src/Command directory, you will find an example command, the `quote` command, that you can use to test your application and use as a reference to build your own commands.

### 🎨 [Terminal Themes](dev/themes.md)
Cosmic allows you to customize the look and feel of your application using themes. Themes are defined in json files and mapped into PHP classes, themes can be loaded from a directory or added manually. Cosmic comes with three bundled themes that you can use out of the box, but you can also create your own themes to customize the look and feel of your application.

### 📦 PHAR Packaging
With the help of Box, Cosmic enables you to package your application into a PHAR file for easy distribution. The PHAR file contains all the files and dependencies required by the application, so you can distribute it as a single file.

### 💬 [Notifications](dev/notifications.md)
Cosmic allows you to send desktop notifications using the `Notifier` class. Under the hood the Notifier class uses the [jolicode/jolinotif]() component to send the notifications.

### 🌀 [Spinners](dev/spinner.md)
Spinners are visual indicators of an ongoing process, and they can be used to indicate that a process is running in the background. Cosmic allows you to display spinners in your terminal using the `Spinner` class. 

### 🗓️ [Tables](dev/tables.md)
Cosmic allows you to display tables in your terminal using the `Table` class. Tables are fully customizable via themes and can be used to display data in a tabular format.

### 🔫 [Lifecycle Events](dev/lifecycle.md)
Cosmic allows you to hook into the application lifecycle using the `Lifecycle` class.


Please refer to the individual documentation sections for more detailed information about each feature.
