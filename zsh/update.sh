#!/bin/bash

cd ~/

git clone https://github.com/Pansiere/Automations.git

rm .zshrc
mv ~/Automations/zsh/.zshrc ~/.zshrc
rm -rf Automations

echo "Pansiere - Configuracoes concluidas com sucesso. Reinicie o terminal para aplicar."
