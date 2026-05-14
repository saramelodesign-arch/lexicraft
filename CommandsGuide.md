# LexiCraft — Guia de Comandos e Ambiente de Desenvolvimento

## Stack Atual

* Laravel 13
* PHP 8.3+
* Livewire 4
* Volt
* PostgreSQL
* Tailwind CSS 4
* Alpine.js
* Vite
* Laravel Scout
* Meilisearch
* Spatie Media Library

---

# Estrutura Geral do Ambiente

O ambiente de desenvolvimento do LexiCraft é composto por vários serviços separados.

```text
Laragon
├── PHP
├── Nginx
└── Terminal

PostgreSQL
├── Base de dados

Laravel
├── Backend da aplicação

Vite
├── Frontend assets

Meilisearch
├── Pesquisa semântica
```

---

# Ordem Correta para Trabalhar no Projeto

## 1. Abrir Laragon

Executar:

```text
Laragon
```

Depois clicar:

```text
Start All
```

Isto inicia:

* PHP
* Nginx

---

## 2. Garantir que PostgreSQL está ativo

Abrir:

```text
pgAdmin 4
```

Verificar:

* servidor online
* base de dados acessível

---

## 3. Abrir o projeto no VS Code

Pasta:

```text
C:\Projects\lexicraft
```

---

## 4. Abrir terminal no VS Code

Menu:

```text
Terminal → New Terminal
```

---

# Comandos Principais

# Navegação

## Entrar na pasta do projeto

```bash
cd C:\Projects\lexicraft
```

### O que faz

Muda o terminal para a pasta do projeto.

---

# Composer (PHP / Laravel)

# Instalar dependências PHP

```bash
composer install
```

### O que faz

Instala todas as dependências PHP definidas no `composer.json`.

Cria:

```text
/vendor
```

### Quando usar

* primeira instalação
* após pull do GitHub
* após mudar dependências

---

# Atualizar dependências PHP

```bash
composer update
```

### O que faz

Atualiza dependências para versões mais recentes compatíveis.

### IMPORTANTE

Não executar frequentemente.
Pode:

* quebrar packages
* atualizar versões inesperadas
* causar incompatibilidades

---

# Verificar problemas do Composer

```bash
composer diagnose
```

### O que faz

Verifica:

* configuração Composer
* problemas de rede
* dependências
* ambiente PHP

---

# Validar composer.json

```bash
composer validate
```

### O que faz

Verifica:

* JSON válido
* estrutura correta
* schema Composer

---

# Laravel Artisan

# Gerar APP_KEY

```bash
php artisan key:generate
```

### O que faz

Gera a chave da aplicação Laravel.

Usada para:

* sessões
* cookies
* encriptação
* autenticação
* CSRF

### IMPORTANTE

Nunca alterar em produção após utilizadores ativos.

---

# Iniciar servidor Laravel

```bash
php artisan serve
```

### O que faz

Inicia o servidor local Laravel.

### URL

```text
http://127.0.0.1:8000
```

### IMPORTANTE

Manter o terminal aberto.

---

# Executar migrations

```bash
php artisan migrate
```

### O que faz

Cria tabelas no PostgreSQL.

Inclui:

* tabelas
* foreign keys
* indexes
* constraints

---

# Reverter última migration

```bash
php artisan migrate:rollback
```

### O que faz

Remove a última migration executada.

---

# Recriar base de dados completa

```bash
php artisan migrate:fresh
```

### O que faz

Apaga todas as tabelas e recria tudo.

### CUIDADO

Perde todos os dados.

---

# Recriar DB + seeders

```bash
php artisan migrate:fresh --seed
```

### O que faz

* recria DB
* executa seeders

---

# Ver rotas

```bash
php artisan route:list
```

### O que faz

Lista todas as rotas da aplicação.

Muito útil para debugging.

---

# Limpar caches Laravel

```bash
php artisan optimize:clear
```

### O que faz

Limpa:

* config cache
* route cache
* view cache
* event cache

### Quando usar

Quando Laravel parece:

* ignorar alterações
* usar config antiga
* mostrar comportamento estranho

---

# Executar testes

```bash
php artisan test
```

### O que faz

Executa testes PHPUnit/Pest.

---

# Criar model + migration

```bash
php artisan make:model Concept -m
```

### O que faz

Cria:

```text
app/Models/Concept.php
```

E:

```text
database/migrations/xxxx_create_concepts_table.php
```

---

# Criar migration

```bash
php artisan make:migration create_concepts_table
```

### O que faz

