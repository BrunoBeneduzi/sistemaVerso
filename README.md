# API de Sincronização de Produtos e Preços

API REST desenvolvida em PHP 8 + Laravel 11 para processamento, transformação e sincronização de dados de produtos e preços utilizando Views SQL para normalização dos dados.

---

## Tecnologias

- PHP 8.2
- Laravel 11
- SQLite
- Docker
- Docker Compose

---

## Requisitos

- Docker
- Docker Compose

> Nenhuma outra dependência precisa ser instalada na máquina host além do Docker.

---

## Como rodar o projeto

### 1. Clone o repositório

```bash
git clone https://github.com/seu-usuario/seu-repositorio.git
cd seu-repositorio
```

### 2. Suba os containers

```bash
docker compose up -d --build
```

### 3. Instale as dependências (vendor)

```bash
docker compose exec app composer install
```

> Esse comando baixa todas as dependências do PHP listadas no `composer.json`,
> equivalente ao `mvn install` do Java com Maven.

### 4. Configure o ambiente

Crie o arquivo `.env` na pasta `src/` com o seguinte conteúdo:

```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=sqlite
DB_DATABASE=/var/www/html/database/database.sqlite
```

Depois gere a chave da aplicação:

```bash
docker compose exec app php artisan key:generate
```

### 5. Crie o arquivo do banco de dados

```bash
docker compose exec app touch database/database.sqlite
```

### 6. Rode as migrations e popule as tabelas base

```bash
docker compose exec app php artisan migrate --seed
```

### 7. Acesse a API

http://localhost:8000/api

---

## Como rodar os testes

```bash
docker compose exec app php artisan test
```

Para ver detalhado:

```bash
docker compose exec app php artisan test --verbose
```

---

## Endpoints

### POST /api/sincronizar/produtos

Executa a sincronização dos dados de `produtos_base` para `produto_insercao` utilizando a `view_produtos`.

**Request:**

POST http://localhost:8000/api/sincronizar/produtos

**Response:**
```json
{
    "message": "Produtos sincronizados com sucesso!",
    "inseridos": 10,
    "atualizados": 0,
    "removidos": 0
}
```

---

### POST /api/sincronizar/precos

Executa a sincronização dos dados de `precos_base` para `preco_insercao` utilizando a `view_precos`.

**Request:**

POST http://localhost:8000/api/sincronizar/precos

**Response:**
```json
{
    "message": "Preços sincronizados com sucesso!",
    "inseridos": 10,
    "atualizados": 0,
    "removidos": 0
}
```

---

### GET /api/produtos-precos

Retorna os produtos sincronizados com seus respectivos preços de forma paginada.

**Request:**

GET http://localhost:8000/api/produtos-precos

**Parâmetros de query string:**

| Parâmetro  | Tipo | Padrão | Descrição                      |
|------------|------|--------|--------------------------------|
| `per_page` | int  | 10     | Quantidade de itens por página |
| `page`     | int  | 1      | Número da página               |

**Exemplo com paginação:**

GET http://localhost:8000/api/produtos-precos?per_page=5&page=2

**Response:**
```json
{
    "current_page": 1,
    "data": [
        {
            "prod_id": 1,
            "prod_cod": "PRD001",
            "prod_nome": "Teclado Mecânico RGB",
            "prod_cat": "PERIFERICOS",
            "prod_subcat": "TECLADOS",
            "prod_desc": "Teclado com iluminação RGB e switches azuis",
            "prod_fab": "HyperTech",
            "prod_mod": "HT-KEY-RGB",
            "prod_cor": "PRETO",
            "prod_peso": "1,2kg",
            "prod_und": "UN",
            "prod_atv": 1,
            "prod_dt_cad": "2025/10/10",
            "precos": [
                {
                    "preco_id": 1,
                    "prc_cod_prod": "PRD001",
                    "prc_valor": "499.90",
                    "prc_moeda": "BRL",
                    "prc_promo": "474.90",
                    "prc_status": "ativo"
                }
            ]
        }
    ],
    "per_page": 10,
    "total": 10,
    "last_page": 1,
    "next_page_url": null,
    "prev_page_url": null
}
```
<img width="933" height="865" alt="image" src="https://github.com/user-attachments/assets/8702411e-169a-497f-970d-ee9c9a8fb59a" />

---
