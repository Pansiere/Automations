#!/bin/bash

cd ~/

git clone https://github.com/Pansiere/Automations.git

rm ~/.tmux.conf
mv ~/Automations/zsh/.tmux.conf ~/.tmux.conf
rm -rf Automations

echo "Pansiere - Configuracao concluida. Reinicie o terminal para aplicar."
