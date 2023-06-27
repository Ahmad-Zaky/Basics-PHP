<?php view("partials.head", ["heading" => $heading]); ?>
<?php view("partials.nav", ["module" => $module]); ?>
<?php view("partials.banner", ["heading" => $heading]); ?>

<main>
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <p class="mb-6">
            <a href="<?= route("notes.index") ?>" class="text-blue-500 underline"><?= __("Go Back ...") ?></a>
        </p>

        <?php if ($note->completed): ?>
            <div>
                <div class="flex items-center mb-4">
                    <label for="completed" class="text-sm font-medium text-gray-900 dark:text-gray-300"><?= __("Completed At") ?>:&nbsp;</label>
                    <small><?= $note->completed_at ?></small>
                </div>        
            </div>
        <?php endif; ?>

        <p><?= sanitize($note->body) ?></p>

        <div class="mt-6 flex">
            <div class="mr-2">                
                <form action="<?= route("notes.destroy", ["id" => $note->id]) ?>" method="POST">

                    <?= csrfInput() ?>

                    <input type="hidden" name="_method" value="DELETE">
    
                    <button type="submit" href="#" class="bg-transparent hover:bg-red-500 text-red-700 font-semibold hover:text-white py-2 px-4 border border-red-500 hover:border-transparent rounded">
                        <?= __("Delete") ?>
                    </button>
                </form>
            </div>

            <div class="ml-2">
                <a
                    type="submit"
                    href="<?= route("notes.edit", ["id" => $note->id]) ?>"
                    class="bg-transparent hover:bg-green-500 text-green-700 font-semibold hover:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded"
                >
                    <?= __("Edit") ?>
                </a>
            </div>
        </div>
    </div>
</main>

<?php view("partials.footer"); ?>
