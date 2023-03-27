<div id="halaman-ekstraksi-eof" style="display: none" class="halaman">
    <div style="justify-content: space-between;" class="row">
        <?php include_view('component/elements/button-custom-back', ['class' => 'main-menu', 'id' => 'ada']) ?>
        <h1>Ekstraksi EOF</h1>
    </div>
    <div class="separator mb-5"></div>

    <div class="col-12">
        <div class="alert alert-info" role="alert">
            <p class="text-white">Setelah melakukan ekstraksi, file hasil ekstraksi akan otomatis didownload</p>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <form id="form-ekstraksi-eof" enctype="multipart/form-data" method="POST" action="<?= base_url('ws/ekstraksi') ?>">
                    <div class="form-group">
                        <label>Video (<small>yang sudah disisipkan file</small>)</label>
                        <input type="file" name="video" id="key" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-primary mb-0">Ekstraksi</button>
                    <a style="display: none;" download id="download-file-ekstraksi" class="btn btn-outline-info ml-3 mb-0">Download</a>
                </form>

            </div>
        </div>
    </div>
</div>
