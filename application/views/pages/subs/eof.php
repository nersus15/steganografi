<div id="halaman-eof" style="display: none" class="halaman">
    <div style="justify-content: space-between;" class="row">
        <?php include_view('component/elements/button-custom-back', ['class' => 'main-menu', 'id' => 'ada']) ?>
        <h1>End Of File</h1>
    </div>
    <div class="separator mb-5"></div>

    <div class="col-12">
        <div class="alert alert-info" role="alert">
            <p class="text-white">Tombol download untuk vide hasil penyisipan akan muncul setelah proses penyisipan selesai</p>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <form id="form-eof" enctype="multipart/form-data" method="POST" action="<?= base_url('ws/embed') ?>">
                    <div class="form-group">
                        <label>File</label>
                        <input type="file" name="file" id="key" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Video</label>
                        <input type="file" name="video" id="key" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-primary mb-0">Sisipkan</button>
                    <a style="display: none;" download id="download-video-eof" class="btn btn-outline-info ml-3 mb-0">Download</a>
                </form>

            </div>
        </div>
    </div>

</div>