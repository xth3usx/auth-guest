# Portal de Acesso Wi-Fi - Autenticação via Gov.br

Este repositório contém o código-fonte de um portal de acesso Wi-Fi que utiliza a autenticação via OAuth2 com o Gov.br. O sistema permite que usuários se autentiquem utilizando suas credenciais Gov.br para acessar a rede Wi-Fi de forma segura e eficiente.

## Funcionalidades

- **Autenticação via OAuth2**: Utilização do protocolo OAuth2 para autenticação segura através do Gov.br.
- **Geração de tokens**: Geração de `code_verifier` e `code_challenge` para implementação de PKCE (Proof Key for Code Exchange), aumentando a segurança do processo de autenticação.
- **Interface responsiva**: Portal otimizado para dispositivos móveis, garantindo uma experiência de usuário consistente em diferentes telas.
- **Perfis de usuário**: Exibição de informações do perfil do usuário autenticado, incluindo nome, CPF e e-mail.
  
## Tecnologias Utilizadas

- **PHP**: Linguagem de back-end para manipulação da autenticação OAuth2 e comunicação com o Gov.br.
- **OAuth2**: Protocolo de autenticação para comunicação segura entre o cliente e o servidor Gov.br.
- **HTML/CSS**: Interface de usuário simples e responsiva.
- **Gov.br API**: Integração com a API de autenticação Gov.br para autenticação e obtenção de informações do usuário.

## Estrutura do Projeto

- `index.php`: Página principal que inicia o processo de autenticação e redireciona o usuário para o Gov.br.
- `callback.php`: Lida com o retorno da API Gov.br após a autenticação, troca o código de autorização por tokens e exibe as informações do usuário.
- `autenticacao.php`: Contém funções relacionadas à geração de `nonce`, `state`, `code_verifier`, `code_challenge`, e preparação da URL de autenticação.
- `config.php`: Configurações da API OAuth2 do Gov.br, incluindo `CLIENT_ID`, `CLIENT_SECRET`, e URLs de autenticação e token.
- `css/style.css`: Arquivo de estilização da interface de usuário do portal.
  
## Pré-requisitos

Certifique-se de ter os seguintes componentes configurados em seu ambiente antes de executar o projeto:

- **PHP 7.4+** com suporte a `cURL` e `OpenSSL`.
- **Servidor Apache** ou Nginx configurado para hospedar o portal.
- **Certificado SSL válido** (recomendado) para suportar o redirecionamento seguro de OAuth2.
