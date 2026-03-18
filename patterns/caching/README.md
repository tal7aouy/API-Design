# Caching Pattern

We show a simple in-memory caching abstraction used in services.

Goals:

- Keep caching out of controllers.
- Make cache optional (services work without it).
- Allow different backends (memory, Redis) behind a small interface.

The examples demonstrate:

- Caching read-heavy endpoints (e.g. product listings).
- Explicit TTLs.
- Manual invalidation on writes.

