## 🎛️ Features

Cosmic is a PHP framework and a CLI application for generating PHP applications. Here are some of its main features:

### 🧰 Application Management
With Cosmic, you can quickly generate a new PHP application with a simple command. Besides the generation you can use cosmic, to build, sign and distribute your application.

### 📦 PHAR Packaging
With the help of [Box](https://box-project.github.io/box/), Cosmic enables you to package your application into a PHAR file for easy distribution. The PHAR file contains all the files and dependencies required by the application, so you can distribute it as a single file.

### 🎨 Terminal Themes
Cosmic allows you to customize the look and feel of your application using themes. Themes are defined in json files and mapped into PHP classes, themes can be loaded from a directory or added manually. Cosmic comes with three bundled themes that you can use out of the box, but you can also create your own themes to customize the look and feel of your application.

### 🌈 UI Components
Cosmic provides a set of UI components that you can use to build your application. These components are built on top of the [Termwind]().
You can use, headers, paragraphs, tables, spinners, lists, progress bars, and more.

### 💬 Notifications
Cosmic allows you to send desktop notifications using the `Notifier` class and the `NotifiableInterface`. Under the hood the Notifier class uses the [jolicode/jolinotif](https://github.com/jolicode/JoliNotif) component to send the notifications.

### 🔫 Lifecycle Events
Cosmic allows you to hook into the application lifecycle using the `Lifecycle` class.


