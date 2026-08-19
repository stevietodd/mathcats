<?php

declare(strict_types=1);

final class Cards
{
    /** @var array<string, string> */
    private const ART = [
        'goblin' => '👺',
        'imp' => '😈',
        'sprite' => '✨',
        'spark' => '🔥',
        'bat' => '🦇',
        'mushroom' => '🍄',
        'newt' => '🦎',
        'pixie' => '🧚',
        'soldier' => '🪖',
        'bug' => '🪲',
        'frog' => '🐸',
        'bird' => '🐦',
        'plant' => '🌱',
        'wolf' => '🐺',
        'knight' => '🛡️',
        'dragon' => '🐉',
        'ranger' => '🏹',
        'slime' => '🟢',
        'moth' => '🦋',
        'mage' => '🧙',
        'badger' => '🦡',
        'elf' => '🧝',
        'crab' => '🦀',
        'cat' => '🐱',
        'panther' => '🐈‍⬛',
        'griffin' => '🦅',
        'witch' => '🔮',
        'boar' => '🐗',
        'seer' => '🌙',
        'golem' => '🗿',
        'warrior' => '⚔️',
        'captain' => '🏴‍☠️',
        'assassin' => '🗡️',
        'empress' => '👑',
        'paladin' => '⚜️',
        'mythic' => '🌟',
        'moon' => '🌕',
        'star' => '⭐',
        'lion' => '🦁',
        'ghost' => '👻',
    ];

    public static function emoji(string $artKey): string
    {
        return self::ART[$artKey] ?? '🐱';
    }

    public static function findBySlug(string $slug): ?array
    {
        $stmt = Database::pdo()->prepare('SELECT * FROM cards WHERE slug = ?');
        $stmt->execute([$slug]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function randomByRarity(string $rarity): array
    {
        $stmt = Database::pdo()->prepare(
            'SELECT * FROM cards WHERE rarity = ? ORDER BY RANDOM() LIMIT 1'
        );
        $stmt->execute([$rarity]);
        $row = $stmt->fetch();
        if ($row) {
            return $row;
        }
        $fallback = Database::pdo()->query('SELECT * FROM cards ORDER BY RANDOM() LIMIT 1')->fetch();
        if (!$fallback) {
            throw new RuntimeException('No cards seeded.');
        }
        return $fallback;
    }

    /** @return list<array<string, mixed>> */
    public static function forPack(int $packId): array
    {
        $stmt = Database::pdo()->prepare(
            'SELECT c.*, uc.slot, uc.id AS pull_id
             FROM user_cards uc
             JOIN cards c ON c.id = uc.card_id
             WHERE uc.pack_id = ?
             ORDER BY uc.slot'
        );
        $stmt->execute([$packId]);
        return $stmt->fetchAll();
    }

    /** @return list<array<string, mixed>> */
    public static function binder(int $userId, ?string $rarity = null): array
    {
        $sql = 'SELECT c.*, COUNT(uc.id) AS owned,
                       MIN(uc.acquired_at) AS first_acquired
                FROM user_cards uc
                JOIN cards c ON c.id = uc.card_id
                WHERE uc.user_id = ?';
        $params = [$userId];
        if ($rarity !== null && $rarity !== '') {
            $sql .= ' AND c.rarity = ?';
            $params[] = $rarity;
        }
        $sql .= ' GROUP BY c.id
                  ORDER BY CASE c.rarity
                    WHEN \'legendary\' THEN 1
                    WHEN \'epic\' THEN 2
                    WHEN \'rare\' THEN 3
                    WHEN \'uncommon\' THEN 4
                    ELSE 5 END,
                  c.sort_order, c.name';
        $stmt = Database::pdo()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function ownedCount(int $userId, int $cardId): int
    {
        $stmt = Database::pdo()->prepare(
            'SELECT COUNT(*) FROM user_cards WHERE user_id = ? AND card_id = ?'
        );
        $stmt->execute([$userId, $cardId]);
        return (int) $stmt->fetchColumn();
    }

    /** @return array{unique:int,total:int} */
    public static function collectionStats(int $userId): array
    {
        $stmt = Database::pdo()->prepare(
            'SELECT COUNT(*) AS total, COUNT(DISTINCT card_id) AS unique_count
             FROM user_cards WHERE user_id = ?'
        );
        $stmt->execute([$userId]);
        $row = $stmt->fetch() ?: ['total' => 0, 'unique_count' => 0];
        return [
            'unique' => (int) $row['unique_count'],
            'total' => (int) $row['total'],
        ];
    }

    public static function catalogCount(): int
    {
        return (int) Database::pdo()->query('SELECT COUNT(*) FROM cards')->fetchColumn();
    }

    /** @return list<array<string, mixed>> */
    public static function recentPulls(int $userId, int $limit = 3): array
    {
        $stmt = Database::pdo()->prepare(
            'SELECT c.*, uc.acquired_at
             FROM user_cards uc
             JOIN cards c ON c.id = uc.card_id
             WHERE uc.user_id = ?
             ORDER BY uc.id DESC
             LIMIT ?'
        );
        $stmt->bindValue(1, $userId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
