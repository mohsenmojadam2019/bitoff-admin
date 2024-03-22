# Bitoff Admin in Docker

**Table of Contents**

- [Setup](#setup)
  - [1. Build docker image](#1-build-docker-image)
  - [2. Point `.admin.bitoff.test` domains to localhost](#2-point-bitofftest-domains-to-localhost)
  - [3. Proxy the domains to the nginx container](#3-proxy-the-domains-to-the-nginx-container)
    - [3.1. Setup nginx on your host](#31-setup-nginx-on-your-host)
    - [3.2. Override docker-compose config](#32-override-docker-compose-config)
  - [4. Helper alias (Optional)](#4-helper-alias-optional)
- [Usage](#usage)
  - [Bring up containers](#bring-up-containers)
  - [Run php command](#run-php-command)
  - [Run composer command](#run-composer-command)
  - [Run phpunit](#run-phpunit)
  - [Get code coverage using phpunit and xdebug](#get-code-coverage-using-phpunit-and-xdebug)
  - [Run artisan commands](#run-artisan-commands)
  - [Run tinker session](#run-tinker-session)
  - [Run shell session on php container](#run-shell-session-on-php-container)
  - [Run shell session on php container as root](#run-shell-session-on-php-container-as-root)
  - [Run binaries installed by composer](#run-binaries-installed-by-composer)

## Setup

### 1. Build docker image

```shell
$ ./docker/bita build
```

### 2. Point `.admin.bitoff.test` domains to localhost

Add following lines to `/etc/hosts`:

```
127.0.0.1   admin.bitoff.test
```

### 3. Proxy the domains to the nginx container

Use one of the following methods:

#### 3.1. Setup nginx on your host

1. Install `nginx` using your package manager (e.g `apt install nginx`).
2. Copy following config to

   - If on Ubuntu (or Debian based distro): `/etc/nginx/sites-available/bitoff-admin.conf`
     - Run `sudo ln -s ../sites-available/bitoff-admin.conf /etc/nginx/sites-enabled/bitoff-admin.conf` afterwards
   - If on Arch-based distro: `/etc/nginx/conf.d/bitoff-admin.conf`
     - Add `include conf.d/*.conf` to `/etc/nginx/nginx.conf` afterwards

   ```
   server {
        listen 80;
        server_name admin.bitoff.test;

        client_max_body_size 20g;
        proxy_connect_timeout 1d;
        proxy_read_timeout 1d;
        proxy_send_timeout 1d;

        location / {
            proxy_set_header Host $host;
            proxy_set_header X-Real-IP $remote_addr;
            proxy_pass http://127.0.0.1:8084;
        }
   }
   ```

3. Reload nginx config:
   ```shell
    sudo nginx -s reload
   ```

#### 3.2. Override docker-compose config

Create a file named `docker-compose.override.yml` with the following content:

```yaml
services:
  nginx:
    ports:
      - 80:80
```

### 4. Helper alias (Optional)

You can define an alias to run commands easier:

```shell
alias bita='./docker/bita'
```

And add it to your shell config:

```shell
echo "alias bita='./docker/bita'" > ~/.${SHELL}rc
```

## Usage

### Bring up containers

```shell
bita up -d
```

### Run php command

```shell
bita php -i
```

### Run composer command

```shell
bita composer install
```

### Run phpunit

```shell
bita phpunit
```

Or

```shell
bita pu
```

### Get code coverage using phpunit and xdebug

_Note_ Additional arguments are passed to `phpunit`

```shell
bita coverage
```

Or

```shell
bita cov
```

### Run artisan commands

```shell
bita artisan list
```

Or

```shell
bita art list
```

### Run tinker session

```shell
bita tinker
```

### Run npm cli

```shell
bita npm
```

### Run shell session on php container

```shell
bita shell
```

Or

```shell
bita bash
```

### Run shell session on php container as root

```shell
bita root-shell
```

### Run binaries installed by composer

```shell
bita bin php-cs-fixer fix
```
