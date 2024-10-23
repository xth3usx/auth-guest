## Estrutura do Projeto

- `index.php`: Inicia o processo de autenticação via Gov.br
- `callback.php`: Processa o retorno e troca o código por tokens de acesso
- `config.php`: Armazena as configurações da API Gov.br

Nota: Alguns parâmetros confidenciais foram omitidos do arquivo de configuração (config.php).

## Pré-requisitos

- PHP 7.4+ com `cURL` e `OpenSSL`
- Servidor Apache
- Certificado SSL válido
