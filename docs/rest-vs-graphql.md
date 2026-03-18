# REST vs GraphQL (and friends)

Most examples in this repo use **REST**, but you should understand when **GraphQL** is a better fit.

---

## REST (Representational State Transfer)

**Shape**

- Resources as URLs: `/posts`, `/posts/{id}`, `/users/{id}/orders`.
- Fixed endpoints with flexible query params.

**When to use**

- Public APIs, third-party integrations.
- Simple to medium complexity domains.
- When caching via HTTP/CDN matters a lot.

**Pros**

- Simple mental model.
- Native browser and HTTP tooling support.
- Great fit for resource-oriented problems.

**Cons**

- Over-fetching or under-fetching for complex UIs.
- Versioning is explicit and sometimes heavy.

You’ll see REST used for the **Blog** and **E‑commerce** mini projects.

---

## GraphQL

**Shape**

- Single endpoint (usually `/graphql`).
- Clients send queries specifying exactly what they need.

**When to use**

- Complex frontends (e.g. large SPAs, mobile apps) that need many related resources.
- When different clients need different shapes of similar data.

**Pros**

- Clients avoid over/under-fetching.
- Strong schema with introspection.

**Cons**

- More operational complexity.
- Harder to leverage HTTP caching.
- Requires strict performance and authorization discipline.

In this repo, `docs/api-types.md` outlines GraphQL concepts; the code focuses on REST but is structured in a way that maps cleanly to GraphQL resolvers.

---

## RPC, Webhooks, gRPC (Quick Contrast)

- **RPC** – Procedure calls over HTTP or other transports; feels like calling methods remotely.
- **Webhooks** – Your API **calls the client** when events happen.
- **gRPC** – Contract-first, binary protocol (Protobuf) over HTTP/2, great for service-to-service backends.

We go into each in more detail in `docs/api-types.md` with when/why to choose them.

