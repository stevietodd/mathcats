# ArithmeCats

Mobile-first PHP game: kids solve grade 3–5 arithmetic to open fantasy trading-card packs. Cats sit at the top of the rarity chart. The more answers they get right, the luckier the hero card.

## Stack

- PHP 8+ (SQLite via PDO)
- Plain PHP front controller (no framework)
- Tailwind CSS (CDN)
- Apache `mod_rewrite` (`.htaccess` in `public/`)

## Local quick start

```bash
cd arithmecats
php bin/seed.php
php -S 127.0.0.1:8000 -t public public/index.php
```

Open http://127.0.0.1:8000/

| Username | Password |
|----------|----------|
| `demo` | `cats1234` |

## How it works

1. Sign up with a username (no email).
2. Pick + − × ÷ and play a round of 10 problems.
3. Finish the round to earn a 3-card pack. Slot 3 is the hero pull; its rarity odds rise with accuracy.
4. Collect cards in a personal binder. Duplicates increment a count.

Problems and answers are stored on the server. Pack opening is a single POST; refreshing cannot re-roll.

## DreamHost deploy

1. Upload the project (e.g. `~/arithmecats`).
2. Set the domain’s **web directory** to `arithmecats/public`.
3. Ensure `data/` is writable by the web server.
4. SSH in and run `php bin/seed.php`.

For production, create `config/config.local.php`:

```php
<?php
return [
    'debug' => false,
    'base_url' => '',
];
```
