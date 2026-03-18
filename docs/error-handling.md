# Error Handling

Bad APIs leak stack traces or return `500` for everything.

Good APIs:

- Use **consistent error shapes**.
- Distinguish between client and server errors.
- Log enough detail internally, not in responses.

---

## Error Response Shape

We standardize on this structure:

```json
{
  "error": {
    "code": "VALIDATION_ERROR",
    "message": "One or more fields are invalid.",
    "details": {
      "title": ["This field is required."]
    }
  }
}
```

- `code` – Stable machine-readable string.
- `message` – Human-readable summary, safe for clients.
- `details` – Optional field-level errors.

You’ll see framework-specific mappings in all three languages.

---

## Mapping Exceptions to HTTP

**Client-side issues (4xx)**:

- Validation errors → `400` or `422`.
- Auth issues → `401` or `403`.
- Conflicts → `409`.

**Server-side issues (5xx)**:

- Unexpected exceptions.
- External service outages.

In code we:

- Throw domain-specific exceptions from services.
- Map them to HTTP in controllers/middleware.

---

## Examples

- `examples/bad/*` – plain strings, inconsistent formats, leaking internals.
- `examples/good/*` – unified error format, proper status codes.

