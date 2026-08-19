<?php

declare(strict_types=1);

final class PlayController
{
    public static function setup(): void
    {
        Auth::requireLogin();
        $active = HomeController::activeRound((int) Auth::user()['id']);
        if ($active) {
            redirect('/play');
        }
        render('play_setup', [
            'title' => 'New round',
            'operations' => Problems::OPERATIONS,
        ]);
    }

    public static function start(): void
    {
        Auth::requireLogin();
        verify_csrf();

        $userId = (int) Auth::user()['id'];
        $active = HomeController::activeRound($userId);
        if ($active) {
            redirect('/play');
        }

        $posted = $_POST['operations'] ?? [];
        if (!is_array($posted)) {
            $posted = [];
        }
        $ops = Problems::sanitizeOperations($posted);
        if ($ops === []) {
            flash('error', 'Pick at least one kind of problem.');
            redirect('/play/setup');
        }

        $pdo = Database::pdo();
        $pdo->beginTransaction();
        try {
            $pdo->prepare(
                'INSERT INTO rounds (user_id, operations, problem_count, status) VALUES (?, ?, ?, \'in_progress\')'
            )->execute([$userId, json_encode($ops, JSON_UNESCAPED_UNICODE), Problems::ROUND_SIZE]);
            $roundId = (int) $pdo->lastInsertId();

            $insert = $pdo->prepare(
                'INSERT INTO round_problems (round_id, position, prompt, correct_answer) VALUES (?, ?, ?, ?)'
            );
            foreach (Problems::generateRound($ops) as $i => $problem) {
                $insert->execute([$roundId, $i + 1, $problem['prompt'], $problem['answer']]);
            }
            $pdo->commit();
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }

        unset($_SESSION['last_result']);
        redirect('/play');
    }

    public static function play(): void
    {
        Auth::requireLogin();
        $round = HomeController::activeRound((int) Auth::user()['id']);
        if (!$round) {
            redirect('/play/setup');
        }

        $problem = self::nextProblem((int) $round['id']);
        if (!$problem) {
            self::completeRound($round);
            redirect('/rounds/' . (int) $round['id']);
        }

        $answered = self::answeredCount((int) $round['id']);
        $lastResult = $_SESSION['last_result'] ?? null;
        unset($_SESSION['last_result']);

        render('play', [
            'title' => 'Solve',
            'round' => $round,
            'problem' => $problem,
            'progress' => $answered + 1,
            'total' => (int) $round['problem_count'],
            'lastResult' => $lastResult,
        ]);
    }

