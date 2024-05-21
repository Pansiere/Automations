#!/bin/bash

cd ~/

git clone https://github.com/pansiere/zshrc

mv ~/zshrc/.tmux.conf ~/.tmux.conf
rm -rf zshrc

echo "Pansiere - Configuracao concluida. Reinicie o terminal para aplicar."
