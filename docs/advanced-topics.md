# Advanced Topics

This doc ties together advanced API design concerns used in the mini projects.

---

## 1. Idempotency

Idempotency matters most for **operations with side effects**:

- Creating orders.
- Charging credit cards.
- Posting webhooks.

We implement an **idempotency key** approach for order creation in the e‑commerce API:

- Clients send `Idempotency-Key` header.
- Server stores a record (`key`, `request_hash`, `response_body`).
- If the same key is seen again with the same request hash, server returns the cached response.

This prevents **double orders** when the client retries on network failures.

---

## 2. Caching Strategies

### Client/Proxy Caching

Use HTTP headers:

- `Cache-Control`, `ETag`, `Last-Modified`.

Great for:

- Public data (product catalogs, blog posts).

### Server-Side Caching

Use in-memory/Redis caches for:

- Expensive queries (top products, home page posts).
- Derived aggregates.

Always define:

- **Key** – How you look up cached data.
- **TTL** – How long it’s valid.
- **Invalidation** – When to clear/update.

Patterns are demonstrated in the `patterns/caching` examples.

---

## 3. OpenAPI / Swagger

We use **OpenAPI** to describe REST APIs:

- FastAPI can auto-generate docs at `/docs`.
- For Express and Laravel-style code, we show how to maintain a minimal `openapi.yaml`.

Benefits:

- Contracts are explicit.
- Tooling for client generation and documentation.

---

## 4. API Security

We apply:

- HTTPS everywhere (assumed).
- JWT for auth.
- Principle of least privilege.
- Avoiding sensitive data in logs and responses.

We also call out common security issues in `examples/bad/*`.

---

## 5. Logging & Monitoring

We use **request correlation IDs** to tie logs together across services and clients:

- Each request may supply `X-Request-Id`, otherwise one is generated.
- Responses include `X-Request-Id` so clients can correlate failures.

In the mini projects we:

- Log incoming requests with correlation IDs.
- Log domain events (post created, order placed).

In real systems you’d integrate with:

- Centralized logging (ELK, Splunk, Datadog).
- Metrics systems (Prometheus, CloudWatch).

The patterns shown here map directly to those tools.

