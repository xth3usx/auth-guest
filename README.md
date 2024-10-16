# Portal de Acesso Wi-Fi - Autenticação via Gov.br

Este repositório contém o código de um portal de acesso Wi-Fi que implementa autenticação segura via Gov.br, utilizando o protocolo OAuth2.

## Funcionalidades

- **Autenticação segura via OAuth2 (Gov.br)**
- **Implementação PKCE** para segurança adicional
- **Interface responsiva** para dispositivos móveis

## Tecnologias Utilizadas

- **PHP** para integração OAuth2
- **HTML/CSS** para a interface responsiva
- **Gov.br API** para autenticação

## Estrutura do Projeto

- `index.php`: Inicia o processo de autenticação via Gov.br
- `callback.php`: Processa o retorno e troca o código por tokens de acesso
- `config.php`: Armazena as configurações da API Gov.br

## Pré-requisitos

- PHP 7.4+ com `cURL` e `OpenSSL`
- Servidor Apache
- Certificado SSL válido
