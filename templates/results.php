<?php
$correct = (int) $round['correct_count'];
$total = (int) $round['problem_count'];
$opened = $pulled !== [];
?>
<div class="mx-auto max-w-lg pt-2">
  <p class="text-sm font-extrabold uppercase tracking-widest text-ginger-700">Round complete</p>
  <h1 class="font-display mt-1 text-4xl"><?= $correct ?>/<?= $total ?></h1>
  <?php if (!empty($lastResult)): ?>
    <div class="mt-4 rounded-2xl px-4 py-3 <?= !empty($lastResult['correct']) ? 'bg-green-100 text-green-900' : 'bg-amber-100 text-amber-900' ?>">
      <p class="font-extrabold"><?= e($lastResult['message']) ?></p>
      <p class="mt-1 text-sm">
        <?= e($lastResult['prompt']) ?> = <?= e((string) $lastResult['user_answer']) ?>
        <?php if (empty($lastResult['correct'])): ?>
          <span class="font-bold"> · answer was <?= (int) $lastResult['correct_answer'] ?></span>
        <?php endif; ?>
      </p>
    </div>
  <?php endif; ?>
  <p class="mt-3 text-lg text-ink-500"><?= e($bandLabel) ?></p>
  <p class="mt-2 text-sm text-ink-500">
    More correct answers make the hero card (slot 3) luckier. You always get a pack.
  </p>

  <?php if (!$opened): ?>
    <form method="post" action="<?= e(url('/rounds/' . (int) $round['id'] . '/open')) ?>" class="mt-8">
      <?= csrf_field() ?>
      <button type="submit" class="w-full rounded-2xl bg-ginger-500 px-5 py-5 text-2xl font-extrabold text-white hover:bg-ginger-700">
        Open pack
      </button>
    </form>
    <div class="mt-6 grid grid-cols-3 gap-3 opacity-70">
      <?php for ($i = 0; $i < 3; $i++): ?>
        <div class="flex min-h-[13rem] items-center justify-center rounded-2xl border-4 border-dashed border-parchment-200 bg-parchment-100 font-display text-3xl text-parchment-500">
          ?
        </div>
      <?php endfor; ?>
    </div>
  <?php else: ?>
    <div class="card-face mt-8 grid grid-cols-3 gap-3">
      <?php foreach ($pulled as $index => $card): ?>
        <?php
          $size = 'sm';
          $href = url('/binder/' . $card['slug']);
          $owned = null;
          $flip = true;
          require ROOT_PATH . '/templates/partials/trading_card.php';
        ?>
      <?php endforeach; ?>
    </div>
    <p class="mt-3 text-center text-xs font-bold uppercase tracking-widest text-ink-500">
      Slot 3 is the hero card
    </p>
    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
      <a href="<?= e(url('/play/setup')) ?>" class="flex-1 rounded-xl bg-ginger-500 px-4 py-3 text-center font-extrabold text-white">Play again</a>
      <a href="<?= e(url('/binder')) ?>" class="flex-1 rounded-xl border-2 border-parchment-200 bg-white px-4 py-3 text-center font-extrabold text-ink-700">Binder</a>
    </div>
  <?php endif; ?>
</div>
