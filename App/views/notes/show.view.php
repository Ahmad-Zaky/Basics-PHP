<?php view("partials.head", ["heading" => $heading]); ?>
<?php view("partials.nav"); ?>
<?php view("partials.banner", ["heading" => $heading]); ?>

<main>
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <p class="mb-6">
            <a href="/notes" class="text-blue-500 underline">Go Back ...</a>
        </p>
        <p><?= sanitize($note["body"]) ?></p>

        <div class="mt-6">
            <form action="/notes?id=<?= $note["id"] ?>" method="POST">
                
                <input type="hidden" name="_method" value="DELETE">

                <button type="submit" href="/notes/create" class="bg-transparent hover:bg-red-500 text-red-700 font-semibold hover:text-white py-2 px-4 border border-red-500 hover:border-transparent rounded">
                    Delete
                </button>
            </form>
        </div>
    </div>
</main>

<?php view("partials.footer"); ?>
