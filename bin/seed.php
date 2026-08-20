<?php

declare(strict_types=1);

/**
 * Seed cards and a demo kid account. Run: php bin/seed.php
 */

require_once dirname(__DIR__) . '/app/bootstrap.php';
require_once ROOT_PATH . '/sql/seed_cards.php';

$pdo = Database::pdo();

$pdo->exec('DELETE FROM user_cards');
$pdo->exec('DELETE FROM packs');
$pdo->exec('DELETE FROM round_problems');
$pdo->exec('DELETE FROM rounds');
$pdo->exec('DELETE FROM cards');
$pdo->exec('DELETE FROM users');

$ins = $pdo->prepare(
    'INSERT INTO cards (slug, name, rarity, tribe, flavor, art_key, sort_order)
     VALUES (?, ?, ?, ?, ?, ?, ?)'
);

foreach (mathcats_seed_cards() as $i => $card) {
    [$slug, $name, $rarity, $tribe, $flavor, $artKey] = $card;
    $ins->execute([$slug, $name, $rarity, $tribe, $flavor, $artKey, $i + 1]);
}

$password = password_hash('cats1234', PASSWORD_DEFAULT);
$pdo->prepare(
    'INSERT INTO users (username, password_hash, display_name) VALUES (?, ?, ?)'
)->execute(['demo', $password, 'Demo']);

$cardCount = (int) $pdo->query('SELECT COUNT(*) FROM cards')->fetchColumn();

echo "MathCats ready.\n";
echo "  {$cardCount} cards seeded\n";
echo "  demo / cats1234\n";
