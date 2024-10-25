<?php
include 'config.php';

ini_set('session.cookie_secure', 1);  // Garante que o cookie de sessão só seja enviado via HTTPS
ini_set('session.cookie_httponly', 1); // Protege o cookie contra acesso via JavaScript

// Função para gerar o "nonce" (valor aleatório)
function generateNonce($length = 32) {
    return bin2hex(random_bytes($length / 2));
}

// Função para gerar o "state" (valor aleatório)
function generateState($length = 32) {
    return bin2hex(random_bytes($length / 2));
}

// Função para gerar o "code_verifier" (valor aleatório)
function generateCodeVerifier($length = 64) {
    return bin2hex(random_bytes($length / 2));
}

// Função para gerar o "code_challenge" usando o método S256
function generateCodeChallenge($code_verifier) {
    return rtrim(strtr(base64_encode(hash('sha256', $code_verifier, true)), '+/', '-_'), '=');
}

// Iniciar a sessão para armazenar o code_verifier
session_start();

// Gerar valores de nonce, state e code_verifier
$nonce = generateNonce();
$state = generateState();
$code_verifier = generateCodeVerifier();
$code_challenge = generateCodeChallenge($code_verifier);

// Armazenar o code_verifier na sessão
$_SESSION['code_verifier'] = $code_verifier;

// Construir a URL de autenticação do Gov.br (ambiente de testes)
$auth_url = AUTH_URL . '?response_type=code'
    . '&client_id=' . CLIENT_ID
    . '&scope=' . urlencode(SCOPE)
    . '&redirect_uri=' . urlencode(REDIRECT_URI)
    . '&nonce=' . $nonce
    . '&state=' . $state
    . '&code_challenge=' . $code_challenge
    . '&code_challenge_method=S256';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal de Acesso Wi-Fi - UFF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        .logo img {
            max-width: 200px; /* Ajuste o tamanho da logo */
            height: auto;
            margin-bottom: 20px;
        }
        h3 {
            font-size: 1.5em;
            color: #0078d7;
            margin-bottom: 20px;
        }
        .login-section {
            margin: 30px 0;
        }
        .login-section br-sign-in {
            width: 100%;
            max-width: 300px;
            display: inline-block;
        }
        .privacy-policy {
            font-size: 0.9em;
            color: #666;
        }
        .privacy-policy a {
            text-decoration: none;
            color: #0078d7;
        }
        .privacy-policy a:hover {
            text-decoration: underline;
        }
        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }
        }
    </style>
    <script src="https://unpkg.com/@govbr-ds/webcomponents/dist/webcomponents.umd.min.js"></script>
</head>
<body>
    <div class="container">
        <!-- Logo da Universidade -->
        <div class="logo">
            <img src="/images/logo.jpg" alt="Descrição Logo">
        </div>

        <!-- Título -->
        <h3>Conecte-se ao Wi-Fi</h3>

        <!-- Seção de Login com Gov.br -->
        <div class="login-section">
            <br-sign-in
                type="primary"
                density="middle"
                label="Entrar com"
                entity="gov.br"
                href="<?php echo $auth_url; ?>"
                is-link="true"
            ></br-sign-in>
        </div>

        <!-- Política de Privacidade e Termos de Uso -->
        <div class="privacy-policy">
            <p>
                <a href="#">Política de Privacidade</a> | <a href="#">Termos de Uso</a>
            </p>
        </div>
    </div>
</body>
</html>
