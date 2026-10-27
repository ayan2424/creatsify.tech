# Creatsify Agency Operations Plan

## Product decision

Creatsify is a single digital agency and software studio, not a multi-tenant SaaS product. The website sells the agency's services and showcases its software products, team, and work. The private admin desk runs the agency's own lead, client, and delivery workflow.

The first release intentionally excludes client logins, client portals, online payments, subscriptions, tenant isolation, and marketplace integrations. Software may link to an official Gumroad checkout; bespoke work starts through a contact/lead conversation and is paid off-site under an agreed milestone contract.

## What the public website must do

- Present services, team CVs, individual portfolios, studio software, and agency case studies.
- Turn contact and support submissions into qualified CRM records.
- Link commercial software to direct Gumroad checkout and offer first-party deployment/support intake.
- Use the same team profiles in public credibility pages and internal delivery assignments.

## What the internal admin desk must do

- Qualify inquiries into CRM client records and retain a searchable communication history.
- Create an agency delivery project after a client accepts a scope.
- Assign one or more team members to a project with a delivery role.
- Track milestones, tasks, internal notes, files, current status, and target dates.
- Keep commercial studio products separate from client-delivery work.

## Core operating workflow

```
Public inquiry / support request
        -> CRM client + opportunity record
        -> agreed scope and off-site payment
        -> agency delivery project
        -> team assignments, milestones, tasks and internal notes
        -> delivered / retained / archived
```

## Delivery data model

`agency_projects` is the internal delivery record and belongs to an optional `crm_client`. It stores the commercial context (`project_type`, `deal_value`, dates) and operational state (`planning`, `active`, `on_hold`, `delivered`, `archived`).

`agency_project_members` relates `team` members to delivery projects. A project has one or more members, a role on that delivery, an allocation percentage, and assignment dates.

`agency_project_milestones` and `agency_project_tasks` represent delivery commitments. `agency_project_notes` holds private operational context. Files will be represented by a dedicated attachment table only after the existing media upload hardening is complete.

## Roles and access

| Role | Access |
| --- | --- |
| `super_admin` | Full configuration, users, audit, CRM, and delivery control. |
| `admin` | CRM, delivery projects, assignments, milestones, and operational communications. |
| `editor` | Public website content, services, portfolio, and team profiles. |
| `team_member` | Reserved for a later internal-only personal workload view; no public or admin-desk access is implied yet. |

## Delivery sequence

1. Secure the existing application: role enforcement, CSRF coverage, signed webhook handling, safe output, and removal of unsafe demo paths.
2. Keep the public agency and support flow focused: services, team, portfolio, inquiry-to-CRM conversion, direct Gumroad links.
3. Add the private delivery workspace: project records, staff assignments, milestones, tasks, notes, and dashboard visibility.
4. Add permissions for an internal team-member workload view only once its exact workflow is confirmed.
5. Add files, reporting, client portal, payments, or SaaS tenancy only as explicitly approved later projects.

## First delivery workspace acceptance criteria

- An admin can create a delivery project and link it to an existing CRM client.
- An admin can assign multiple existing team members, each with a project role.
- An admin can create and update milestones and tasks without exposing them publicly.
- Public portfolio projects remain separate from internal agency delivery projects.
- Every state-changing action is CSRF-protected, auditable, role-protected, and uses prepared SQL.
- New production schema is self-healing for existing aaPanel/XAMPP deployments.
