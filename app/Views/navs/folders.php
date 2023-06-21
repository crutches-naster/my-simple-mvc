<ul class="nav nav-pills p-3 bg-white mb-3 rounded-pill align-items-center">
    <?php foreach ($folders as $folder): ?>
        <li class="nav-item">
            <a href="<?= url("folders/{$folder->id}") ?>"
               class="nav-link rounded-pill note-link d-flex align-items-center px-2 px-md-3 mr-0 mr-md-2 <?= $activeFolder && $activeFolder === $folder->id ? 'active' : '' ?>"
               id="all-category">
                <i class="icon-layers mr-1"></i><span class="d-none d-md-block"><?= $folder->title ?></span>
            </a>
        </li>
    <?php endforeach; ?>
    <li class="nav-item d-flex flex-row">
        <a href="<?= url('folders/create') ?>"
           class="nav-link rounded-pill note-link d-flex align-items-center px-2 px-md-3 mr-0 mr-md-2"
        ><i class="fa fa-plus-circle" aria-hidden="true"></i></a>

        <?php if( !\App\Models\Folder::isGeneral($activeFolder) ): ?>
            <a href="<?= url("folders/{$activeFolder}/edit") ?>"
               class="nav-link rounded-pill note-link d-flex align-items-center px-2 px-md-3 mr-0 mr-md-2"
               style="color: #4f4f1c;"
            ><i class="fa fa-pencil" aria-hidden="true"></i></a>
            <form action="<?= url("folders/{$activeFolder}/destroy") ?>" method="POST">
                <button type="submit"
                        class="nav-link rounded-pill note-link d-flex align-items-center px-2 px-md-3 mr-0 mr-md-2"
                        style="color: #e22424;"
                >
                    <i class="fa fa-trash" aria-hidden="true"></i>
                </button>
            </form>
        <?php endif; ?>
    </li>
</ul>
<ul class="nav align-items-center d-flex w-100 justify-content-end mb-3">
    <li class="nav-item d-flex flex-row">
        <a href="<?= url('notes/create') ?>"
           class="nav-link rounded-pill note-link d-flex align-items-center px-2 px-md-3 mr-0 mr-md-2"
        ><i class="fa fa-plus-circle" aria-hidden="true"></i> &nbsp; Create note</a>
    </li>
</ul>
