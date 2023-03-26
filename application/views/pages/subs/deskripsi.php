<div id="halaman-deskripsi" style="display: none" class="halaman">
    <div style="justify-content: space-between;" class="row">
        <?php include_view('component/elements/button-custom-back', ['class' => 'main-menu', 'id' => 'ada']) ?>
        <h1>Deskripsi RSA</h1>
    </div>
    <div class="separator mb-5"></div>

     <div class="col-sm-12">
        <div class="alert alert-info" role="alert">
            <p class="text-white">Setelah melakukan deskripsi, file hasil deskripsi akan otomatis didownload</p>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <form id="form-deskripsi" enctype="multipart/form-data" method="POST" action="<?= base_url('ws/deskripsi') ?>">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Private Key</label>
                        <textarea required name="private_key" id="private-key" cols="30" rows="5" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <label>File Hasil Enkripsi</label>
                        <input required type="file" placeholder="File yang akan di deskripsi" name="file" id="file" class="form-control">
                    </div>
                    

                    <button type="submit" class="btn btn-primary mb-0">Deskripsi</button>
                    <a style="display: none;" download id="download-video-deskripsi" class="btn btn-outline-info ml-3 mb-0">Download</a>
                </form>

            </div>
        </div>
    </div>
</div>