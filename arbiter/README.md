# Requirements

 * Apache/PHP Webserver
 * Telegram Bot
 * MySQL Database
 
# Installation
Follow these simple instructions:
 * Replace all occurences of `webby.yourdomain.tld`
 * Replace all occurences of `webby` with your ally name (no spaces!)
 * Upload files to your hosting account
 * Create a symbolic link from `/path/to/arbiter/public` to your webserver public directory (public_html, htdocs, www)
 * Create a MySQL user named `patools_webby` with databases `patools_webby` `patools_webby_forum`
 * Import MySQL Data (`patools_webby.sql` and `patools_webby_forum.sql`)
 * Create a Telegram Bot (see @BotFather on TG for instructions)
 * Open .env from `arbiter/.env` folder and configure, as desired
 
Voila!
