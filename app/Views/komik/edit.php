<?= $this->extend('layouts/template'); ?>


<?= $this->section('content'); ?>

<div class="container">
    <div class="row">
        <div class="col-8">
            <h2 class="my-3">Form Ubah Data Komik</h2>

            <form action="/komik/update/<?= $komik['id'] ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field(); ?>
                <input type="hidden" name="slug" value="<?= $komik['slug'] ?>">
                <input type="hidden" name="coverLama" value="<?= $komik['cover']; ?>">
                <div class="mb-3">
                    <label for="judul" class="form-label">Judul</label>
                    <input type="text" name="judul" class="form-control <?= ($validation->hasError('judul')) ? 'is-invalid' : ''; ?>" id="judul" value="<?= (old('judul')) ? old('judul') : $komik['judul']; ?>" autofocus>
                    <div class="invalid-feedback">
                        <?= $validation->getError('judul'); ?>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="penulis" class="form-label">Penulis</label>
                    <input type="text" name="penulis" class="form-control" id="penulis" value="<?= (old('penulis')) ? old('penulis') : $komik['penulis']; ?>">
                </div>
                <div class="mb-3">
                    <label for="penerbit" class="form-label">Penerbit</label>
                    <input type="text" name="penerbit" class="form-control" id="penerbit" value="<?= (old('penerbit')) ? old('penerbit') : $komik['penerbit']; ?>">
                </div>
                <div class="mb-3">
                    <label for="cover" class="form-label">Cover</label>
                    <div class="col-sm-2">
                        <img src="/img/<?= $komik['cover'] ?>" class="img-thumbnail img-preview">
                    </div>
                    <input class="form-control <?= ($validation->hasError('cover')) ? 'is-invalid' : ''; ?>" name="cover" type="file" id="cover" onchange="previewImg()">
                    <div class="invalid-feedback">
                        <?= $validation->getError('cover'); ?>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Ubah Data</button>
            </form>


        </div>
    </div>
</div>

<?= $this->endSection(); ?>