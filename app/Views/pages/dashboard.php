<?php view('partials/header'); ?>

    <div class="page-content container note-has-grid">
        <?= view('navs/folders', compact('folders', 'activeFolder')) ?>
        <?= view('partials/notes/list', compact('notes')) ?>

    </div>
<?php view('partials/footer');
