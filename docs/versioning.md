# API Versioning

Versioning is about **protecting clients** as you evolve your API.

---

## When to Version

Version when you make a **breaking change**:

- Removing a field.
- Changing the meaning or type of a field.
- Changing response shape in a way that breaks existing clients.

Adding optional fields or new endpoints is usually **non-breaking**.

---

## Common Strategies

### 1. URI Versioning (Recommended Here)

Example:

- `/v1/posts/123`
- `/v2/posts/123`

Pros:

- Very explicit.
- Easy to route and document.

Cons:

- URL changes; may require clients to update many references.

### 2. Header-Based Versioning

Example:

- `Accept: application/vnd.example.v1+json`

Pros:

- Clean URLs.

Cons:

- Less visible.
- Harder to debug with just a browser.

### 3. Field-Level Versioning (Advanced)

Sometimes you can avoid endpoint-level versioning by:

- Keeping old fields but marking them as deprecated.
- Introducing new fields (`total_price_v2`) alongside old ones.

This works best when you **control clients** and can migrate them quickly.

---

## Versioning in This Repo

We use **URI versioning** in the mini projects:

- `projects/BlogApi/*` expose `/api/v1/...` routes.
- The e‑commerce API shows how a `v2` might evolve from `v1` for orders.

Patterns you’ll see:

- Shared service layer across versions when business rules are mostly the same.
- Thin v1/v2 controllers that adapt HTTP contracts to the same core services.
