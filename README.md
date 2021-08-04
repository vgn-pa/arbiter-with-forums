# Requirements

 * Apache/PHP Webserver
 * Telegram Bot
 * MySQL Database
 
# Installation
Follow these simple instructions:
 * Replace all occurrences of `webby.yourdomain.tld`
 * Replace all occurrences of `webby` with your ally name (no spaces!)
 * Upload files to your hosting account
 * Create a symbolic link from `/path/to/arbiter/public` to your webserver public directory (public_html, htdocs, www)
 * Create a MySQL user named `patools_webby` with databases `patools_webby` `patools_webby_forum`
 * Import MySQL Data (`patools_webby.sql` and `patools_webby_forum.sql`)
 * Create a Telegram Bot (see @BotFather on TG for instructions)
 * Open .env from `arbiter/.env` folder and configure, as desired
 * Add `php artisan schedule:run` to your crontab, run every minute
 
Voila!

# Usage

Open in your browser and enjoy. CLI available via `php artisan` in `arbiter` folder.

