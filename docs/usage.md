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

### 🔑 sign
The sign command is used to sign the application PHAR file using GPG. This command requires the GPG key ID to sign the PHAR file. You can use the `--key` or `--user` options to specify the key ID or you can set the `APP_SIGNING_KEY` environment variable to the key ID.

### 🚀 publish
The publish command is used to publish a new release of your application to GitHub. At the moment this command uses the GitHub CLI under the hood to create the release, so you need to have the GitHub CLI installed and configured to use this command.

### 🚚 install
The install command is used to install your application in the system. This command will copy the PHAR file to the `/usr/local/bin` directory and will make the file executable system-wide . This command requires sudo privileges.

### ❤️ about
The about command is used to display information about the application.

### 🔮 help
The help command is used to display information about the available commands. Cosmic gives you the choice of store the command help in a file using a subset of markdown syntax. This file is located in the `docs/commands` directory and is named after the command name. For example, the help for the `quote` command is stored in the `docs/commands/quote.md` file.

### 🐚 completion
The completion command is used to generate the shell completion script for the application. This command detects the shell you are using and generates the completion script for that shell. The completion script is output to the standard output and you can redirect it to a file to use it later.
