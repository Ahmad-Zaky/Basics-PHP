<?php view("partials.head", ["heading" => $heading]); ?>
<?php view("partials.nav", ["module" => $module]); ?>
<?php view("partials.banner", ["heading" => $heading]); ?>

<main>
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <p class="mb-6">
            <a href="<?= route("notes.index") ?>" class="text-blue-500 underline">Go Back ...</a>
        </p>

        <form action="<?= route("notes.store") ?>" method="POST">

            <?= csrfInput() ?>

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
                            ><?= old("body", "") ?></textarea>
                           
                            <?php if (hasErrors("body")): ?>
                                <?php view("partials.errors", ["formErrors" => errors("body")]); ?>
                            <?php endif; ?>

                        </div>
                    </div>

                    <div>
                        <div class="flex items-center mb-4">

                            <input
                                type="hidden"
                                id="completed-hidden"
                                name="completed"
                                value="<?= old("completed", false) ?>"
                            />

                            <input
                                <?php if(old("completed", false)): ?>
                                    checked
                                <?php endif; ?>
                                id="completed"
                                type="checkbox"
                                value="<?= old("completed", false) ?>"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                            >
                            <label for="completed" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Completed</label>
                        </div>

                        <?php if (hasErrors("completed")): ?>
                            <?php view("partials.errors", ["formErrors" => errors("completed")]); ?>
                        <?php endif; ?>
                            
                    </div>

                </div>
                <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
                    <button type="submit" class="inline-flex justify-center rounded-md bg-indigo-600 py-2 px-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">Save</button>
                </div>
            </div>
        </form>
    </div>
</main>

<?php view("partials.footer"); ?>