# ✋ Requirements

Cosmic needs the following to run:

- 🐘 php **8.2** with the following extensions:
    - json
    - hash
    - mbstring
    - openssl
    - pcntl
    - posix
    - random
    - zip
- 📦 [box](https://box-project.github.io/box/) to build the application phar file
- 🔑 [gpg](https://www.gnupg.org/) to sign the application phar file
- 👾 [gh](https://cli.github.com/) to create releases on GitHub

You can install these requirements using the following commands:

## 🐘 PHP 8.2 and extensions

```bash
sudo add-apt-repository ppa:ondrej/php -y
sudo add-apt-repository ppa:openswoole/ppa -y    
sudo apt update    
yes | sudo apt install php8.2 php8.2-cli php8.2-common php8.2-{json,hash,mbstring,openssl,pctnl,posix,random,zip}   
```

## 📦 Box using phive

```bash
phive install humbug/box
```

you can install box using other methods, please refer to the [box documentation](https://box-project.github.io/box2/#/installation) for more information.


## 🔑 GPG

```bash
sudo apt-get install gnupg
```

## 👾 GitHub CLI

```bash
type -p curl >/dev/null || (sudo apt update && sudo apt install curl -y)
curl -fsSL https://cli.github.com/packages/githubcli-archive-keyring.gpg | sudo dd of=/usr/share/keyrings/githubcli-archive-keyring.gpg \
&& sudo chmod go+r /usr/share/keyrings/githubcli-archive-keyring.gpg \
&& echo "deb [arch=$(dpkg --print-architecture) signed-by=/usr/share/keyrings/githubcli-archive-keyring.gpg] https://cli.github.com/packages stable main" | sudo tee /etc/apt/sources.list.d/github-cli.list > /dev/null \
&& sudo apt update \
&& sudo apt install gh -y
```
