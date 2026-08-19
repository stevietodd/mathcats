<div>
  <div class="flex items-end justify-between gap-3">
    <div>
      <h1 class="font-display text-4xl">Binder</h1>
      <p class="mt-1 text-ink-500"><?= (int) $stats['unique'] ?> / <?= (int) $catalogCount ?> unique · <?= (int) $stats['total'] ?> cards owned</p>
    </div>
  </div>

  <div class="mt-4 flex gap-2 overflow-x-auto pb-1">
    <a href="<?= e(url('/binder')) ?>" class="whitespace-nowrap rounded-full px-3 py-1 text-sm font-bold <?= $filter === '' ? 'bg-ginger-500 text-white' : 'bg-white text-ink-500 ring-1 ring-parchment-200' ?>">All</a>
    <?php foreach (rarity_order() as $rarity): ?>
      <a href="<?= e(url('/binder?rarity=' . $rarity)) ?>" class="whitespace-nowrap rounded-full px-3 py-1 text-sm font-bold <?= $filter === $rarity ? 'bg-ginger-500 text-white' : 'bg-white text-ink-500 ring-1 ring-parchment-200' ?>">
        <?= e(rarity_label($rarity)) ?>
      </a>
    <?php endforeach; ?>
  </div>

  <?php if (!$cards): ?>
    <p class="mt-8 text-ink-500">No cards in this slice of the binder yet. Play a round to draw some.</p>
  <?php else: ?>
    <div class="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-3">
      <?php foreach ($cards as $card): ?>
        <?php
          $size = 'md';
          $href = url('/binder/' . $card['slug']);
          $owned = (int) $card['owned'];
          $flip = false;
          require ROOT_PATH . '/templates/partials/trading_card.php';
        ?>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
