<?php

/**
 * List of sidebar navigations
 */
$sidebarNavs =
  [
    'Home',
    [
      'name' => 'Dashboard',
      'link' => '/admin/dashboard',
      'icon' => 'ti ti-layout-dashboard'
    ],
    'Transaksi',
    [
      'name' => 'Peminjaman',
      'link' => '/admin/loans',
      'icon' => 'ti ti-arrows-exchange'
    ],
    'Master',
    [
      'name' => 'Anggota',
      'link' => '/admin/members',
      'icon' => 'ti ti-user'
    ],
    [
      'name' => 'Buku',
      'link' => '/admin/books',
      'icon' => 'ti ti-book'
    ],
    [
      'name' => 'Kategori',
      'link' => '/admin/categories',
      'icon' => 'ti ti-category-2'
    ],
    [
      'name' => 'Rak',
      'link' => '/admin/racks',
      'icon' => 'ti ti-columns'
    ],
  ];
?>

<!-- Sidebar Start -->
<aside class="left-sidebar">
  <!-- Sidebar scroll-->
  <div>
    <!-- Brand -->
    <div class="brand-logo d-flex align-items-center justify-content-between">
      <a href="./index.html" class="text-nowrap logo-img">
        <img src="../assets/images/logos/dark-logo.svg" width="180" alt="" />
      </a>
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
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?= base_url($nav['link']) ?>" aria-expanded="false">
                <span>
                  <i class="<?= $nav['icon']; ?>"></i>
                </span>
                <span class="hide-menu"><?= $nav['name']; ?></span>
              </a>
            </li>
          <?php endif; ?>
        <?php endforeach; ?>
      </ul>
    </nav>
    <!-- End Sidebar navigation -->
  </div>
  <!-- End Sidebar scroll-->
</aside>
<!--  Sidebar End -->