
<?php require partialsPath() . "head.view.php"; ?>
<?php require partialsPath() . "nav.view.php"; ?>
<?php require partialsPath() . "banner.view.php"; ?>

<main>
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <p class="mb-6">
            <a href="/notes" class="text-blue-500 underline">Go Back ...</a>
        </p>
        <p><?= sanitize($note["body"]) ?></p>
    </div>
</main>

<?php require partialsPath() . "footer.view.php"; ?>
