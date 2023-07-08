$(document).ready(function(){
    $('body').removeClass('show-spinner');
    var toasCofig = {
        wrapper: '.modal',
        id: 'toast',
        delay: 6000,
        autohide: true,
        show: true,
        bg: 'bg-danger',
        textColor: 'text-white',
        time: waktu(null, 'HH:mm'),
        toastId: 'login',
        title: 'Gagal, Terjadi kesalahan',
        type: 'danger',
        hancurkan: true
    }
    
    var formid = "<?php echo $formid ?>";
    var submitSukses = <?php echo isset($submitSukses) ? $submitSukses: 'null' ?>;
    var submitError = <?php echo isset($submitError) ? $submitError : 'null' ?>;
    var options = {
        submitError: function(response){
            endLoading();
            $('#btn-login').prop('disabled', false);
            var responseText  = JSON.parse(response.responseText);
            toasCofig.message = responseText.message;
            var tc = {...toasCofig, wrapper: 'form', message: responseText.message};
            makeToast(tc);
            if(isFunction(submitError))
                submitError(response);

        },
        sebelumSubmit: function(input, ){
            showLoading();
            $('#alert_danger').text('').hide();
            $('#btn-login').prop('disabled', true);
        }, 
        submitSuccess: function(data){
            endLoading();
            $('#btn-login').prop('disabled', false);

            var tc = {...toasCofig, message: '', wrapper: 'form', bg: 'bg-success', title: 'Berhasil login', type: 'success'};
            makeToast(tc)
            setTimeout(function(){
                location.href = path;
            }, 1500);
            if(isFunction(submitSukses))
                submitSukses(data);
        }
    }
    $(formid).initFormAjax(options);

    $("#register").click(function (e) {
        e.preventDefault();
        var options = {
            modalId: "modal-form-register",
            wrapper: "body",
            opt: {
                type: 'form',
                ajax: true,
                rules: [
                    {
                        name: 'noSpace',
                        method: function (value, element) { return value.indexOf(" ") < 0; },
                        message: "Tidak boleh ada spasi",
                        field: 'user'
                    },
                    {
                        name: 'uniqeUsername',
                        url: path + 'ws/cek_username',
                        field: 'user',
                        message: 'Username sudah digunakan'
                    },
                    {
                        name: 'uniqueEmail',
                        url: path + 'ws/cek_email',
                        field: 'email',
                        message: 'Email sudah digunakan'
                    },
                    {
                        name: 'securePassword',
                        method: function(value, element){
                            return value.match(/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/);
                        },
                        message: "Password minimal 8 karakter dan memiliki minimal 1 angka dan 1 huruf",
                        field: 'pass',
                    },
                    {
                        name: 'repasswordSame',
                        method: function(value, element){
                            return value == $("#pass").val();
                        },
                        message: "Harus sama dengan password",
                        field: 'repass'
                    }
                ],
                sebelumSubmit: function () {
                    $('body').addClass('show-spinner');
                    $('#register').prop('disabled', true);

                    
                },
                submitError: function(response){
                    $('body').removeClass('show-spinner');
                    $('#register').prop('disabled', false);
                    var responseText  = JSON.parse(response.responseText);
                    var tc = {...toasCofig, message: responseText.message};
                    makeToast(tc);

                },
                submitSuccess: function (res) {
                    $('body').removeClass('show-spinner');
                    $('#register').prop('disabled', false);

                    if(res.type == 'success'){
                        var tc = {...toasCofig, bg: 'bg-success', title: 'Berhasil mendaftar', message: 'Silahkan login dengan akun anda', type: 'success'};
                        setTimeout(function(){
                            $("#modal-form-register").modal('hide');
                        }, 3000);
                    }else{
                        toasCofig.message = responseText.message;
                        var tc = {...toasCofig, message: responseText.message};
                    }
                    makeToast(tc)
                },
                open: true,
                destroy: true,
                modalPos: 'def',
                saatBuka: () => {
                    $('#user').keyup(function(){
                        var value = $(this).val();
                        if(!value){
                            $('button[type="submit"]').prop('disabled', false);
                            $("#err-username").hide();
                            return;
                        }

                        // $.post(path + 'ws/cek_username', {username: value}, function(res){
                        //     if(res.type != 'success') return;
                        //     if(res.boleh){
                        //         $('button[type="submit"]').prop('disabled', false);
                        //         $("#err-username").hide();
                        //     }else{
                        //         $('button[type="submit"]').prop('disabled', true);
                        //         $("#err-username").show();
                        //     }
                        // });
                    });
                },
                saatTutup: () => {
                   
                },
                formOpt: {
                    enctype: 'multipart/form-data',
                    formId: "form-register",
                    formAct: path + "ws/register",
                    formMethod: 'POST',
                },
                modalTitle: "Register",
                modalBody: {
                    input: [
                        {
                            label: 'Username <span class="symbol-required"></span>', placeholder: 'Masukkan Username',
                            type: 'text', name: 'username', id: 'user', attr: 'required autocomplete="off"'
                        },
                        // {
                        //     // type: 'custom', text: '<p class="text-danger" style="display:none" id="err-username"> Username tidak dapat digunakan karena sudah terdaftar </p>'
                        // },
                        {
                            label: 'Nama Lengkap', placeholder: 'Masukkan Nama Lengkap',
                            type: 'text', name: 'nama', id: 'nama', attr: 'autocomplete="off"'
                        },
                        {
                            label: 'Email <span class="symbol-required"></span>', placeholder: 'Masukkan Email',
                            type: 'email', name: 'email', id: 'email', attr: 'required autocomplete="off"'
                        },
                        {
                            label: 'Password <span class="symbol-required"></span>', placeholder: 'Masukkan Password',
                            type: 'password', name: 'password', id: 'pass', attr: 'required autocomplete="off"'
                        },
                        {
                            label: 'Konfirmasi Password <span class="symbol-required"></span>', placeholder: 'Masukkan Password Lagi',
                            type: 'password', name: 'repassword', id: 'repass', attr: 'required autocomplete="off"'
                        },
                        
                    ],
                    buttons: [
                        { type: 'reset', text: 'Batal', id: "cancel", class: "btn btn-empty" },
                        { type: 'submit', text: 'Daftar', id: "daftar", class: "btn btn btn-primary" }
                    ],
                },
            }
        }

        generateModal(options.modalId, options.wrapper, options.opt);
    });
});