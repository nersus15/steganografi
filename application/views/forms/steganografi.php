<div class="card mb-4">
    <div class="card-body">
        <h5 class="mb-4">Basic</h5>
        <form action="<?= base_url('ws/enkripsi') ?>" enctype="multipart/form-data" method="post">
        <div class="form-group">
            <label for="">Pilih Video</label>
            <input type="file" name="video" id="video" class="form-control">
        </div>

        <div class="form-group">
            <button type="submi" class="btn btn-outline-primary btn-sm">enkripsi</button>
        </div>
    </form>
    </div>
</div>