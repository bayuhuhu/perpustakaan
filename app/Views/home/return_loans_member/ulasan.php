<?php

use CodeIgniter\I18n\Time;

$now = Time::now(locale: 'id');

if (empty($loans)) : ?>
  <h5 class="card-title text-center  fw-semibold my-4 text-danger">Ulasan tidak ditemukan</h5>
  <p class="text-danger text-center "><?= $msg ?? ''; ?></p>
<?php else : ?>
  <h5 class="card-title text-center fw-semibold my-4 ">Ulasan Pilihan</h5>
  <?php foreach ($loans as $key => $loan) : ?>
    <div class="p-2">
      <div class="card mb-1 ">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class=""><i class="ti ti-user-circle"></i></div>
            <h5 class="card-title ms-3"><?= $loan['first_name'] . ' ' . $loan['last_name']; ?></h5>
          </div>
          <?php $attributes = json_decode($loan['attributes'], true); ?>
          <div class="rating ">
            <?php
            $rating = $attributes['rating'];
            for ($j = 1; $j <= 5; $j++) {
              $checked = ($j == $rating) ? 'checked' : '';
              $star_color = ($j <= $rating) ? 'text-warning' : '';
            ?>
              <input type="radio" id="star<?= $j ?>" name="rating" value="<?= $j ?>" <?= $checked ?> disabled />
              <label for="star<?= $j ?>" title="<?= $j ?> star" class="<?= $star_color ?>"></label>
            <?php } ?>
          </div>
          <p class="card-text mt-2"><?= $attributes['ulasan'] ?></p>

        </div>
      </div>
    </div>
  <?php endforeach; ?>
<?php endif; ?>

<style>
  .rating {
    display: flex;
  }

  .rating input {
    display: none;
  }

  .rating label {
    cursor: pointer;
    width: 15px;
    height: 15px;
    margin: 0 2px;
    font-size: 1rem;
    color: #ccc;
    display: inline-block;
  }

  .rating label:before {
    content: '\2605';
  }

  .rating input:checked~label {
    color: #ccc;
  }

  .rating input:checked~label:before {
    content: '\2605';
  }
</style>