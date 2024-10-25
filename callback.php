<?php
include 'config.php';

ini_set('session.cookie_secure', 1);  // Garante que o cookie de sessão só seja enviado via HTTPS
ini_set('session.cookie_httponly', 1); // Protege o cookie contra acesso via JavaScript

session_start();
echo "Sessão ID: " . session_id() . "<br>";
print_r($_SESSION);  // Exibe todas as variáveis armazenadas na sessão

if (isset($_SESSION['code_verifier'])) {
    echo "Code Verifier encontrado: " . $_SESSION['code_verifier'];
} else {
    echo "Nenhum code_verifier encontrado na sessão.";
}

// Verificar se o código foi recebido
if (isset($_GET['code'])) {
    $code = $_GET['code'];

    // Verificar se o code_verifier foi armazenado na sessão
    if (isset($_SESSION['code_verifier'])) {
        $code_verifier = $_SESSION['code_verifier'];
        echo "Code Verifier encontrado: " . $code_verifier; // Para depuração

        // Trocar o código pelo token de acesso
        $token_data = getAccessToken($code, $code_verifier);

        if ($token_data && isset($token_data['access_token'])) {
            $access_token = $token_data['access_token'];
            $id_token = isset($token_data['id_token']) ? $token_data['id_token'] : null;

            // Validar os tokens usando a chave pública do provedor Gov.br
            if (validateToken($access_token, $id_token)) {
                // Obter informações do usuário
                $userinfo = getUserInfo($access_token);

                if ($userinfo) {
                    echo renderUserProfile($userinfo, $id_token);
                } else {
                    echo renderError("Erro ao obter informações do usuário.");
                }
            } else {
                echo renderError("Falha na validação do token.");
            }
        } else {
            echo renderError("Erro ao trocar o código pelo token.");
        }
    } else {
        echo renderError("Nenhum code_verifier encontrado na sessão.");
    }
} else {
    echo renderError("Código de autorização não recebido.");
}

// Função para trocar o código pelo token de acesso
function getAccessToken($code, $code_verifier) {
    $token_url = TOKEN_URL;

    $params = [
        'grant_type' => 'authorization_code',
        'code' => $code,
        'redirect_uri' => REDIRECT_URI,
        'client_id' => CLIENT_ID,
        'code_verifier' => $code_verifier
    ];

    $ch = curl_init($token_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded',
        'Authorization: Basic ' . base64_encode(CLIENT_ID . ':' . CLIENT_SECRET)
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// Função para validar o token utilizando a chave pública do Gov.br
function validateToken($access_token, $id_token) {
    $ch = curl_init('https://sso.staging.acesso.gov.br/jwk');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    $jwk = json_decode($response, true);

    // Aqui você deve implementar a lógica para validar o token, usando a chave pública obtida.
    // Normalmente, isso envolve verificar a assinatura do JWT e seu conteúdo, como expiração.

    return true;  // Simplificado. Implementar a lógica real.
}

// Função para obter as informações do usuário
function getUserInfo($access_token) {
    $ch = curl_init(USERINFO_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $access_token
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// Função para exibir o perfil do usuário
function renderUserProfile($userinfo, $id_token = null) {
    $html = '<h2>Perfil do Usuário</h2>';
    $html .= '<p>Nome: ' . htmlspecialchars($userinfo['name']) . '</p>';
    $html .= '<p>Email: ' . htmlspecialchars($userinfo['email']) . '</p>';

    if ($id_token) {
        $html .= '<p>ID Token: ' . htmlspecialchars($id_token) . '</p>';
    }

    return $html;
}

// Função para exibir erros
function renderError($message) {
    return '<p style="color: red;">' . htmlspecialchars($message) . '</p>';
}

// Redirecionamento para uma página após receber o código
if (!isset($_GET['code'])) {
    header("Location: /erro.html"); // Página de erro caso o código não seja recebido
    exit();
}
?>
