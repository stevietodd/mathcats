<div class="mx-auto max-w-sm pt-8">
  <p class="text-sm font-extrabold uppercase tracking-widest text-ginger-700">Welcome back</p>
  <h1 class="font-display mt-1 text-4xl text-ink-900">Log in</h1>
  <p class="mt-2 text-ink-500">Use the username you picked. No email needed.</p>
  <form method="post" action="<?= e(url('/login')) ?>" class="mt-8 space-y-4">
    <?= csrf_field() ?>
    <div>
      <label class="mb-1 block text-sm font-bold" for="username">Username</label>
      <input class="field-input" type="text" name="username" id="username" autocomplete="username" required>
    </div>
    <div>
      <label class="mb-1 block text-sm font-bold" for="password">Password</label>
      <input class="field-input" type="password" name="password" id="password" autocomplete="current-password" required>
    </div>
    <button type="submit" class="w-full rounded-xl bg-ginger-500 px-4 py-3 text-lg font-extrabold text-white hover:bg-ginger-700">
      Log in
    </button>
  </form>
  <p class="mt-6 text-center text-ink-500">
    New here?
    <a class="font-bold text-ginger-700" href="<?= e(url('/signup')) ?>">Create a trainer</a>
  </p>
</div>
