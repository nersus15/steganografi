<div id="halaman-generate-key" style="display: none" class="halaman">
    <div style="justify-content: space-between;" class="row">
        <?php include_view('component/elements/button-custom-back', ['class' => 'main-menu', 'id' => 'ada']) ?>
        <h1>Generate Key</h1>
    </div>

    <div class="separator mb-5"></div>


    <div class="col-sm-12">
        <div class="alert alert-info" role="alert">
            <p class="text-white">Setelah generate key, disarankan untuk menyimpan key kedalam file.</p>
        </div>
        <div style="justify-content: space-around;" class="row mb-4">
            <button id="proses-generate-key" class="btn btn-custom btn-outline-white">Generate key</button>
        </div>

        <div style="display: none;" class="border p-4" id="public-key">
            <div style="cursor: pointer;justify-content: flex-end;" id="copy-public-key"  class="row copy-clipboard">
                <span><i style="font-size: 15px;" class="iconsmind-File-Copy2"></i> Copy</span>
            </div>
            <p></p>
        </div>

        <div style="display: none;" class="border p-4 mt-4" id="private-key">
            <div style="cursor: pointer;justify-content: flex-end;" id="copy-private-key"  class="row copy-clipboard">
                <span><i style="font-size: 15px;" class="iconsmind-File-Copy2"></i> Copy</span>
            </div>
            <p></p>
        </div>
    </div>
</div>