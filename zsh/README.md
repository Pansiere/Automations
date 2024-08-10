# Setup do Ambiente Zsh v1.0.0

## Script de automação para configuração de terminal ZSH

Este script configura automaticamente o ambiente de shell Zsh, instalando e configurando o Oh My Zsh, plugins essenciais e definindo o fuso horário.

## Pré-requisitos

Antes de executar o script, certifique-se de que o sistema atende aos seguintes requisitos:

- **Acesso à Internet:** Necessário para baixar pacotes e clonar repositórios.
- **Permissões de sudo:** O script requer permissões de `sudo` para instalar pacotes.
- **Sudo instalado:** Certifique-se de que o `sudo` esteja instalado no sistema. Caso contrário, instale-o com o seguinte comando:

```bash
apt install sudo -y
```

## [Deboin e Ubuntu] Para instalar basta colar no seu terminal abaixo

```bash
sudo apt update && \
sudo apt install git -y && \
cd ~/ && \
git clone https://github.com/Pansiere/Automations.git && \
cd Automations/zsh && \
sudo chmod +x setup.sh && \
./setup.sh && \
cd
```

## [EM OUTRAS DISTROS] Será necessário adaptar os arquivos
