<div class="mx-auto max-w-md">
  <div class="flex items-center justify-between">
    <p class="text-sm font-extrabold uppercase tracking-widest text-ginger-700">
      Problem <?= (int) $progress ?> of <?= (int) $total ?>
    </p>
    <div class="h-2 w-28 overflow-hidden rounded-full bg-parchment-200">
      <div class="h-full rounded-full bg-ginger-500" style="width: <?= (int) round(100 * $progress / max(1, $total)) ?>%"></div>
    </div>
  </div>

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

  <h1 class="font-display mt-8 text-center text-5xl leading-tight sm:text-6xl"><?= e($problem['prompt']) ?></h1>

  <form method="post" action="<?= e(url('/play/answer')) ?>" class="mt-8" id="answer-form">
    <?= csrf_field() ?>
    <label class="sr-only" for="answer">Your answer</label>
    <input
      class="field-input text-center font-display text-4xl tracking-wide"
      type="text"
      inputmode="numeric"
      pattern="-?[0-9]*"
      name="answer"
      id="answer"
      autocomplete="off"
      required
    >

    <div class="mt-4 grid grid-cols-3 gap-2" id="keypad">
      <?php foreach (['1','2','3','4','5','6','7','8','9'] as $n): ?>
        <button type="button" data-key="<?= e($n) ?>" class="rounded-2xl bg-white py-4 text-2xl font-extrabold shadow-sm ring-1 ring-parchment-200 active:bg-parchment-100"><?= e($n) ?></button>
      <?php endforeach; ?>
      <button type="button" data-key="back" class="rounded-2xl bg-parchment-200 py-4 text-lg font-extrabold text-ink-700">⌫</button>
      <button type="button" data-key="0" class="rounded-2xl bg-white py-4 text-2xl font-extrabold shadow-sm ring-1 ring-parchment-200">0</button>
      <button type="submit" class="rounded-2xl bg-ginger-500 py-4 text-lg font-extrabold text-white">Check</button>
    </div>
  </form>
</div>
<script>
  (function () {
    var input = document.getElementById('answer');
    var pad = document.getElementById('keypad');
    if (!input || !pad) return;
    pad.addEventListener('click', function (event) {
      var btn = event.target.closest('[data-key]');
      if (!btn) return;
      var key = btn.getAttribute('data-key');
      if (key === 'back') {
        input.value = input.value.slice(0, -1);
        return;
      }
      if (input.value.length >= 6) return;
      input.value += key;
    });
    input.focus();
  })();
</script>
