<?php view('partials/header'); ?>
    <div class="container">
        <div class="row">
            <form action="<?= url('notes/store') ?>" method="post">
                <div class="col-12">
                    <ul class="nav nav-pills p-3 bg-white mb-3 rounded-pill align-items-center justify-content-between">
                        <li class="nav-item d-flex flex-row">
                            <a href="<?= urlBack() ?>"
                               class="nav-link rounded-pill note-link d-flex align-items-center px-2 px-md-3 mr-0 mr-md-2"
                            ><i class="fa fa-arrow-left" aria-hidden="true"></i> &nbsp; Back</a>
                        </li>
                    </ul>
                </div>
                <div class="col-12 d-flex flex-column" style="padding: 0 1rem">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" id="title" placeholder="Put some title">
                    </div>
                    <div class="mb-3">
                        <label for="folders" class="form-label">Folder</label>
                        <select name="folder_id" class="form-control" id="folders">
                            <?php foreach($folders as $folder): ?>
                                <option value="<?= $folder->id ?>"><?= $folder->title ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea class="form-control" name="content" id="content" rows="5" placeholder="Note content.."></textarea>
                    </div>
                    <div class="mb-3 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php view('partials/footer'); ?>
