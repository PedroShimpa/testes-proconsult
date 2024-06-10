# Teste de Admissão ProConsult

Este projeto é inspirado no teste de admissão da Pro Consult Engenharia.

## Aplicação de Exemplo
O acesso a aplicação esta disponivel em: https://chamados.pfpdesenvolvimento.com.br/

Link dos requisitos do projeto: [Teste de Admissão ProConsult](https://github.com/ProConsult-Dev/teste-admissao-proconsult)
### Requisitos

- PHP 7.3 (Utilizado PHP 7.3.30)
- MySQL

## Iniciando o Projeto

Este projeto utiliza o framework Laravel. Siga os passos abaixo para iniciar o projeto:

1. Renomeie o arquivo `.env.example` para `.env`.
2. Crie e configure o banco de dados.
3. Configure os dados de envio de e-mail no arquivo `.env`.
4. Execute o comando: `php artisan migrate --seed` (a seed é para criar um colaborador para testes, mas não é obrigatorio, você pode gerar ele com comando também).
5. Execute o comando: `php artisan key:generate`.

### Usuário de Teste (Colaborador)

- Email: teste@chamados.com
- Senha: 123456

### Gerar um colaborador
Para transformar um usuario em colaborador:
1. Register um usuario normalmente na plataforma
2. Execute o comando `php artisan gerar:colaborador exemplo@email.com`

## Documentação

A documentação do projeto está disponível no arquivo `Chamados.postman_collection.json`. Você pode importá-lo no Postman para acessar a documentação.

## Testes Automaticos

*Atenção*: Executar os testes automatico gera dados adversos.

1. Execute o comando: `php artisan test`.