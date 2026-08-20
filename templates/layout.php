<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title><?= e(($title ?? 'Play') . ' · ' . $appName) ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            parchment: { 50:'#fbf6ea', 100:'#f4ead8', 200:'#e8d5b5', 500:'#c9a66b', 700:'#8a6230' },
            ink: { 500:'#6b4f35', 700:'#3b2a1a', 900:'#24180f' },
            ginger: { 400:'#e0894c', 500:'#c45c26', 700:'#8c3d16' },
            jewel: {
              common:'#6b7280',
              uncommon:'#16a34a',
              rare:'#2563eb',
              epic:'#7c3aed',
              legendary:'#c9a227'
            }
          },
          fontFamily: {
            display: ['"Lilita One"', 'system-ui', 'sans-serif'],
            body: ['Nunito', 'system-ui', 'sans-serif'],
          }
        }
      }
    }
  </script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Nunito:wght@500;700;800&display=swap" rel="stylesheet">
  <style>
    body { font-family: Nunito, system-ui, sans-serif; }
    .font-display { font-family: 'Lilita One', system-ui, sans-serif; }
    :root {
      --bottom-nav-height: 5.5rem;
      --bottom-nav-gap: 1.25rem;
      --bottom-nav-space: calc(var(--bottom-nav-height) + var(--bottom-nav-gap));
    }
    html { scroll-padding-bottom: var(--bottom-nav-space); }
    .bottom-nav-spacer {
      flex-shrink: 0;
      height: var(--bottom-nav-space);
      pointer-events: none;
    }
    .field-input {
      width: 100%;
      border-radius: 0.85rem;
      border: 2px solid #e8d5b5;
      background: #fff;
      padding: 0.85rem 1rem;
      font-size: 1.1rem;
    }
    .field-input:focus {
      outline: none;
      border-color: #c45c26;
      box-shadow: 0 0 0 3px rgba(196, 92, 38, 0.2);
    }
    .card-face { perspective: 800px; }
    .card-flip {
      transform-style: preserve-3d;
      animation: packFlip 0.7s ease-out both;
    }
    .card-flip:nth-child(2) { animation-delay: 0.12s; }
    .card-flip:nth-child(3) { animation-delay: 0.24s; }
    @keyframes packFlip {
      from { transform: rotateY(90deg) scale(0.9); opacity: 0; }
      to { transform: rotateY(0) scale(1); opacity: 1; }
    }
    .rarity-common { --rarity: #6b7280; }
    .rarity-uncommon { --rarity: #16a34a; }
    .rarity-rare { --rarity: #2563eb; }
    .rarity-epic { --rarity: #7c3aed; }
    .rarity-legendary { --rarity: #c9a227; }
  </style>
</head>
<body class="min-h-screen bg-parchment-50 text-ink-900 antialiased">
  <div class="pointer-events-none fixed inset-0 opacity-40" style="background-image: radial-gradient(circle at 12% 8%, rgba(196,92,38,0.12), transparent 28%), radial-gradient(circle at 88% 0%, rgba(201,162,39,0.16), transparent 32%);"></div>
  <div class="relative mx-auto flex min-h-screen max-w-3xl flex-col">
    <header class="sticky top-0 z-20 border-b border-parchment-200/80 bg-parchment-50/95 backdrop-blur">
      <div class="flex items-center justify-between px-4 py-3">
        <a href="<?= e(url('/')) ?>" class="flex items-center gap-2 font-display text-2xl tracking-tight text-ginger-700">
          <span aria-hidden="true">🐱</span> MathCats
        </a>
        <div class="flex items-center gap-3 text-sm">
          <?php if ($currentUser): ?>
            <span class="hidden text-ink-500 sm:inline"><?= e(Auth::displayName()) ?></span>
            <form method="post" action="<?= e(url('/logout')) ?>">
              <?= csrf_field() ?>
              <button type="submit" class="text-ink-500 hover:text-ginger-700">Log out</button>
            </form>
          <?php else: ?>
            <a href="<?= e(url('/login')) ?>" class="font-bold text-ginger-700 hover:text-ginger-500">Log in</a>
          <?php endif; ?>
        </div>
      </div>
    </header>

    <?php if ($flashes): ?>
      <div class="space-y-2 px-4 pt-3">
        <?php foreach ($flashes as $flash): ?>
          <div class="rounded-xl px-3 py-2 text-sm <?= $flash['type'] === 'error' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' ?>">
            <?= e($flash['message']) ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <main class="flex-1 px-4 py-4">
      <?php require $templateFile; ?>
    </main>

    <div class="bottom-nav-spacer" aria-hidden="true"></div>

    <?php $path = request_path(); ?>
    <nav id="bottom-nav"
         class="fixed bottom-0 left-0 right-0 z-20 border-t border-parchment-200 bg-white/95 backdrop-blur"
         style="padding-bottom: env(safe-area-inset-bottom, 0px);">
      <div class="mx-auto grid max-w-3xl <?= $currentUser ? 'grid-cols-2' : 'grid-cols-2' ?> text-center text-xs font-bold text-ink-500">
        <a href="<?= e(url('/')) ?>" class="flex flex-col items-center gap-1 py-3 <?= $path === '/' || str_starts_with($path, '/play') || str_starts_with($path, '/rounds') ? 'text-ginger-700' : '' ?>">
          <span class="text-lg leading-none">✦</span>Play
        </a>
        <?php if ($currentUser): ?>
          <a href="<?= e(url('/binder')) ?>" class="flex flex-col items-center gap-1 py-3 <?= str_starts_with($path, '/binder') ? 'text-ginger-700' : '' ?>">
            <span class="text-lg leading-none">☰</span>Binder
          </a>
        <?php else: ?>
          <a href="<?= e(url('/signup')) ?>" class="flex flex-col items-center gap-1 py-3 <?= str_starts_with($path, '/signup') ? 'text-ginger-700' : '' ?>">
            <span class="text-lg leading-none">★</span>Sign up
          </a>
        <?php endif; ?>
      </div>
    </nav>
  </div>
  <script>
    (function () {
      var nav = document.getElementById('bottom-nav');
      if (!nav) return;
      function syncBottomNavSpace() {
        var height = Math.ceil(nav.getBoundingClientRect().height);
        if (!height) return;
        document.documentElement.style.setProperty('--bottom-nav-height', height + 'px');
      }
      syncBottomNavSpace();
      window.addEventListener('resize', syncBottomNavSpace);
      window.addEventListener('orientationchange', syncBottomNavSpace);
      if (window.visualViewport) {
        window.visualViewport.addEventListener('resize', syncBottomNavSpace);
      }
    })();
  </script>
</body>
</html>
