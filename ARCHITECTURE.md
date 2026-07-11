# Caronte ERP — Arquitetura e Contexto

> Documento de referência para desenvolvedores que irão manter ou evoluir o projeto.

---

## Visão Geral

**Caronte ERP** é um sistema de gestão de estoque estruturado como **monorepo de dois microsserviços**:

| Serviço | Diretório | Stack | Função |
|---------|-----------|-------|--------|
| **API** | `back/` | Laravel 13 · PHP-FPM · Nginx | REST API pura (sem views) |
| **SPA** | `front/` | Vue 3 · Vite · Nginx | Interface web que consome a API via JWT |

Os dois serviços se comunicam exclusivamente via HTTP/JSON. O backend não tem conhecimento do frontend e vice-versa — o acoplamento é feito apenas pelo contrato da API.

---

## Estrutura do Repositório

```
caronte-erp/
├── back/                    ← Laravel 13 (API pura)
├── front/                   ← Vue 3 SPA
├── docker/                  ← Configs de Nginx, PHP-FPM, Supervisor, MySQL
├── docker-compose.dev.yml   ← Ambiente de desenvolvimento completo
├── docker-compose.prod.yml  ← Ambiente de produção
├── .env.example             ← Variáveis para Docker Compose
└── Makefile                 ← Atalhos para comandos Docker
```

---

## Backend — `back/`

### Stack

- **PHP 8.3** + **Laravel 13**
- **JWT Auth** via `tymon/jwt-auth`
- **PostgreSQL** (Docker) / **MySQL** (local)
- **Redis** para cache, sessão e fila (Docker) / `file`/`sync` (local)

### Estrutura de diretórios

```
back/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php          ← Login, Register, Logout, Refresh, Me
│   │   │   └── Stock/                      ← CRUD de cada entidade de estoque
│   │   │       ├── WarehouseController.php
│   │   │       ├── SupplierController.php
│   │   │       ├── ProductController.php
│   │   │       ├── PurchaseOrderController.php
│   │   │       ├── StockMovementController.php
│   │   │       └── PhysicalInventoryController.php
│   │   ├── Requests/                       ← Validação de entrada (Form Requests)
│   │   │   ├── Auth/
│   │   │   └── Stock/
│   │   └── Resources/                      ← Formatação de saída (API Resources)
│   │       ├── UserResource.php
│   │       └── Stock/
│   ├── Models/                             ← Eloquent ORM
│   │   ├── User.php
│   │   ├── Warehouse.php / StockPosition.php
│   │   ├── Product.php / Batch.php
│   │   ├── Supplier.php
│   │   ├── PurchaseOrder.php / PurchaseOrderItem.php
│   │   ├── StockMovement.php
│   │   └── PhysicalInventory.php / PhysicalInventoryCount.php
│   ├── Services/                           ← Regras de negócio (fora dos controllers)
│   │   ├── AuthService.php
│   │   ├── ProductService.php
│   │   ├── WarehouseService.php
│   │   ├── SupplierService.php
│   │   ├── PurchaseOrderService.php
│   │   ├── StockMovementService.php
│   │   └── PhysicalInventoryService.php
│   └── Providers/
│       └── AppServiceProvider.php
├── bootstrap/
│   └── app.php                             ← Ponto de configuração do Laravel
├── config/                                 ← Configs: db, auth, jwt, cors, cache…
├── database/
│   ├── migrations/                         ← Schema versionado
│   └── seeders/
├── routes/
│   ├── api_erp_estoque.php                 ← TODAS as rotas da API
│   └── console.php
├── tests/
│   ├── Feature/                            ← Testes de integração HTTP
│   └── Unit/                              ← Testes de unidade
└── vendor/                                 ← Dependências (gitignored)
```

### Camadas de responsabilidade

```
Request HTTP
    ↓
Form Request (validação + autorização)
    ↓
Controller (orquestração, retorna Resource)
    ↓
Service (regra de negócio pura, testável)
    ↓
Model / Eloquent (acesso ao banco)
    ↓
API Resource (formata o JSON de resposta)
    ↓
Response JSON
```

### Rotas da API

Todas as rotas ficam em `back/routes/api_erp_estoque.php` e são servidas com prefixo `/api/`:

| Grupo | Prefixo | Auth | Descrição |
|-------|---------|------|-----------|
| Auth público | `/api/auth` | Não | login, register |
| Auth protegido | `/api/auth` | JWT | logout, refresh, me |
| Estoque | `/api/estoque/*` | JWT | warehouses, suppliers, products, purchase-orders, inventories, stock-movements |

