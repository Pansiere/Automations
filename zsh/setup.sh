#!/bin/bash

cd ~/
rm Automations

sudo apt install update -y
sudo apt install sudo -y
sudo apt install exa -y
sudo apt install vim -y
sudo apt install neofetch -y
sudo apt install zsh -y
sudo apt install curl git -y

chsh -s $(which zsh)

sudo y | sh -c "$(curl -fsSL https://raw.githubusercontent.com/ohmyzsh/ohmyzsh/master/tools/install.sh)"
sudo y | bash -c "$(curl --fail --show-error --silent --location https://raw.githubusercontent.com/zdharma-continuum/zinit/HEAD/scripts/install.sh)"

sudo git clone https://github.com/zsh-users/zsh-autosuggestions ${ZSH_CUSTOM:-~/.oh-my-zsh/custom}/plugins/zsh-autosuggestions

sudo git clone https://github.com/Pansiere/Automations.git #Setup das minhas alias
rm .zshrc
mv ~/Automations/zsh/.zshrc ~/.zshrc
rm -rf Automations

echo "Pansiere - Configuracao concluida. Reinicie o terminal para aplicar."
