# Types of APIs

This section covers:

- REST
- GraphQL
- RPC
- Webhooks
- gRPC (intro)

For each type we’ll look at **when to use it**, **pros/cons**, and **real-world examples**.

---

## 1. REST APIs

### When to Use

- Public or third-party APIs.
- CRUD-heavy domains: blogs, e‑commerce, ticketing, CRM.
- When you care about HTTP semantics, caching, and interoperability.

### Pros

- Simple model: resources + HTTP verbs.
- Fits naturally with HTTP infrastructure (gateways, CDNs).
- Easy onboarding for most engineers.

### Cons

- Can lead to over/under-fetching.
- Harder to express complex queries elegantly.

### Real-World Use Cases

- GitHub REST API
- Stripe’s core REST endpoints
- Most SaaS public APIs

Our **Blog API** and **E‑commerce API** are both REST.

---

## 2. GraphQL

### When to Use

- Rich client applications (web/mobile) with complex data needs.
- Multiple frontend teams consuming the same backend.

### Pros

- Clients specify exactly what fields they need.
- Strongly typed schema; good tooling.

### Cons

- Overhead of a GraphQL server and schema.
- Requires careful performance tuning (N+1 queries, complexity limits).
- Authorization rules can be subtle.

### Real-World Use Cases

- GitHub GraphQL API
- Shopify Storefront API

**Pattern to notice**: our REST controllers and services are structured so that you could easily reuse them as **GraphQL resolvers**.

---

## 3. RPC (Remote Procedure Call)

### Description

RPC treats the network like a place to call **methods**:

- `POST /rpc/CreateOrder` with a JSON body.
- `POST /rpc/UserService.GetProfile`.

### When to Use

- **Internal** service-to-service communication where you control both sides.
- Simple backoffice tools where ergonomics matter more than public stability.

### Pros

- Straightforward mapping from functions to endpoints.
- Often less ceremony when you own both client and server.

### Cons

- Tends to produce **chatty** APIs.
- Easy to create tight coupling between client and server implementation.
- Harder to evolve without coordination.

### Example Use Cases

- Internal admin panels.
- Communication between microservices behind a gateway.

In `examples/bad/` you’ll see **REST over RPC** anti-patterns, like `/createPostNow`.

---

## 4. Webhooks

### Description

Webhooks are **outbound callbacks** from your API to the client’s server.

- Client registers a URL (e.g. `https://client.app/webhooks/payment`).
- Your system sends HTTP requests to that URL on specific events.

### When to Use

- Integrations where clients need to react to events:
  - Payment succeeded
  - Order shipped
  - Comment created

### Pros

- Decouples producers from consumers.
- Great for event-driven workflows.

### Cons

- Delivery reliability and retries are your problem.
- Client endpoints may be slow or unreliable.
- Security (signing, IP allowlists) must be handled carefully.

### Real-World Use Cases

- Stripe, PayPal, and most payment providers.
- GitHub webhooks for repo events.

In `docs/advanced-topics.md` we touch on **idempotent webhook handlers**.

---

## 5. gRPC (Intro)

### Description

- Uses **Protocol Buffers** for schema.
- Runs over HTTP/2.
- Supports streaming and bidirectional communication.

### When to Use

- High-performance **service-to-service** communication.
- Polyglot microservice environments.

### Pros

- Compact, fast, and type-safe.
- First-class streaming support.

### Cons

- Not as friendly to browsers without a gateway.
- More operational complexity.

### Real-World Use Cases

- Large-scale microservice architectures (Google, Netflix-style).

We won’t build a full gRPC service here, but you’ll see patterns (DTOs, clear contracts, explicit versioning) that map directly to good gRPC design.

