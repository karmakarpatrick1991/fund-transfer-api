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
git clone https://github.com/karmakarpatrick1991/fund-transfer-api
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
## API Response Format

All APIs return a consistent JSON response structure:

Success Response
---
```
{
    "status": 200,
    "error": null,
    "message": {
        "account_no": 1,
        "reference_no": "5f8e19d3172a8ead1338ecccbc80b304",
        "balance": "1000.00",
        "message": "Account info successfully retrieved."
    }
}
```
Error Response
---
```
{
    "status": 400,
    "error": "INVALID_BALANCE_PAYLOAD",
    "message": {
        "account_no": null,
        "reference_no": null,
        "message": "Invalid Balance Payload/Balance must be numeric and greater than 0"
    }
}
```
COMMON ERROR CODES
---
## Common Error Codes

| HTTP Status | Error Code               | Description                                               |
|-------------|--------------------------|-----------------------------------------------------------|
| 200         | SUCCESS                  | Successful Operation.                                     |
| 400         | MISSING_ACCOUNT_PAYLOAD  | Account creation payload is missing.                      |
| 400         | INVALID_BALANCE          | Required balance field is missing.                        |
| 400         | INVALID_BALANCE_VALUE    | Balance value must be numeric.                            |
| 400         | MINIMUM_REQUIRED_BALANCE | Initial balance must be greater than or equal to zero.    |
| 400         | MISSING_UUID_REFERENCE   | Account reference UUID is missing.                        |
| 400         | MISSING_BALANCE_PAYLOAD  | Update payload is missing.                                |
| 400         | INVALID_BALANCE_PAYLOAD  | Balance must be numeric and greater than or equal to zero. |
| 400         | MISSING_IDEMPOTENCY_KEY  | Idempotency-Key header is missing.                        |
| 404         | ACCOUNT_NOT_FOUND        | Account does not exist.                                   |
| 409         | DUPLICATE_TRANSACTION    | Transfer already processed with the same Idempotency-Key. |
| 422         | INSUFFICIENT_FUNDS       | Source account has insufficient balance.                  |
| 500         | INTERNAL_SERVER_ERROR    | Unexpected system error occurred.                         |
---

## Redis Usage

Redis is used for:

- Idempotency key storage
- Account data caching
- Cache invalidation on account updates and transfers

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

Approximate time spent: ~7 hours

---

## AI Assistance

AI tools used:

- ChatGPT
- GitHub Copilot

AI was used to assist with:
- Architecture discussions
- Symfony setup guidance
- Documentation preparation

All generated code was reviewed, modified, tested, and validated manually.

---
