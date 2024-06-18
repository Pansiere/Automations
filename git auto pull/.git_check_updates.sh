#!/bin/bash

# Define BASE_DIR como o diretório atual
BASE_DIR="$PWD"

# Define o arquivo oculto para armazenar os diretórios com atualizações
UPDATE_FILE="$BASE_DIR/.available_pull"

# Limpa o arquivo de atualizações anteriores, se existir
> "$UPDATE_FILE"

# Percorre todas as subpastas dentro de BASE_DIR
for REPO_DIR in "$BASE_DIR"/*; do
    # Verifica se é um diretório
    if [ -d "$REPO_DIR" ]; then
        echo "Verificando repositório em: $REPO_DIR"
        
        # Navegar até o diretório do repositório
        cd "$REPO_DIR"
        
        # Verificar se é um repositório git
        if [ -d ".git" ]; then
            # Atualizar a referência remota
            git remote update
            
            # Verificar se há novas mudanças no repositório remoto
            LOCAL=$(git rev-parse @)
            REMOTE=$(git rev-parse @{u})
            
            if [ "$LOCAL" != "$REMOTE" ]; then
                echo "DETECTADO - Novas mudanças encontradas no repositório remoto em: $REPO_DIR"
                echo "$REPO_DIR" >> "$UPDATE_FILE"
            else
                echo "O repositório local está atualizado."
            fi
        else
            echo "Não é um repositório git: $REPO_DIR"
        fi
        # Adiciona uma quebra de linha
        echo
    fi
done
