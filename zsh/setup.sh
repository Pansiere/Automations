#!/bin/bash

cd ~/

# Atualiza pacotes e instala programas necessários
apt update
apt install sudo -y
apt install exa -y
apt install vim -y
apt install neofetch -y
apt install zsh -y
apt install curl git -y

# Altera o shell padrão para Zsh
chsh -s $(which zsh)

# Instala o Oh My Zsh
y | sh -c "$(curl -fsSL https://raw.githubusercontent.com/ohmyzsh/ohmyzsh/master/tools/install.sh)"

# Instala o Zinit
y | bash -c "$(curl --fail --show-error --silent --location https://raw.githubusercontent.com/zdharma-continuum/zinit/HEAD/scripts/install.sh)"

# Clona os plugins do Zsh
ZSH_CUSTOM=${ZSH_CUSTOM:-~/.oh-my-zsh/custom}/plugins

# Garante que o diretório de plugins exista
mkdir -p $ZSH_CUSTOM

# Clona ou atualiza os repositórios
clone_or_update() {
    local repo_url=$1
    local target_dir=$2
    
    if [ -d "$target_dir" ]; then
        echo "Directory $target_dir already exists, updating repository."
        cd "$target_dir" && git pull origin master
    else
        git clone "$repo_url" "$target_dir"
    fi
}

clone_or_update https://github.com/zsh-users/zsh-autosuggestions $ZSH_CUSTOM/zsh-autosuggestions
clone_or_update https://github.com/zsh-users/zsh-syntax-highlighting.git $ZSH_CUSTOM/zsh-syntax-highlighting
clone_or_update https://github.com/zdharma-continuum/fast-syntax-highlighting.git $ZSH_CUSTOM/fast-syntax-highlighting
clone_or_update https://github.com/marlonrichert/zsh-autocomplete.git $ZSH_CUSTOM/zsh-autocomplete

# Configura o .zshrc
cd ~/
rm -f .zshrc
mv ~/Automations/zsh/.zshrc ~/.zshrc
rm -rf Automations

echo "Pansiere - Configurações concluídas com sucesso. Reinicie o terminal para aplicar."
