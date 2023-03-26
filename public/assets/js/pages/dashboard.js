$(document).ready(function(){
    // Element Register
    var elements = {
        btnGenerateKey: $('#generate-key'),
        btnEnkripsi: $('#enkripsi'),
        btnDeskripsi: $('#deskripsi'),
        btnEof: $('#eof'),
        btnEkstraksiEof: $('#ekstraksi-eof'),
        btnRsaEof: $('#rsa-eof'),
        btnBack: $('.main-menu'),

        btnPosesGenerateKey: $('#proses-generate-key'),
    }
    var cpr = localStorage.getItem('iscpr');

    // Cek CPR
    if(cpr == 1){
        $("#cpr").prop('checked', true).trigger('change');
    }else if(cpr == 0){
        $("#cpr").prop('checked', false).trigger('change');
    }

    // Event Listener
    elements.btnGenerateKey.click({halaman: 'generate-key'}, switchHalaman);
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
            // window.open(path + 'ws/file/' + res.name, '_blank')
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
                message: error.message,
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
            // window.open(path + 'ws/file/' + res.name, '_blank')
        }
    });

    $("#form-eofs").initFormAjax({
        sebelumSubmit: function(){
            $("#download-video-eof").attr('href', '').hide().prop('disabled', true);
            showLoading();
        },
        submitError: function(res){
            endLoading();
            var error = res.responseJSON;
            makeToast({
                title: 'Terjadi kesalahan',
                message: error.message,
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
            $("#download-video-eof").attr('href', res.url).show().prop('disabled', false);
        }
    });

    $("#form-ekstraksi-eofs").initFormAjax({
        sebelumSubmit: function(){
            showLoading();
        },
        submitError: function(res){
            endLoading();
            var error = res.responseJSON;
            makeToast({
                title: 'Terjadi kesalahan',
                message: error.message,
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
            window.open(path + 'ws/file/' + res.name, '_blank')
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
                message: error.message,
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
            $("#download-video-rsa-eof").attr('href', res.url).show().prop('disabled', false);
        }
    });

    $("#form-rsa-eof-deskripsi").initFormAjax({
        sebelumSubmit: function(){
            showLoading();
        },
        submitError: function(res){
            endLoading();
            var error = res.responseJSON;
            makeToast({
                title: 'Terjadi kesalahan',
                message: error.message,
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
            window.open(path + 'ws/file/' + res.name, '_blank')
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
        switchHalaman({data: {halaman: lastPage}})
    }

});