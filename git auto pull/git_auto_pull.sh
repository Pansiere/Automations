#!/bin/bash

mv /tmp/Automations/git\ auto\ pull/.git_check_updates.sh  .
mv /tmp/Automations/git\ auto\ pull/.git_pull.sh .
chmod +x .git_check_updates.sh
chmod +x .git_pull.sh
rm -rf /tmp/Automations

echo "Configuracoes concluidas com sucesso. #Pansiere"
