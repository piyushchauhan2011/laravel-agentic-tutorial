# ATSLab (Laravel 12 + DDEV)

Learning project for agentic Laravel development using a small ATS domain.

## Stack

- Laravel 12
- DDEV + Postgres 17 + Redis
- Sanctum API auth
- Horizon + Telescope + Pulse package
- Filament admin panel
- Spatie roles/permissions
- Bootstrap 5 + SCSS + Alpine.js

## Quick start

```bash
ddev start
ddev composer install
ddev npm install
ddev npm run build
ddev artisan migrate:fresh --seed
```

Open:

- App: `https://laravel-agentic-tutorial.ddev.site`
- Dashboard: `/dashboard`
- Database learning: `/learn/database`
- Filament: `/admin`
- Horizon: `/horizon`
- Telescope: `/telescope`

## Demo credentials

- Email: `admin@atslab.test`
- Password: `password`

## API

- Token: `POST /api/v1/auth/tokens`
- Positions CRUD: `/api/v1/positions`
- Pipeline metrics (CTE): `GET /api/v1/metrics/pipeline`

All `/api/v1/*` routes (except token creation) require `Authorization: Bearer <token>`.

## Queue & schedule

- Queues: `default`, `notifications`, `reports`
- Run worker UI: `ddev artisan horizon`
- Run scheduler: `ddev artisan schedule:work`

## Notes

- ATS domain uses `positions` (not `jobs`) to avoid conflict with Laravel queue jobs.
- Project is intentionally small but includes foundations for auth, API, observability, and async workflows.
