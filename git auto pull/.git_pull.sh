#!/bin/bash

# Define BASE_DIR como o diretório atual
BASE_DIR="$PWD"

# Define o arquivo oculto para armazenar os diretórios com atualizações
UPDATE_FILE="$BASE_DIR/.available_pull"

# Verifica se o arquivo de atualizações existe
if [ -f "$UPDATE_FILE" ]; then
    # Lê cada linha do arquivo de atualizações
    while IFS= read -r REPO_DIR; do
        # Verifica se a linha não está vazia
        if [ -n "$REPO_DIR" ]; then
            echo "Realizando git pull no repositório: $REPO_DIR"
            
            # Navegar até o diretório do repositório
            cd "$REPO_DIR"
            
            # Verificar se é um repositório git
            if [ -d ".git" ]; then
                git pull
                echo "git pull executado no repositório: $REPO_DIR"
            else
                echo "Não é um repositório git: $REPO_DIR"
            fi
            
            # Adiciona uma quebra de linha
            echo
        fi
    done < "$UPDATE_FILE"
else
    echo "Nenhuma atualização disponível. O arquivo $UPDATE_FILE não existe."
fi
