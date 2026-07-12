# Themes

Each HTML preset ships ready-to-use theme variants. You pick one at install time; the chosen
theme's views/JS are copied into your generated package, so you own and can edit them.

| Theme | Description |
|-------|-------------|
| `tailwind` | Tailwind CSS utility classes. The `unlicensed` page pulls the Tailwind CDN so it's styled out of the box (remove it once your app bundles Tailwind). |
| `bootstrap` | Bootstrap 5 classes; CDN included on the standalone page. |
| `alpine` | Tailwind + Alpine.js for interactivity (registers an `lvLicenseForm()` Alpine component instead of the vanilla JS). |
| `unstyled` | Semantic HTML with `.lv-*` hook classes and no framework — style it yourself. |
| `custom` | The unstyled skeleton with `TODO` markers and `data-lv-*` hooks, ready for your design system. |

Support matrix:

| Preset | tailwind | bootstrap | alpine | unstyled | custom | filament |
|--------|:--------:|:---------:|:------:|:--------:|:------:|:--------:|
| Blade | ✓ | ✓ | ✓ | ✓ | ✓ | |
| Livewire | ✓ | ✓ | | ✓ | ✓ | |
| Vue | ✓ | ✓ | | ✓ | ✓ | |
| Filament | | | | | | ✓ |

(Livewire already bundles Alpine, and Vue is itself the interactivity layer, so neither exposes a
separate `alpine` theme. Filament renders through its own design system.)

## Adding a theme to a preset

1. Add `presets/<preset>/stubs/themes/<theme>/` with the same file layout as an existing theme
   (`views/…`, `js/…`).
2. Add the theme key to that preset's `supportedThemes` in `presets/<preset>/src/Presets/<Preset>PresetDefinition.php`.
3. The generation test loops every `supportedTheme`, so it will be covered automatically.

---

[← Docs index](../../README.md#documentation)
