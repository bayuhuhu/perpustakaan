<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Laporan Peminjaman</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php

use CodeIgniter\I18n\Time;

if (session()->getFlashdata('msg')) : ?>
    <div class="pb-2">
        <div class="alert <?= (session()->getFlashdata('error') ?? false) ? 'alert-danger' : 'alert-success'; ?> alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('msg') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">

        <div class="row mb-2">
            <div class="col-12 col-lg-5">
                <h5 class="card-title fw-semibold mb-4">Data Peminjaman</h5>
            </div>
        </div>
        <div class="col-12 col-lg-12 mb-4">
            <form action="<?= base_url('admin/absensi_member'); ?>" method="GET">
                <div class="row g-3">
                    <div class="col-3">
                        <input type="date" name="start_date" class="form-control" value="<?= $_GET['start_date'] ?? ''; ?>" required>
                    </div>
                    <div class="col-3">
                        <input type="date" name="end_date" class="form-control" value="<?= $_GET['end_date'] ?? ''; ?>" required>
                    </div>
                    <div class="col-2">
                        <button type="submit" class="btn btn-primary">Cari</button>
                    </div>

                    <!-- <div class="col-2">
                        <a href="<?= base_url('admin/cetak_laporan_peminjaman'); ?>" class="btn btn-success">Cetak Laporan</a>
                    </div> -->
                </div>
            </form>
        </div>

        <table class="table table-hover table-striped">
            <thead class="table-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nama peminjam</th>
                    <th scope="col">Tgl pinjam</th>

                </tr>
            </thead>
            <tbody class="table-group-divider">
                <?php
                $i = 1 + ($itemPerPage * ($currentPage - 1));

                $now = Time::now(locale: 'id');
                ?>
                <?php if (empty($absensi)) : ?>
                    <tr>
                        <td class="text-center" colspan="8"><b>Tidak ada data</b></td>
                    </tr>
                <?php endif; ?>
                <?php
                foreach ($absensi as $key => $absen) :
                    $absenCreateDate = Time::parse($absen['created_at'], locale: 'id');


                ?>
                    <tr>
                        <th scope="row"><?= $i++; ?></th>
                        <td>

                            <p>
                                <b><?= "{$absen['first_name']} {$absen['last_name']}"; ?></b>
                            </p>
                        </td>
                        <td>
                            <b><?= $absenCreateDate->toLocalizedString('dd/MM/y'); ?></b><br>
                            <b><?= $absenCreateDate->toLocalizedString('HH:mm:ss'); ?></b>

                        </td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?= $pager->links('loans', 'my_pager'); ?>
    </div>
</div>
<?= $this->endSection() ?>