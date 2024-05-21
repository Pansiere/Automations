#!/bin/bash

cd ~/

apt install sudo -y
apt install exa -y
apt install vim -y
apt install neofetch -y
sudo apt install zsh -y
apt install curl git -y

chsh -s $(which zsh)

y | sh -c "$(curl -fsSL https://raw.githubusercontent.com/ohmyzsh/ohmyzsh/master/tools/install.sh)"
y | bash -c "$(curl --fail --show-error --silent --location https://raw.githubusercontent.com/zdharma-continuum/zinit/HEAD/scripts/install.sh)"

git clone https://github.com/zsh-users/zsh-autosuggestions ${ZSH_CUSTOM:-~/.oh-my-zsh/custom}/plugins/zsh-autosuggestions
git clone https://github.com/pansiere/zshrc

rm .zshrc
mv ~/zshrc/.zshrc ~/.zshrc
rm -rf zshrc

echo "Pansiere - Configuracao concluida. Reinicie o terminal para aplicar."
