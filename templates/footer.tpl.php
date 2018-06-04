<footer class="main-footer">
<div class="container">
    <div class="row">
	   	<div class="col-sm-6">
  			<section class="block block-block clearfix">
	  			<?php if ($logo) : ?>
         		<img class="image-responsive" src="<?php print $logo; ?>" alt="<?php print t('Argentina.gob.ar - Presidencia de la Nación'); ?>" />
      		<?php endif; ?>
    			<p class="text-muted small">
    				<?php print t("Los contenidos de Argentina.gob.ar están licenciados bajo ") . l("Creative Commons Reconocimiento 2.5 Argentina License","https://creativecommons.org/licenses/by/2.5/ar/", array('attributes' => array('target'=>'_blank'))); ?>
    			</p>
        </section>
			<?php if (!empty($page['footer1'])): ?>
		    <?php print render($page['footer1']); ?>
			<?php endif; ?>
		</div>
    <div class="col-md-3 col-sm-6">
    <?php if (!empty($page['footer2'])): ?>
      <?php print render($page['footer2']); ?>
    <?php endif; ?>
    </div>
    	<div class="col-md-3 col-sm-6">
			<?php if (!empty($page['footer3'])): ?>
			    <?php print render($page['footer3']); ?>
			<?php endif; ?>
		</div>
	</div>
</div>
</footer>