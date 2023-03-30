<?php view("partials.head", ["heading" => $heading]); ?>
<?php view("partials.nav", ["module" => $module]); ?>
<?php view("partials.banner", ["heading" => $heading]); ?>

<main>
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <p class="mb-6">
            <a href="<?= route("notes.index") ?>" class="text-blue-500 underline">Go Back ...</a>
        </p>

        <form action="<?= route("notes.update", ["id" => $note->id]) ?>" method="POST">

            <?= csrfInput() ?>

            <input type="hidden" name="_method" value="PUT">

            <div class="shadow sm:overflow-hidden sm:rounded-md">
                <div class="space-y-6 bg-white px-4 py-5 sm:p-6">

                    <div>
                        <label for="body" class="block text-sm font-medium leading-6 text-gray-900">Body</label>
                        <div class="mt-2">
                            <textarea
                                id="body"
                                name="body"
                                maxlength="500"
                                minlength="50"
                                rows="3"
                                class="mt-1 block w-full rounded-md border-0 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:py-1.5 sm:text-sm sm:leading-6"
                                placeholder="Here's an idea for a note ..."
                            ><?= old("body", $note->body) ?></textarea>

                            <?php if (hasErrors("body")): ?>
                                <ul>
                                    <?php foreach (errors("body") as $error): ?>
                                        <li class="text-red-500 text-xs mt-2"><?= $error ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
                    <a href="<?= route("notes.index") ?>" class="inline-flex justify-center rounded-md bg-gray-600 py-2 px-3 text-sm font-semibold text-white shadow-sm hover:bg-gray-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-500">Cancel</a>
                    <button type="submit" class="inline-flex justify-center rounded-md bg-indigo-600 py-2 px-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">Update</button>
                </div>
            </div>
        </form>
</main>

<?php view("partials.footer"); ?>