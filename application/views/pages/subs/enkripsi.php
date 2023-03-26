<div id="halaman-enkripsi" style="display: none" class="halaman">
    <div style="justify-content: space-between;" class="row">
        <?php include_view('component/elements/button-custom-back', ['class' => 'main-menu', 'id' => 'ada']) ?>
        <h1>Enkripsi RSA</h1>
    </div>
    <div class="separator mb-5"></div>

    <div class="col-sm-12">
        <div class="alert alert-info" role="alert">
            <p class="text-white">Setelah melakukan enkripsi, file hasil enkripsi akan otomatis didownload</p>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <form id="form-enkripsi" enctype="multipart/form-data" method="POST" action="<?= base_url('ws/enkripsi') ?>">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Public Key</label>
                        <textarea required name="public_key" id="key" cols="30" rows="5" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">File</label>
                        <input required type="file" placeholder="File yang akan di enkripsi" name="file" id="file" class="form-control">
                    </div>
                    

                    <button type="submit" class="btn btn-primary mb-0">Enkripsi</button>
                    <a style="display: none;" download id="download-video-enkripsi" class="btn btn-outline-info ml-3 mb-0">Download</a>
                </form>

            </div>
        </div>
    </div>
</div>
</div>