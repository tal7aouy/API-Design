## Production-Ready API Design

This repository is a **hands-on, opinionated curriculum** for learning API design from **beginner to advanced**, using **real, production-style code** in:

- **PHP** (`Laravel-style`)
- **TypeScript** (`Express`-style Node)
- **Python** (`FastAPI`)

You will learn how to design, implement, and evolve APIs that are **clean**, **consistent**, **secure**, and **maintainable**.

---

## Repository Structure

```text
README.md
docs/
examples/
projects/
patterns/
languages/
```

- `docs/` – Core API design concepts and theory.
- `examples/` – Focused **bad vs good** code samples.
- `projects/` – Two mini production-style APIs (Blog + E‑commerce).
- `patterns/` – Reusable API patterns (pagination, filtering, sorting, caching).
- `languages/` – Language-specific foundations and bootstrapping.

---

## Learning Roadmap

Follow this order if you want a **guided curriculum**:

1. **Foundations**
   - `docs/api-basics.md`
   - `docs/api-design-principles.md`
   - `docs/rest-vs-graphql.md`
2. **API Types & Trade-offs**
   - `docs/api-types.md`
3. **Core Cross-Cutting Concerns**
   - `docs/authentication.md`
   - `docs/error-handling.md`
   - `docs/rate-limiting.md`
   - `docs/versioning.md`
4. **Patterns**
   - `patterns/pagination/README.md`
   - `patterns/filtering/README.md`
   - `patterns/sorting/README.md`
   - `patterns/caching/README.md`
5. **Bad vs Good Examples**
   - `examples/bad/` vs `examples/good/` (compare side by side)
6. **Mini Projects**
   - `projects/BlogApi/` – posts, comments, JWT auth, pagination
   - `projects/EcommerceApi/` – products, orders, inventory logic
7. **Advanced Topics**
   - `docs/advanced-topics.md`

At every step, you will see **Laravel**, **Express (TypeScript)**, and **FastAPI** implementations.

---

## Running the Examples

### Prerequisites

- **PHP 8.1+** with Composer
- **Node.js 18+** with npm or pnpm
- **Python 3.10+** with `pip`

Each language folder has its own minimal setup.

---

### PHP (Laravel-style)

Code lives in `languages/php/` and per-project folders.

Install dependencies:

```bash
cd languages/php
composer install
```

Run the example HTTP server (using Laravel-style controllers/services but a minimal bootstrap script):

```bash
# Run the Blog API
cd projects/BlogApi/php
php -S localhost:8001 bootstrap.php

# Run the E-commerce API
cd projects/EcommerceApi/php
php -S localhost:8002 bootstrap.php
```

See:

- `examples/good/php/` for focused examples
- `projects/BlogApi/php/` and `projects/EcommerceApi/php/` for mini APIs

---

### TypeScript (Express)

Code lives in `languages/typescript/` and project-specific subfolders.

Install dependencies:

```bash
cd languages/typescript
npm install
```

Run a project (example: Blog API):

```bash
npm run dev:blog
```

See:

- `examples/good/typescript/`
- `projects/BlogApi/typescript/`
- `projects/EcommerceApi/typescript/`

---

### Python (FastAPI)

Code lives in `languages/python/` and project-specific subfolders.

Install dependencies:

```bash
cd languages/python
pip install -r requirements.txt
```

Run a project (example: Blog API):

```bash
uvicorn projects.blog_api.main:app --reload
```

See:

- `examples/good/python/`
- `projects/BlogApi/python/`
- `projects/EcommerceApi/python/`

---

## What You Will Learn (Highlights)

- **API Types**: REST, GraphQL, RPC, Webhooks, gRPC – when to use each, with realistic examples.
- **Bad vs Good Design**: Naming, endpoints, validation, error handling, and security mistakes, plus corrected versions.
- **Architecture**: Controllers vs Services vs Repositories, DTOs, Validation, Dependency Injection, and **thin controllers, fat services**.
- **SOLID for APIs**: How SRP, OCP, and others apply to request handling and business logic.
- **Advanced Topics**: Versioning, idempotency, rate limiting, caching, OpenAPI/Swagger, JWT & OAuth2 basics, logging & monitoring.

---

## How to Use This Repo

- **As a guided course**: Follow the roadmap from top to bottom and implement exercises as you go.
- **As a reference**: Jump to `patterns/` or `projects/` when you need real examples of clean API design.
- **As a teaching aid**: Use the `examples/bad` vs `examples/good` sections for code reviews and workshops.

The code is deliberately **opinionated** but grounded in **real-world production experience**.
