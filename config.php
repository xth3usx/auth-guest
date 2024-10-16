<?php
// Configurações OAuth2 para a API Gov.br
define('CLIENT_ID', 'seu_client_id_aqui');
define('CLIENT_SECRET', 'seu_client_secret_aqui');
define('REDIRECT_URI', 'https://seudominio/callback.php');
define('AUTH_URL', 'https://sso.staging.acesso.gov.br/authorize');
define('TOKEN_URL', 'https://sso.staging.acesso.gov.br/token');
define('USERINFO_URL', 'https://sso.staging.acesso.gov.br/userinfo');
define('SCOPE', 'openid email');
?>
