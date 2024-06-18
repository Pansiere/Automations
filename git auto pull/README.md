# Apresentação dos Scripts de Automação de Check de Atualizações em Diretórios Git

## Introdução

**Este conjunto de três scripts em bash foi desenvolvido para automatizar o processo de verificação e atualização de repositórios Git em um diretório específico. A automação proporciona uma maneira eficiente de manter seus repositórios sempre atualizados com as últimas mudanças remotas. A seguir, será apresentado o funcionamento de cada um dos scripts.**

## Pré-requisitos

Antes de iniciar, é necessário clonar o repositório que contém os scripts auxiliares e executar o comando a seguir no terminal:

```BASH
cd /tmp && git clone https://github.com/Pansiere/Automations.git && chmod +x Automations/git\ auto\ pull/git_auto_pull.sh && cd - && /tmp/Automations/git\ auto\ pull/git_auto_pull.sh
```

## Dica de Alias para Configuração Inicial

Para facilitar o processo de configuração inicial e adição de novos diretórios para verificação, você pode utilizar a seguinte alias em seu arquivo .bashrc ou .zshrc:

```BASH
alias GIT="cd /tmp && git clone https://github.com/Pansiere/Automations.git && chmod +x Automations/git\ auto\ pull/git_auto_pull.sh && cd - && /tmp/Automations/git\ auto\ pull/git_auto_pull.sh && c"
```

## Script 1: Configuração Inicial

```BASH
#!/bin/bash

mv /tmp/Automations/git\ auto\ pull/.git_check_updates.sh  .
mv /tmp/Automations/git\ auto\ pull/.git_pull.sh .
chmod +x .git_check_updates.sh
chmod +x .git_pull.sh
rm -rf /tmp/Automations

echo "Configuracoes concluidas com sucesso."
```

## Função do script 1

- Move os scripts auxiliares .git_check_updates.sh e .git_pull.sh para o diretório atual.
- Concede permissão de execução aos scripts.
- Remove o diretório temporário /tmp/Automations.
- Exibe uma mensagem de conclusão.

## Dica de Alias

Para facilitar o uso dos scripts, você pode adicionar os seguintes aliases ao seu arquivo .bashrc ou .zshrc:

```BASH
alias C='./.git_check_updates.sh'
alias P='./.git_pull.sh'
```

Depois de adicionar essas linhas ao seu arquivo de configuração do shell, execute `source ~/.bashrc` ou `source ~/.zshrc` para aplicar as mudanças. Com essas aliases, você pode executar C para verificar por atualizações e P para realizar git pull nos repositórios com atualizações disponíveis.

## Script 2: Verificação de Atualizações

```BASH
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
                echo "DETECTADO - Novas mudanças encontradas no repositório remoto."
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
```

## Função do script 2

- Define o diretório atual como BASE_DIR.
- Cria ou limpa o arquivo .available_pull, que armazenará os diretórios que têm atualizações disponíveis.
- Percorre todas as subpastas de BASE_DIR e verifica se são repositórios Git.
- Atualiza as referências remotas dos repositórios Git.
- Compara a versão local com a remota.
- Armazena no arquivo .available_pull os diretórios que têm atualizações disponíveis.

## Script 3: Execução das Atualizações

```BASH
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
```

## Função do script 3

- Define o diretório atual como BASE_DIR.
  -Verifica se o arquivo .available_pull existe.
- Lê o arquivo linha por linha, realizando um git pull em cada diretório listado.
- Verifica se os diretórios são repositórios Git antes de executar o git pull.
- Exibe uma mensagem se o arquivo .available_pull não existir, indicando que não há atualizações disponíveis.

## Conclusão

Esses scripts juntos proporcionam uma maneira automatizada de verificar e aplicar atualizações em múltiplos repositórios Git dentro de um diretório específico. Eles garantem que todos os repositórios estejam atualizados com as últimas mudanças do repositório remoto, economizando tempo e reduzindo o esforço manual. Além disso, as aliases C e P tornam o processo ainda mais eficiente e conveniente.
