<?php if(!empty($content['top'])): ?>
<section class="jumbotron">
  <div class="jumbotron_body">
    <div class="container">
      <div><?php print $content['top']; ?></div>
    </div>
  </div>
  <div class="overlay"></div>
</section>
<?php endif; ?>
<?php if(!empty($content['middle1'])): ?>
<section>
  <div class="container">
   <div class="row">
      <?php print $content['middle1']; ?>
  </div>
  </div>
</section>
<?php endif; ?>
<?php if(!empty($content['middle2'])): ?>
<section>
  <div class="container">
   <div class="row">
      <?php print $content['middle2']; ?>
  </div>
  </div>
</section>
<?php endif; ?>
<?php if(!empty($content['middle3'])): ?>
<section>
  <div class="container">
   <div class="row">
      <?php print $content['middle3']; ?>
  </div>
  </div>
</section>
<?php endif; ?>
<?php if(!empty($content['bottom'])): ?>
<section>
  <div class="container">
   <div class="row">
      <?php print $content['bottom']; ?>
  </div>
  </div>
</section>
<?php endif; ?>