### Autenticação JWT

1. Cliente faz `POST /api/auth/login` → recebe `{ token, user }`
2. Token é enviado em todas as requisições como `Authorization: Bearer <token>`
3. Em caso de 401, o SPA faz `POST /api/auth/refresh` automaticamente

### CORS

Configurado em `back/config/cors.php`. A variável `FRONTEND_URL` define a origem permitida:

```env
FRONTEND_URL=http://localhost:5173   # dev local
FRONTEND_URL=https://app.caronteerp.com  # produção
```

### Convenções de código

- **Controllers** — mínimos: delegam tudo para o Service, retornam Resource
- **Services** — stateless, injetados nos Controllers; contêm toda regra de negócio
- **Form Requests** — toda validação de entrada fica aqui (nunca nos controllers)
- **API Resources** — toda formatação de resposta fica aqui (nunca nos models)
- `declare(strict_types=1)` em todos os arquivos PHP
- Nomenclatura em inglês para code, comentários em português

---

## Frontend — `front/`

### Stack

- **Vue 3** (Composition API + `<script setup>`)
- **Vue Router 4** (history mode)
- **Axios** com interceptors JWT
- **Tailwind CSS v4** + **DaisyUI v5**
- **Vite 8**

### Estrutura de diretórios

```
front/
├── src/
│   ├── main.js                 ← Entry point: monta o app e inicializa o tema
│   ├── App.vue                 ← Root component: RouterView + inicializa dark mode
│   ├── app.css                 ← Tailwind v4 + DaisyUI, dark mode variant
│   ├── custom.css              ← Estilos globais adicionais
│   │
│   ├── assets/                 ← Imagens importadas via @/assets/
│   │   ├── logo.png            ← Logo flat 256×256
│   │   └── logo-rounded.png    ← Logo com cantos arredondados 256×256
│   │
│   ├── api/                    ← Camada de comunicação com o backend
│   │   ├── http.js             ← Instância Axios configurada (baseURL, interceptors JWT)
│   │   ├── auth.js             ← Estado reativo de autenticação + helpers
│   │   └── estoque.js          ← Todos os endpoints do ERP
│   │
│   ├── composables/            ← Lógica reutilizável (hooks Vue)
│   │   └── useTheme.js         ← Toggle light/dark, persiste no localStorage
│   │
│   ├── router/
│   │   └── index.js            ← Rotas + navigation guard (verifica JWT)
│   │
│   ├── layouts/
│   │   └── AppLayout.vue       ← Layout com sidebar responsivo + topbar
│   │
│   ├── components/
│   │   ├── DrawerPanel.vue     ← Painel lateral reutilizável para forms
│   │   └── landing/            ← Componentes da landing page pública
│   │       ├── TheNavbar.vue
│   │       ├── HeroSection.vue
│   │       ├── BenefitsSlider.vue
│   │       ├── ComparisonTable.vue
│   │       ├── JourneySelector.vue
│   │       ├── SocialProof.vue
│   │       ├── TestimonialsSection.vue
│   │       ├── FaqSection.vue
│   │       ├── BlogSection.vue
│   │       └── TheFooter.vue
│   │
│   └── views/                  ← Páginas da aplicação
│       ├── LandingPage.vue     ← Página pública (/)
│       ├── Dashboard.vue       ← /app/dashboard
│       ├── NotFound.vue        ← 404
│       ├── auth/
│       │   ├── Login.vue
│       │   └── Register.vue
│       └── stock/
│           ├── Warehouses.vue
│           ├── Suppliers.vue
│           ├── Products.vue
│           ├── PurchaseOrders.vue
│           ├── StockMovements.vue
│           └── PhysicalInventory.vue
│
├── public/                     ← Arquivos estáticos servidos sem processamento
│   ├── favicon.ico
│   ├── logo.png / logo-rounded.png
│   └── CaronteSoftware.png
│
├── prototype/                  ← Protótipo estático HTML (referência de UI)
│
├── index.html                  ← Shell HTML do SPA (entrada do Vite)
├── vite.config.js              ← Alias @→src/, proxy /api→backend, porta 5173
├── package.json                ← Dependências frontend
├── nginx.conf                  ← Config Nginx para servir o SPA em produção
├── Dockerfile.dev              ← Container Node com Vite HMR
└── Dockerfile.prod             ← Multi-stage: build → Nginx static
```

