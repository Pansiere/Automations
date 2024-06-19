#!/bin/bash

rm ~/.ssh/authorized_keys
mv /tmp/ssh/authorized_keys ~/.ssh/
rm -rf /tpm/Automations
rm -rf /tmp/ssh

echo "Configuracoes concluidas com sucesso. #Pansiere"
