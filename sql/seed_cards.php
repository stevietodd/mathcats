<?php

declare(strict_types=1);

/**
 * Starter trading-card catalog.
 * Common/Uncommon: mostly non-cat fantasy.
 * Rare: a few cats. Epic/Legendary: almost entirely cats.
 *
 * @return list<array{slug:string,name:string,rarity:string,tribe:string,flavor:string,art_key:string}>
 */
function mathcats_seed_cards(): array
{
    return [
        // Common
        ['goblin-scribbler', 'Goblin Scribbler', 'common', 'Goblin', 'His homework is mostly doodles.', 'goblin'],
        ['pebble-imp', 'Pebble Imp', 'common', 'Imp', 'Throws tiny rocks with great confidence.', 'imp'],
        ['meadow-sprite', 'Meadow Sprite', 'common', 'Sprite', 'Lives in a dandelion and charges rent.', 'sprite'],
        ['dust-mote', 'Dust Mote', 'common', 'Sprite', 'Barely there. Still trying its best.', 'spark'],
        ['cave-bat', 'Cave Bat', 'common', 'Beast', 'Hangs around. That is the whole job.', 'bat'],
        ['mushroom-scout', 'Mushroom Scout', 'common', 'Folk', 'Reports on damp news only.', 'mushroom'],
        ['river-newt', 'River Newt', 'common', 'Beast', 'Damp, determined, slightly lost.', 'newt'],
        ['hedge-pixie', 'Hedge Pixie', 'common', 'Pixie', 'Trims bushes into surprise faces.', 'pixie'],
        ['tin-soldier', 'Tin Soldier', 'common', 'Construct', 'Marches in a very small circle.', 'soldier'],
        ['lantern-bug', 'Lantern Bug', 'common', 'Bug', 'Lights the path one blink at a time.', 'bug'],
        ['puddle-frog', 'Puddle Frog', 'common', 'Beast', 'Claims every puddle as a kingdom.', 'frog'],
        ['sparrow-page', 'Sparrow Page', 'common', 'Folk', 'Delivers notes. Sometimes the right ones.', 'bird'],
        ['rootling', 'Rootling', 'common', 'Plant', 'Trips heroes on purpose. Maybe.', 'plant'],
        ['ember-spark', 'Ember Spark', 'common', 'Elemental', 'A sneeze away from a campfire.', 'spark'],

        // Uncommon
        ['timber-wolf', 'Timber Wolf', 'uncommon', 'Beast', 'Howls at fractions until they simplify.', 'wolf'],
        ['squire-of-the-oak', 'Squire of the Oak', 'uncommon', 'Knight', 'Swears loyalty to a very patient tree.', 'knight'],
        ['bronze-hatchling', 'Bronze Hatchling', 'uncommon', 'Dragon', 'Practices roaring. It comes out as a squeak.', 'dragon'],
        ['forest-ranger', 'Forest Ranger', 'uncommon', 'Folk', 'Knows every shortcut and every snack bush.', 'ranger'],
        ['crystal-slime', 'Crystal Slime', 'uncommon', 'Slime', 'Bounces. Sparkles. Refuses to explain.', 'slime'],
        ['beetle-knight', 'Beetle Knight', 'uncommon', 'Bug', 'Armor so shiny it blinds ants.', 'bug'],
        ['moon-moth', 'Moon Moth', 'uncommon', 'Bug', 'Follows lanterns and leftover homework.', 'moth'],
        ['apprentice-mage', 'Apprentice Mage', 'uncommon', 'Mage', 'Turned a pencil into a slightly nicer pencil.', 'mage'],
        ['shield-badger', 'Shield Badger', 'uncommon', 'Beast', 'Digs in and does not budge.', 'badger'],
        ['wind-archer', 'Wind Archer', 'uncommon', 'Elf', 'Never misses a leaf. Targets vary.', 'elf'],
        ['tide-crab', 'Tide Crab', 'uncommon', 'Beast', 'Side-steps every problem, then pinches it.', 'crab'],
        ['tabby-trainee', 'Tabby Trainee', 'uncommon', 'Cat', 'Naps between drills. Still improving.', 'cat'],

        // Rare
        ['storm-mage', 'Storm Mage', 'rare', 'Mage', 'Keeps lightning in a jar labeled maybe.', 'mage'],
        ['ember-dragon', 'Ember Dragon', 'rare', 'Dragon', 'Warm enough to toast marshmallows mid-battle.', 'dragon'],
        ['paladin-of-dawn', 'Paladin of Dawn', 'rare', 'Knight', 'Shows up early. Brings muffins. Smites politely.', 'paladin'],
        ['shadow-panther', 'Shadow Panther', 'rare', 'Cat', 'Pounces from the dark between numbers.', 'panther'],
        ['gilded-griffin', 'Gilded Griffin', 'rare', 'Beast', 'Half eagle, half lion, all drama.', 'griffin'],
        ['frost-witch', 'Frost Witch', 'rare', 'Mage', 'Freezes time just long enough to count.', 'witch'],
        ['calico-cavalier', 'Calico Cavalier', 'rare', 'Cat', 'Rides into class on a very patient broom.', 'cat'],
        ['thunder-boar', 'Thunder Boar', 'rare', 'Beast', 'Charges first. Math later.', 'boar'],
        ['star-seer', 'Star Seer', 'rare', 'Mage', 'Reads constellations like answer keys.', 'seer'],
        ['marble-golem', 'Marble Golem', 'rare', 'Construct', 'Slow, sturdy, excellent at carrying ones.', 'golem'],

        // Epic (almost all cats)
        ['whiskerblade', 'Whiskerblade', 'epic', 'Cat', 'A duelist whose sword is mostly attitude.', 'warrior'],
        ['arcane-paw', 'Arcane Paw', 'epic', 'Cat', 'Casts spells by knocking cups off the table.', 'mage'],
        ['duchess-softstep', 'Duchess Softstep', 'epic', 'Cat', 'Rules the velvet court. Demands chin scratches.', 'empress'],
        ['captain-clawford', 'Captain Clawford', 'epic', 'Cat', 'Sails the milk sea. Never shares the fish.', 'captain'],
        ['hexwhisker', 'Hexwhisker', 'epic', 'Cat', 'Curses homework. Blesses snacks.', 'witch'],
        ['lionheart-of-the-keep', 'Lionheart of the Keep', 'epic', 'Cat', 'Guards the tower. Also the sunbeam.', 'lion'],
        ['nightpounce', 'Nightpounce', 'epic', 'Cat', 'Appears, solves, vanishes. Leaves a hairball.', 'assassin'],
        ['velvet-drake', 'The Velvet Drake', 'epic', 'Dragon', 'A rare scaled ally of the cat courts.', 'dragon'],

        // Legendary (all cats)
        ['empress-whiskerion', 'Empress Whiskerion', 'legendary', 'Cat', 'Nine lives. Nine crowns. Zero remaining treats.', 'empress'],
        ['sir-pouncealot', 'Sir Pouncealot', 'legendary', 'Cat', 'Knighted for bravery and excellent loaf form.', 'paladin'],
        ['the-ninth-life', 'The Ninth Life', 'legendary', 'Cat', 'The last chance. The brightest one.', 'ghost'],
        ['mooncrown', 'Mooncrown, the First Cat', 'legendary', 'Cat', 'Older than bedtime. Softer than starlight.', 'moon'],
        ['lord-whiskermaw', 'Lord Whiskermaw', 'legendary', 'Cat', 'Speaks in riddles and slow blinks.', 'mythic'],
        ['aetherpurr', 'Aetherpurr, Star-Cat', 'legendary', 'Cat', 'Purrs in a frequency that solves equations.', 'star'],
    ];
}
