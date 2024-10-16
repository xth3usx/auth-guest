<?php
include 'config.php';

session_start();

if (isset($_GET['code'])) {
    $code = $_GET['code'];

    if (isset($_SESSION['code_verifier'])) {
        $code_verifier = $_SESSION['code_verifier'];
        $token_data = getAccessToken($code, $code_verifier);

        if ($token_data && isset($token_data['access_token'])) {
            $access_token = $token_data['access_token'];
            $userinfo = getUserInfo($access_token);

            if ($userinfo) {
                // Exibir informações do usuário em uma página HTML
                echo renderUserProfile($userinfo, $token_data['id_token'] ?? null);
            } else {
                echo renderError("Erro ao obter informações do usuário.");
            }
        } else {
            echo renderError("Erro ao trocar o código pelo token.");
        }
    } else {
        echo renderError("Nenhum code_verifier encontrado.");
    }
} else {
    echo renderError("Código de autorização não recebido.");
}

function renderUserProfile($userinfo, $id_token = null) {
    $html = '<!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Perfil do Usuário</title>
        <style>
            body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; }
            .container { max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
            h1 { text-align: center; color: #333; }
            p { margin: 10px 0; }
            .footer { text-align: center; margin-top: 20px; }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Autenticação bem-sucedida!</h1>
            <p><strong>Bem-vindo, </strong>' . htmlspecialchars($userinfo['name'] ?? 'Nome não disponível') . '</p>
            <p><strong>CPF:</strong> ' . htmlspecialchars($userinfo['sub'] ?? 'Não disponível') . '</p>
            <p><strong>Nível de Confiança:</strong> ' . htmlspecialchars($userinfo['confiabilidade'] ?? 'Não disponível') . '</p>
            <p><strong>Método de Autenticação:</strong> ' . htmlspecialchars(implode(', ', $userinfo['amr'] ?? [])) . '</p>
            <p><strong>E-mail:</strong> ' . htmlspecialchars($userinfo['email'] ?? 'Não disponível') . '</p>';

    if ($id_token) {
        $id_token_data = decodeIdToken($id_token);
        $html .= '<pre>ID Token Data: ' . htmlspecialchars(print_r($id_token_data, true)) . '</pre>';
    }

    $html .= '</div>
            <div class="footer">

            </div>
        </body>
    </html>';

    return $html;
}

function renderError($message) {
    return '<!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Erro</title>
        <style>
            body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; }
            .container { max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
            h1 { text-align: center; color: #e74c3c; }
            p { text-align: center; color: #333; }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Erro</h1>
            <p>' . htmlspecialchars($message) . '</p>
        </div>
    </body>
    </html>';
}

// Função para trocar o código de autorização pelo token de acesso
function getAccessToken($code, $code_verifier) {
    $params = [
        'grant_type' => 'authorization_code',
        'code' => $code,
        'redirect_uri' => REDIRECT_URI,
        'client_id' => CLIENT_ID,
        'client_secret' => CLIENT_SECRET,
        'code_verifier' => $code_verifier
    ];

    $ch = curl_init(TOKEN_URL);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// Função para obter as informações do usuário com o token de acesso
function getUserInfo($token) {
    $ch = curl_init(USERINFO_URL);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $token]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// Função para decodificar o id_token
function decodeIdToken($id_token) {
    $parts = explode('.', $id_token);
    if (count($parts) === 3) {
        return json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $parts[1])), true);
    }
    return null;
}
?>
