<?php
$menu = [
    'generate-key', 'enkripsi', 'deskripsi', 'eof', 'ekstraksi-eof', 'rsa-eof'
];
?>

<style>
    .halaman {
        margin-bottom: 25px;
    }

    .border {
        border: whitesmoke 1px solid;
        border-radius: 15px;
    }

    .row {
        row-gap: 15px;
    }

    i {
        font-size: 45px;
    }

    .circle {
        width: 100px;
        height: 100px;
        background: white;
        -moz-border-radius: 50px;
        -webkit-border-radius: 50px;
        border-radius: 50px;
        display: flex;
        align-items: center;
        justify-content: space-around;
        margin: auto;
    }

    p.title {
        color: white;
        text-align: center;

    }

    .card-body {
        display: flex;
        flex-direction: column;
        justify-content: space-around;
    }

    .btn-custom {
        border-radius: 10px !important;
    }

    button.custom-button-back {
        display: flex;
        height: 3em;
        width: 100px;
        align-items: center;
        justify-content: center;
        background-color: #eeeeee4b;
        border-radius: 3px;
        letter-spacing: 1px;
        transition: all 0.2s linear;
        cursor: pointer;
        border: none;
        background: #fff;
    }

    button.custom-button-back>svg {
        margin-right: 5px;
        margin-left: 5px;
        font-size: 20px;
        transition: all 0.4s ease-in;
    }

    button.custom-button-back:hover>svg {
        font-size: 1.2em;
        transform: translateX(-5px);
    }

    button.custom-button-back:hover {
        box-shadow: 9px 9px 33px #d1d1d1, -9px -9px 33px #ffffff;
        transform: translateY(-2px);
    }
</style>

<div class="halaman" id="halaman-main-menu">
    <div style="justify-content: space-between;" class="row">
        <div class="col">
            <p class="text-white">Current Page On Reload</p>
            <?php include_view('component/elements/button-custom-switch', ['class' => '', 'id' => 'cpr']) ?>
        </div>
        <h1>Main Menu</h1>
    </div>
    <div class="separator mb-5"></div>

    <div class="row">
        <div class="col-sm-6 col-md-4">
            <div class="card border">
                <div class="card-body">
                    <div class="circle">
                        <i class="text-primary iconsmind-Data-Key"></i>
                    </div>

                    <p class="title mt-3">Generate Key</p>
                    <button id="generate-key" class="btn btn-custom btn-outline-white"><?= is_login() ? 'Selengkapnya' : 'Harus Login' ?></button>
                </div>
                <?php if(!is_login()): ?>
                <div class="card-footer">
                    <p><a class="text-primary" href="<?= base_url('login') ?>">Klik disini</a> untuk login</p>
                </div>
                <?php endif ?>
            </div>
        </div>
        <div class="col-sm-6 col-md-4">
            <div class="card border">
                <div class="card-body">
                    <div class="circle">
                        <i class="text-primary iconsmind-Key-Lock"></i>
                    </div>

                    <p class="title mt-3">Enkripsi RSA</p>
                    <button id="enkripsi" class="btn btn-custom btn-outline-white">Selengkapnya</button>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-4">
            <div class="card border">
                <div class="card-body">
                    <div class="circle">
                        <i class="text-primary iconsmind-Unlock"></i>
                    </div>

                    <p class="title mt-3">Deskripsi RSA</p>
                    <button id="deskripsi" class="btn btn-custom btn-outline-white">Selengkapnya</button>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-4">
            <div class="card border">
                <div class="card-body">
                    <div class="circle">
                        <i class="text-primary iconsmind-Cloud-Lock"></i>
                    </div>

                    <p class="title mt-3">End Of File</p>
                    <button id="eof" class="btn btn-custom btn-outline-white">Selengkapnya</button>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-4">
            <div class="card border">
                <div class="card-body">
                    <div class="circle">
                        <i class="text-primary iconsmind-Cloud-Video"></i>
                    </div>

                    <p class="title mt-3">Ekstraksi End Of File</p>
                    <button id="ekstraksi-eof" class="btn btn-custom btn-outline-white">Selengkapnya</button>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-4">
            <div class="card border">
                <div class="card-body">
                    <div class="circle">
                        <i class="text-primary iconsmind-Server"></i>
                    </div>

                    <p class="title mt-3">Hybrid RSA dan EOF</p>
                    <button id="rsa-eof" class="btn btn-custom btn-outline-white">Selengkapnya</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
foreach ($menu as $m) {
    include_view('pages/subs/' . $m);
}
?>