Cria apenas migration.

---

# Criar teste

```bash
php artisan make:test ConceptTest
```

### O que faz

Cria teste feature.

---

# NPM / Frontend

# Instalar dependências frontend

```bash
npm install
```

### O que faz

Instala:

* Vite
* Tailwind
* Alpine
* frontend packages

Cria:

```text
/node_modules
```

---

# Iniciar Vite

```bash
npm run dev
```

### O que faz

Inicia servidor frontend.

Responsável por:

* Tailwind
* CSS
* assets
* hot reload

### IMPORTANTE

Manter terminal aberto.

---

# Build produção

```bash
npm run build
```

### O que faz

Compila assets finais otimizados.

Gera:

```text
/public/build
```

Usado em produção.

---

# Atualizar packages frontend

```bash
npm update
```

### O que faz

Atualiza dependências frontend.

---

# Git

# Ver estado do repositório

```bash
git status
```

### O que faz

Mostra:

* ficheiros alterados
* staged
* commits pendentes

---

# Adicionar alterações

```bash
git add .
```

### O que faz

Prepara ficheiros para commit.

---

# Criar commit

```bash
git commit -m "Mensagem"
```

### O que faz

Cria snapshot do projeto.

---

# Enviar para GitHub

```bash
git push
```

### O que faz

Envia commits para GitHub.

---

# Atualizar repositório local

```bash
git pull
```

### O que faz

Obtém alterações do GitHub.

---

# PostgreSQL

# pgAdmin

Aplicação gráfica para:

* gerir base de dados
* executar queries
* criar DBs
* visualizar tabelas

---

# Configuração típica no .env

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=lexicraft
DB_USERNAME=postgres
DB_PASSWORD=postgres
```

---

# Meilisearch

# Iniciar Meilisearch

Ir para:

```text
C:\Meilisearch
```

Executar:

```bash
.\meilisearch.exe
```

---

# O que faz

Inicia o motor de pesquisa semântica.

Responsável por:

* pesquisa rápida
* typo tolerance
* autocomplete
* pesquisa multilanguage

---

# URL

```text
http://127.0.0.1:7700
```

---

# Configuração Scout no .env

```env
SCOUT_DRIVER=meilisearch
MEILISEARCH_HOST=http://127.0.0.1:7700
```

---

# Fluxo Diário Correto

# Sempre que começares a trabalhar

## 1

Abrir Laragon

---

## 2

Garantir PostgreSQL ativo

---

## 3

Abrir VS Code

---

## 4

Abrir projeto

---

## 5

Terminal 1

```bash
php artisan serve
```

---

## 6

Terminal 2

```bash
npm run dev
```

---

## 7 (Opcional)

```bash
.\meilisearch.exe
```

---

# URLs Importantes

# Laravel

```text
http://127.0.0.1:8000
```

---

# Meilisearch

```text
http://127.0.0.1:7700
```

---

# Conceitos Fundamentais

# Laravel

Backend framework.

Responsável por:

* rotas
* DB
* auth
* lógica
* SEO
* rendering

---

# Livewire

Sistema reativo frontend/backend usando PHP.

Permite:

* interfaces dinâmicas
* sem SPA complexa
* sem APIs separadas

---

# Volt

Single File Components para Livewire.

Permite:

* lógica
* Blade
* estado

No mesmo ficheiro.

---

# Vite

Frontend bundler.

Responsável por:

* compilar assets
* Tailwind
* hot reload

---

# PostgreSQL

Base de dados relacional principal.

---

# Meilisearch

Motor especializado em pesquisa rápida.

---

# Scout

Ponte entre Laravel e Meilisearch.

---

# Tailwind

Framework CSS utility-first.

---

# Alpine.js

Micro-framework JavaScript leve.

Usado para:

* dropdowns
* modals
* interações simples

---

# Estrutura Correta de Terminais

# Terminal 1

```bash
php artisan serve
```

---

# Terminal 2

```bash
npm run dev
```

---

# Terminal 3 (opcional)

```bash
.\meilisearch.exe
```

---

# Regras Importantes

## Nunca fazer commit do `.env`

---

## Nunca regenerar APP_KEY em produção

---

## Não usar `composer update` frequentemente

---

## Não usar `migrate:fresh` sem perceber consequências

---

## Manter migrations pequenas e organizadas

---

## Executar `php artisan test` frequentemente

---

## Usar commits claros

Bom exemplo:

```text
Add multilingual concept architecture
```

Mau exemplo:

```text
fix stuff
```
