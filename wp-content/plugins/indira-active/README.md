## Indira Active Blog Child Child Theme Plugin

This plugin includes "safe" customizations to the _Refined Pro_ Genesis child theme so it (and the Genesis Framework) can be updated without needing to worry about files being overriden.

Currently, this plugin only does theme/template-related adjustments.


## Directories

* `.vscode` : Visual Studio Code project settings
* `assets` : Where source and compiled versions of SCSS and JS live, and the future for any images that may be needed
* `includes` : Basically the root of non-CSS adjustments


## Includes

* `header.php` : Modifications to the global site header
* `footer.php` : Modifications to the global site footer, such as the copyright info
* `functions.php` : "Helper functions" for misc. template usage


## Asset Build Process

The CSS and JS assets enqueued in the main plugin file (`indira-active.php`) are **compiled** from the `scss`, `js/foot`, and `js/head` directories. Do not edit any `.css` files or direct children of the `js` folder, as they're all going to get overwritten.

`head.js` is likely **not** included because it's bad practice to have scripts running before the page can render, but it's there just in case it's absolutely needed. In enqueue it, you'll have to add a `wp_enqueue_script()` call in `indira-active.php/enqueue_scripts method`.

### How to build SCSS and JS

If you're not using lando, don't include `lando` in these commands.

1. Make sure the environment is spun up in the root of the site: `lando start`.
2. Navigate to this plugin's directory and nstall front end dependencies: `lando npm install`. This might take a long time, and you shouldn't need to run as admin or `sudo` or anything.
3. Start Browsersync, which also starts "watch" tasks: `lando gulp browsersync`.
4. Visit the `External` _Access URL_ that is given to you; the local one probably won't work unless you're using MAMP or XAMPP or something mega local / old school. You should see "Connected to Browsersync" at the top right of the screen for a second.
5. Edit `.scss` files or `js/foot/` files -- you'll see browsersync run the "change" stuff, and it'll also show you any compilation errors.
6. You don't need most browser prefixes for SCSS -- it's built into the gulp script. Ex: Don't use `-moz-linear-gradient` or you'll get it in there twice.
7. Make sure the `wp_enqueue_script()` call for `js/foot.min.js` in `indira-active.php/enqueue_scripts` is uncommented if you start adding JS.