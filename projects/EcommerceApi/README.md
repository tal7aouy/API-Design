# E-commerce API (Mini Project)

This folder contains full **E-commerce API** implementations in:

- **PHP** (Laravel-style patterns)
- **TypeScript** (Express)
- **Python** (FastAPI)

## Features

- Products CRUD
- Orders with inventory validation
- Idempotent order creation (Idempotency-Key)

## Running

### PHP

```bash
cd projects/EcommerceApi/php
php -S localhost:8002 bootstrap.php
```

### TypeScript

```bash
cd languages/typescript
npm install
npm run dev:ecommerce
```

### Python

```bash
cd languages/python
pip install -r requirements.txt
uvicorn projects.ecommerce_api.main:app --reload
```
