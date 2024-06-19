#!/bin/bash

if [ -f ~/.ssh/authorized_keys ]; then
    rm ~/.ssh/authorized_keys
fi

if [ -f /tmp/ssh/authorized_keys ]; then
    mv /tmp/ssh/authorized_keys ~/.ssh/
fi

if [ -d /tpm/Automations ]; then
    rm -rf /tpm/Automations
fi
if [ -d /tmp/ssh ]; then
    rm -rf /tmp/ssh
fi

echo "Configurações concluídas com sucesso. #Pansiere"
