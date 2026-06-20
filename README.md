# Fund Transfer API

A secure fund transfer API built with Symfony, MySQL, and Redis.

## Features

- Account Creation
- Account Balance Update
- Account Retrieval
- Secure Fund Transfer
- Idempotency Support
- Redis Caching
- Transaction Integrity using MySQL Transactions
- Pessimistic Locking to prevent concurrent balance issues
- Error Handling and Validation

---

## Requirements

- PHP 8.3+
- Composer
- MySQL 8+
- Redis
- Symfony CLI (optional)

---

## Installation

### Clone Repository

```bash
git clone <repository-url>
cd fund-transfer-api
```

### Install Dependencies

```bash
composer install
```

### Configure Environment

Create `.env.local`

```env
DATABASE_URL="mysql://root:password@127.0.0.1:3306/paysera_transfer_db"
REDIS_URL=redis://127.0.0.1:6379
```

### Create Database

```bash
php bin/console doctrine:database:create
```

### Run Migrations

```bash
php bin/console doctrine:migrations:migrate
```

### Start Redis

```bash
redis-server
```

Verify Redis:

```bash
redis-cli ping
```

Expected response:

```text
PONG
```

### Start Application

```bash
symfony serve
```

Application will be available at:

```text
https://127.0.0.1:8000
```

---

## API Usage

### Create Account

**POST**

```http
/app/create
```

Request:

```json
{
    "initial_balance": 1000
}
```

---

### Get Account

**GET**

```http
/app/account/{account_uuid}
```

Example:

```http
/app/account/5f8e19d3172a8ead1338ecccbc80b304
```

---

### Update Account Balance

**PUT**

```http
/app/update/{account_uuid}
```

Request:

```json
{
    "balance": 2000
}
```

---

### Transfer Funds

**POST**

```http
/app/transfer
```

Headers:

```http
Idempotency-Key: txn-123456
```

Request:

```json
{
    "source_account_id": 1,
    "destination_account_id": 2,
    "amount": 100
}
```

---

## Redis Usage

Redis is used for:

- Idempotency key storage
- Account data caching
- Cache invalidation on account updates

---

## Architecture

```text
Client
  |
Symfony API
  |
  +---- Redis
  |        |
  |        +---- Account Cache
  |        +---- Idempotency Keys
  |
  +---- MySQL
           |
           +---- Accounts
           +---- Transfers
```

---

## Time Spent

Approximate time spent: ~X hours

---

## AI Assistance

AI tools used:

- ChatGPT
- GitHub Copilot

AI was used to assist with:
- Architecture discussions
- Symfony setup guidance
- Redis integration
- Documentation preparation

All generated code was reviewed, modified, tested, and validated manually.

---
