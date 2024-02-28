<?= $this->extend('layouts/home_layout') ?>

<?= $this->section('head') ?>
<title>Peminjaman Baru</title>
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
    <a href="<?= base_url('loans/member/search'); ?>" class="btn btn-outline-primary mb-5">
      <i class="ti ti-arrow-left"></i>
      Kembali
    </a>
    <h5 class="card-title fw-semibold mb-4">Peminjaman Buku Sedang Diproses Oleh Admin</h5>


    <table class="table table-hover table-striped">
      <thead class="table-light">
        <tr>
          <th scope="col">#</th>
          <th scope="col">Nama peminjam</th>
          <th scope="col">Judul buku</th>
          <th scope="col" class="text-center">Jumlah</th>
          <th scope="col">Tgl pinjam</th>
          <th scope="col">Tgl pengembalian</th>
          <th scope="col" class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody class="table-group-divider">
        <?php
        $i = 1;
        foreach ($newLoans as $loan) : ?>
          <tr>
            <th scope="row"><?= $i++; ?></th>
            <td>
              <p class="text-primary-emphasis ">
                <b><?= "{$loan['first_name']} {$loan['last_name']}"; ?></b>
              </p>
            </td>
            <td>
              <p class="text-primary-emphasis "><b><?= "{$loan['title']} ({$loan['year']})"; ?></b></p>
              <p class="text-body"><?= "Author: {$loan['author']}"; ?></p>
            </td>
            <td class="text-center"><b><?= $loan['quantity']; ?></b></td>
            <td><b><?= Time::parse($loan['loan_date'])->toLocalizedString('d/M/y'); ?></b></td>
            <td>
              <b><?= Time::parse($loan['due_date'])->toLocalizedString('d/M/y'); ?></b>
            </td>
            <td class="text-center">
              <div class="d-flex justify-content-center gap-2">
                <a href="<?= base_url("loans/{$loan['uid']}"); ?>" class="btn btn-primary mb-2">
                  <i class="ti ti-eye"></i>
                  Detail
                </a>

              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?= $this->endSection() ?>