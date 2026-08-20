-- MathCats schema (SQLite)

PRAGMA foreign_keys = ON;

CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE COLLATE NOCASE,
    password_hash TEXT NOT NULL,
    display_name TEXT NOT NULL DEFAULT '',
    created_at TEXT NOT NULL DEFAULT (datetime('now'))
);

CREATE TABLE IF NOT EXISTS cards (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    slug TEXT NOT NULL UNIQUE,
    name TEXT NOT NULL,
    rarity TEXT NOT NULL CHECK (rarity IN ('common', 'uncommon', 'rare', 'epic', 'legendary')),
    tribe TEXT NOT NULL DEFAULT '',
    flavor TEXT NOT NULL DEFAULT '',
    art_key TEXT NOT NULL DEFAULT 'cat',
    sort_order INTEGER NOT NULL DEFAULT 0
);

CREATE TABLE IF NOT EXISTS rounds (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    operations TEXT NOT NULL,
    problem_count INTEGER NOT NULL DEFAULT 10,
    correct_count INTEGER NOT NULL DEFAULT 0,
    accuracy REAL,
    status TEXT NOT NULL DEFAULT 'in_progress' CHECK (status IN ('in_progress', 'complete')),
    created_at TEXT NOT NULL DEFAULT (datetime('now')),
    finished_at TEXT
);

CREATE TABLE IF NOT EXISTS round_problems (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    round_id INTEGER NOT NULL REFERENCES rounds(id) ON DELETE CASCADE,
    position INTEGER NOT NULL,
    prompt TEXT NOT NULL,
    correct_answer INTEGER NOT NULL,
    user_answer INTEGER,
    is_correct INTEGER,
    shown_at TEXT NOT NULL DEFAULT (datetime('now')),
    answered_at TEXT,
    UNIQUE (round_id, position)
);

CREATE TABLE IF NOT EXISTS packs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    round_id INTEGER NOT NULL UNIQUE REFERENCES rounds(id) ON DELETE CASCADE,
    user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    odds_band TEXT NOT NULL,
    opened_at TEXT
);

CREATE TABLE IF NOT EXISTS user_cards (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    card_id INTEGER NOT NULL REFERENCES cards(id),
    pack_id INTEGER NOT NULL REFERENCES packs(id) ON DELETE CASCADE,
    slot INTEGER NOT NULL,
    acquired_at TEXT NOT NULL DEFAULT (datetime('now'))
);

CREATE INDEX IF NOT EXISTS idx_rounds_user_status ON rounds(user_id, status);
CREATE INDEX IF NOT EXISTS idx_round_problems_round ON round_problems(round_id, position);
CREATE INDEX IF NOT EXISTS idx_user_cards_user ON user_cards(user_id);
CREATE INDEX IF NOT EXISTS idx_user_cards_pack ON user_cards(pack_id);
CREATE INDEX IF NOT EXISTS idx_cards_rarity ON cards(rarity, sort_order);
