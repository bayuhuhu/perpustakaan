<?= $this->extend('layouts/home_layout') ?>

<?= $this->section('head') ?>

<?= $this->section('head') ?>
<title>Peminjaman Baru</title>
<?= $this->endSection() ?>
<?= $this->section('content') ?>


<style>
    #img {
        background-image: url(<?= base_url('assets/images/image.png') ?>);
        /* background-size: 100% auto; */
        background-size: cover;

    }
</style>
<div class="w-auto card  p-5">
    <div class="p-1 card bg-dark-subtle">

        <div id="img" class="d-flex justify-content-around ">
            <div class="p-3">
                <div class="text-center">
                    <img class="w-25" src="<?= base_url('assets/images/smea.png') ?>" alt="">
                    <h1 class="text-uppercase fw-bold ">Perpustakaan </h1>
                    <h6 class="text-uppercase">smkn 1 boyolangu</h6>
                    <img class="mt-2" id="qr-code" src="<?= base_url(MEMBERS_QR_CODE_URI . $member['qr_code']); ?>" alt="" style="max-width: 230px; height: 130px; object-fit: contain;">
                    <h3 class="mt-2 text-capitalize"><?= $member['first_name'] . ' ' . $member['last_name'] ?></h3>
                </div>

            </div>
            <div class="p-3">
                <h2 class="text-center  text-bg-dark p-2 mb-3"><?= $member['type'] ?></h2>
                <h4 class="text-center text-dark mb-4"><?= '240' . $member['id'] ?></h4>
                <div class="p-2"><img style="width: 200px; height: 250px; object-fit: cover; box-shadow: 2px 2px 4px 0px rgba(0, 0, 0, 0.3);
" src="<?= base_url(USER_PROFILE_URI . $member['profile_picture']) ?>" alt="">
                </div>

            </div>


        </div>
    </div>
</div>
<?= $this->endSection() ?>


<?= $this->endSection() ?>