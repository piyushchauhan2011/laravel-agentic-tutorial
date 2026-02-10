# Laravel 12 + Boost ATS Learning Project Plan (Single-Tenant, Postgres, Agentic-First)

## Summary
Build a small-scale ATS (Applicant Tracking System) from scratch as a milestone-based learning project using Laravel 12 and `laravel/boost`, with daily agentic workflow practice.

The implementation will use:

- Backend: Laravel 12, Postgres, Redis (queues/cache/sessions), Horizon, Scheduler
- AI workflow: Boost resources/instructions + MCP in everyday development loop
- UI split: Blade + Alpine + Bootstrap 5 (SCSS) for public/recruiter UX, Filament for admin/back-office
- Auth: Breeze + Sanctum + Policies/Gates (role-based authorization)
- Observability: Horizon + Telescope + Pulse, structured logging and exception handling
- API: Versioned REST (`/api/v1`) with Laravel API Resources and policy enforcement
- Testing: Pest, feature-first

## Milestone Plan (Balanced 60–90 min sessions)

### 1) Foundation + Environment

- Create Laravel 12 project in this workspace.
- Configure DDEV with Postgres and Redis services.
- Install baseline packages: Breeze, Sanctum, Boost, Horizon, Telescope, Pulse, Filament, Pest.
- Configure `.env`, queue driver (`redis`), cache/session (`redis`), DB (`pgsql`).
- Define coding standards and folder conventions.

Outcome: app boots in DDEV, login works, Horizon/Telescope/Pulse accessible in local.

### 2) Boost Agentic Workflow Setup

Install and initialize Boost:

- `composer require laravel/boost --dev`
- `php artisan boost:install`
- `php artisan boost:update` (after upgrades)

- Register project instructions/resources for ATS domain, coding standards, and architecture decisions.
- Add workflow docs: “how to prompt”, “definition of done”, “agent QA checklist”.

Outcome: repeatable AI-assisted dev loop with explicit guardrails.

### 3) Domain Modeling + Core Migrations

Create ATS entities:

- users, roles/permissions (or equivalent role mapping)
- companies (single tenant still modeled)
- positions (renamed from jobs to avoid conflict with Laravel queue jobs table)
- candidates
- applications
- application_stages
- interviews
- offers
- notes
- activities (audit timeline)

- Add DB constraints, indexes, enums/value objects where appropriate.
- Add seeders/factories for realistic sample data.

Outcome: consistent domain schema with referential integrity and seedable data.

### 4) Authn/Authz + Security Baseline

- Breeze auth screens and flows.
- Sanctum token auth for API clients.
- Policies for all ATS resources; Gates for cross-cutting abilities.
- Role model: `admin`, `recruiter`, `hiring_manager`, `viewer`.
- Security defaults: rate limiting, password policies, CSRF/session hardening, signed URLs where needed.

Outcome: all sensitive operations policy-protected.

### 5) Public/Recruiter UI (Blade + Alpine + Bootstrap SCSS)

- Build recruiter-facing pages: positions list/detail, candidates, application pipeline board, interview calendar/list.
- Bootstrap SCSS theme system:
  - define color tokens (slate + teal + amber palette)
  - compile with Vite
  - reusable component partials
- Alpine for lightweight interactions (filters, modal forms, stage transitions).

Outcome: coherent themed UI, mobile-friendly, no admin concerns mixed in.

### 6) Filament Admin Panel

Separate Filament panel for admin/back-office:

- user/role management
- lookup tables (stages, status config)
- operational dashboards

- Apply policies in Filament resources/actions.

Outcome: operational admin separated from recruiter-facing UI.

### 7) REST API v1 + Best Practices

- Versioned endpoints under `/api/v1`.
- Resource controllers + Form Requests + API Resources.
- Standard response envelope + pagination + filtering/sorting.
- Idempotency strategy for sensitive mutation endpoints (offers/status changes).
- API error contract with consistent validation/auth/business error shapes.

Outcome: clean, documented, test-covered API.

### 8) Jobs, Queues, Scheduling, CTE Use Cases

Async jobs:

- application score calculation
- interview reminder notifications
- stale application nudges

