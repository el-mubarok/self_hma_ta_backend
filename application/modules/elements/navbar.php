<nav class="header-navbar navbar navbar-with-menu navbar-fixed-top navbar-semi-dark navbar-shadow">
  <div class="navbar-wrapper">
    <div class="navbar-header" style="display: flex; justify-content: center; align-items: center;">
      <ul class="nav navbar-nav">
        <li class="nav-item mobile-menu hidden-md-up float-xs-left">
          <a class="nav-link nav-menu-main menu-toggle hidden-xs">
            <i class="icon-menu5 font-large-1"></i>
          </a>
        </li>
        <li class="nav-item">
          <a href="/" class="navbar-brand nav-link" style="padding: 0 !important; margin: 0 !important;">
            <img alt="branding logo" src="<?= base_url()?>app-assets/images/logo/robust-logo-light.png" data-expand="<?= base_url()?>app-assets/images/logo/robust-logo-light.png" data-collapse="<?= base_url()?>app-assets/images/logo/robust-logo-small.png" class="brand-logo">
          </a>
        </li>
        <li class="nav-item hidden-md-up float-xs-right">
          <a data-toggle="collapse" data-target="#navbar-mobile" class="nav-link open-navbar-container">
            <i class="icon-ellipsis pe-2x icon-icon-rotate-right-right"></i>
          </a>
        </li>
      </ul>
    </div>
    <div class="navbar-container content container-fluid">
      <div id="navbar-mobile" class="collapse navbar-toggleable-sm">
        <ul class="nav navbar-nav">
          <li class="nav-item hidden-sm-down">
            <a class="nav-link nav-menu-main menu-toggle hidden-xs">
              <i class="icon-menu5"> </i>
            </a>
          </li>
          <li class="nav-item hidden-sm-down">
            <a href="#" class="nav-link nav-link-expand">
              <i class="ficon icon-expand2"></i>
            </a>
          </li>
        </ul>
        <ul class="nav navbar-nav float-xs-right">
          <li class="dropdown dropdown-user nav-item">
            <a href="#" data-toggle="dropdown" class="dropdown-toggle nav-link dropdown-user-link">
              <span class="avatar avatar-online">
                <img src="<?= base_url()?>app-assets/images/portrait/small/avatar-s-1.png" alt="avatar">
                <i></i>
              </span>
              <span class="user-name text-capitalize">
                <?= $_SESSION[SESSION_ROLE]?>
              </span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
              <a href="<?=site_url('auth/logout')?>" class="dropdown-item">
                <i class="icon-power3"></i> Logout
              </a>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</nav>