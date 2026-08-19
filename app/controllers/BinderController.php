<?php

declare(strict_types=1);

final class BinderController
{
    public static function index(): void
    {
        Auth::requireLogin();
        $userId = (int) Auth::user()['id'];
        $rarity = (string) ($_GET['rarity'] ?? '');
        if ($rarity !== '' && !in_array($rarity, ['common', 'uncommon', 'rare', 'epic', 'legendary'], true)) {
            $rarity = '';
        }

        $cards = Cards::binder($userId, $rarity !== '' ? $rarity : null);
        $stats = Cards::collectionStats($userId);

        render('binder', [
            'title' => 'Binder',
            'cards' => $cards,
            'stats' => $stats,
            'catalogCount' => Cards::catalogCount(),
            'filter' => $rarity,
        ]);
    }

    public static function show(string $slug): void
    {
        Auth::requireLogin();
        $card = Cards::findBySlug($slug);
        if (!$card) {
            http_response_code(404);
            render('errors/404', ['title' => 'Not found']);
            return;
        }

        $owned = Cards::ownedCount((int) Auth::user()['id'], (int) $card['id']);
        if ($owned < 1) {
            http_response_code(404);
            render('errors/404', ['title' => 'Not found']);
            return;
        }

        render('card_detail', [
            'title' => $card['name'],
            'card' => $card,
            'owned' => $owned,
        ]);
    }
}
