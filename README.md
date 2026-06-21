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

## Design Decisions

- Doctrine transactions ensure atomic transfers.
- Pessimistic locking prevents race conditions.
- Redis-backed idempotency prevents duplicate requests.
- Transfer audit records maintain traceability.
- BCMath is used for monetary calculations.

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

rename file .env-backup as .env


```env
DATABASE_URL="mysql://root:password@127.0.0.1:3306/{database_name}"
REDIS_URL=redis://127.0.0.1:6379
```

### Create Database
Create a database of your choice and place the database name in the DATABASE_URL="" as mentioned above
```bash
php bin/console doctrine:database:create
```

### Run Migrations
It will populate the necessary table named `accounts` and `transfer`
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
http://127.0.0.1:8000/
```
![Architecture Diagram](assets/docs/img_0_home.png)
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
![Architecture Diagram](assets/docs/img_1_create.png)
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
![Architecture Diagram](assets/docs/img_2_view.png)

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
![Architecture Diagram](assets/docs/img_3_update.png)

---

### Transfer Funds

**POST**

```http
/app/transfer
```
![Architecture Diagram](assets/docs/img_4_transfer.png)

Headers:

```http
Idempotency-Key: txn-123456
```

Request:
![Architecture Diagram](assets/docs/img_5_idempotency.png)

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

Approximate time spent: ~ 7 - 8 hours

---

## AI Assistance

AI tools used:

- ChatGPT
- GitHub Copilot

AI was used to assist with:
- Architecture discussions
- Documentation preparation

All generated code was reviewed, modified, tested, and validated manually.

---
##  Future Improvements

* Enhanced Validation & API Documentation
  Introduce Request DTOs, Symfony Validator constraints, and OpenAPI/Swagger documentation for improved API usability and maintainability.
* Improved Security
  Add authentication, authorization, rate limiting, and stronger idempotency key management to further secure financial transactions.
* Scalability & Performance
  Introduce asynchronous processing with Symfony Messenger, distributed locking, and advanced caching strategies for high-volume workloads.
* Observability & Monitoring
  Implement structured logging, metrics collection, health checks, and monitoring dashboards to improve operational visibility.
* Infrastructure & Automation
  Add Docker support, CI/CD pipelines, automated deployment workflows, and load testing to enhance production readiness.
