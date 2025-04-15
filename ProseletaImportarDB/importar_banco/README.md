# Importar Banco - Script de Automacao

Este script facilita a importacao de um banco de dados a partir de uma URL SQL. Ele deve ser configurado corretamente para ser executado de qualquer lugar no terminal.

## 📌 Instalacao

Para instalar o script e torná-lo acessível globalmente, siga os passos abaixo:

```bash
chmod +x importa-banco.sh
sudo cp importa-banco.sh /usr/local/bin/importarbanco
```

Isso permite que o script seja chamado apenas digitando `importarbanco` no terminal.

## 🚀 Uso

Para executar o script, forneça os três parâmetros obrigatórios:

```bash
importarbanco <URL_SQL> <Nome_Banco> <ID_Cliente>
```

### 📌 Exemplo de Uso

```bash
importarbanco https://meuservidor.com/dump.sql meu_banco 123
```

Se algum parâmetro estiver faltando, o script exibirá uma mensagem de erro explicando o uso correto.

## 🔧 Requisitos

- Bash instalado no sistema
- Permissão para copiar arquivos para `/usr/local/bin/`

## 📝 Observações

- Caso o comando `importarbanco` não seja reconhecido imediatamente, tente reiniciar o terminal ou rodar:
  ```bash
  hash -r
  ```
