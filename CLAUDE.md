# CredentialsRecords

Notes for AI assistants working on the Dotclear plugin CredentialsRecords.

**Read first** the shared conventions of the author's Dotclear modules:
https://raw.githubusercontent.com/JcDenis/gh2da/master/CONVENTIONS.md
(if a web tool refuses the address, use `curl -s` on it from the shell).
The rules below take precedence for this module.

## Module

Display dotclear's credentials records. A user guide with screenshots is available in French: [guide d'utilisation](https://github.com/JcDenis/CredentialsRecords/blob/master/docs/fr/utilisation.md).

Admin page. Nothing on the public side.

Required Dotclear and PHP versions: see `_define.php`.

## Structure

* `src/Backend.php`: admin side: menu entry, favorites, behaviors registration.
* `src/BackendList.php`: credentials list class.
* `src/Manage.php`: admin page of the module.
* `src/ManageVars.php`: admin page (vars).
* `src/My.php`: module helpers (`My::id()`, settings, permissions).
* `js/`: `backend.js`.
* Behaviors used: `adminColumnsListsV2`, `adminFiltersListsV3`, `adminDashboardFavoritesV2`.

## Pitfalls

* None recorded yet: add here the traps met while working on the module.

## Testing

* Check the admin page, the admin help, in English and French.
* CI browses the common admin and public pages; extra pages can be listed in `.github/dotclear-test.txt`.
* The user guide `docs/fr/utilisation.md` describes the features: update it, and its screenshots, when the interface changes.