    public static function answer(): void
    {
        Auth::requireLogin();
        verify_csrf();

        $userId = (int) Auth::user()['id'];
        $round = HomeController::activeRound($userId);
        if (!$round) {
            redirect('/play/setup');
        }

        $problem = self::nextProblemForGrading((int) $round['id']);
        if (!$problem) {
            self::completeRound($round);
            redirect('/rounds/' . (int) $round['id']);
        }

        $raw = trim((string) ($_POST['answer'] ?? ''));
        if ($raw === '' || !preg_match('/^-?\d+$/', $raw)) {
            flash('error', 'Type a whole number, then hit Check.');
            redirect('/play');
        }

        $userAnswer = (int) $raw;
        $correct = $userAnswer === (int) $problem['correct_answer'];

        Database::pdo()->prepare(
            'UPDATE round_problems
             SET user_answer = ?, is_correct = ?, answered_at = datetime(\'now\')
             WHERE id = ? AND user_answer IS NULL'
        )->execute([$userAnswer, $correct ? 1 : 0, (int) $problem['id']]);

        $_SESSION['last_result'] = [
            'correct' => $correct,
            'prompt' => $problem['prompt'],
            'user_answer' => $userAnswer,
            'correct_answer' => (int) $problem['correct_answer'],
            'message' => $correct ? self::hitCopy() : self::missCopy(),
        ];

        $still = self::nextProblem((int) $round['id']);
        if (!$still) {
            $fresh = self::loadRound((int) $round['id'], $userId);
            if ($fresh) {
                self::completeRound($fresh);
                redirect('/rounds/' . (int) $round['id']);
            }
        }

        redirect('/play');
    }

    public static function results(string $id): void
    {
        Auth::requireLogin();
        $round = self::loadRound((int) $id, (int) Auth::user()['id']);
        if (!$round || $round['status'] !== 'complete') {
            http_response_code(404);
            render('errors/404', ['title' => 'Not found']);
            return;
        }

        $pack = self::packForRound((int) $round['id']);
        $pulled = $pack && $pack['opened_at'] ? Cards::forPack((int) $pack['id']) : [];
        $lastResult = $_SESSION['last_result'] ?? null;
        unset($_SESSION['last_result']);

        render('results', [
            'title' => 'Pack',
            'round' => $round,
            'pack' => $pack,
            'pulled' => $pulled,
            'lastResult' => $lastResult,
            'bandLabel' => $pack ? PackOdds::bandLabel((string) $pack['odds_band']) : '',
        ]);
    }

    public static function open(string $id): void
    {
        Auth::requireLogin();
        verify_csrf();
        $userId = (int) Auth::user()['id'];
        $round = self::loadRound((int) $id, $userId);
        if (!$round || $round['status'] !== 'complete') {
            redirect('/');
        }

        $pack = self::packForRound((int) $round['id']);
        if (!$pack) {
            redirect('/rounds/' . (int) $round['id']);
        }

        PackOdds::openPack($pack);
        redirect('/rounds/' . (int) $round['id']);
    }

    private static function nextProblem(int $roundId): ?array
    {
        $stmt = Database::pdo()->prepare(
            'SELECT id, position, prompt FROM round_problems
             WHERE round_id = ? AND user_answer IS NULL
             ORDER BY position ASC
             LIMIT 1'
        );
        $stmt->execute([$roundId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    private static function nextProblemForGrading(int $roundId): ?array
    {
        $stmt = Database::pdo()->prepare(
            'SELECT * FROM round_problems
             WHERE round_id = ? AND user_answer IS NULL
             ORDER BY position ASC
             LIMIT 1'
        );
        $stmt->execute([$roundId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    private static function answeredCount(int $roundId): int
    {
        $stmt = Database::pdo()->prepare(
            'SELECT COUNT(*) FROM round_problems WHERE round_id = ? AND user_answer IS NOT NULL'
        );
        $stmt->execute([$roundId]);
        return (int) $stmt->fetchColumn();
    }

    private static function completeRound(array $round): void
    {
        if ($round['status'] === 'complete') {
            return;
        }

        $stmt = Database::pdo()->prepare(
            'SELECT COUNT(*) AS total,
                    SUM(CASE WHEN is_correct = 1 THEN 1 ELSE 0 END) AS correct
             FROM round_problems WHERE round_id = ?'
        );
        $stmt->execute([(int) $round['id']]);
        $counts = $stmt->fetch() ?: ['total' => 0, 'correct' => 0];
        $total = max(1, (int) $counts['total']);
        $correct = (int) $counts['correct'];
        $accuracy = round(100.0 * $correct / $total, 2);
        $band = PackOdds::bandFromAccuracy($accuracy);

        $pdo = Database::pdo();
        $pdo->prepare(
            'UPDATE rounds
             SET correct_count = ?, accuracy = ?, status = \'complete\', finished_at = datetime(\'now\')
             WHERE id = ? AND status = \'in_progress\''
        )->execute([$correct, $accuracy, (int) $round['id']]);

        $existing = self::packForRound((int) $round['id']);
        if (!$existing) {
            $pdo->prepare(
                'INSERT INTO packs (round_id, user_id, odds_band) VALUES (?, ?, ?)'
            )->execute([(int) $round['id'], (int) $round['user_id'], $band]);
        }
    }

    private static function loadRound(int $id, int $userId): ?array
    {
        $stmt = Database::pdo()->prepare('SELECT * FROM rounds WHERE id = ? AND user_id = ?');
        $stmt->execute([$id, $userId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    private static function packForRound(int $roundId): ?array
    {
        $stmt = Database::pdo()->prepare('SELECT * FROM packs WHERE round_id = ?');
        $stmt->execute([$roundId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    private static function hitCopy(): string
    {
        $lines = [
            'Purrfect!',
            'Yes! A card sparkles a little brighter.',
            'Nailed it!',
            'That one counted. Keep going!',
        ];
        return $lines[array_rand($lines)];
    }

    private static function missCopy(): string
    {
        $lines = [
            'Nice try — on to the next one.',
            'So close! You have got this.',
            'Shake it off. New problem, new chance.',
            'Misses still finish the round. Keep drawing!',
        ];
        return $lines[array_rand($lines)];
    }
}
