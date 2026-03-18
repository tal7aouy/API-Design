# API Basics

## What is an API?

An **API (Application Programming Interface)** is a contract that lets two pieces of software talk to each other. In web development, that contract is usually:

- **Input**: HTTP method + URL + headers + body
- **Output**: HTTP status code + headers + body (often JSON)

A good API is **predictable**, **consistent**, and **boring in a good way**.

---

## Core HTTP Concepts

### Resources and URLs

Think in **resources**, not RPC-style verbs.

- **Bad**: `/doCreatePost`, `/getAllProductsNow`
- **Good**: `/posts`, `/products/{id}`

A resource usually maps to a **noun** in your domain: `users`, `orders`, `comments`.

### HTTP Methods

- `GET` – Read data (safe, idempotent)
- `POST` – Create data (not idempotent)
- `PUT` – Replace a resource (idempotent)
- `PATCH` – Partially update a resource
- `DELETE` – Delete a resource (idempotent at the contract level)

Use the **semantics**, not just "whatever works".

### Status Codes (Practical Subset)

- `200 OK` – Successful GET / non-creating POST
- `201 Created` – Resource created
- `204 No Content` – Success, no response body
- `400 Bad Request` – Client error (validation failed)
- `401 Unauthorized` – Missing/invalid authentication
- `403 Forbidden` – Authenticated but not allowed
- `404 Not Found` – Resource doesn’t exist
- `409 Conflict` – Version/idempotency conflicts
- `422 Unprocessable Entity` – Semantic validation errors
- `500 Internal Server Error` – Unexpected server failure

You don’t need all 60+ status codes—just **use this subset consistently**.

---

## Representations (JSON)

Most APIs here use **JSON**. A typical pattern:

```json
{
  "id": "post_123",
  "title": "API Design 101",
  "body": "...",
  "author": {
    "id": "user_1",
    "name": "Alice"
  }
}
```

Guidelines:

- Use **snake_case** or **camelCase** consistently (this repo uses `snake_case` in JSON by default).
- Return **stable IDs** (`"post_123"`, not array indices).
- Avoid leaking internal DB schema (e.g. no `password_hash`, no `is_deleted` flags).

---

## Statelessness

APIs in this repo are designed to be **stateless**:

- Each request contains everything the server needs (auth token, filters, etc.).
- Server does not store per-client session state in memory.

Statelessness makes it easier to **scale horizontally** and reason about behavior.

---

## Idempotency (Intro)

An operation is **idempotent** if calling it once or many times has the **same effect**.

- `GET /posts/1` – idempotent
- `PUT /posts/1` – idempotent (replace the resource)
- `DELETE /posts/1` – idempotent from the client’s perspective
- `POST /posts` – usually **not idempotent** unless you add an idempotency key

You will see real idempotency implementations in the **advanced topics** and **e‑commerce API**.

