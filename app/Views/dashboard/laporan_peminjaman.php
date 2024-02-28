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
            <form action="<?= base_url('admin/laporan_peminjaman'); ?>" method="GET">
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
                    <div class="col-2">
                        <a href="<?= base_url('admin/laporan_peminjaman?late=true'); ?>" class="btn btn-danger">Keterlambatan</a>
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
                    <th scope="col">Judul buku</th>
                    <th scope="col" class="text-center">Jumlah</th>
                    <th scope="col">Tgl pinjam</th>
                    <th scope="col">Tenggat</th>
                    <th scope="col" class="text-center">Status</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                <?php
                $i = 1 + ($itemPerPage * ($currentPage - 1));

                $now = Time::now(locale: 'id');
                ?>
                <?php if (empty($loans)) : ?>
                    <tr>
                        <td class="text-center" colspan="8"><b>Tidak ada data</b></td>
                    </tr>
                <?php endif; ?>
                <?php
                foreach ($loans as $key => $loan) :
                    $loanCreateDate = Time::parse($loan['loan_date'], locale: 'id');
                    $loanDueDate = Time::parse($loan['due_date'], locale: 'id');

                    $isLate = $now->isAfter($loanDueDate);
                    $isDueDate = $now->today()->difference($loanDueDate)->getDays() == 0;
                ?>
                    <tr>
                        <th scope="row"><?= $i++; ?></th>
                        <td>
                            <a href="<?= base_url("admin/members/{$loan['member_uid']}"); ?>" class="text-primary-emphasis text-decoration-underline">
                                <p>
                                    <b><?= "{$loan['first_name']} {$loan['last_name']}"; ?></b>
                                </p>
                            </a>
                        </td>
                        <td>
                            <a href="<?= base_url("admin/books/{$loan['slug']}"); ?>">
                                <p class="text-primary-emphasis text-decoration-underline"><b><?= "{$loan['title']} ({$loan['year']})"; ?></b></p>
                                <p class="text-body"><?= "Author: {$loan['author']}"; ?></p>
                            </a>
                        </td>
                        <td class="text-center"><?= $loan['quantity']; ?></td>
                        <td>
                            <b><?= $loanCreateDate->toLocalizedString('dd/MM/y'); ?></b><br>
                            <b><?= $loanCreateDate->toLocalizedString('HH:mm:ss'); ?></b>
                        </td>
                        <td>
                            <b><?= $loanDueDate->toLocalizedString('dd/MM/y'); ?></b>
                        </td>
                        <td class="text-center">
                            <?php if ($now->isBefore($loanDueDate)) : ?>
                                <span class="badge bg-success rounded-3 fw-semibold">Normal</span>
                            <?php elseif ($now->today()->equals($loanDueDate)) : ?>
                                <span class="badge bg-warning rounded-3 fw-semibold">Jatuh tempo</span>
                            <?php else : ?>
                                <span class="badge bg-danger rounded-3 fw-semibold">Terlambat</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?= $pager->links('loans', 'my_pager'); ?>
    </div>
</div>
<?= $this->endSection() ?>