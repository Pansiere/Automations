#!/bin/bash

cd ~/

rm .zshrc
mv ~/Automations/zsh/.zshrc ~/.zshrc
rm -rf Automations

echo "Pansiere - Configuracoes concluidas com sucesso. Reinicie o terminal para aplicar."
