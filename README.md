# Laravel CRUD — Produtos & Pessoas

API RESTful em Laravel com CRUD completo de **Produtos** e **Pessoas**, relacionamento many-to-many, Service Layer, Form Requests, API Resources e testes com Pest.

---

## 📁 Estrutura de Arquivos

```
app/
├── Http/
│   ├── Controllers/Api/
│   │   ├── PessoaController.php
│   │   └── ProdutoController.php
│   ├── Requests/
│   │   ├── PessoaRequest.php
│   │   └── ProdutoRequest.php
│   └── Resources/
│       ├── PessoaResource.php
│       └── ProdutoResource.php
├── Models/
│   ├── Pessoa.php
│   └── Produto.php
└── Services/
    ├── PessoaService.php
    └── ProdutoService.php

database/
├── factories/
│   ├── PessoaFactory.php
│   └── ProdutoFactory.php
├── migrations/
│   ├── ..._create_pessoas_table.php
│   ├── ..._create_produtos_table.php
│   └── ..._create_pessoa_produto_table.php
└── seeders/
    └── DatabaseSeeder.php

routes/
└── api.php

tests/
├── Feature/
│   ├── PessoaTest.php
│   └── ProdutoTest.php
├── Unit/
│   └── PessoaServiceTest.php
└── Pest.php
```

---

## 🚀 Instalação

```bash
# 1. Criar projeto Laravel
composer create-project laravel/laravel meu-projeto
cd meu-projeto

# 2. Copiar os arquivos deste projeto

# 3. Instalar Pest
composer require pestphp/pest pestphp/pest-plugin-laravel --dev
php artisan pest:install

# 4. Configurar .env
cp .env.example .env
php artisan key:generate
# editar DB_* no .env

# 5. Rodar migrations e seeds
php artisan migrate --seed

# 6. Iniciar servidor
php artisan serve
```

---

## 🗄️ Banco de Dados

### Tabela `pessoas`
| Coluna           | Tipo         | Observação         |
|------------------|--------------|--------------------|
| id               | bigint PK    |                    |
| nome             | varchar(255) | obrigatório        |
| email            | varchar(255) | único              |
| cpf              | varchar(14)  | único              |
| telefone         | varchar(20)  | nullable           |
| data_nascimento  | date         | nullable           |
| deleted_at       | timestamp    | soft delete        |

### Tabela `produtos`
| Coluna     | Tipo          | Observação         |
|------------|---------------|--------------------|
| id         | bigint PK     |                    |
| nome       | varchar(255)  | obrigatório        |
| descricao  | text          | nullable           |
| preco      | decimal(10,2) | obrigatório        |
| estoque    | integer       | default 0          |
| categoria  | varchar(100)  | nullable           |
| ativo      | boolean       | default true       |
| deleted_at | timestamp     | soft delete        |

### Tabela pivot `pessoa_produto`
| Coluna         | Tipo          |
|----------------|---------------|
| pessoa_id      | FK pessoas    |
| produto_id     | FK produtos   |
| quantidade     | integer       |
| preco_unitario | decimal(10,2) |

---

## 🔌 Endpoints da API

### Pessoas
| Método | Endpoint              | Descrição              |
|--------|-----------------------|------------------------|
| GET    | /api/v1/pessoas       | Listar (paginado)      |
| POST   | /api/v1/pessoas       | Criar pessoa           |
| GET    | /api/v1/pessoas/{id}  | Exibir pessoa          |
| PUT    | /api/v1/pessoas/{id}  | Atualizar pessoa       |
| DELETE | /api/v1/pessoas/{id}  | Remover (soft delete)  |

### Produtos
| Método | Endpoint              | Descrição              |
|--------|-----------------------|------------------------|
| GET    | /api/v1/produtos      | Listar (paginado)      |
| POST   | /api/v1/produtos      | Criar produto          |
| GET    | /api/v1/produtos/{id} | Exibir produto         |
| PUT    | /api/v1/produtos/{id} | Atualizar produto      |
| DELETE | /api/v1/produtos/{id} | Remover (soft delete)  |

---

## 📦 Exemplos de Payload

### POST /api/v1/pessoas
```json
{
  "nome": "João Silva",
  "email": "joao@email.com",
  "cpf": "123.456.789-09",
  "telefone": "(81) 99999-0000",
  "data_nascimento": "1990-05-15",
  "produtos": [
    { "produto_id": 1, "quantidade": 2, "preco_unitario": 49.90 }
  ]
}
```

### POST /api/v1/produtos
```json
{
  "nome": "Notebook Pro",
  "descricao": "Notebook de alta performance",
  "preco": 4999.90,
  "estoque": 10,
  "categoria": "Eletrônicos",
  "ativo": true
}
```

---

## 🧪 Rodando os Testes

```bash
# Todos os testes
php artisan test

# Com Pest diretamente
./vendor/bin/pest

# Apenas testes de Feature
./vendor/bin/pest tests/Feature

# Apenas testes de Unit
./vendor/bin/pest tests/Unit

# Com cobertura (requer Xdebug ou PCOV)
./vendor/bin/pest --coverage
```

---

## 🏗️ Arquitetura

```
Request → Controller → Service → Model → Database
                ↓
           FormRequest (validação)
                ↓
          ApiResource (resposta)
```

- **Controller** — recebe requisição, chama Service, retorna Resource
- **Service** — lógica de negócio, transações DB
- **FormRequest** — validação centralizada
- **ApiResource** — formata a resposta JSON
- **Factory** — gera dados falsos para testes
