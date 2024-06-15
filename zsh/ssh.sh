#!/bin/bash

cd ~/

git clone git@github.com:Pansiere/ssh.git

rm ~/.ssh/authorized_keys
mv ~/ssh/authorized_keys ~/.ssh/
rm -rf Automations
rm -rf ssh

echo "Pansiere - Configuracoes concluidas com sucesso. Reinicie o terminal para aplicar."
