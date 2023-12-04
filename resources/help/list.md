# ⛑️ List Command

The **list** command lists all commands. This is the default command, so if you call **alfred** without 
any arguments, it will display the list of commands.

```sh
/usr/local/bin/cosmic
/usr/local/bin/cosmic list
```

You can also display the commands for a specific namespace:
```sh
/usr/local/bin/cosmic list test
```

You can also output the information in other formats by using the *--format* option:
```sh
/usr/local/bin/cosmic list --format=xml
```

It's also possible to get raw list of commands (useful for embedding command runner):
```sh
/usr/local/bin/cosmic list --raw
```
