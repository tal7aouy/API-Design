# Filtering Pattern

Filtering should be **predictable and documented**.

We use query params with clear, stable names:

- `?status=published`
- `?author_id=user_123`
- `?created_before=2024-01-01`

Complex filters can use bracketed syntax or structured JSON, but for most APIs **simple query params are enough**.

Each language example shows:

- Parsing allowed filters.
- Rejecting unknown/unsupported filters.
- Passing clean filter objects into the service/repository layer.

