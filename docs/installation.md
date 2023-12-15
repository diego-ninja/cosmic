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
