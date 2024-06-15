#!/bin/bash

cd ~/

rm ~/.tmux.conf
mv ~/Automations/zsh/.tmux.conf ~/.tmux.conf
rm -rf Automations

echo "Pansiere - Configuracoes concluidas com sucesso. Reinicie o terminal para aplicar."
