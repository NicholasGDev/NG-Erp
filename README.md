# ngERP — Inventory Management System

> Laravel 13 · Vue 3 · JWT Auth · PostgreSQL · Redis · TailwindCSS v4 · DaisyUI

---

## Overview

**ngERP** is a Single Page Application (SPA) for inventory and purchasing management, featuring JWT authentication, warehouse control, products, batches, stock movements (Kardex), physical inventory, and purchase orders.

---

## Flowcharts

### 1. General Architecture

```mermaid
flowchart TD
    Browser(["🌐 Browser\n(Vue 3 SPA)"])
    Nginx["Nginx\n(Reverse Proxy)"]
    Laravel["Laravel 13\n(REST API)"]
    PG[("PostgreSQL\n(Data)")]
    Redis[("Redis\n(Cache / Queue / Session)")]

    Browser -->|"HTTP/HTTPS"| Nginx
    Nginx -->|"FastCGI"| Laravel
    Laravel -->|"Eloquent ORM"| PG
    Laravel -->|"Cache & Queues"| Redis

```

---

### 2. Authentication Flow (JWT)

```mermaid
sequenceDiagram
    actor User as User
    participant SPA as Vue SPA
    participant API as Laravel API
    participant DB as PostgreSQL

    User->>SPA: Fills in email and password
    SPA->>API: POST /api/auth/login
    API->>DB: Verifies credentials
    DB-->>API: User found
    API-->>SPA: { token, user }
    SPA->>SPA: Saves token in localStorage
    SPA-->>User: Redirects to /app/dashboard

    Note over SPA,API: All subsequent requests send\nAuthorization: Bearer <token>

    SPA->>API: Any protected route
    API->>API: Validates JWT (auth:api)
    API-->>SPA: Response data

    Note over SPA,API: Expired token (401)
    SPA->>API: POST /api/auth/refresh
    API-->>SPA: New token
    SPA->>API: Repeats original request

```

---

### 3. Inventory Flow (Warehouses → Products → Movements)

```mermaid
flowchart LR
    A[Register\nWarehouse] --> B[Register\nPositions]
    B --> C[Register\nProducts]
    C --> D{Tracks\nBatch?}
    D -- Yes --> E[Create Batch\n+ Expiration Date]
    D -- No --> F[Direct\nMovement]
    E --> F
    F --> G{Movement\nType}
    G -- Purchase Inflow --> H[Updates\nAverage Cost]
    G -- Sales Outflow --> I[Deducts\nStock]
    G -- Transfer --> J[Updates Origin\n& Destination\nWarehouses]
    G -- Adjustment --> K[Inventory\nCorrection]
    H & I & J & K --> L[Kardex\nRegistered]

```

---

### 4. Purchase Order Flow

```mermaid
stateDiagram-v2
    [*] --> Draft : Created by user
    Draft --> Issued : Confirm order
    Issued --> Partially_Received : Part of goods arrived
    Partially_Received --> Partially_Received : More items received
    Partially_Received --> Completed : All items received
    Issued --> Completed : Fully received
    Issued --> Cancelled : Cancel order
    Draft --> Cancelled : Cancel draft
    Completed --> [*]
    Cancelled --> [*]

    note right of Issued
        Upon receiving goods,
        a Purchase Inflow movement
        is automatically registered
        in the Kardex
    end note

```

---

### 5. Physical Inventory Flow

```mermaid
flowchart TD
    Start([Start Inventory]) --> Seleciona[Select Warehouse]
    Seleciona --> Conta[Physical Count\nby product/batch]
    Conta --> Compara{Difference\nfound?}
    Compara -- No --> Confirma[Confirm Count]
    Compara -- Yes --> Ajuste[Register Adjustment\nin Kardex]
    Ajuste --> Confirma
    Confirma --> Status{All items\ncounted?}
    Status -- No --> Conta
    Status -- Yes --> Encerra[Status: Adjusted]
    Encerra --> End([Inventory Finished])

```

---

### 6. Frontend Navigation (Vue Router)

