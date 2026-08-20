<?php

declare(strict_types=1);

/**
 * Copy to config.local.php and adjust for your environment.
 */

return [
    'debug' => true,
    'app_name' => 'MathCats',
    'base_url' => '', // e.g. '' when site is at domain root, or '/mathcats' if in a subfolder
    'timezone' => 'America/New_York',
    'db_path' => dirname(__DIR__) . '/data/mathcats.sqlite3',
];
