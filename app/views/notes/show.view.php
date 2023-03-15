
<?php view("partials.head"); ?>
<?php view("partials.nav"); ?>
<?php view("partials.banner", ["heading" => $heading]); ?>

<main>
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <p class="mb-6">
            <a href="/notes" class="text-blue-500 underline">Go Back ...</a>
        </p>
        <p><?= sanitize($note["body"]) ?></p>
    </div>
</main>

<?php view("partials.footer"); ?>
