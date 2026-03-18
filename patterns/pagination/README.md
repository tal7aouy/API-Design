# Pagination Pattern

We use a **cursor-based** pagination pattern by default.

---

## Response Shape

```json
{
  "data": [/* items */],
  "pagination": {
    "next_cursor": "opaque-string-or-null",
    "previous_cursor": null,
    "limit": 20
  }
}
```

- `data` – Array of resources.
- `next_cursor` – Opaque token for the next page.
- `limit` – Requested page size.

---

## Request Params

- `cursor` – (optional) token from previous response.
- `limit` – (optional) page size, with sane max.

All three languages have implementations in this folder.

