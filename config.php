<?php
// Configurações OAuth2 para a API Gov.br
define('CLIENT_ID', 'ID_CLIENTE_GOV_BR');
define('CLIENT_SECRET', 'SEGREDO_CLIENTE_GOV_BR');
define('REDIRECT_URI', 'HTTPS://RETORNO_REGISTRADO_GOV_BR/CALLBACK.PHP');
define('AUTH_URL', 'https://sso.staging.acesso.gov.br/authorize');
define('TOKEN_URL', 'https://sso.staging.acesso.gov.br/token');
define('USERINFO_URL', 'https://sso.staging.acesso.gov.br/userinfo');
define('SCOPE', 'openid email');
?>
