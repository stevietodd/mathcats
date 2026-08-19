<?php if (!empty($guest)): ?>
  <section class="pt-6">
    <p class="text-sm font-extrabold uppercase tracking-widest text-ginger-700">Fantasy cards · grade 3–5 math</p>
    <h1 class="font-display mt-2 text-4xl leading-none text-ink-900">Solve. Draw. Collect cats.</h1>
    <p class="mt-3 max-w-md text-lg text-ink-500">
      Finish a round of 10 problems to open a pack. The more you get right, the luckier the hero card.
    </p>
    <div class="mt-6 flex flex-col gap-3 sm:flex-row">
      <a href="<?= e(url('/signup')) ?>" class="rounded-xl bg-ginger-500 px-5 py-3 text-center text-lg font-extrabold text-white hover:bg-ginger-700">Sign up</a>
      <a href="<?= e(url('/login')) ?>" class="rounded-xl border-2 border-parchment-200 bg-white px-5 py-3 text-center text-lg font-extrabold text-ink-700">Log in</a>
    </div>
  </section>
<?php else: ?>
  <section class="pt-2">
    <p class="text-sm font-extrabold uppercase tracking-widest text-ginger-700">Hi, <?= e(Auth::displayName()) ?></p>
    <h1 class="font-display mt-1 text-4xl text-ink-900">Ready for a round?</h1>
    <p class="mt-2 text-ink-500">Ten problems. One pack of three cards. Slot three is the hero pull.</p>

    <?php if ($activeRound): ?>
      <a href="<?= e(url('/play')) ?>" class="mt-6 block rounded-2xl bg-ginger-500 px-5 py-4 text-center text-xl font-extrabold text-white hover:bg-ginger-700">
        Continue round
      </a>
    <?php else: ?>
      <a href="<?= e(url('/play/setup')) ?>" class="mt-6 block rounded-2xl bg-ginger-500 px-5 py-4 text-center text-xl font-extrabold text-white hover:bg-ginger-700">
        Play a round
      </a>
    <?php endif; ?>
  </section>

  <?php if ($lastRound): ?>
    <section class="mt-8 rounded-2xl border-2 border-parchment-200 bg-white p-4">
      <p class="text-xs font-extrabold uppercase tracking-widest text-ink-500">Last round</p>
      <p class="mt-1 font-display text-2xl">
        <?= (int) $lastRound['correct_count'] ?>/<?= (int) $lastRound['problem_count'] ?>
      </p>
      <p class="text-sm text-ink-500">
        <?= e(PackOdds::bandLabel((string) ($lastRound['odds_band'] ?? PackOdds::bandFromAccuracy((float) $lastRound['accuracy'])))) ?>
      </p>
      <?php if (!empty($lastRound['pack_id'])): ?>
        <a href="<?= e(url('/rounds/' . (int) $lastRound['id'])) ?>" class="mt-2 inline-block text-sm font-bold text-ginger-700">See pack</a>
      <?php endif; ?>
    </section>
  <?php endif; ?>

  <section class="mt-8">
    <div class="flex items-end justify-between">
      <div>
        <h2 class="font-display text-2xl">Binder</h2>
        <p class="text-sm text-ink-500"><?= (int) $stats['unique'] ?> / <?= (int) $catalogCount ?> unique · <?= (int) $stats['total'] ?> cards</p>
      </div>
      <a href="<?= e(url('/binder')) ?>" class="text-sm font-bold text-ginger-700">Open binder</a>
    </div>
    <?php if ($recent): ?>
      <div class="mt-4 grid grid-cols-3 gap-3">
        <?php foreach ($recent as $card): ?>
          <?php
            $size = 'sm';
            $href = url('/binder/' . $card['slug']);
            $owned = null;
            $flip = false;
            require ROOT_PATH . '/templates/partials/trading_card.php';
          ?>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p class="mt-3 text-ink-500">No cards yet. Finish a round to open your first pack.</p>
    <?php endif; ?>
  </section>
<?php endif; ?>
