# Caronte ERP — Rodar Localmente (sem Docker)

Guia para desenvolvedores que querem rodar o projeto diretamente no sistema,
usando **MySQL local** e **Node.js** instalados na máquina.

---

## Pré-requisitos

| Ferramenta | Versão mínima | Como verificar |
|------------|--------------|----------------|
| PHP | 8.3+ | `php -v` |
| Composer | 2.x | `composer --version` |
| Node.js | 22+ | `node -v` |
| npm | 10+ | `npm -v` |
| MySQL | 8.0+ | `mysql --version` |

> **Redis é opcional** no modo local. O guia usa `file`/`sync` no lugar de Redis.

---

## 1. Clonar o repositório

```bash
git clone https://github.com/seu-usuario/caronte-erp.git
cd caronte-erp
```

---

## 2. Configurar o banco de dados MySQL

Abra o MySQL e crie o banco (se já não existir):

```sql
mysql -u root -p

CREATE DATABASE IF NOT EXISTS caronte CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

> O banco `caronte` já existe na sua máquina — pule esse passo.

---

## 3. Configurar o Backend (Laravel)

Todos os comandos do backend rodam dentro da pasta `back/`.

### 3.1 Instalar dependências PHP

```bash
cd back
composer install
```

### 3.2 Criar o arquivo de ambiente

```bash
cp .env.example .env
```

Edite `back/.env` com as suas credenciais MySQL:

```env
APP_NAME="Caronte ERP"
APP_ENV=local
APP_KEY=                      # será preenchido no próximo passo
APP_DEBUG=true
APP_URL=http://localhost:8000

FRONTEND_URL=http://localhost:5173

# MySQL local
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=caronte
DB_USERNAME=root              # seu usuário MySQL
DB_PASSWORD=                  # sua senha MySQL (deixe vazio se não tiver)

# Sem Redis — modo local simples
CACHE_STORE=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file

MAIL_MAILER=log
MAIL_FROM_ADDRESS="dev@caronteerp.com"
MAIL_FROM_NAME="Caronte ERP"

JWT_SECRET=                   # será preenchido no próximo passo
```

### 3.3 Gerar as chaves da aplicação

```bash
# Ainda dentro de back/

# Gera APP_KEY (criptografia Laravel)
php artisan key:generate

# Gera JWT_SECRET (autenticação JWT)
php artisan jwt:secret
```

### 3.4 Rodar as migrations

```bash
php artisan migrate
```

Saída esperada:

```
INFO  Running migrations.
  2026_01_01_000001_create_warehouses_table ............. DONE
  2026_01_01_000002_create_stock_positions_table ........ DONE
  ...
```

### 3.5 Iniciar o servidor Laravel

```bash
php artisan serve --port=8000
```

A API estará disponível em **http://localhost:8000**.

Teste rápido:

```bash
curl http://localhost:8000/up
# Resposta esperada: status 200
```

---

## 4. Configurar o Frontend (Vue 3)

Abra um **novo terminal** e entre na pasta `front/`.

### 4.1 Instalar dependências Node

```bash
cd front
npm install
```

### 4.2 Criar o arquivo de ambiente

```bash
cp .env.example .env
```

O arquivo `front/.env` deve conter:

```env
# Axios usa /api como base — o proxy do Vite redireciona para o backend
VITE_API_URL=/api
VITE_API_TARGET=http://localhost:8000
```

### 4.3 Iniciar o servidor de desenvolvimento Vite

```bash
npm run dev
```

O SPA estará disponível em **http://localhost:5173**.

O Vite já faz proxy automático de `/api` para `http://localhost:8000`,
então não há problemas de CORS no browser durante o desenvolvimento.

---

## 5. Usar a aplicação

| URL | O que é |
|-----|---------|
| `http://localhost:5173` | SPA Vue (frontend) |
| `http://localhost:8000/api` | API REST (backend) |
| `http://localhost:8000/up` | Health check do backend |

### Criar um usuário

```bash
# Via API (curl)
curl -s -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{"name":"Admin","email":"admin@caronte.com","password":"password","password_confirmation":"password"}' \
  | python3 -m json.tool
```

Ou simplesmente acesse `http://localhost:5173/register` no browser.

---

## 6. Comandos úteis do dia a dia

Todos os comandos abaixo são executados dentro de `back/`:

```bash
# Resetar o banco e rodar todas as migrations do zero
php artisan migrate:fresh

# Resetar e popular com seeders
php artisan migrate:fresh --seed

# Listar todas as rotas da API
php artisan route:list --path=api

# Limpar caches
php artisan config:clear
php artisan route:clear
php artisan cache:clear

# Rodar testes
php artisan test
# ou
php artisan test --filter NomeDoTeste

# Abrir o REPL interativo do Laravel
php artisan tinker
```

Dentro de `front/`:

```bash
# Build de produção (gera front/dist/)
npm run build

# Preview do build de produção
npm run preview
```

---

## 7. Trabalhando nos dois ao mesmo tempo

A forma mais prática: **dois terminais simultâneos**.

**Terminal 1 — Backend:**
```bash
cd back
php artisan serve --port=8000
```

**Terminal 2 — Frontend:**
```bash
cd front
npm run dev
```

Acesse `http://localhost:5173` no browser.

---

## 8. Problemas comuns

### `php artisan key:generate` falha
Verifique se o arquivo `back/.env` foi criado: `cp .env.example .env`

### Erro de conexão com MySQL — `Access denied`
Verifique `DB_USERNAME` e `DB_PASSWORD` em `back/.env`. Teste a conexão:
```bash
mysql -u root -p caronte -e "SELECT 1;"
```

### Erro de conexão com MySQL — `Connection refused`
Verifique se o MySQL está rodando:
```bash
sudo systemctl status mysql
sudo systemctl start mysql   # se não estiver rodando
```

### Rota `/api/...` retorna 404 no browser
Certifique-se de que o Vite está rodando (`npm run dev` em `front/`) e que o proxy está configurado (`VITE_API_TARGET=http://localhost:8000`).

### `Class "App\Http\Middleware\..." not found`
Execute `composer dump-autoload` dentro de `back/`.

### JWT token inválido ou expirado
Verifique se `JWT_SECRET` está definido em `back/.env`. Se não estiver, rode `php artisan jwt:secret`.
