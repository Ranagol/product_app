# CLAUDE.md

Quick reference for this Symfony 8.1 + PHP 8.4 project.

See [AGENTS.md](AGENTS.md) for comprehensive Symfony conventions, workflow commands, testing guidance, and how to add new features via Flex recipes.

**Key points:**
- Use `#[Route]` attributes for routing (no YAML routing)
- Controllers are thin; delegate to services
- Install features with `composer require <package>` → Flex recipe configures it
- Run via `symfony serve -d` or `bin/console` for commands
- Tests: extend `WebTestCase` for HTTP tests, run with `php bin/phpunit`
- Check `bin/console debug:router`, `debug:container`, `debug:autowiring <name>` when unsure about configuration
