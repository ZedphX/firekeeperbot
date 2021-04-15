# Fire Keeper Bot

Fire Keeper is a Telegram bot made with PHP in Laravel using the [telebot](https://github.com/westacks/telebot) library. It can set reminders for whatever the user wants to remember.

This bot imitates the Fire Keeper of the Dark Souls saga, more concretely the Dark Souls 3 one.

## Database Setting

```
php artisan migrate
```

## Cron Setting

```
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

## Commands

### Register commands

```
php artisan telebot:commands --setup
```

### Remove commands

```
php artisan telebot:commands --remove
```

### Setup Webhooks

-   Production:
    ```
    php artisan telebot:webhook --setup
    ```
-   Development:
    ```
    php artisan telebot:polling
    ```
