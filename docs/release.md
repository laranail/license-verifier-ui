# Release

Releases are **tag-driven** (`vX.Y.Z`) and published on GitHub; Packagist syncs from the tag. One
tag versions the core **and** the four preset packages together (this is a monorepo whose root is
the core — see [Architecture](architecture.md)).

## Steps

1. Update `CHANGELOG.md` (root) and the affected `presets/*/CHANGELOG.md`: move the unreleased
   entries under a new `## [X.Y.Z] - YYYY-MM-DD` heading.
2. Commit on `main` (ensure `git config user.email` is set to your GitHub no-reply address).
3. Tag + push:
   ```bash
   git tag vX.Y.Z
   git push origin main --tags
   ```
4. Create the GitHub release with that version's `CHANGELOG.md` section as the body.

CI (`tests.yml` on PHP 8.4/8.5 + `static-analysis.yml`: pint, phpstan, rector) must be green on
the tagged commit.

## Versioning

Semver, currently pre-1.0 (`0.x`) — the public API may change between minor versions. The public
API is the base classes generated packages extend (`BaseLicenseController`,
`BasePresetServiceProvider`, the preset family bases), the `PresetContributor` contract, the stub
token set, and the command signatures — breaking any of these is a major bump once `1.0` lands.

---

[← Docs index](../README.md#documentation)
