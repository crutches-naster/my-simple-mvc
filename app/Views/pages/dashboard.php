<?php view('partials/header'); ?>
    <div class="page-content container note-has-grid">
        <?php if (\App\Helpers\Session::check()): ?>
        Logged in
        <?php else: ?>
        Logged out
        <?php endif; ?>
    </div>
<?php view('partials/footer');
