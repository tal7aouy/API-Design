# Authentication & Authorization

APIs must know **who** is calling and **what** they are allowed to do.

We focus on **JWT-based auth** for simplicity, with notes on OAuth2.

---

## Authentication vs Authorization

- **Authentication (authn)** – Proving identity (login, tokens).
- **Authorization (authz)** – Checking permissions for a given identity.

In all three languages we:

- Extract a **user identity** from a JWT.
- Attach it to the request context.
- Use services to enforce authorization rules.

---

## JWT (JSON Web Token) Basics

A JWT is a signed token containing claims like:

```json
{
  "sub": "user_123",
  "role": "admin",
  "exp": 1735689600
}
```

Guidelines:

- Use **HS256** or **RS256** with a strong secret / key pair.
- Keep tokens short-lived.
- Store minimal claims (user ID, role/permissions, maybe tenant ID).

---

## Common Pitfalls (Shown in `examples/bad/`)

- Accepting JWTs without verifying signatures.
- Relying only on user IDs from the client body.
- Returning overly specific auth error messages.

---

## Patterns in This Repo

We implement a small but realistic stack:

- **PHP (Laravel-style)** – Auth middleware that parses JWT and populates `AuthUser` DTO; controllers require it.
- **TypeScript (Express)** – Express middleware adds `req.user`; services use that for authorization.
- **Python (FastAPI)** – Dependency that decodes JWT and returns a `CurrentUser` Pydantic model.

All mini projects require JWT for:

- Creating/editing posts and comments.
- Placing orders, viewing user-specific data.

---

## OAuth2 (High-Level)

We won’t implement full OAuth2 flows here, but you should know:

- Use OAuth2 when delegating auth to third parties (Google, GitHub) or exposing APIs to other organizations.
- Prefer **Authorization Code with PKCE** for browser-based and mobile apps.

You can still apply all the patterns in this repo **behind** an OAuth2 gateway.

