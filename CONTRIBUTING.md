# Contributing

Thanks for contributing to `laranail/license-verifier-ui`.

## Ground rules

- Discuss substantial changes in an issue first.
- `composer format` (Pint) for style; `composer test` (Pest) must stay green.
- Commits are authored under your own git identity (no AI attribution).

## Layout

This is a **monorepo whose root is the core package**. The core engine lives in `src/`; each
preset is a publishable path sub-package under `presets/<preset>/`. Run everything from the root
(the root `composer.json` wires the preset packages in via dev path repositories).

```
src/                     core engine: Generators/, Presets/, Themes/, Validation/, Http/, Providers/, Console/
presets/<preset>/        composer.json + base classes + stubs/{scaffold,themes/<theme>}
                         scaffold/ holds that preset's composer.json/README/.gitignore stubs too
tests/                   Unit · Validation · Generation · Feature
scripts/generate-preview.php   renders every preset × theme into generated-preview/ (composer preview)
```

Each preset owns **all** of its stubs under `presets/<preset>/stubs/`: the generic skeleton
(`scaffold/composer.json.stub`, `README.md.stub`, `gitignore.stub`), the preset's PHP/routes/config
scaffold, and the per-theme views/js. There is no shared root `stubs/` folder.

## When you change a preset or theme

- Stubs use `$UPPER_SNAKE$` tokens (never `{{ }}` — that collides with Blade). The generator
  asserts no token is left unresolved.
- Adding a theme: drop `presets/<preset>/stubs/themes/<theme>/` and add its key to the preset's
  `supportedThemes`. The generation test loops every supported theme, so it's covered automatically.
- Run `composer preview` and eyeball `generated-preview/<preset>/<theme>/`, then `composer test`.

## Tests

- **PHP** — `composer test` (Pest). The `Feature` suite boots a generated package per preset and
  exercises it against the verifier null driver (Blade/Vue routes, the Livewire activation form);
  the `Generation` suite checks every preset × theme renders with valid composer + no leftover
  tokens. The `Filament` suite compiles the generated Filament views with Filament's Blade
  components registered (full interactive Filament page rendering needs Filament's own panel
  harness, so it is not covered here).
- **JS (Vue preset)** — `cd presets/vue && npm install && npm test` (Vitest + `@vue/test-utils`,
  jsdom). The Vue SFC stub is token-free, so `@vitejs/plugin-vue` compiles `*.vue.stub` directly;
  the test mounts every theme's SFC and drives status/activate/deactivate with a mocked `fetch`.

## Keep the boundary

The core owns all logic (base controllers/components/providers, the generator, validators). A
generated package must stay **thin** — its classes subclass the base and bake in literals; the
heavy lifting (and updates) stay in the core.
