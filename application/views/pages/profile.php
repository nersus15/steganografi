<div class="container emp-profile">
    <div id="alert_danger" style="display: none;" class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong></strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <form id="bqn-form-edit-user" enctype="multipart/form-data" action="<?php echo base_url('ws/update_profile') ?>" method="post">
        <input type="hidden" name="_username" value="<?php echo $user['username'] ?>">
        <input type="hidden" name="_mode" value="edit-pr">
        <input type="hidden" name="id_info" value="<?php echo $user['id'] ?>">
        <div class="row">
            <div class="col-md-4">
                <div id="pp" class="profile-img">
                    <a data-lightbox="pp" data-title="Tekan tombol esc untuk keluar" href="<?php echo base_url('public/assets/img/profile/' . $user['photo']) ?>"><img style="cursor: pointer;" id="pp-preview" src="<?php echo base_url('public/assets/img/profile/' . $user['photo']) ?>" alt="Photo profile" /></a>
                    <label style="cursor: pointer;" id="file" class="file btn btn-lg btn-primary">
                        Change Photo (Max. 5MB)
                        <input accept="image/*" id="n-pp" type="file" name="pp" />
                    </label>
                </div>
            </div>
            <div class="col-md-6 mt-4">
                <div class="profile-head">
                    <h5>
                        <?php echo $user['nama'] ?? $user['username'] ?>
                    </h5>
                    <h6>
                        <?echo $user['role'] ?>
                    </h6>
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Akun</a>
                        </li>
                        <?php if(is_login('member')): ?>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Data Member</a>
                            </li>
                        <?php endif ?>
                    </ul>
                </div>

                <div class="tab-content profile-tab" id="myTabContent">
                    <?php 
                        include_view('pages/profile/user');
                        if(is_login('member'))
                            include_view('pages/profile/member'); 
                    ?>
                   
                </div>
            </div>
            <div class="col-md-2 mt-4">
                <button type="submit" class="profile-edit-btn text-white btn-warning" id="btn-edit">Edit Profile</button>
            </div>
        </div>
    </form>
</div>