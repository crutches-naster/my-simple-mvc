<?php view('partials/header'); ?>
    <div class="d-flex align-items-center py-4 bg-body-tertiary">
        <main class="form-signin w-100 mt-5 m-auto">
            <form method="post" action="<?= url('folders/store') ?>">
                <h1 class="h3 mb-3 fw-normal">New Folder</h1>

                <div class="form-floating">
                    <input type="text" class="form-control" id="floatingInput" name="title"
                           placeholder="Some note title..">
                    <label for="floatingInput">Title</label>
                </div>

                <button class="btn btn-primary w-100 py-2" type="submit">Create</button>
            </form>
        </main>
    </div>
<?php
view('partials/footer');
