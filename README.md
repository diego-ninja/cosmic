










![](assets/logo-portrait.png)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/diego-ninja/preloader.svg?style=flat)](https://packagist.org/packages/diego-ninja/preloader)
[![Total Downloads](https://img.shields.io/packagist/dt/diego-ninja/preloader.svg?style=flat)](https://packagist.org/packages/diego-ninja/preloader)
![PHP Version](https://img.shields.io/packagist/php-v/diego-ninja/preloader.svg?style=flat)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![wakatime](https://wakatime.com/badge/user/bd65f055-c9f3-4f73-92aa-3c9810f70cc3/project/018c0d4c-5525-4929-a0c3-da68ddd3448f.svg)](https://wakatime.com/badge/user/bd65f055-c9f3-4f73-92aa-3c9810f70cc3/project/018c0d4c-5525-4929-a0c3-da68ddd3448f)

- [Overview](#-overview)
- [Requirements](#-requirements)
- [Installation](#-installation)
  - [Using Phive](#using-phive)
  - [GitHub releases](#github-releases)
  - [Building from source](#building-from-source)
- [Basic usage](#-basic-usage)
  - [Bundled Commands](#bundled-commands)
- [Contributing](#-contributing)
- [Credits](#-credits)

You can place this at the top of your `README.md` file. The links are based on the headers in your document. If you add or remove headers, you'll need to update the Table of Contents accordingly.

# 🛸 Overview

Cosmic is, in one hand a PHP framework for building CLI applications, and in the other hand a CLI application for building PHP applications. Using Cosmic you can
generate a fully functional PHP application and start coding right away using the Cosmic framework. 

In your commands you can use nice spinners and tables, send os based notifications or develop your own themes to customize the look and feel of your application.

Cosmic uses [Symfony Console](https://symfony.com/doc/current/components/console.html) and [Termwind](https://github.com/nunomaduro/termwind) under the hood among other packages.


# ✋ Requirements

Cosmic needs the following to run:
 - php **8.2** with the following extensions:
   - json
   - hash
   - mbstring
   - openssl
   - pcntl
   - posix
   - random
   - zip
 - [box](https://box-project.github.io/box/) to build the application phar file

# 📦 Installation

You can install Cosmic using one of the following methods:

## Using Phive
```bash
phive install diego-ninja/cosmic
```

To upgrade cosmic use the following command:
```bash
phive update diego-ninja/cosmic
```

## GitHub releases
You may download the Cosmic PHAR directly from the GitHub release directly. You should however beware that it is not as secure as downloading it from the other mediums. Hence, it is recommended to check the signature when doing so:

```bash
# Do adjust the URL based on the latest release
wget -O cosmic "https://github.com/diego-ninja/cosmic/releases/download/1.0.0/cosmic.phar"
wget -O cosmic.asc "https://github.com/diego-ninja/cosmic/releases/download/1.0.0/cosmic.phar.asc"

# Check that the signature matches
gpg --verify cosmic.asc cosmic

# Check the issuer (the ID can also be found from the previous command)
gpg --keyserver hkps://keys.openpgp.org --recv-keys CFC27BC39EF44C7253BF9A2CDACB70CB34CD5799

rm cosmic.asc
chmod +x cosmic
sudo mv cosmic /usr/local/bin/cosmic
```

## Building from source
```bash
git clone git@github.com:diego-ninja/cosmic.git
cd cosmic
composer install
php cosmic app:install
```
this will install the cosmic application in `/usr/local/bin` directory.
Cosmic will ask you for your sudo password to install the application. If you prefer installing the application in another directory you can use the `--path` option.


# 🧰 Basic usage

Once installed you can use the `cosmic` command to generate, build and install your own application. 

To generate a new application you can use the `app:init` command:

```bash
cosmic init
```
this command is interactive and will ask you for the name, author and other information about your application. Once the command finishes you will have a fully functional PHP application in the directory you specified.
The generated application includes an example command, the `quote` command,  that you can use to test your application amd use as a reference to build your own commands.

<pre><font color="#A3BE8C"><b>👽</b></font> <font color="#005FD7">php</font> <font color="#00AFFF"><u style="text-decoration-style:single">cosmic-app</u></font> <font color="#00AFFF">quote</font>

 <font color="#EBCB8B"><i>- &quot;What has risen may sink, and what has sunk may rise.&quot;</i></font>

 🐙 The Nameless City - <font color="#81A1C1">Howard Phillips Lovecraft</font> 

</pre>

The cosmic binary is able to detect if it is executed inside a cosmic application and will use the application environment instead of the bundled environment. This allows you to use the cosmic binary to build and install your application.

## Bundled Commands

Cosmic comes with a set of commands that you can use to generate, build and install your own application, this default set of commands is called the `core` commands and are available in every Cosmic application.

The cosmic core commands are:

### 🛠️ init
The init command is used to generate a new application boilerplate. After executing this command you will have a fully functional PHP application that you can use right away to start coding your own commands.

### 📦 build
The build command is used to build your application into a single PHAR file. This file can be used to distribute your application to other users.

### 🚚 install
The install command is used to install your application in the system. This command will copy the PHAR file to the `/usr/local/bin` directory and will make the file executable system-wide . This command requires sudo privileges.

### ❤️ about
The about command is used to display information about the application.

### 🔮 help
The help command is used to display information about the available commands. Cosmic gives you the choice of store the command help in a file using a subset of markdown syntax. This file is located in the `docs/commands` directory and is named after the command name. For example, the help for the `quote` command is stored in the `docs/commands/quote.md` file.

### 🐚 completion
The completion command is used to generate the shell completion script for the application. This command detects the shell you are using and generates the completion script for that shell. The completion script is output to the standard output and you can redirect it to a file to use it later.


## 🤝 Contributing

Contributions, issues, and feature requests are welcome! Here are some ways you can contribute:

1. **Improve or update the documentation:** If you've noticed an error, or if you think some details could be explained more clearly or thoroughly, we would appreciate your help to improve the documentation.

2. **Report bugs:** If you find a bug, please create a new issue describing the problem and include as much detail as you can.

3. **Suggest new features or enhancements:** If you have an idea for a new feature or an enhancement to an existing feature, please create a new issue describing your suggestion.

4. **Submit a pull request:** If you've fixed a bug or developed a new feature, we would love to include your contributions in the project. Please create a new pull request and be sure to describe your changes in detail.

Thank you for your interest in contributing to Cosmic!


## 🙏 Credits

This project is developed and maintained by 🥷 [Diego Ninja](https://diego.ninja). 

Special thanks to:

- [Symfony Console](https://symfony.com/doc/current/components/console.html) for providing the console component used in this project.
- [Termwind](https://github.com/nunomaduro/termwind) for providing the terminal styling capabilities.
- [Box](https://box-project.github.io/box/) for enabling us to package the application into a PHAR file.
- All the contributors and testers who have helped to improve this project through their contributions.

If you find this project useful, please consider giving it a ⭐ on GitHub!
