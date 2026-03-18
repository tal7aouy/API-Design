# API Design Principles

This repo is intentionally **opinionated**. The principles below drive all code examples.

---

## 1. Design for Consumers, Not Servers

Bad APIs mirror database tables. Good APIs mirror **use cases**.

- Ask: *What does the client need to do?*
- Avoid over-chatty designs that require 5 calls to render a page.

**Example** – Blog post detail page:

- **Bad**: client calls `/posts`, `/users`, `/comments`, `/likes` separately.
- **Good**: client calls `/posts/{id}` and gets the post, author summary, and comments needed for that screen.

---

## 2. Consistency Over Cleverness

- Use the **same patterns** for pagination, filtering, and errors everywhere.
- Follow a documented naming convention for fields and endpoints.

If a junior dev can guess your endpoint shape without docs, you’re winning.

---

## 3. Separation of Concerns

In code we enforce **layers**:

- **Controller / Route handler** – HTTP concerns only (params, status codes).
- **Service** – Business rules and workflows.
- **Repository / Data access** – Persistence only.
- **DTO / Validator** – Defines and validates request/response shapes.

This keeps controllers **thin** and services **testable**.

You will see this pattern repeated in:

- Laravel-style controllers + services
- Express route handlers + service classes
- FastAPI routers + dependency-injected services

---

## 4. Validation First

Never trust input:

- Validate **shape** (required fields, types) and **semantics** (title length, price > 0).
- Fail **fast** with clear error payloads.

We standardize error responses as something like:

```json
{
  "error": {
    "code": "VALIDATION_ERROR",
    "message": "One or more fields are invalid.",
    "details": {
      "title": ["This field is required."],
      "price": ["Must be greater than 0."]
    }
  }
}
```

You’ll see this structure applied in all three languages.

---

## 5. Explicit, Stable Contracts

Once an endpoint is public, you’re **on the hook** for it.

- Avoid breaking changes.
- Prefer **additive** evolution (new optional fields, new endpoints).
- Use **versioning** when you must break compatibility.

We document strategies in `docs/versioning.md` and show examples in the projects.

---

## 6. Secure by Default

Common mistakes we call out in `examples/bad/`:

- Returning sensitive fields (password hashes, internal IDs).
- Inconsistent auth checks.
- Overly verbose error messages in auth flows.

Good examples show:

- Centralized auth middleware/dependencies.
- Principle of least privilege.
- Clean separation between **authn** (who are you) and **authz** (what can you do).

---

## 7. Observability

A production-grade API must be **observable**:

- Structured logging of requests, responses (with care for PII).
- Correlation IDs for tracing.
- Metrics for latency, error rates, and key business flows.

In the mini projects we show how to log:

- Incoming requests with correlation IDs.
- High-level domain events (order created, post published).

---

## 8. Backwards Compatibility & Deprecation

- Add fields; don’t rename them.
- Add new endpoints; don’t change meanings of existing ones.
- When you must deprecate, provide **overlap** time and documentation.

We demonstrate this with **v1 vs v2** examples in the blog and e‑commerce APIs.

