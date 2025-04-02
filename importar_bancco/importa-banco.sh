#!/bin/sh
# PRECISO FAZER UMA MELHORIA AQUI, DEIXAR A URL COM DEFAULT "" E DEIXAR COMO ULTIMO PARAMETRO, PARA NAO PRECISAR PASSAR '""' FAZIA NA HORA DE RODAR O SCRIPT

# Verificar se os parâmetros foram fornecidos
if [ "$#" -ne 3 ]; then
  echo "Erro: Número incorreto de parâmetros."
  echo "Uso correto:"
  echo "  $0 <URL_SQL> <Nome_Banco> <ID_Cliente>"
  echo ""
  echo "Exemplo:"
  echo "  $0 "" proseleta 155"
  echo ""
  echo "Exemplo:"
  echo "  $0 https://exemplo.com/banco meu_banco 123"
  exit 1
fi


URL_SQL="$1"
NOME_BANCO="$2"
ID_CLIENTE="$3"
FILENAME=$(basename "$URL_SQL" | cut -d'?' -f1)
SQL_FILE="${FILENAME%.gz}"

# Baixar o arquivo SQL compactado
echo "Baixando o arquivo SQL de $URL_SQL..."
wget "$URL_SQL" -O "$FILENAME"

# Descompactar o arquivo
echo "Descompactando o arquivo $FILENAME..."
gunzip "$FILENAME"

# Reduzir o arquivo SQL usando o script fornecido
echo "Reduzindo o arquivo $SQL_FILE..."
reduced_file="reduced_$SQL_FILE"

tables_to_skip="
tbprobanca_provasv2
tbprobanca_provasv2_matrizes
tbprobanca_provas_questoes_alternativas
tbconcursos_etapas_cartoesresposta
tbinscricoes_boletos
tblogs_acoes
tblog_erros_registro_pagamento
tbprobanca_provasv2_cadernos
tbmarketing_visitantes_fontes
tbmarketing_visitantes_origens
tbmarketing_campanhas
tbmarketing_campanhas_destinatarios
"

# Construir expressão para excluir linhas das tabelas indesejadas
exclude_expr=$(printf "/INSERT INTO \`%s\`/d;" $tables_to_skip)

sed -e "$exclude_expr" -e 's/utf8mb4_0900_ai_ci/utf8mb4_general_ci/g' "$SQL_FILE" > "$reduced_file"

echo "Arquivo reduzido gerado com sucesso: $reduced_file"

# Dropar o banco de dados do cliente
echo "Removendo banco de dados antigo do cliente..."
docker exec -i mysql_ps mysql -uroot -psecret -e "DROP DATABASE IF EXISTS \`${NOME_BANCO}\`;"

# Importar o arquivo reduzido no Docker
echo "Importando o arquivo reduzido no Docker..."
docker exec -i mysql_ps mysql -uroot -psecret -hdatabase < "$reduced_file"

# Executar comandos adicionais no container PHP
echo "Criando cliente no Laravel..."
docker exec -it php_ps bash -c "
  cd v2
  php artisan dev:criar-cliente \"$NOME_BANCO\" \"$ID_CLIENTE\"
"

# Apagar o arquivo .gz e o arquivo reduzido
echo "Limpando arquivos temporários..."
rm "$SQL_FILE"
rm "$reduced_file"

echo "Processo concluído com sucesso!"
