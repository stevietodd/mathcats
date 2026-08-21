<?php
$parts = Problems::parse((string) ($prompt ?? ''));
$size = $size ?? 'lg';
$compact = !empty($compact);
$showInput = !empty($showInput);
$answerText = $answerText ?? null;
$numClass = $size === 'sm'
    ? 'text-2xl'
    : 'text-6xl sm:text-7xl';
$opClass = $size === 'sm'
    ? 'text-xl'
    : 'text-5xl sm:text-6xl';
$lineClass = $size === 'sm' ? 'border-t-2 mt-1' : 'border-t-4 mt-2';
?>
<div class="math-problem inline-grid grid-cols-[auto_auto] items-baseline justify-items-end font-display tabular-nums leading-none text-ink-900 <?= $compact ? '' : 'mx-auto' ?>">
  <span></span>
  <span class="<?= e($numClass) ?> pl-3"><?= (int) $parts['top'] ?></span>
  <span class="<?= e($opClass) ?> pr-3"><?= e($parts['op']) ?></span>
  <span class="<?= e($numClass) ?> pl-3"><?= (int) $parts['bottom'] ?></span>
  <span class="col-start-2 <?= e($lineClass) ?> w-full border-ink-900"></span>
  <?php if ($showInput): ?>
    <input
      class="col-start-2 mt-2 w-full min-w-[2ch] border-0 bg-transparent p-0 text-right font-display <?= e($numClass) ?> leading-none text-ink-900 caret-ginger-500 focus:outline-none focus:ring-0"
      type="text"
      inputmode="numeric"
      pattern="-?[0-9]*"
      name="answer"
      id="answer"
      autocomplete="off"
      required
    >
  <?php elseif ($answerText !== null): ?>
    <span class="col-start-2 mt-2 <?= e($numClass) ?> text-right"><?= e((string) $answerText) ?></span>
  <?php endif; ?>
</div>
