# 🐚 Shell Completion Command


The completion command dumps the shell completion script required
to use shell autocompletion (currently, [bash](https://www.gnu.org/software/bash/), [fish](https://fishshell.com/), [zsh](https://ohmyz.sh/) completion are supported).

## 📦 Static installation

---

Dump the script to a global completion file and restart your shell:

```sh
cosmic completion fish | sudo tee /etc/fish/completions/cosmic.fish
```

Or dump the script to a local file and source it:

```sh

cosmic completion fish > completion.sh
  
# source the file whenever you use the project
source completion.sh
  
# or add this line at the end of your "~/.config/fish/config.fish" file:
source /path/to/completion.sh

```

## 📦 Dynamic installation

---

Add this to the end of your shell configuration file (e.g. **"~/.config/fish/config.fish"**):

```sh
eval "$(/home/diego/Projects/Scalefast/cosmic/cosmic completion fish)"
```
