$(document).ready(function(){
    var isLogin = "<?= $isLogin ?>";

    // Element Register
    var elements = {
        btnGenerateKey: $('#generate-key'),
        btnEnkripsi: $('#enkripsi'),
        btnDeskripsi: $('#deskripsi'),
        btnEof: $('#eof'),
        btnEkstraksiEof: $('#ekstraksi-eof'),
        btnRsaEof: $('#rsa-eof'),
        btnBack: $('.main-menu'),
        keys: $('#table-rsa tbody'),

        btnPosesGenerateKey: $('#proses-generate-key'),
    }
    var cpr = localStorage.getItem('iscpr');

    // Cek CPR
    if(cpr == 1){
        $("#cpr").prop('checked', true).trigger('change');
    }else if(cpr == 0){
        $("#cpr").prop('checked', false).trigger('change');
    }

    // Disable tombol selengkapnya untuk generate-key jika belum login
    if(isLogin == 0)
        elements.btnGenerateKey.prop('disabled', true);
    else
        elements.btnGenerateKey.prop('disabled', false);

    // Event Listener
    elements.btnGenerateKey.click({halaman: 'generate-key'}, function(e){
        e.preventDefault();
        if(isLogin == 0){
            alert("Anda harus login terlebih dahulu");
            return;
        }
        renderKeys();
        switchHalaman({data: {halaman: 'generate-key'}})
    });
    elements.btnEnkripsi.click({halaman: 'enkripsi'}, switchHalaman);
    elements.btnDeskripsi.click({halaman: 'deskripsi'}, switchHalaman);
    elements.btnEof.click({halaman: 'eof'}, switchHalaman);
    elements.btnEkstraksiEof.click({halaman: 'ekstraksi-eof'}, switchHalaman);
    elements.btnRsaEof.click({halaman: 'rsa-eof'}, switchHalaman);
    elements.btnBack.click({halaman: 'main-menu'}, switchHalaman);

    elements.btnPosesGenerateKey.click(function(){
        showLoading();
        sendRequest(path + 'ws/rsakey', {}, function(result){
            endLoading();
            $('#private-key p').text(result.private_key).parent().show();
            $('#public-key p').text(result.public_key).parent().show();
            // window.open(path + 'ws/key/' + result.name, '_blank');

            renderKeys();
        });
    });

    $("#cpr").change(function(){
        if($(this).is(':checked')){
            localStorage.setItem('iscpr', '1');
        }else{
            localStorage.setItem('iscpr', '0');
        }
    })

    $(".copy-clipboard").click(function(){
        var pesan = "Public key berhasil di copy";
        if($(this).attr('id') == 'copy-private-key')
            pesan = 'Private key berhasil di copy';
        copyToClipboard($(this).next('p').text(), pesan);
    });
    $("#form-enkripsi").initFormAjax({
        sebelumSubmit: function(){
            showLoading();
            $("#download-video-enkripsi").attr('href', '').hide().prop('disabled', true);
        },
        submitError: function(res){
            endLoading();
            var error = res.responseJSON;
            makeToast({
                title: 'Terjadi kesalahan',
                message: error.err || error.message,
                id: 'defaut-config',
                cara_tempel: 'after',
                autohide: true,
                show: true,
                time: moment().format('H:m:s'),
                hancurkan: true,
                wrapper: 'body',
                delay: 5000,
                bg: 'bg-danger'
            });
        },
        submitSuccess: function(res){
            endLoading();
            makeToast({
                title: 'Berhasil melakukan enkripsi',
                message: 'Waktu proses: ' + res.eta + ' detik',
                id: 'defaut-config',
                cara_tempel: 'after',
                autohide: true,
                show: true,
                time: moment().format('H:m:s'),
                hancurkan: true,
                wrapper: 'body',
                delay: 5000,
                bg: 'bg-success'
            });
            $("#download-video-enkripsi").attr('href', res.url).show().prop('disabled', false);

        }
    });

    $("#form-deskripsi").initFormAjax({
        sebelumSubmit: function(){
            showLoading();
            $("#download-video-deskripsi").attr('href', '').hide().prop('disabled', true);
        },
        submitError: function(res){
            endLoading();
            var error = res.responseJSON;
            makeToast({
                title: 'Terjadi kesalahan',
                message: error.err || error.message,
                id: 'defaut-config',
                cara_tempel: 'after',
                autohide: true,
                show: true,
                time: moment().format('H:m:s'),
                hancurkan: true,
                wrapper: 'body',
                delay: 5000,
                bg: 'bg-danger'
            });
        },
        submitSuccess: function(res){
            endLoading();
            $("#download-video-deskripsi").attr('href', res.url).show().prop('disabled', false);
            makeToast({
                title: 'Berhasil melakukan deskripsi',
                message: 'Waktu proses: ' + res.eta + ' detik',
                id: 'defaut-config',
                cara_tempel: 'after',
                autohide: true,
                show: true,
                time: moment().format('H:m:s'),
                hancurkan: true,
                wrapper: 'body',
                delay: 5000,
                bg: 'bg-success'
            });
        }
    });

    $("#form-eof").initFormAjax({
        sebelumSubmit: function(){
            $("#download-video-eof").attr('href', '').hide().prop('disabled', true);
            showLoading();
        },
        submitError: function(res){
            endLoading();
            var error = res.responseJSON;
            makeToast({
                title: 'Terjadi kesalahan',
                message: error.err || error.message,
                id: 'defaut-config',
                cara_tempel: 'after',
                autohide: true,
                show: true,
                time: moment().format('H:m:s'),
                hancurkan: true,
                wrapper: 'body',
                delay: 5000,
                bg: 'bg-danger'
            });
        },
        submitSuccess: function(res){
            endLoading();
            makeToast({
                title: 'Berhasil melakukan penyisipan file',
                message: 'Waktu proses: ' + res.eta + ' detik',
                id: 'defaut-config',
                cara_tempel: 'after',
                autohide: true,
                show: true,
                time: moment().format('H:m:s'),
                hancurkan: true,
                wrapper: 'body',
                delay: 5000,
                bg: 'bg-success'
            });
            $("#download-video-eof").attr('href', res.url).show().prop('disabled', false);
        }
    });

    $("#form-ekstraksi-eof").initFormAjax({
        sebelumSubmit: function(){
            $("#download-file-ekstraksi").attr('href', '').hide().prop('disabled', true);
            showLoading();
        },
        submitError: function(res){
            endLoading();
            var error = res.responseJSON;
            makeToast({
                title: 'Terjadi kesalahan',
                message: error.err || error.message,
                id: 'defaut-config',
                cara_tempel: 'after',
                autohide: true,
                show: true,
                time: moment().format('H:m:s'),
                hancurkan: true,
                wrapper: 'body',
                delay: 5000,
                bg: 'bg-danger'
            });
        },
        submitSuccess: function(res){
            endLoading();
            makeToast({
                title: 'Berhasil melakukan ekstraksi file',
                message: 'Waktu proses: ' + res.eta + ' detik',
                id: 'defaut-config',
                cara_tempel: 'after',
                autohide: true,
                show: true,
                time: moment().format('H:m:s'),
                hancurkan: true,
                wrapper: 'body',
                delay: 5000,
                bg: 'bg-success'
            });
            $("#download-file-ekstraksi").attr('href', res.url).show().prop('disabled', false);
        }
    });

    $("#form-rsa-eof-enkripsi").initFormAjax({
        sebelumSubmit: function(){
            $("#download-video-rsa-eof").attr('href', '').hide().prop('disabled', true);
            showLoading();
        },
        submitError: function(res){
            endLoading();
            var error = res.responseJSON;
            makeToast({
                title: 'Terjadi kesalahan',
                message: error.err || error.message,
                id: 'defaut-config',
                cara_tempel: 'after',
                autohide: true,
                show: true,
                time: moment().format('H:m:s'),
                hancurkan: true,
                wrapper: 'body',
                delay: 5000,
                bg: 'bg-danger'
            });
        },
        submitSuccess: function(res){
            endLoading();
            makeToast({
                title: 'Berhasil melakukan enkripsi dan penyisipan file',
                message: 'Waktu proses: ' + res.eta + ' detik',
                id: 'defaut-config',
                cara_tempel: 'after',
                autohide: true,
                show: true,
                time: moment().format('H:m:s'),
                hancurkan: true,
                wrapper: 'body',
                delay: 5000,
                bg: 'bg-success'
            });
            $("#download-video-rsa-eof").attr('href', res.url).show().prop('disabled', false);
        }
    });

    $("#form-rsa-eof-deskripsi").initFormAjax({
        sebelumSubmit: function(){
            showLoading();
            $("#download-file-deskrip-eof").attr('href', '').hide().prop('disabled', true);
        },
        submitError: function(res){
            endLoading();
            var error = res.responseJSON;
            makeToast({
                title: 'Terjadi kesalahan',
                message: error.err || error.message,
                id: 'defaut-config',
                cara_tempel: 'after',
                autohide: true,
                show: true,
                time: moment().format('H:m:s'),
                hancurkan: true,
                wrapper: 'body',
                delay: 5000,
                bg: 'bg-danger'
            });
        },
        submitSuccess: function(res){
            endLoading();
            makeToast({
                title: 'Berhasil melakukan ekstraksi dan deskripsi file',
                message: 'Waktu proses: ' + res.eta + ' detik',
                id: 'defaut-config',
                cara_tempel: 'after',
                autohide: true,
                show: true,
                time: moment().format('H:m:s'),
                hancurkan: true,
                wrapper: 'body',
                delay: 5000,
                bg: 'bg-success'
            });
            $("#download-file-deskrip-eof").attr('href', res.url).show().prop('disabled', false);
        }
    });

    // Functions
    function switchHalaman(event){
        var halaman = event.data.halaman;
        var current = $(".halaman:visible");
        var showing = $("#halaman-" + halaman);

        current.fadeOut('slow');
        showing.fadeIn('slow').show();

        if(cpr == 1){
            localStorage.setItem('lstpg', halaman);
        }
    }

    function sendRequest(url, data, callback){
        $.post(url, data, function(result){
            if(typeof(callback) == 'function')
                callback(result)
        });
    }

    // Go To Last Page
    var lastPage = localStorage.getItem('lstpg');
    if(cpr == 1 && lastPage){
        switchHalaman({data: {halaman: lastPage}});
        if(lastPage == 'generate-key')
            renderKeys();
    }


    function renderKeys(keys){
        elements.keys.empty();
        // Ambil dan render data rsa-key
        fetch(path + 'ws/rsakey').then(res => res.json()).then(res => {
            var keys = res.data;
            console.log(keys);
            var row = '';
            keys.forEach((k, i) => {
                row += ' <tr class="rsa-key text-white" data-index="'+i+'"> <input type="hidden" id="rsa-key-private-'+i+'" value="'+ k.private +'"> <input type="hidden" id="rsa-key-public-'+i+'" value="'+ k.public +'"> <td>'+(i +1) +'</td><td>' + (k.dibuat) + '</td><td style="cursor:pointer" class="public-rsa" data-index="'+i+'">' + ( k.public.substr(0, 15) + ' ...') + ' <span><i style="font-size: 15px;" class="iconsmind-File-Copy2"></i> Copy</span></td><td class="private-rsa" data-index="'+i+'" style="cursor:pointer">' + (k.private.substr(0,15) + ' ...') + ' <span><i style="font-size: 15px;" class="iconsmind-File-Copy2"></i> Copy</span></td></tr>';
            });
            elements.keys.append(row);
            $(".public-rsa").click(function(){
                // ambil indexnya
                var index = $(this).data('index');

                var public = $("#rsa-key-public-" + index).val();
                var pesan = "Public key berhasil di copy";
                copyToClipboard(public, pesan);
            });
            $(".private-rsa").click(function(){
                // ambil indexnya
                var index = $(this).data('index');
                var pesan = "Private key berhasil di copy";
                var private = $("#rsa-key-private-" + index).val();
                copyToClipboard(private, pesan);

            });
        });
    }
});