Queue config for Horizon (named queues: `default`, `notifications`, `reports`).

Scheduler tasks:

- nightly pipeline metrics snapshot
- stale record sweeps
- weekly digest generation

Postgres CTE examples:

- funnel metrics by stage
- recursive stage-path analysis for application history

Outcome: production-like async workflow + practical Postgres CTE patterns.

### 9) Observability + Error Handling

- Horizon queue monitoring and retry/failure policies.
- Telescope for request/query/job inspection (local/dev controls).
- Pulse for performance/usage metrics.
- Centralized exception mapping for web/API (domain exceptions -> user-safe responses).
- Structured logs with correlation IDs and contextual metadata.

Outcome: traceable failures, measurable performance, actionable diagnostics.

### 10) Hardening + Documentation + Learning Wrap-Up

- Performance checks (N+1 prevention, indexes, eager loading strategy).
- Backup/restore notes for Postgres in DDEV.
- Add architecture decision records (ADRs) and runbook.
- Final “agentic playbook”: prompts, review checklist, refactor loop.

Outcome: maintainable reference project and reusable learning template.

## Public Interfaces and Types (Decision-Complete)

### Web Routes (Blade/Filament)

- Auth routes from Breeze.
- Recruiter routes (`/positions`, `/candidates`, `/applications`, `/interviews`, `/offers`) guarded by auth + policies.
- Filament panel under `/admin` with role-restricted access.

### API Routes (`/api/v1`)

- `POST /auth/tokens`
- `GET/POST /positions`, `GET/PATCH /positions/{position}`
- `GET/POST /candidates`, `GET/PATCH /candidates/{candidate}`
- `GET/POST /applications`, `PATCH /applications/{application}/stage`
- `POST /interviews`, `PATCH /interviews/{interview}`
- `POST /offers`, `PATCH /offers/{offer}`
- Standardized filtering params (`filter[...]`), sorting (`sort`), includes (`include`).

### Domain Events / Jobs

- Events: `ApplicationSubmitted`, `ApplicationStageChanged`, `InterviewScheduled`, `OfferExtended`.
- Jobs: `ComputeApplicationScoreJob`, `SendInterviewReminderJob`, `BuildPipelineMetricsJob`, `SendDigestJob`.
- Queue names: `default`, `notifications`, `reports`.

### Error/Response Contracts

- Validation: Laravel default 422 shape normalized via API exception handler.
- Authz/authn: 401/403 standardized payload.
- Domain/business errors: 409/422 with machine-readable code and human message.
- All API responses include request correlation ID in headers.

## Testing and Acceptance Criteria

### Test Layers

- Feature tests (primary): auth, policies, API contracts, pipeline transitions, scheduler commands.
- Integration tests: queue dispatch + Horizon queue selection, notification flows.
- DB tests: Postgres-specific query behavior (CTE correctness), transaction handling.
- UI smoke tests: critical recruiter pages + Filament access restrictions.

### Required Scenarios

- Recruiter can create position and move candidate through stages with policy enforcement.
- Unauthorized role cannot access restricted endpoints/panel actions.
- Scheduled tasks run and produce expected artifacts/metrics.
- Queue failures are captured and retry behavior works.
- API returns consistent error contract for validation, auth, and domain failures.
- CTE-powered metrics endpoint returns correct funnel counts for seeded fixtures.

### Done Criteria Per Milestone

- Green test subset for that milestone.
- Updated docs/runbook.
- Boost-assisted dev notes captured (prompt + output + review decision).
- No unresolved critical Telescope/Horizon warnings for introduced features.

## Assumptions and Defaults Chosen

- Single-tenant ATS for learning speed.
- REST-first API (not GraphQL/JSON:API strict).
- Redis required for Horizon; Postgres remains system-of-record DB.
- Filament reserved for admin/back-office; Bootstrap+Alpine for core user workflows.
- Pest as default testing style.
- Observability stack is Laravel-native first (Horizon + Telescope + Pulse).
- Theme direction: Bootstrap 5 SCSS with a professional slate/teal/amber palette.
- Local development target is DDEV; Docker access must be available on host for runtime.
