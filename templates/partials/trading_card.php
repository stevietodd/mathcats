<?php
$rarity = $card['rarity'] ?? 'common';
$size = $size ?? 'md';
$owned = isset($owned) ? (int) $owned : null;
$href = $href ?? null;
$flip = !empty($flip);
$pad = $size === 'lg' ? 'p-5 min-h-[22rem]' : ($size === 'sm' ? 'p-3 min-h-[13rem]' : 'p-4 min-h-[16rem]');
$emojiSize = $size === 'lg' ? 'text-7xl' : ($size === 'sm' ? 'text-4xl' : 'text-5xl');
$wrapperClass = $flip ? 'card-flip block' : 'block';
?>
<?php if ($href): ?>
<a href="<?= e($href) ?>" class="<?= e($wrapperClass) ?> hover:-translate-y-0.5">
<?php else: ?>
<div class="<?= e($wrapperClass) ?>">
<?php endif; ?>
  <div class="rarity-<?= e($rarity) ?> relative flex h-full flex-col overflow-hidden rounded-2xl border-4 bg-white shadow-md <?= e($pad) ?>" style="border-color: var(--rarity);">
    <div class="absolute inset-x-0 top-0 h-2" style="background: var(--rarity);"></div>
    <p class="text-[0.65rem] font-extrabold uppercase tracking-widest" style="color: var(--rarity);">
      <?= e(rarity_label($rarity)) ?>
    </p>
    <div class="flex flex-1 items-center justify-center py-3">
      <span class="<?= e($emojiSize) ?>" aria-hidden="true"><?= Cards::emoji((string) $card['art_key']) ?></span>
    </div>
    <h3 class="font-display text-lg leading-tight text-ink-900"><?= e($card['name']) ?></h3>
    <p class="mt-1 text-xs font-bold uppercase tracking-wide text-ink-500"><?= e($card['tribe']) ?></p>
    <?php if ($owned !== null): ?>
      <p class="mt-2 text-xs font-bold text-ginger-700">×<?= $owned ?></p>
    <?php endif; ?>
  </div>
<?php if ($href): ?>
</a>
<?php else: ?>
</div>
<?php endif; ?>
