# Fire Keeper Bot

## Database Setting

```
php artisan migrate
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
