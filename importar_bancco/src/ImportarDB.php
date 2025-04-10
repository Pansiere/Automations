<?php

namespace ImportarBanco;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

class ImportarDB
{
    public function importar()
    {
        // Credenciais de login
        $email = 'joaopv@impactaweb.com.br';
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
