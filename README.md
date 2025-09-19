# MiniFrame

**MiniFrame**, desenvolvido por **Micael Parise**.
Este projeto é um mini framework **PHP**, com objetivo de dar um inicio rápido para projetos simples com objetivo de serem leves.

---

## Tecnologias Utilizadas

- **PHP**: ^8.3
- **Composer**: Gerenciador de dependências PHP

---

## Pré-requisitos

Antes de iniciar, você precisa ter instalado em sua máquina:

1. **PHP** (>= 8.3): [Download PHP](https://www.php.net/downloads)
2. **Composer**: [Download Composer](https://getcomposer.org/download/)
3. Um banco de dados relacional (MySQL, SQLite)

---

## Instalação e Configuração do Ambiente

A seguir, o **passo a passo completo** para rodar o projeto na sua máquina local:

---

### 1. Clonar o Repositório

Abra o terminal e execute:

```bash
gh repo clone MicaelParise/MiniFrame
cd MiniFrame
```

### 2. Instalar as Dependências PHP

Para instalar todas as dependências do Laravel, Execute:

```bash
composer install
```

### 3. Configurar o Ambiente

Laravel utiliza um arquivo ".env" para armazenar variáveis de ambiente. Para criar o seu:

```bash
cp .env.example .env
```

### 4. Configurar o Banco de Dados

Abra o arquivo ".env" no seu editor de texto e configure as seguintes variáveis conforme o seu ambiente:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=chronos_db
DB_USERNAME=root
DB_PASSWORD=
```
> Você pode utilizar MySQL ou SQLite.

- Exemplo usando SQLite:

```dotenv
DB_CONNECTION=sqlite
#DB_HOST=127.0.0.1
#DB_PORT=3306
#DB_DATABASE=chronos_db
#DB_USERNAME=root
#DB_PASSWORD=
```

### 5. Rodar as Migrações

As migrações criam as tabelas do banco de dados necessárias para o funcionamento da aplicação. Para executá-las:

```bash
php artisan migrate
```

---

## Observações Finais

- Este projeto é **privado** e destinado exclusivamente para uso interno e pessoal de **Micael Parise**.
- Para qualquer dúvida técnica, consulte o time responsável.