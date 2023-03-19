<?php view("partials.head", ["heading" => $heading]); ?>
<?php view("partials.nav"); ?>
<?php view("partials.banner", ["heading" => $heading]); ?>

<main>
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="<?= route("notes.create") ?>" class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
                Create Note +
            </a>
        </div>

        <ul>
            <?php foreach ($notes as $note): ?>
                <li>
                    <a href="<?= route("notes.show") ?>?id=<?= $note["id"] ?>" class="text-blue-500 hover:underline">
                        <p class="ellipsis"><?= sanitize($note["body"]) ?></p>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

    </div>
</main>

<?php view("partials.footer"); ?>
