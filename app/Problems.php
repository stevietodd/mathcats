<?php

declare(strict_types=1);

final class Problems
{
    public const OPERATIONS = ['+', '-', '×', '÷'];
    public const ROUND_SIZE = 10;

    /**
     * @param list<string> $operations
     * @return list<array{prompt:string,answer:int}>
     */
    public static function generateRound(array $operations, int $count = self::ROUND_SIZE): array
    {
        $ops = array_values(array_intersect(self::OPERATIONS, $operations));
        if ($ops === []) {
            $ops = self::OPERATIONS;
        }

        $problems = [];
        $seen = [];
        $guard = 0;
        while (count($problems) < $count && $guard < 200) {
            $guard++;
            $op = $ops[array_rand($ops)];
            $problem = self::generateOne($op);
            $key = $problem['prompt'];
            if (isset($seen[$key])) {
                continue;
            }
            $seen[$key] = true;
            $problems[] = $problem;
        }

        while (count($problems) < $count) {
            $problems[] = self::generateOne($ops[array_rand($ops)]);
        }

        return $problems;
    }

    /** @return array{prompt:string,answer:int} */
    public static function generateOne(string $op): array
    {
        return match ($op) {
            '+' => self::addition(),
            '-' => self::subtraction(),
            '×' => self::multiplication(),
            '÷' => self::division(),
            default => self::addition(),
        };
    }

    /**
     * @return array{top:int,op:string,bottom:int}
     */
    public static function parse(string $prompt): array
    {
        $data = json_decode($prompt, true);
        if (is_array($data) && isset($data['top'], $data['op'], $data['bottom'])) {
            return [
                'top' => (int) $data['top'],
                'op' => (string) $data['op'],
                'bottom' => (int) $data['bottom'],
            ];
        }

        if (preg_match('/^(-?\d+)\s*([+\-−×÷])\s*(-?\d+)$/u', trim($prompt), $m)) {
            return [
                'top' => (int) $m[1],
                'op' => $m[2] === '-' ? '−' : $m[2],
                'bottom' => (int) $m[3],
            ];
        }

        return ['top' => 0, 'op' => '+', 'bottom' => 0];
    }

    public static function encode(int $top, string $op, int $bottom): string
    {
        $json = json_encode(['top' => $top, 'op' => $op, 'bottom' => $bottom], JSON_UNESCAPED_UNICODE);
        return is_string($json) ? $json : '{"top":0,"op":"+","bottom":0}';
    }

    /** @return array{prompt:string,answer:int} */
    private static function make(int $top, string $op, int $bottom, int $answer): array
    {
        return ['prompt' => self::encode($top, $op, $bottom), 'answer' => $answer];
    }

    /** @return array{prompt:string,answer:int} */
    private static function addition(): array
    {
        if (random_int(1, 3) === 1) {
            $a = random_int(100, 899);
            $b = random_int(10, min(99, 999 - $a));
        } else {
            $a = random_int(10, 99);
            $b = random_int(10, 99);
        }
        return self::make($a, '+', $b, $a + $b);
    }

    /** @return array{prompt:string,answer:int} */
    private static function subtraction(): array
    {
        $bottom = random_int(0, 9);
        $top = random_int($bottom, 17);
        return self::make($top, '−', $bottom, $top - $bottom);
    }

    /** @return array{prompt:string,answer:int} */
    private static function multiplication(): array
    {
        if (random_int(1, 4) === 1) {
            $a = random_int(11, 48);
            $b = random_int(2, 9);
        } else {
            $a = random_int(2, 12);
            $b = random_int(2, 12);
        }
        return self::make($a, '×', $b, $a * $b);
    }

    /** @return array{prompt:string,answer:int} */
    private static function division(): array
    {
        if (random_int(1, 3) === 1) {
            $divisor = random_int(2, 9);
            $quotient = random_int(11, 20);
        } else {
            $divisor = random_int(2, 12);
            $quotient = random_int(2, 12);
        }
        $dividend = $divisor * $quotient;
        return self::make($dividend, '÷', $divisor, $quotient);
    }

    /**
     * @param list<string> $posted
     * @return list<string>
     */
    public static function sanitizeOperations(array $posted): array
    {
        $ops = [];
        foreach ($posted as $op) {
            if (is_string($op) && in_array($op, self::OPERATIONS, true)) {
                $ops[] = $op;
            }
        }
        return array_values(array_unique($ops));
    }
}
