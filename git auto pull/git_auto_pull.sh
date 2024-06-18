#!/bin/bash

git clone git@github.com:Pansiere/Automations.git

mv Automations/git\ auto\ pull/.git_check_updates.sh  .
mv Automations/git\ auto\ pull/.git_pull.sh .
rm -rf Automations

echo "Configuracoes concluidas com sucesso. - #Pansiere -"
