<div class="mx-auto max-w-sm pt-8">
  <p class="text-sm font-extrabold uppercase tracking-widest text-ginger-700">Join the cat court</p>
  <h1 class="font-display mt-1 text-4xl text-ink-900">Sign up</h1>
  <p class="mt-2 text-ink-500">Pick a username. Solve math. Draw fantasy cats.</p>
  <form method="post" action="<?= e(url('/signup')) ?>" class="mt-8 space-y-4">
    <?= csrf_field() ?>
    <div>
      <label class="mb-1 block text-sm font-bold" for="username">Username</label>
      <input class="field-input" type="text" name="username" id="username" autocomplete="username" minlength="3" maxlength="20" required>
      <p class="mt-1 text-xs text-ink-500">3–20 letters, numbers, or underscores. Start with a letter.</p>
    </div>
    <div>
      <label class="mb-1 block text-sm font-bold" for="display_name">Display name <span class="font-medium text-ink-500">(optional)</span></label>
      <input class="field-input" type="text" name="display_name" id="display_name" autocomplete="nickname" maxlength="30">
    </div>
    <div>
      <label class="mb-1 block text-sm font-bold" for="password">Password</label>
      <input class="field-input" type="password" name="password" id="password" autocomplete="new-password" minlength="4" required>
    </div>
    <button type="submit" class="w-full rounded-xl bg-ginger-500 px-4 py-3 text-lg font-extrabold text-white hover:bg-ginger-700">
      Create my binder
    </button>
  </form>
  <p class="mt-6 text-center text-ink-500">
    Already playing?
    <a class="font-bold text-ginger-700" href="<?= e(url('/login')) ?>">Log in</a>
  </p>
</div>
