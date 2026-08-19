<div class="mx-auto max-w-md pt-4">
  <h1 class="font-display text-4xl">Pick your problems</h1>
  <p class="mt-2 text-ink-500">Choose any mix. You will get 10 questions, then a pack.</p>

  <form method="post" action="<?= e(url('/play/start')) ?>" class="mt-8 space-y-3">
    <?= csrf_field() ?>
    <?php foreach ($operations as $op): ?>
      <label class="flex cursor-pointer items-center gap-4 rounded-2xl border-2 border-parchment-200 bg-white px-4 py-4 has-[:checked]:border-ginger-500 has-[:checked]:bg-parchment-100">
        <input type="checkbox" name="operations[]" value="<?= e($op) ?>" checked class="h-6 w-6 accent-ginger-500">
        <span>
          <span class="block font-display text-2xl leading-none"><?= e($op) ?></span>
          <span class="text-sm font-bold text-ink-500"><?= e(operation_label($op)) ?></span>
        </span>
      </label>
    <?php endforeach; ?>
    <button type="submit" class="mt-4 w-full rounded-xl bg-ginger-500 px-4 py-4 text-xl font-extrabold text-white hover:bg-ginger-700">
      Start round
    </button>
  </form>
</div>
