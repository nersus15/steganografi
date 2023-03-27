<div id="halaman-rsa-eof" style="display: none" class="halaman">
    <div style="justify-content: space-between;" class="row">
        <?php include_view('component/elements/button-custom-back', ['class' => 'main-menu', 'id' => 'ada']) ?>
        <h1>Hybrid RSA dan EOF</h1>
    </div>
    <div class="separator mb-5"></div>

    <div class="row">
        <div class="col-sm-12 col-md-6">
            <h2>Enkripsi</h2>
            <div class="alert alert-info" role="alert">
                <p class="text-white">Tombol download akan muncul setelah proses selesai</p>
            </div>
            <div class="card mb-4">
                <div class="card-body">
                    <form id="form-rsa-eof-enkripsi" enctype="multipart/form-data" method="POST" action="<?= base_url('ws/rsaeof/enkrip') ?>">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Public Key</label>
                            <textarea required name="public_key" cols="30" rows="5" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label>File</label>
                            <input type="file" name="file" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Video</label>
                            <input type="file" name="video" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-primary mb-0">Enkripsi</button>
                        <a style="display: none;" download id="download-video-rsa-eof" class="btn btn-outline-info ml-3 mb-0">Download</a>
                    </form>

                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-6">
            <h2>Deskripsi</h2>
            <div class="alert alert-info" role="alert">
                <p class="text-white">Tombol download akan muncul setelah proses selesai</p>
            </div>
            <div class="card mb-4">
                <div class="card-body">
                    <form id="form-rsa-eof-deskripsi" enctype="multipart/form-data" method="POST" action="<?= base_url('ws/rsaeof/deskrip') ?>">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Private Key</label>
                            <textarea required name="private_key" id="private-key" cols="30" rows="5" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Video</label>
                            <input type="file" name="video_enkripted" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-primary mb-0">Deskripsi</button>
                        <a style="display: none;" download id="download-file-deskrip-eof" class="btn btn-outline-info ml-3 mb-0">Download</a>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>