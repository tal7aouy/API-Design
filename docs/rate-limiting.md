# Rate Limiting

Rate limiting protects your API and other users from abuse and mistakes.

---

## Concepts

- **Limit** – Maximum allowed requests in a window (e.g. 100/minute).
- **Window** – Time period over which you count.
- **Key** – What you limit on (IP, user ID, API key).

---

## Approaches

1. **Fixed Window** – Simple counter per key per window.
2. **Sliding Window / Token Bucket** – Smoother distribution.
3. **Leaky Bucket** – Focus on average rate.

For teaching, we implement a simple **token bucket-like** approach in:

- Express middleware.
- FastAPI dependency.

We store counts in memory for simplicity; in real systems you’d use Redis.

---

## HTTP Contracts

When you throttle a client:

- Return `429 Too Many Requests`.
- Include useful headers:
  - `Retry-After`
  - `X-RateLimit-Limit`
  - `X-RateLimit-Remaining`

