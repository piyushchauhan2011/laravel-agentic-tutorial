# ATSLab AI Guidelines

- Use `positions` for ATS vacancies; never use `jobs` for domain entities.
- Prefer Postgres-compatible SQL and include CTEs for reporting endpoints.
- Enforce authorization through Policies + roles (`admin`, `recruiter`, `hiring_manager`, `viewer`).
- API responses must be JSON and stable for client usage.
- Queue-heavy tasks should use named queues: `default`, `notifications`, `reports`.
- Protect observability UIs (Horizon, Telescope, Filament) with admin-only access in non-local envs.
