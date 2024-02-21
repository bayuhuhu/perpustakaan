  <?php

  /**
   * List of sidebar navigations
   */
  $sidebarNavs =
    [
      'Home',
      [
        'name' => 'Buku',
        'link' => '/',
        'icon' => 'ti ti-book'
      ],
      [
        'name' => 'Kategori',
        'link' => '/categories',
        'icon' => 'ti ti-category-2'
      ],
      'Transaksi',
      [
        'name' => 'Peminjaman',
        'link' => '/loans/member/search',
        'icon' => 'ti ti-arrows-exchange'
      ],
      [
        'name' => 'Pengembalian',
        'link' => '/returns/new/search',
        'icon' => 'ti ti-check'
      ],
      [
        'name' => 'Denda',
        'link' => '/fines',
        'icon' => 'ti ti-report-money'
      ],
      'Member',
      [
        'name' => 'Daftar Member',
        'link' => '/register-member',
        'icon' => 'ti ti-user'
      ],
    ];


  $kategoriBook =
    [
      [
        'name' => 'Fiksi',
        'kategori' => 'fiksi',
      ],
      [
        'name' => 'Non-Fiksi',
        'kategori' => 'non-fiksi',
      ],
      [
        'name' => 'Sejarah',
        'kategori' => 'sejarah',
      ],
      [
        'name' => 'Komik',
        'kategori' => 'komik',
      ],
      [
        'name' => 'Teknologi',
        'kategori' => 'teknologi',
      ],
    ]


  ?>

  <!-- Sidebar Start -->
  <aside class="left-sidebar position-fixed">
    <!-- Sidebar scroll-->
    <div id="">
      <!-- Brand -->
      <div class="brand-logo d-flex align-items-center justify-content-between">
        <div class="pt-4 mx-auto">
          <a href="<?= base_url(); ?>">
            <h2>Buku<span class="text-primary">Hub</span></h2>
          </a>
        </div>
        <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
          <i class="ti ti-x fs-8"></i>
        </div>
      </div>

      <!-- Sidebar navigation-->
      <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
        <ul id="sidebarnav">
          <?php foreach ($sidebarNavs as $nav) : ?>
            <?php if (gettype($nav) === 'string') : ?>
              <li class="nav-small-cap">
                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                <span class="hide-menu"><?= $nav; ?></span>
              </li>
            <?php else : ?>
              <?php if ($nav['name'] === 'Kategori') { ?>
                <li class="sidebar-item">
                  <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse" data-bs-target="#auth" aria-expanded="false" aria-controls="auth">
                    <i class="<?= $nav['icon']; ?>"></i>
                    <span>Pilih Kategori</span>
                    <i class="ti ti-chevron-down"></i>
                  </a>
                  <ul id="auth" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                    <?php foreach ($kategoriBook as $book) : ?>
                      <li class="sidebar-item">
                        <a onclick="getKategori('<?= $book['kategori']; ?>') " class="sidebar-link"><?= $book['name']; ?></a>
                      </li>
                    <?php endforeach; ?>
                  </ul>
                </li>
              <?php
              } else { ?>
                <li class="sidebar-item">
                  <a class="sidebar-link" href="<?= base_url($nav['link']) ?>" aria-expanded="false">
                    <span>
                      <i class="<?= $nav['icon']; ?>"></i>
                    </span>
                    <span class="hide-menu"><?= $nav['name']; ?></span>
                  </a>
                </li>
              <?php } ?>
            <?php endif; ?>
          <?php endforeach; ?>
        </ul>
      </nav>
      <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
  </aside>
  <script>
    function getKategori(param) {
      // console.log(param);

      jQuery.ajax({
        url: `<?= base_url(''); ?>`,
        type: 'get',
        data: {
          'param': param
        },
        success: function(response, status, xhr) {
          $('#book').html(response); // Memperbarui elemen HTML dengan ulasan yang diterima
          $('html, body').animate({
            scrollTop: $("#book").offset().top // Menggeser halaman ke elemen dengan id 'book'
          }, 500);
        },
        error: function(xhr, status, thrown) {
          console.log(thrown);
          $('#book').html(thrown); // Menampilkan pesan kesalahan jika terjadi kesalahan
        }
      });
    }
  </script>
  <!--  Sidebar End -->