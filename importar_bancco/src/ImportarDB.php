<?php

namespace Importar;

use GuzzleHttp\{Client, Cookie\CookieJar, Exception\GuzzleException};

class ImportarDB
{
    public function importar()
    {
        try {
            $cookieJar = new CookieJar();

            $client = new Client([
                'timeout' => 15,
                'verify' => false,
                'cookies' => $cookieJar,
                'allow_redirects' => true,
            ]);

            $response = $client->request('GET', 'https://idcap.org.br/superadmin');

            echo "Status Code: " . $response->getStatusCode() . "\n";
        } catch (GuzzleException $e) {
            echo "Erro na requisição: " . $e->getMessage() . "\n";
            exit();
        }

        var_dump($response, 123, $cookieJar->toArray());

        exit();
        // 





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

        // Credenciais de login
        $this->obterCredenciaisLogin();
        $email = getenv('EMAIL');
        $password = getenv('PASSWORD');
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
