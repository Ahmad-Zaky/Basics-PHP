<?php view("partials.head"); ?>
<?php view("partials.nav"); ?>

<main>
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold"><?= $message ?: __('Sorry, You are not authorized.') ?></h1>
        <p class="mt-4">
            <a href="/" class="text-blue underline"><?= __("Go Back Home") ?></a>
        </p>
    </div>
</main>

<?php view("partials.footer"); ?>
