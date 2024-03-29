<?php view("partials.head", ["heading" => $heading]); ?>
<?php view("partials.nav"); ?>
<?php view("partials.banner", ["heading" => $heading]); ?>

<main>
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <p class="mb-6">
            <a href="<?= route("home") ?>" class="text-blue-500 underline"><?= __("Go Back ...") ?></a>
        </p>

        <form action="<?= route("signup") ?>" method="POST">

            <?= csrfInput() ?>

            <div class="shadow sm:overflow-hidden sm:rounded-md">
                <div class="space-y-6 bg-white px-4 py-5 sm:p-6">

                <h1 class="mb-8 text-3xl text-center"><?= __("Sign up") ?></h1>
                    <input
                        required
                        type="text"
                        class="block border border-grey-light w-full p-3 rounded mb-4"
                        name="name"
                        value="<?= old("name", "") ?>"
                        placeholder="<?= __("Full Name") ?>" />

                    <?php if (hasErrors("name")): ?>
                        <?php view("partials.errors", ["formErrors" => errors("name")]); ?>
                    <?php endif; ?>

                    <input 
                        required
                        type="text"
                        class="block border border-grey-light w-full p-3 rounded mb-4"
                        name="email"
                        value="<?= old("email", "") ?>"
                        placeholder="<?= __("Email") ?>" />

                    <?php if (hasErrors("email")): ?>
                        <?php view("partials.errors", ["formErrors" => errors("email")]); ?>
                    <?php endif; ?>

                    <input 
                        required
                        type="password"
                        class="block border border-grey-light w-full p-3 rounded mb-4"
                        name="password"
                        placeholder="<?= __("Password") ?>" />

                    <?php if (hasErrors("password")): ?>
                        <?php view("partials.errors", ["formErrors" => errors("password")]); ?>
                    <?php endif; ?>

                    <input 
                        required
                        type="password"
                        class="block border border-grey-light w-full p-3 rounded mb-4"
                        name="password_confirmation"
                        placeholder="<?= __("Confirm Password") ?>" />

                    <button
                        type="submit"
                        class="w-full text-center py-3 rounded bg-violet-700 text-white hover:bg-violet-900 focus:outline-none my-1"
                    ><?= __("Submit") ?></button>

                </div>
            </div>
        </form>
    </div>
</main>

<?php view("partials.footer"); ?>