#!/bin/bash
echo -e "\n################## PROSELETA ##################"
echo -e "######## Script pra importação de DB ##########\n"

# Solicita as credenciais do usuário
# read -p "Digite seu e-mail: " email
# read -sp "Digite sua senha: " password
# echo
email="joaopv@impactaweb.com.br"
password=""

# Define a URL do endpoint
url="https://idcap.org.br/superadmin"

# Faz uma requisição GET para obter os cookies e o HTML da página de login
curl -s -c cookies.txt "$url" > login.html

# Extrai o valor do cookie XSRF-TOKEN do arquivo cookies.txt
xsrf_token=$(grep "XSRF-TOKEN" cookies.txt | awk '{print $7}')

# Faz a requisição POST com os cookies, o XSRF-TOKEN como cabeçalho e as credenciais
status=$(curl -s -o response.txt -w "%{http_code}" -b cookies.txt -X POST "$url" -H "X-XSRF-TOKEN: $xsrf_token" -d "email=$email" -d "password=$password")

# Exibe a resposta do servidor
echo "Status da requisição: $status"
if [ "$status" -eq 200 ]; then
    echo -e "\n\nLogin realizado com sucesso!\n"
else
    cat response.txt
    echo -e "Erro ao realizar o login. Código HTTP: $status\n"
fi

# Remove os arquivos temporários
rm -f cookies.txt login.html response.txt