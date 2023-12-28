# 🧰 Basic usage

Once installed you can use the `cosmic` command to generate, build and install your own application.

## 🛠️ Generation

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


## 📦 Building

Once you have generated your application you can use the `app:build` command to build your application into a single PHAR file:

```bash
cosmic build
```
this will generate a PHAR file in the `builds` directory of your application. This binary is usable in your local system, but is not signed and cannot be distributed to other users. 

## 🔑 Signing
In order to distribute this binary using phar.io and make it installable with phive you need to sign it with a GPG key. You can use the `sign` command to sign the PHAR file, before signing binaries you need to generate a GPG key pair, please refer to phar.io and [GPG documentation](https://www.gnupg.org/gph/en/manual.html) to know how to [generate](https://phar.io/howto/generate-gpg-key.html) and [publish](https://phar.io/howto/uploading-public-keys.html) a key.

```bash
cosmic sign builds/cosmic-app.phar --user yosoy@diego.ninja
```

## 🚀 Publishing 

once you have a signed binary you can publish it to GitHub using the `publish` command:

```bash
cosmic publish v1.2.0-beta --prerelease
```

the publish command just creates a release in the GitHub repo of your application, so you need to have an initialized GitHub repo for your application before using this command. 


And that's it, you have a fully functional PHP application that you can distribute to your users. Just keep coding and adding new commands and functionality to your application.
