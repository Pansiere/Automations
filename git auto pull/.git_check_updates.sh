#!/bin/bash

BASE_DIR="$PWD"

UPDATE_FILE="$BASE_DIR/.available_pull"

> "$UPDATE_FILE"

for REPO_DIR in "$BASE_DIR"/*; do

    if [ -d "$REPO_DIR" ]; then
        echo "Verificando repositório em: $REPO_DIR"   
        
        cd "$REPO_DIR"
        
        if [ -d ".git" ]; then
            
            git remote update
            
            LOCAL=$(git rev-parse @)
            REMOTE=$(git rev-parse @{u})
            
            if [ "$LOCAL" != "$REMOTE" ]; then
                echo "DETECTADO - Novas mudanças encontradas no repositório remoto."
                echo "$REPO_DIR" >> "$UPDATE_FILE"
            else
                echo "O repositório local está atualizado."
            fi
        else
            echo "Não é um repositório git: $REPO_DIR"
        fi
        echo
    fi
done
