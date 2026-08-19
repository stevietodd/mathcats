<?php

declare(strict_types=1);

final class PackOdds
{
    /** @var array<string, array<string, int>> */
    private const HERO = [
        'perfect' => ['common' => 0, 'uncommon' => 10, 'rare' => 30, 'epic' => 40, 'legendary' => 20],
        'great' => ['common' => 5, 'uncommon' => 25, 'rare' => 40, 'epic' => 25, 'legendary' => 5],
        'good' => ['common' => 19, 'uncommon' => 40, 'rare' => 30, 'epic' => 10, 'legendary' => 1],
        'ok' => ['common' => 50, 'uncommon' => 30, 'rare' => 15, 'epic' => 4, 'legendary' => 1],
        'low' => ['common' => 70, 'uncommon' => 22, 'rare' => 7, 'epic' => 1, 'legendary' => 0],
    ];

    /** Milder table for pack slots 1–2. */
    /** @var array<string, array<string, int>> */
    private const FILLER = [
        'perfect' => ['common' => 20, 'uncommon' => 40, 'rare' => 30, 'epic' => 9, 'legendary' => 1],
        'great' => ['common' => 35, 'uncommon' => 40, 'rare' => 20, 'epic' => 5, 'legendary' => 0],
        'good' => ['common' => 50, 'uncommon' => 35, 'rare' => 13, 'epic' => 2, 'legendary' => 0],
        'ok' => ['common' => 65, 'uncommon' => 28, 'rare' => 7, 'epic' => 0, 'legendary' => 0],
        'low' => ['common' => 80, 'uncommon' => 18, 'rare' => 2, 'epic' => 0, 'legendary' => 0],
    ];

    public static function bandFromAccuracy(float $accuracy): string
    {
        if ($accuracy >= 100.0) {
            return 'perfect';
        }
        if ($accuracy >= 90.0) {
            return 'great';
        }
        if ($accuracy >= 70.0) {
            return 'good';
        }
        if ($accuracy >= 50.0) {
            return 'ok';
        }
        return 'low';
    }

    public static function bandLabel(string $band): string
    {
        return match ($band) {
            'perfect' => 'Legendary luck',
            'great' => 'Lucky pack',
            'good' => 'Solid pack',
            'ok' => 'Okay pack',
            default => 'Practice pack',
        };
    }

    public static function pickRarity(string $band, bool $heroSlot): string
    {
        $table = $heroSlot ? self::HERO : self::FILLER;
        $weights = $table[$band] ?? $table['low'];
        $total = array_sum($weights);
        if ($total <= 0) {
            return 'common';
        }
        $roll = random_int(1, $total);
        $running = 0;
        foreach ($weights as $rarity => $weight) {
            $running += $weight;
            if ($roll <= $running) {
                return $rarity;
            }
        }
        return 'common';
    }

    /**
     * Open a pack once. Refreshing after this cannot re-roll.
     *
     * @return list<array<string, mixed>>
     */
    public static function openPack(array $pack): array
    {
        $existing = Cards::forPack((int) $pack['id']);
        if ($existing !== []) {
            return $existing;
        }

        $pdo = Database::pdo();
        $pdo->beginTransaction();
        try {
            $locked = $pdo->prepare('SELECT * FROM packs WHERE id = ?');
            $locked->execute([(int) $pack['id']]);
            $row = $locked->fetch();
            if (!$row) {
                $pdo->rollBack();
                throw new RuntimeException('Pack not found.');
            }
            $already = Cards::forPack((int) $row['id']);
            if ($already !== [] || $row['opened_at']) {
                if ($already !== [] && !$row['opened_at']) {
                    $pdo->prepare('UPDATE packs SET opened_at = datetime(\'now\') WHERE id = ?')
                        ->execute([(int) $row['id']]);
                }
                $pdo->commit();
                return $already !== [] ? $already : Cards::forPack((int) $row['id']);
            }

            $band = (string) $row['odds_band'];
            $insert = $pdo->prepare(
                'INSERT INTO user_cards (user_id, card_id, pack_id, slot) VALUES (?, ?, ?, ?)'
            );
            for ($slot = 1; $slot <= 3; $slot++) {
                $hero = $slot === 3;
                $rarity = self::pickRarity($band, $hero);
                $card = Cards::randomByRarity($rarity);
                $insert->execute([
                    (int) $row['user_id'],
                    (int) $card['id'],
                    (int) $row['id'],
                    $slot,
                ]);
            }

            $pdo->prepare('UPDATE packs SET opened_at = datetime(\'now\') WHERE id = ?')
                ->execute([(int) $row['id']]);
            $pdo->commit();
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }

        return Cards::forPack((int) $pack['id']);
    }
}
