<?php view("partials.head", ["heading" => $heading]); ?>
<?php view("partials.nav"); ?>
<?php view("partials.banner", ["heading" => $heading]); ?>

<main>
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <p>Hello, <strong><?= auth("name") ?? 'Guest' ?></strong>. Welcome to the home page.
    </div>
</main>

<?php view("partials.footer"); ?>
