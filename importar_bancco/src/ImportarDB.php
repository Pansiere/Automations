<?php

namespace Importar;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

class ImportarDB
{
    /**
     * Importa o banco de dados para o site IDCAP.
     *
     * Para funcionar, é necessário ter um arquivo .env com as variáveis de ambiente DB_HOST, DB_USER, DB_PASS e DB_NAME.
     * O arquivo .env deve estar na pasta raiz do projeto.
     *
     * A execução desse script fará o login no site IDCAP com as credenciais informadas e, em seguida, fará uma requisição POST
     * para o endpoint superadmin/painel/ com um JSON contendo os dados a serem importados.
     *
     * @return void
     */
    public function importar()
    {
        $this->obterCredenciais();

        // Credenciais de login
        $email = '';
        $password = '';

        // URLs dos endpoints
        $loginUrl = "https://idcap.org.br/superadmin";
        $painelUrl = "https://idcap.org.br/superadmin/painel/";

        // Configura o CookieJar para manter os cookies de sessão
        $cookieJar = new CookieJar();
        $client = new Client([
            'cookies' => $cookieJar,
        ]);

        // 1. Faz a requisição GET para obter os cookies iniciais
        try {
            $responseGet = $client->get($loginUrl);
        } catch (\Exception $e) {
            echo "Erro na requisição GET: " . $e->getMessage() . "\n";
            exit(1);
        }

        // 2. Extrai o XSRF-TOKEN dos cookies
        $xsrf_token = null;
        foreach ($cookieJar->toArray() as $cookie) {
            if ($cookie['Name'] === 'XSRF-TOKEN') {
                $xsrf_token = $cookie['Value'];
                break;
            }
        }

        if (!$xsrf_token) {
            echo "Não foi possível extrair o XSRF-TOKEN.\n";
            exit(1);
        }

        // 3. Realiza o login com uma requisição POST
        try {
            $responsePost = $client->post($loginUrl, [
                'headers' => [
                    'X-XSRF-TOKEN' => $xsrf_token,
                ],
                'form_params' => [
                    'email'    => $email,
                    'password' => $password,
                ],
            ]);
        } catch (\Exception $e) {
            echo "Erro na requisição POST de login: " . $e->getMessage() . "\n";
            exit(1);
        }

        // Verifica o sucesso do login
        $statusCode = $responsePost->getStatusCode();
        echo "Status da requisição de login: $statusCode\n";

        if ($statusCode === 200) {
            echo "\nLogin realizado com sucesso!\n";

            $codigoData = $this->getCodigoData();

            // 4. Faz a requisição POST para superadmin/painel/ com o JSON
            try {
                $responsePainelPost = $client->post($painelUrl, [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'X-XSRF-TOKEN' => $xsrf_token, // Incluído caso o servidor exija
                    ],
                    'json' => $codigoData,
                ]);

                $statusPainelPost = $responsePainelPost->getStatusCode();
                if ($statusPainelPost === 200 || $statusPainelPost === 201) {
                    echo "POST para 'superadmin/painel/' bem-sucedido!\n";
                    $conteudo = $responsePainelPost->getBody()->getContents();
                    echo "Resposta do servidor:\n";
                    echo $conteudo . "\n";
                } else {
                    echo "Erro ao fazer POST para 'superadmin/painel/'. Código HTTP: $statusPainelPost\n";
                }
            } catch (\Exception $e) {
                echo "Erro ao fazer POST para 'superadmin/painel/': " . $e->getMessage() . "\n";
            }
        } else {
            $body = $responsePost->getBody()->getContents();
            echo "\nErro ao realizar o login. Código HTTP: $statusCode\n";
            echo $body;
        }
    }

    /**
     * Lê o arquivo .env e define as variáveis de ambiente para posterior uso.
     *
     * @return array As credenciais de acesso ao banco de dados.
     * Contém as chaves 'host', 'user', 'pass' e 'name'.
     */
    private function obterCredenciais(): array
    {
        //Fazer uma tratativa aqui, para caso o arquivo estiver vazio, ele pediar para que o usuario digite o email e senha

        // Caminho para o arquivo .env
        $envFile = __DIR__ . '/../.env';

        // Verifica se o arquivo existe
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
        } else {
            echo "Arquivo .env não encontrado.";
        }

        // Acessa as variáveis com getenv()
        $email = getenv('EMAIL');
        $password = getenv('PASSWORD');

        if ($email && $password) {
            echo "Alguma variável não foi definida.";
        }

        return [
            'host' => $email,
            'name' => $password
        ];
    }

    /**
     * Retorna os dados a serem enviados no POST para a rota 'superadmin/painel/'.
     *
     * @return array Os dados a serem enviados, contendo um array 'codigo' com os valores.
     */
    private function getCodigoData()
    {
        // Dados a serem enviados no POST
        $codigoData = [
            'codigo' => [
                "2",
                "1",
                "3",
                "2",
                "1",
                "3"
            ]
        ];

        return $codigoData;
    }
}
