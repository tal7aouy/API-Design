# Sorting Pattern

Sorting is controlled via:

- `sort_by` – field name (e.g. `created_at`, `price`).
- `sort_direction` – `asc` or `desc`.

Example:

- `/posts?sort_by=created_at&sort_direction=desc`

In code we:

- Maintain a **whitelist** of sortable fields.
- Map field names to actual DB columns.
- Default to a stable sort (e.g. `created_at desc`).

