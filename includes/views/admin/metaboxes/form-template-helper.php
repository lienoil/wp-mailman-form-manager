<div>
	<?php _e( 'Need Help?', $globals['text-domain'] ); ?>
	<p>ADD INSTRUCTIONS HERE e.g. </p>

	<?php
ob_start(); ?>
&lt;loop&gt;
	&lt;div class=col-sm-3&gt;%label%&lt;/div&gt;&lt;div class=col-sm-9&gt;%field% %error%&lt;/div&gt;
&lt;/loop&gt;
<?php

	$content = html_entity_decode( ob_get_clean() );

	echo $content;

	$pattern = '#<loop[^>]*>(.*?)</loop>#s';
	$subject = $content;
	// $matches = array();
	$matches = preg_match( $pattern, $subject );

    echo $matches;

	?>
</div>