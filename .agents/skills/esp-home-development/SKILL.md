---
name: esp-home-development
description: "Develop, review, test, or deploy the ESP Home Yii2 and NodeMCU project. Use for changes to device control, SQLite, Docker, scheduler, UI, or project documentation; not for unrelated repositories."
---

# ESP Home development

Read `AGENTS.md`, `.agents/project.md`, and the relevant document in
`docs/project/` before acting. Check `git status --short` and preserve unrelated
changes.

For work on the active server, read `.local/remote-host.md` first. Never place
hostnames, users, paths, secrets, live SQLite data, or `.env` values in tracked
files. Do not deploy automatically unless the user asks.

Use incremental, testable changes:

- schema changes use a new migration and a backup/restore plan;
- device commands must be validated and must not be sent to live ESP hardware
  merely to test code;
- PHP changes receive syntax checks; dependency changes receive Composer
  validation; Docker changes receive Compose/image validation;
- update `docs/project/` when an implementation or operational contract changes.

When delegating, read the matching brief in `.agents/roles/`. Give each agent a
bounded task and retain one owner for a migration, layout, asset bundle, or
deployment configuration.
