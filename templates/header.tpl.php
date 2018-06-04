<header>
  <nav class="navbar navbar-top navbar-default" role="navigation">
    <div class="container">
      <div>
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#main-navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/">
            <?php if ($logo) : ?>
            <img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" width="274" height="50" />
            <?php endif; ?>
            <?php if (!empty($site_name)): ?>
            <h1 class="sr-only"><?php print $site_name; ?> <small>Poncho</small></h1>
            <?php endif; ?>
          </a>
        </div>
        <div class="collapse navbar-collapse" id="main-navbar-collapse">
          <div class="navbar-right">
            <?php if (!empty($page['header2'])): ?>
              <?php print render($page['header2']); ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </nav>
</header>