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

        // GET incial para superadmin
        try {
            $response = $client->get('https://ps-adm-101.selecao.net.br/superadmin/auth/login');
            echo "---- GET inicial para /superadmin ----\n";
            echo "--------------- Sucesso --------------\n\n";

            echo "Status Code:\n";
            echo $response->getStatusCode() . "\n\n";

            $this->debugarResponse($response);

        } catch (GuzzleException $e) {
            echo "--- GET inicial para /superadmin ---\n\n";
            echo "--------------- Erro ---------------\n";

            echo "Erro:\n";
            echo $e->getMessage();

            exit();
        }

        // POST para superadmin/api/auth/login
        try {
            $this->obterCredenciaisLogin();

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

            $this->debugarResponse($response);

        } catch (GuzzleException $e) {
            echo "--- POST para /superadmin/api/auth/login ---\n";
            echo "-------------------- Erro ------------------\n\n";

            echo "Erro:\n";
            echo $e->getMessage() . "\n";

            echo "Credenciais:\n";
            echo 'Email: ' . getenv('EMAIL') . "\n";
            echo 'Senha: ' . getenv('PASSWORD');

            // Preciso fazer isso rodar...
            $this->obterCredenciaisLogin(true);

            exit();
        }

        // POST para superadmin/api/auth/verificar2fa
        $codigo = $this->obterCodigo2fa();
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

                $this->debugarResponse($response);

            } catch (GuzzleException $e) {
                echo "--- POST para /superadmin/superadmin/api/auth/verificar2fa ---\n";
                echo "-------------------- Erro ------------------\n\n";

                $errorMessage = $e->getMessage();
                echo "Erro:\n";
                echo "$errorMessage\n";

                if (strpos($errorMessage, '420 unknown status') !== false &&
                    strpos($errorMessage, 'código inválido') !== false) {

                    // Solicita um novo código ao usuário
                    $codigo = $this->obterCodigo2fa();
                } else {
                    // Se o erro não for de código inválido, interrompe a execução
                    exit();
                }
            }
        }

        exit('rodou');


	// GET https://ps-adm-101.selecao.net.br/superadmin/api/homologacao/servidores/options










































    }

    /**
     * Debuga uma resposta, exibindo seu header e conteúdo no console.
     *
     * @param \GuzzleHttp\Psr7\Response $response A resposta a ser debugada.
     */
    private function debugarResponse($response): void
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
    private function obterCredenciaisLogin($olicitar = false): void
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
    private function obterCodigo2fa(): array
    {
        // Preciso desenvolver uma função que pede os 6 numeros de uma vez só,
        // e em seguinda pega cada numero e coloque um um indice, para retornar
        // um array com os 6 indices e seus numeros...

        die('Preciso desenvolver uma função que pede os 6 numeros de uma vez…');

        $codigoData = [];

        return $codigoData;
    }
}