### Camadas do frontend

```
View (.vue)
  └─ usa composables para lógica reutilizável (useTheme, etc.)
  └─ chama funções da camada api/
      └─ api/estoque.js   — endpoints organizados por entidade
      └─ api/auth.js      — estado reativo de autenticação
          └─ api/http.js  — Axios + interceptor JWT + refresh automático
```

### Roteamento e guarda de navegação

```
/                     → LandingPage (público)
/login                → Login (redireciona para /app se já autenticado)
/register             → Register (redireciona para /app se já autenticado)
/app/dashboard        → Dashboard (requer autenticação)
/app/armazens         → Warehouses
/app/produtos         → Products
/app/movimentacoes    → StockMovements
/app/inventarios      → PhysicalInventory
/app/fornecedores     → Suppliers
/app/pedidos-compra   → PurchaseOrders
```

O `router/index.js` verifica `localStorage.getItem('ng_jwt')` antes de cada navegação:
- Sem token → redireciona para `/login`
- Com token em rota guest → redireciona para `/app/dashboard`

### Dark Mode

Controlado pelo composable `useTheme.js`:
- Persiste em `localStorage` (`caronte_theme`)
- Na primeira visita, respeita `prefers-color-scheme` do sistema
- Aplica `data-theme="light|dark"` no `<html>`
- DaisyUI lê o `data-theme` para aplicar o tema
- Tailwind `dark:` classes ativadas via `@custom-variant dark` no CSS

### Convenções de código

- Composition API com `<script setup>` em todos os componentes
- Imports via alias `@/` (aponta para `front/src/`)
- Componentes em PascalCase; arquivos de composable em camelCase
- Estado de autenticação em `api/auth.js` (sem Pinia/Vuex por ora)
- Texto da interface em português; código em inglês

---

## Comunicação entre os serviços

```
Browser → front/ (Vite :5173 dev | Nginx :80 prod)
                ↓ HTTP /api/* + Authorization: Bearer <jwt>
         back/ (Nginx :8000 → PHP-FPM)
                ↓
         PostgreSQL / MySQL
         Redis (cache, queue, session)
```

Em desenvolvimento, o Vite faz proxy de `/api` para o backend (`VITE_API_TARGET=http://nginx:80`), evitando CORS no browser. Em produção, o CORS é configurado via `FRONTEND_URL` no backend.

---

## Variáveis de Ambiente

### Raiz (Docker Compose)

| Variável | Padrão | Uso |
|----------|--------|-----|
| `APP_PORT` | `8000` | Porta exposta do backend |
| `FRONTEND_PORT` | `5173` | Porta exposta do frontend |
| `DB_DATABASE` | `caronte` | Nome do banco |
| `DB_USERNAME` | `caronte` | Usuário do banco |
| `DB_PASSWORD` | `secret` | Senha do banco |
| `REDIS_PASSWORD` | `secret_redis` | Senha do Redis |

### Backend (`back/.env`)

| Variável | Descrição |
|----------|-----------|
| `APP_KEY` | Chave de criptografia Laravel (gerada por `artisan key:generate`) |
| `JWT_SECRET` | Secret JWT (gerado por `artisan jwt:secret`) |
| `FRONTEND_URL` | Origem permitida no CORS |
| `DB_CONNECTION` | `mysql` ou `pgsql` |

### Frontend (`front/.env`)

| Variável | Descrição |
|----------|-----------|
| `VITE_API_URL` | Base URL do Axios (padrão: `/api`, usa o proxy do Vite em dev) |
| `VITE_API_TARGET` | Alvo interno do proxy Vite (ex: `http://nginx:80`) |

---

## Decisões de design

| Decisão | Motivo |
|---------|--------|
| Dois serviços independentes | Permite deploy, escala e evolução separados |
| JWT stateless | Sem dependência de sessão no backend, compatível com múltiplas instâncias |
| Services layer separada dos Controllers | Testabilidade; controller é só orquestrador |
| Sem Pinia/Vuex | Estado de auth em módulo reativo simples; adicionar Pinia quando o estado crescer |
| DaisyUI + Tailwind v4 | UI consistente com baixo CSS customizado; dark mode nativo |
| PostgreSQL (Docker) / MySQL (local) | Flexibilidade; Laravel suporta ambos nativamente |