```mermaid
flowchart TD
    Root["/"] -->|"meta: guest"| Landing["Landing Page\n(Public)"]
    Root --> Login["/login\n(meta: guest)"]
    Root --> Register["/register\n(meta: guest)"]
    Root --> App["/app\n(meta: requiresAuth)"]

    App --> Dashboard["/app/dashboard"]
    App --> Armazens["/app/armazens"]
    App --> Produtos["/app/produtos"]
    App --> Movimentacoes["/app/movimentacoes"]
    App --> Inventarios["/app/inventarios"]
    App --> Fornecedores["/app/fornecedores"]
    App --> Pedidos["/app/pedidos-compra"]

    Guard{{"Navigation Guard\n(localStorage: ng_jwt)"}}

    Landing -->|"Authenticated?"| Guard
    Login -->|"Authenticated?"| Guard
    App -->|"No token?"| Guard
    Guard -- "Yes → guest" --> Dashboard
    Guard -- "No → requiresAuth" --> Login

```

---

## Prerequisites

| Tool | Minimum Version |
| --- | --- |
| PHP | 8.3+ |
| Composer | 2.x |
| Node.js | 22+ |
| npm | 10+ |
| PostgreSQL | 16+ |
| Redis | 7+ |

> **Alternative:** use Docker (recommended) — see below.

---

## Installation — Local Development

### 1. Clone the repository

```bash
git clone https://github.com/seu-usuario/ng-erp.git
cd ng-erp

```

### 2. Install PHP dependencies

```bash
composer install

```

### 3. Configure the environment

```bash
cp .env.example .env
php artisan key:generate

```

Edit `.env` with your database and Redis credentials:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=ngerp
DB_USERNAME=your_user
DB_PASSWORD=your_password

REDIS_HOST=127.0.0.1
REDIS_PORT=6379

```

### 4. Generate the JWT secret

```bash
php artisan jwt:secret

```

### 5. Run migrations

```bash
php artisan migrate

```

### 6. Install frontend dependencies

```bash
npm install

```

### 7. Start the development environment

```bash
# In separate terminals:
php artisan serve        # Laravel API at http://localhost:8000
npm run dev              # Vite at http://localhost:5173

```

Or use the project's combined command:

```bash
composer dev

```

> This boots up the PHP server, queue worker, log watcher, and Vite simultaneously via `concurrently`.

---

## Installation — Docker (Recommended)

### 1. Clone and configure

```bash
git clone https://github.com/seu-usuario/ng-erp.git
cd ng-erp
cp .env.example .env

```

### 2. Spin up the containers

```bash
make up
# or
docker compose -f docker-compose.dev.yml up -d --build

```

### 3. Install dependencies and migrate

```bash
make shell
# Inside the container:
composer install
php artisan key:generate
php artisan jwt:secret
php artisan migrate
exit

```

### 4. Build the frontend

```bash
make shell
npm install && npm run build
exit

```

The application will be available at **http://localhost:80**.

---

## Useful Commands

```bash
# Migrations
php artisan migrate
php artisan migrate:fresh --seed   # Resets and re-seeds the database

# Routes
php artisan route:list --path=api  # Lists all API routes

# Cache
php artisan config:clear
php artisan route:clear
php artisan cache:clear

# Make (Docker)
make up          # Spins up the environment
make down        # Tears down the environment
make shell       # Opens a shell in the container
make artisan cmd='migrate'
make logs

```

---

## Module Structure

```
app/
├── Http/Controllers/
│   ├── AuthController.php          ← Login, Register, Refresh, Me
│   └── Estoque/
│       ├── ArmazemController.php
│       ├── ProdutoController.php
│       ├── FornecedorController.php
│       ├── PedidoCompraController.php
│       ├── MovimentacaoEstoqueController.php
│       └── InventarioController.php
├── Models/                         ← Eloquent models
├── Services/                       ← Business logic
resources/js/
├── api/
│   ├── http.js                     ← Axios + JWT interceptors
│   ├── auth.js                     ← Authentication store
│   └── estoque.js                  ← ERP endpoints
├── components/
│   ├── DrawerPanel.vue             ← Reusable side panel
│   └── landing/                    ← Landing page components
├── layouts/
│   └── AppLayout.vue               ← Layout with responsive sidebar
├── router/index.js                 ← Routes + navigation guards
└── views/
    ├── auth/                       ← Login and Registration
    ├── estoque/                    ← ERP Modules
    └── LandingPage.vue

```

---

## License

MIT © 2026 ngERP