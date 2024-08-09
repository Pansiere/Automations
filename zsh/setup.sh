#!/bin/bash

cd ~/

apt update
apt install sudo -y
apt install exa -y
apt install vim -y
apt install neofetch -y
apt install zsh -y
apt install curl git -y

chsh -s $(which zsh)

y | sh -c "$(curl -fsSL https://raw.githubusercontent.com/ohmyzsh/ohmyzsh/master/tools/install.sh)"
y | bash -c "$(curl --fail --show-error --silent --location https://raw.githubusercontent.com/zdharma-continuum/zinit/HEAD/scripts/install.sh)"

git clone https://github.com/zsh-users/zsh-autosuggestions ${ZSH_CUSTOM:-~/.oh-my-zsh/custom}/plugins/zsh-autosuggestions
git clone https://github.com/zsh-users/zsh-syntax-highlighting.git $ZSH_CUSTOM/plugins/zsh-syntax-highlighting
git clone https://github.com/zdharma-continuum/fast-syntax-highlighting.git ${ZSH_CUSTOM:-$HOME/.oh-my-zsh/custom}/plugins/fast-syntax-highlighting
git clone --depth 1 -- https://github.com/marlonrichert/zsh-autocomplete.git $ZSH_CUSTOM/plugins/zsh-autocomplete

cd ~/
rm .zshrc
mv /Automations/zsh/.zshrc ~/.zshrc
rm -rf Automations

echo "Pansiere - Configuracoes concluidas com sucesso. Reinicie o terminal para aplicar."
