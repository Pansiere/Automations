#!/bin/bash

cd ~/

# Atualiza pacotes e instala programas necessários
sudo apt update
sudo apt install eza vim neofetch zsh curl git -y

# Altera o shell padrão para Zsh (caso o zsh esteja instalado corretamente)
if command -v zsh >/dev/null 2>&1; then
    sudo chsh -s $(which zsh) $(whoami)
else
    echo "Erro: zsh não foi instalado corretamente."
    exit 1
fi

# Instala o Oh My Zsh
sh -c "$(curl -fsSL https://raw.githubusercontent.com/ohmyzsh/ohmyzsh/master/tools/install.sh)"

# Instala o Zinit
bash -c "$(curl --fail --show-error --silent --location https://raw.githubusercontent.com/zdharma-continuum/zinit/HEAD/scripts/install.sh)"

# Clona os plugins do Zsh
ZSH_CUSTOM=${ZSH_CUSTOM:-~/.oh-my-zsh/custom}/plugins

# Garante que o diretório de plugins exista
mkdir -p $ZSH_CUSTOM

# Função para clonar ou atualizar repositórios
clone_or_update() {
    local repo_url=$1
    local target_dir=$2
    
    if [ -d "$target_dir" ]; then
        echo "Diretório $target_dir já existe, atualizando repositório."
        cd "$target_dir" && git pull origin master
    else
        git clone "$repo_url" "$target_dir"
    fi
}

clone_or_update https://github.com/zsh-users/zsh-autosuggestions $ZSH_CUSTOM/zsh-autosuggestions
clone_or_update https://github.com/zsh-users/zsh-syntax-highlighting.git $ZSH_CUSTOM/zsh-syntax-highlighting
clone_or_update https://github.com/zdharma-continuum/fast-syntax-highlighting.git $ZSH_CUSTOM/fast-syntax-highlighting
clone_or_update https://github.com/marlonrichert/zsh-autocomplete.git $ZSH_CUSTOM/zsh-autocomplete

# Configura o .zshrc
cd ~/
rm -f .zshrc
mv ~/Automations/zsh/.zshrc ~/.zshrc
rm -rf Automations

echo "====================================================="
echo "======= Configurações concluídas com sucesso! ======="
echo "====================================================="
echo "========== Reinicie o terminal para aplicar ========="
echo "====================================================="
echo "====== Feito por João Pedro Vicente Pansiere ========"
echo "================ Obrigado por usar! ================="
echo "====================================================="
