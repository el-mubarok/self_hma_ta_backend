<?php 
  $_role = $_SESSION[SESSION_ROLE];
?>

<div data-scroll-to-active="true" class="main-menu menu-fixed menu-dark menu-accordion menu-shadow">
  <!-- main menu header-->
  <!-- <div class="main-menu-header">
    <input type="text" placeholder="Search" class="menu-search form-control round" />
  </div> -->
  <!-- / main menu header-->
  <!-- main menu content-->
  <div class="main-menu-content">
    <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
      <li class="nav-item <?= ($page['parent'] == 1) ? 'active':'' ?>">
        <a href="<?= site_url()?>">
          <i class="icon-home3"></i>
          <span class="menu-title">Dashboard</span>
        </a>
      </li>
      <!-- <if($_role == ROLE_ADMIN):?> -->
      <!-- <li class="nav-item <?= ($page['parent'] == 2) ? 'active':'' ?>">
        <a href="<?= site_url('warga')?>">
          <i class="icon-head"></i>
          <span class="menu-title">Data Warga</span>
        </a>
      </li> -->
      <!-- <endif;> -->
      <!-- <li class=" nav-item">
        <a href="index.html">
          <i class="icon-globe2"></i>
          <span class="menu-title">Pengurus RW</span>
        </a>
      </li> -->

      <li class="nav-item has-sub <?= ($page['parent'] == 3) ? 'open':'' ?>">
        <a href="index.html">
          <i class="icon-bar-graph-2"></i>
          <span class="menu-title">Data Iuran</span>
        </a>
        <ul class="menu-content">
          <li class="<?= ($page['parent'] == 3 && $page['sub'] == 1) ? 'active':'' ?>">
            <a href="<?= site_url('keuangan/semua')?>" class="menu-item">Semua Iuran</a>
          </li>
          <li class="<?= ($page['parent'] == 3 && $page['sub'] == 2) ? 'active':'' ?>">
            <a href="<?= site_url('keuangan/perbulan')?>" class="menu-item">Iuran Perbulan</a>
          </li>
        </ul>
      </li>

      <!-- <li class="nav-item has-sub <?= ($page['parent'] == 3) ? 'open':'' ?>">
        <a href="index.html">
          <i class="icon-help2"></i>
          <span class="menu-title">Petugas</span>
        </a>
        <ul class="menu-content">
          <li class="<?= ($page['parent'] == 3 && $page['child'] == 1) ? 'active':'' ?>">
            <a href="<?= site_url('petugas')?>" class="menu-item">List Petugas</a>
          </li>
          <li class="<?= ($page['parent'] == 3 && $page['child'] == 2) ? 'active':'' ?>">
            <a href="<?= site_url('petugas/aktifitas')?>" class="menu-item">Aktifitas Petugas</a>
          </li>
        </ul>
      </li>

      <li class="nav-item has-sub <?= ($page['parent'] == 4) ? 'open':'' ?>">
        <a href="index.html">
          <i class="icon-head"></i>
          <span class="menu-title">Data Warga</span>
        </a>
        <ul class="menu-content">
          <li class="<?= ($page['parent'] == 4 && $page['child'] == 1) ? 'active':'' ?>">
            <a href="<?= site_url('warga?t=tetap')?>" class="menu-item">Warga Tetap</a>
          </li>
          <li class="<?= ($page['parent'] == 4 && $page['child'] == 2) ? 'active':'' ?>">
            <a href="<?= site_url('warga?t=kontrak')?>" class="menu-item">Warga Kontrak</a>
          </li>
          <li class="<?= ($page['parent'] == 4 && $page['child'] == 3) ? 'active':'' ?>">
            <a href="<?= site_url('warga/tambahwarga')?>" class="menu-item">Tambah Warga</a>
          </li>
        </ul>
      </li>

      <li class="nav-item has-sub <?= ($page['parent'] == 5) ? 'open':'' ?>">
        <a href="index.html">
          <i class="icon-bar-graph-2"></i>
          <span class="menu-title">Data Keuangan</span>
        </a>
        <ul class="menu-content">
          <li class="has-sub is-shown">
            <a href="#" class="menu-item">Pemasukan</a>
            <ul class="menu-content">
              <li class="<?= ($page['parent'] == 5 && $page['sub'] == 1 && $page['child'] == 1) ? 'active':'' ?>">
                <a href="<?= site_url('keuangan/iuran')?>" class="menu-item">Iuran Bulanan</a>
              </li>
              <li class="<?= ($page['parent'] == 5 && $page['sub'] == 1 && $page['child'] == 2) ? 'active':'' ?>">
                <a href="<?= site_url('keuangan/iuran')?>?pos=2" class="menu-item">Iuran Insidental</a>
              </li>
              <li class="has-sub is-shown <?= ($page['parent'] == 5 && $page['child'] == 3) ? 'open':'' ?>">
                <a href="#" class="menu-item">Donasi</a>
                <ul class="menu-content">
                  <li class="<?= ($page['parent'] == 5 && $page['grandchild'] == 1) ? 'active':'' ?>">
                    <a href="<?= site_url('keuangan/donasi')?>" class="menu-item">Daftar Donasi</a>
                  </li>
                  <li class="<?= ($page['parent'] == 5 && $page['grandchild'] == 2) ? 'active':'' ?>">
                    <a href="<?= site_url('keuangan/donatur')?>" class="menu-item">Daftar Donatur</a>
                  </li>
                </ul>
              </li>
            </ul>
          </li>

          <li class="has-sub is-shown">
            <a href="#" class="menu-item">Pembelanjaan</a>
            <ul class="menu-content">
              <li class="<?= ($page['parent'] == 5 && $page['sub'] == 2 && $page['child'] == 1) ? 'active':'' ?>">
                <a href="<?= site_url('keuangan/belanja')?>" class="menu-item">Pembelanjaan Bulanan</a>
              </li>
              <li class="<?= ($page['parent'] == 5 && $page['sub'] == 2 && $page['child'] == 2) ? 'active':'' ?>">
                <a href="<?= site_url('keuangan/belanja')?>?pos=2" class="menu-item">Pembelanjaan Insidental</a>
              </li>
            </ul>
          </li>
          <li class="<?= ($page['parent'] == 5 && $page['sub'] == 3) ? 'active':'' ?>">
            <a href="<?= site_url('keuangan/laporan')?>" class="menu-item">Laporan</a>
          </li>
        </ul>
      </li> -->

    </ul>
  </div>
  <!-- /main menu content-->
  <!-- main menu footer-->
  <!-- include includes/menu-footer-->
  <!-- main menu footer-->
</div>