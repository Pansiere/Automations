#!/bin/bash

# Caminho para o seu repositório
REPO_DIR="/caminho/para/seu/repositorio"

# Navegar até o diretório do repositório
cd $REPO_DIR

# Atualizar a referência remota
git remote update

# Verificar se há novas mudanças no repositório remoto
LOCAL=$(git rev-parse @)
REMOTE=$(git rev-parse @{u})

if [ $LOCAL != $REMOTE ]; then
    echo "Novas mudanças encontradas no repositório remoto. Realizando git pull..."
    git pull
    # Opcional: notificação de que o pull foi realizado
    notify-send "Git Pull" "Atualizações foram puxadas do repositório remoto."
else
    echo "O repositório local está atualizado."
fi
