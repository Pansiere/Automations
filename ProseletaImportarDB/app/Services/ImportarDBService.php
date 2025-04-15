<?php

namespace App\Services;

class ImportarDBService
{
    /**
     * Debuga uma resposta, exibindo seu header e conteúdo no console.
     *
     * @param \GuzzleHttp\Psr7\Response $response A resposta a ser debugada.
     */
    public static function debugarResponse($response): void
    {
        echo "Header:\n";
        echo json_encode($response->getHeaders()) . "\n\n";

        echo "Body:\n";
        echo $response->getBody()->getContents() . "\n\n";
    }

    /**
     * Carrega as credenciais de login a partir de um arquivo .env e as define como variáveis de ambiente.
     *
     * O método verifica se o arquivo .env existe no diretório raiz do projeto. Se existir, lê o arquivo
     * linha por linha, ignorando comentários e linhas inválidas, e define as variáveis de ambiente correspondentes.
     * Caso as variáveis de ambiente 'EMAIL' e 'PASSWORD' não estejam definidas ou sejam inválidas, solicita
     * ao usuário que insira manualmente essas informações.
     *
     * @return void
     */
    public static function obterCredenciaisLogin($solicitar = false): void
    {
        $envFile = __DIR__ . '/../.env';

        if (file_exists($envFile)) {
            // Lê o arquivo linha por linha
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                // Ignora comentários e linhas inválidas
                if (str_starts_with($line, '#') || strpos($line, '=') === false) {
                    continue;
                }
                // Divide a linha em chave e valor
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);
                // Define a variável de ambiente
                putenv("$key=$value");
            }
        }

        // Verifica se as variáveis de ambiente foram definidas
        while (!getenv('EMAIL') || !getenv('PASSWORD')) {
            echo "\nEMAIL ou SENHA não definidos no arquivo .env ou inválidos\n";

            echo "Digite seu email: ";
            putenv("EMAIL=" . trim(fgets(STDIN)));

            echo "Digite sua senha: ";
            putenv("PASSWORD=" . trim(fgets(STDIN)));
        }
    }

    /**
     * Retorna os dados a serem enviados no POST para a rota 'superadmin/painel/'.
     *
     * @return array Os dados a serem enviados, contendo um array 'codigo' com os valores.
     */
    public static function obterCodigo2fa(): array
    {
        // Preciso desenvolver uma função que pede os 6 numeros de uma vez só,
        // e em seguinda pega cada numero e coloque um um indice, para retornar
        // um array com os 6 indices e seus numeros...

        die('Preciso desenvolver uma função que pede os 6 numeros de uma vez…');

        $codigoData = [];

        return $codigoData;
    }
}
