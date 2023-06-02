$(document).ready(function(){
    $('body').removeClass('show-spinner');
    var toasCofig = {
        wrapper: 'form',
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
            makeToast(toasCofig);
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

            toasCofig.bg = 'bg-success';
            toasCofig.title = 'Berhasil login';
            toasCofig.type = 'success';

            makeToast(toasCofig)
            setTimeout(function(){
                location.href = path;
            });
            if(isFunction(submitSukses))
                submitSukses(data);
        }
    }
    $(formid).initFormAjax(options);


})