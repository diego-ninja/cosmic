# 🛟 Helpers Documentation

## `snakeize(string $input): string`

This function converts a given string to snake case. It takes a string as an input and returns the snake case version of the string.

## `camelize(string $input): string`

This function converts a given string to camel case. It takes a string as an input and returns the camel case version of the string.

## `value(mixed $value, mixed ...$args): mixed`

This function returns the value of a variable. If the variable is a closure, it executes the closure with the provided arguments and returns the result.

## `is_phar(): bool`

This function checks if the current script is running within a Phar archive. It returns `true` if it is, `false` otherwise.

## `is_git(): bool`

This function checks if the current directory is a Git repository. It returns `true` if it is, `false` otherwise.

## `find_binary(string $binary): string`

This function finds the path of a given binary file. It takes the name of the binary file as an input and returns the path of the binary file.

## `get_namespace_from_file(string $file): ?string`

This function retrieves the namespace from a given PHP file. It takes the path of the PHP file as an input and returns the namespace of the file.

## `get_class_from_file(string $file): string`

This function retrieves the fully qualified class name from a given PHP file. It takes the path of the PHP file as an input and returns the fully qualified class name.

## `git_version(string $path): ?string`

This function retrieves the Git version of a given directory. It takes the path of the directory as an input and returns the Git version.

## `is_root(): bool`

This function checks if the current user is root. It returns `true` if the user is root, `false` otherwise.

## `is_nullable(string $property, string $classname = null): bool`

This function checks if a given property of a class is nullable. It takes the property name and the class name as inputs and returns `true` if the property is nullable, `false` otherwise.

## `sudo(string $command, ?string $sudo_passwd = null): string`

This function runs a given command with sudo privileges. It takes the command and an optional sudo password as inputs and returns the output of the command.

## `mask(string $string, ?int $length = null): string`

This function masks a given string. It takes the string and an optional length as inputs and returns the masked string.

## `pluralize(string $item): string`

This function pluralizes a given string. It takes a string as an input and returns the plural form of the string.

## `git_config(string $key): ?string`

This function retrieves a given Git configuration value. It takes the configuration key as an input and returns the configuration value.

## `unzip(string $file, ?string $destination_path = null): bool`

This function unzips a given zip file. It takes the path of the zip file and an optional destination path as inputs and returns `true` if the operation was successful, `false` otherwise.

## `find_env(): string`

This function finds the environment file in the current directory. It returns the name of the environment file.

## `replace(string $string): string`

This function replaces placeholders in a given string. It takes a string as an input and returns the string with the placeholders replaced.

## `is_cosmic(): bool`

This function checks if the current directory is a Cosmic project. It returns `true` if it is, `false` otherwise.

## `randomize(int $length): string`

This function generates a random string of a given length. It takes the length as an input and returns the random string.

## `termwindize(string $message): string`

This function converts a given string to Termwind syntax. It takes a string as an input and returns the Termwind version of the string.

## `cypher(string $plain, string $key): string`

This function encrypts a given string using a given key. It takes the string and the key as inputs and returns the encrypted string.

## `decipher(string $cipher_text, string $key): string`

This function decrypts a given string using a given key. It takes the encrypted string and the key as inputs and returns the decrypted string.

## `human_filesize(int $bytes, int $precision = 2): string`

This function converts a given number of bytes to a human-readable file size. It takes the number of bytes and an optional precision as inputs and returns the human-readable file size.
