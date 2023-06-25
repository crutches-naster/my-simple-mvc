<?php
$content = htmlspecialchars_decode($note->content);
$dots = strlen($content) > 10 ? '...' : '';
?>
<div class="col-md-4 single-note-item all-category note-important" style="">
    <div class="card card-body">
        <span class="side-stick"></span>
        <h5 class="note-title text-truncate w-75 mb-0" data-noteheading="Go for lunch">
            <?= $note->pinned ? '<i class="fa fa-thumb-tack" aria-hidden="true"></i>&nbsp;' : '' ?>
            <?= $note->shared ? '<i class="fa fa-users" aria-hidden="true"></i>&nbsp;' : '' ?>
            <?= $note->completed ? '<i class="fa fa-check-square" aria-hidden="true"></i>&nbsp;' : '' ?>
            <?= $note->title ?>
        </h5>
        <small class="note-date font-8 text-muted">
            <?= $note->author ? "Author: {$note->author}" : '' ?>
        </small>
        <p class="note-date font-12 text-muted"><?= $note->created_at ?></p>
        <div class="note-content">
            <p class="note-inner-content text-muted"
               data-notecontent="Blandit tempus porttitor aasfs. Integer posuere erat a ante venenatis."
            >
                <?= substr($content, 0, 120) . $dots ?>
            </p>
        </div>
        <div class="d-flex align-items-center justify-content-end">
            <a class="btn btn-outline-primary"
               href="<?= url('notes/' . $note->id) ?>"
               style="margin-right: 1rem"><i class="fa fa-eye" aria-hidden="true"></i></a>

            <?php if ($note->author_id === \App\Helpers\Session::id()): ?>
            <form action="<?= url('notes/' . $note->id . '/destroy') ?>" method="post">
                <button type="submit"
                        class="btn btn-outline-danger"
                ><i class="fa fa-trash remove-note"></i></button>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>
