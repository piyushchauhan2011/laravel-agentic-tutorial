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

## Frontend dev mode (important)

Run Vite on your host machine, not inside DDEV:

```bash
npm install
npm run dev
```

Use DDEV for PHP/Laravel commands, and keep `npm run dev` running in a separate host terminal.

### Node.js setup options

Option 1 (`asdf`):

```bash
asdf plugin add nodejs https://github.com/asdf-vm/asdf-nodejs.git
asdf install nodejs 22.17.0
asdf global nodejs 22.17.0
node -v
```

Option 2 (`fnm`):

```bash
fnm install 22
fnm default 22
node -v
```

Then install dependencies and run:

```bash
npm install
npm run dev
```

Open:

- App: `https://laravel-agentic-tutorial.ddev.site`
- Dashboard: `/dashboard`
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
