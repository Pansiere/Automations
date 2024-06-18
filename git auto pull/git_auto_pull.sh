#!/bin/bash

mv Automations/git\ auto\ pull/.git_check_updates.sh  .
mv Automations/git\ auto\ pull/.git_pull.sh .
chmod +x .git_check_updates.sh
chmod +x .git_pull.sh
rm -rf Automations

echo "Configuracoes concluidas com sucesso. - #Pansiere -"
