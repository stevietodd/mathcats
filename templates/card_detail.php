<div class="mx-auto max-w-sm pt-2">
  <a href="<?= e(url('/binder')) ?>" class="text-sm font-bold text-ginger-700">← Binder</a>
  <div class="mt-4">
    <?php
      $size = 'lg';
      $href = null;
      $owned = $owned;
      $flip = false;
      require ROOT_PATH . '/templates/partials/trading_card.php';
    ?>
  </div>
  <p class="mt-6 text-lg leading-relaxed text-ink-700"><?= e($card['flavor']) ?></p>
  <p class="mt-4 text-sm font-bold text-ink-500">
    You own <?= (int) $owned ?> <?= ((int) $owned === 1) ? 'copy' : 'copies' ?>.
  </p>
</div>
