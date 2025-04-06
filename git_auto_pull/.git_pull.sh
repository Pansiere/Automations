#!/bin/bash

BASE_DIR="$PWD"

UPDATE_FILE="$BASE_DIR/.available_pull"

if [ -f "$UPDATE_FILE" ]; then
    
    while IFS= read -r REPO_DIR; do
        
        if [ -n "$REPO_DIR" ]; then
            echo "Realizando git pull no repositório: $REPO_DIR"
            
            cd "$REPO_DIR"
            
            if [ -d ".git" ]; then
                git pull
                echo "git pull executado no repositório: $REPO_DIR"
            else
                echo "Não é um repositório git: $REPO_DIR"
            fi
            
            echo
        fi
    done < "$UPDATE_FILE"
else
    echo "Nenhuma atualização disponível. O arquivo $UPDATE_FILE não existe."
fi
