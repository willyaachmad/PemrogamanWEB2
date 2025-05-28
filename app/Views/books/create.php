<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container">
    <div class="row">
        <div class="col-8">
            <h2 class="my-3">Tambah Data Buku</h2>

            <form action="/books/save" method="post">
                <?= csrf_field(); ?>

                <div class="mb-3">
                    <label for="judul" class="form-label">Judul</label>
                    <input type="text" name="judul" value="<?= old('judul'); ?>"
                        class="form-control <?= ($validation->hasError('judul')) ? 'is-invalid' : ''; ?>">
                    <div class="invalid-feedback"><?= $validation->getError('judul'); ?></div>
                </div>

                <div class="mb-3">
                    <label for="penulis" class="form-label">Penulis</label>
                    <input type="text" name="penulis" value="<?= old('penulis'); ?>"
                        class="form-control <?= ($validation->hasError('penulis')) ? 'is-invalid' : ''; ?>">
                    <div class="invalid-feedback"><?= $validation->getError('penulis'); ?></div>
                </div>

                <div class="mb-3">
                    <label for="penerbit" class="form-label">Penerbit</label>
                    <input type="text" name="penerbit" value="<?= old('penerbit'); ?>"
                        class="form-control <?= ($validation->hasError('penerbit')) ? 'is-invalid' : ''; ?>">
                    <div class="invalid-feedback"><?= $validation->getError('penerbit'); ?></div>
                </div>

                <div class="mb-3">
                    <label for="sampul" class="form-label">Sampul</label>
                    <input type="text" name="sampul" value="<?= old('sampul'); ?>" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">Tambah Buku</button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>