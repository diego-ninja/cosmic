![](assets/logo-portrait.png)

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![wakatime](https://wakatime.com/badge/user/bd65f055-c9f3-4f73-92aa-3c9810f70cc3/project/018c0d4c-5525-4929-a0c3-da68ddd3448f.svg)](https://wakatime.com/badge/user/bd65f055-c9f3-4f73-92aa-3c9810f70cc3/project/018c0d4c-5525-4929-a0c3-da68ddd3448f)

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


# 🧰 Usage

Once installed you can use the `cosmic` command to generate, build and install your own application.

## Commands

Cosmic comes with a set of commands that you can use to generate, build and install your own application, this default set of commands is called the `core` commands and are available in every Cosmic application.

# 📄 Documentation

