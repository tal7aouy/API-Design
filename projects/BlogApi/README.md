# Blog API (Mini Project)

This folder contains full **Blog API** implementations in:

- **PHP** (Laravel-style patterns)
- **TypeScript** (Express)
- **Python** (FastAPI)

## Features

- CRUD posts
- Comments
- JWT-style auth (demo token)
- Pagination pattern

## Running

### PHP

```bash
cd projects/BlogApi/php
php -S localhost:8001 bootstrap.php
```

### TypeScript

```bash
cd languages/typescript
npm install
npm run dev:blog
```

### Python

```bash
cd languages/python
pip install -r requirements.txt
uvicorn projects.blog_api.main:app --reload
```
