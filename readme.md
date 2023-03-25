# Sobre

O projeto em questão se trata de uma aplicação de API para uma pastelaria, que será desenvolvida utilizando as tecnologias Laravel, Docker e MySQL. O Laravel é um framework de aplicação web PHP que oferece uma série de recursos e funcionalidades que tornam o desenvolvimento mais fácil e rápido. O Docker é uma plataforma de virtualização de contêineres que permite empacotar e distribuir aplicativos em contêineres independentes, garantindo a consistência do ambiente de desenvolvimento e produção. O MySQL é um sistema gerenciador de banco de dados relacional que será utilizado para armazenar os dados da aplicação.

Com o uso dessas tecnologias, será possível desenvolver uma aplicação moderna e escalável que ofereça uma API robusta para a pastelaria. A aplicação poderá ser facilmente implantada e escalada usando o Docker, e o Laravel fornecerá uma base sólida para o desenvolvimento rápido e eficiente da API. O MySQL fornecerá um sistema de gerenciamento de banco de dados confiável e escalável para a aplicação.

# 1º Passo
Entre no diretorio app e faça uma copia do arquivo arquivo `.env.example` e nomei essa copia para `.env`

```sh
cp app/.env.example app/.env
```

Depois copie as variaveis de banco que estão no arquivo `docker-compose.yml` para a parte do arquivo `.env` onde são inseridas as variaveis de banco.

# 2° Passo
Dentro da pasta `app` execute o comando no terminal:

```sh
composer install
```

E em seguida para nos certificarmos que não haverá nenhum erro durante o processo de build:
```sh
php artisan key:generate
```

# Conteúdo da Imagem Docker

- <b>PHP</b>, e diversas extensões e Libs do PHP, incluindo mysql, entre outras.

- <b>Nginx</b>, como proxy reverso/servidor. Por fim de testes é que o Nginx está presente nesta imagem, em um momento de otimização está imagem deixará de ter o Nginx.

- <b>Composer</b>, afinal de contas é preciso baixar as dependências mais atuais toda vez que fomos crontruir uma imagem Docker.

# Passo a Passo

## Certifique-se de estar com o Docker em execução.

```sh
docker ps
```

## Certifique-se de ter o Docker Compose instalado.

```sh
docker compose version
```

### Certifique-se que sua aplicação Laravel ficou em `./app` e que existe o seguinte caminho: `/app/public/index.php`

### Certifique-se que sua aplicação Laravel possuí um .env e que este .env está com a `APP_KEY=` definida com valor válido.

## Contruir a imagem Docker, execute:

```sh
docker compose build
```

## Caso não queira utilizar o cache da imagem presente no seu ambiente Docker, então execute:

```sh
docker compose build --no-cache
```

## Para subir a aplicação, execute:

```sh
docker compose up
```

- Para rodar o ambiente sem precisar manter o terminar aberto, execute:

```sh
docker compose up -d
```

- Caso deseje rodar tudo de uma vez só realize o seguinte comando:
```sh
docker compose up -d --build
```

## Para derrubar a aplicação, execute:

```sh
docker compose down
```

## Para entrar dentro do Container da Aplicação, execute:

```sh
docker exec -it web bash
```

# Solução de Problemas

## Problema de permissão

- Quando for criado novos arquivos, ou quando for a primeira inicialização do container com a aplicação, pode então haver um erro de permissão de acesso as pastas, neste caso, entre dentro do container da aplicação e execeute.

```sh
cd /var/www && \
chown -R www-data:www-data * && \
chmod -R o+w app
```
