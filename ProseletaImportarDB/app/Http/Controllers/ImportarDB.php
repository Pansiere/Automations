<?php

namespace App\Http\Controllers;

use App\Services\ImportarDBService;
use GuzzleHttp\{Client, Cookie\CookieJar, Exception\GuzzleException};

class ImportarDB extends Controller
{
    private ImportarDBService $service;

    public function __construct()
    {
        $this->service = new ImportarDBService();
    }

    public function login()
    {
        $cookieJar = new CookieJar();

        $client = new Client([
            'timeout' => 15,
            'verify' => false,
            'cookies' => $cookieJar,
            'decode_content' => false,
            'allow_redirects' => false
        ]);

        // GET incial para superadmin
        try {
            $response = $client->get('https://ps-adm-101.selecao.net.brhahaha/superadmin/auth/login');
            echo "---- GET inicial para /superadmin ----\n";
            echo "--------------- Sucesso --------------\n\n";

            echo "Status Code:\n";
            echo $response->getStatusCode() . "\n\n";

            $this->service->debugarResponse($response);

        } catch (GuzzleException $e) {
            echo "--- GET inicial para /superadmin ---\n\n";
            echo "--------------- Erro ---------------\n";

            echo "Erro:\n";
            echo $e->getMessage();

            exit();
        }

        // POST para superadmin/api/auth/login
        try {
            $this->service->obterCredenciaisLogin();

            $response = $client->post(
                'https://ps-adm-101.selecao.net.br/superadmin/api/auth/login',
                [
                    'form_params' => [
                        'email'    => getenv('EMAIL'),
                        'password' => getenv('PASSWORD')
                    ]
                ]
            );
            $token = json_decode($response->getBody()->getContents())->token;

            echo "--- POST para /superadmin/api/auth/login ---\n";
            echo "------------------ Sucesso -----------------\n\n";

            echo "Status Code:\n";
            echo $response->getStatusCode() . "\n\n";

            $this->service->debugarResponse($response);

        } catch (GuzzleException $e) {
            echo "--- POST para /superadmin/api/auth/login ---\n";
            echo "-------------------- Erro ------------------\n\n";

            echo "Erro:\n";
            echo $e->getMessage() . "\n";

            echo "Credenciais:\n";
            echo 'Email: ' . getenv('EMAIL') . "\n";
            echo 'Senha: ' . getenv('PASSWORD');

            // Preciso fazer isso rodar...
            $this->service->obterCredenciaisLogin(true);

            exit();
        }

        // POST para superadmin/api/auth/verificar2fa
        $codigo = $this->service->obterCodigo2fa();
        while (true) {
            try {
                $response = $client->post(
                    'https://ps-adm-101.selecao.net.br/superadmin/api/auth/verificar2fa',
                    [
                        'headers' => [
                            'Authorization' => 'Token ' . $token,
                            'Accept'        => 'application/json'
                        ],
                        'form_params' => ['codigo' => $codigo]
                        // 'form_params' => ['codigo' => [1, 2, 3, 4, 5]]
                    ]
                );
                echo "--- POST para /superadmin/superadmin/api/auth/verificar2fa ---\n";
                echo "----------------------- Sucesso ----------------------\n\n";

                echo "Status Code:\n";
                echo $response->getStatusCode() . "\n\n";

                $this->service->debugarResponse($response);

            } catch (GuzzleException $e) {
                echo "--- POST para /superadmin/superadmin/api/auth/verificar2fa ---\n";
                echo "-------------------- Erro ------------------\n\n";

                $errorMessage = $e->getMessage();
                echo "Erro:\n";
                echo "$errorMessage\n";

                if (strpos($errorMessage, '420 unknown status') !== false &&
                    strpos($errorMessage, 'código inválido') !== false) {

                    // Solicita um novo código ao usuário
                    $codigo = $this->service->obterCodigo2fa();
                } else {
                    // Se o erro não for de código inválido, interrompe a execução
                    exit();
                }
            }
        }
        exit('rodou');
    }

    public function opcoesDB()
    {
    	// GET https://ps-adm-101.selecao.net.br/superadmin/api/homologacao/servidores/options
    }
}
