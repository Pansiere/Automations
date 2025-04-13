<?php

namespace Importar;

use GuzzleHttp\{Client, Cookie\CookieJar, Exception\GuzzleException};

class ImportarDB
{
    public function importar()
    {
        $cookieJar = new CookieJar();

        $client = new Client([
            'timeout' => 15,
            'verify' => false,
            'cookies' => $cookieJar,
            'decode_content' => false,
            'allow_redirects' => false
        ]);

        // Faz a requisição GET para superadmin
        try {
            $response = $client->get('https://idcap.org.br/superadmin');

            echo "\n--- Requisição GET para superadmin inicial ---\n";
            echo "Status Code: " . $response->getStatusCode() . "\n";
        } catch (GuzzleException $e) {
            echo "\n--- Erro na requisição GET para superadmin inicial ---\n";
            echo "\nErro na requisição: " . $e->getMessage() . "\n";
            exit();
        }


        exit();
        // Faz a requisição POST para superadmin/api/auth/login
        try {
            $cookieJar = new CookieJar();

            $client = new Client([
                'timeout' => 15,
                'verify' => false,
                'cookies' => $cookieJar,
                'decode_content' => false,
                'allow_redirects' => false
            ]);

            $this->obterCredenciaisLogin();


            $postResponse = $client->post(
                'https://idcap.org.br/superadmin/api/auth/login',
                [
                    'form_params' => [
                        'email'    => getenv('EMAIL'),
                        'password' => getenv('PASSWORD')
                    ],
                ]
            );
            echo "POST Status Code: " . $postResponse->getStatusCode() . "\n";
            echo "POST Response Body:\n" . $postResponse->getBody()->getContents() . "\n";

            echo "\n--- Conteúdo completo da resposta de erro ---\n";
            $response = $client->get('https://idcap.org.br/superadmin');
            echo "POST Status Code: " . $response->getStatusCode() . "\n";
            echo "POST Response Body:\n" . $response->getBody()->getContents() . "\n";

            // https://idcap.org.br/superadmin/api/auth/verificar2fa
        } catch (GuzzleException $e) {
            echo "\n--- Conteúdo completo da resposta de erro ---\n";
            $response = $e->getResponse();
            $body = $response->getBody()->getContents();
            echo $body . "\n";
            echo $e->getCode() . "\n";
            echo $e->getMessage() . "\n";
            exit();
        }

        var_dump(getenv('EMAIL'));
        var_dump(getenv('PASSWORD'));
        exit('rodou');














































        // Verifica o sucesso do login
        $statusCode = $responsePost->getStatusCode();
        echo "Status da requisição de login: $statusCode\n";

        if ($statusCode === 200) {
            echo "\nLogin realizado com sucesso!\n";

            $codigoData = $this->getCodigoData();

            // 4. Faz a requisição POST para superadmin/painel/ com o JSON
            try {
                $painelUrl = "https://idcap.org.br/superadmin/painel/";

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
     * Carrega as credenciais de login a partir de um arquivo .env e as define como variáveis de ambiente.
     *
     * O método verifica se o arquivo .env existe no diretório raiz do projeto. Se existir, lê o arquivo
     * linha por linha, ignorando comentários e linhas inválidas, e define as variáveis de ambiente correspondentes.
     * Caso as variáveis de ambiente 'EMAIL' e 'PASSWORD' não estejam definidas ou sejam inválidas, solicita
     * ao usuário que insira manualmente essas informações.
     *
     * @return void
     */
    private function obterCredenciaisLogin(): void
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
