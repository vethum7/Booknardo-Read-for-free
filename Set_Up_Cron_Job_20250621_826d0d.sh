crontab -e
* * * * * /usr/bin/php /var/www/novelhub/cron/publish-chapters.php >> /var/log/novelhub-cron.log 2>&1