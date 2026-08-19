<?php

declare(strict_types=1);

final class HomeController
{
    public static function home(): void
    {
        if (!Auth::check()) {
            render('home', [
                'title' => 'Play',
                'guest' => true,
                'activeRound' => null,
                'lastRound' => null,
                'stats' => ['unique' => 0, 'total' => 0],
                'catalogCount' => Cards::catalogCount(),
                'recent' => [],
            ]);
            return;
        }

        $userId = (int) Auth::user()['id'];
        $active = self::activeRound($userId);
        $last = self::lastCompletedRound($userId);

        render('home', [
            'title' => 'Play',
            'guest' => false,
            'activeRound' => $active,
            'lastRound' => $last,
            'stats' => Cards::collectionStats($userId),
            'catalogCount' => Cards::catalogCount(),
            'recent' => Cards::recentPulls($userId, 3),
        ]);
    }

    public static function activeRound(int $userId): ?array
    {
        $stmt = Database::pdo()->prepare(
            'SELECT * FROM rounds WHERE user_id = ? AND status = \'in_progress\' ORDER BY id DESC LIMIT 1'
        );
        $stmt->execute([$userId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function lastCompletedRound(int $userId): ?array
    {
        $stmt = Database::pdo()->prepare(
            'SELECT r.*, p.id AS pack_id, p.opened_at, p.odds_band
             FROM rounds r
             LEFT JOIN packs p ON p.round_id = r.id
             WHERE r.user_id = ? AND r.status = \'complete\'
             ORDER BY r.id DESC
             LIMIT 1'
        );
        $stmt->execute([$userId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }
}
