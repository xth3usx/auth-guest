# Portal de Acesso Wi-Fi - Autenticação via Gov.br

## Funcionalidades

- **Autenticação segura via OAuth2 (Gov.br)**
- **Implementação PKCE** para segurança adicional
- **Interface responsiva** para dispositivos móveis

## Estrutura do Projeto

- `index.php`: Inicia o processo de autenticação via Gov.br
- `callback.php`: Processa o retorno e troca o código por tokens de acesso
- `config.php`: Armazena as configurações da API Gov.br

## Pré-requisitos

- PHP 7.4+ com `cURL` e `OpenSSL`
- Servidor Apache
- Certificado SSL válido
