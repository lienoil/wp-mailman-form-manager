<div class="container u-full-width after-title">
	<div class="row row-group">
		<div class="two columns">
			<label for="<?php echo $name.'[name]'; ?>"><strong>Name</strong></label>
		</div>
		<div class="ten columns">
			<input name="<?php echo $name.'[name]'; ?>" id="<?php echo $name.'[name]'; ?>" value="<?php echo @$old['name']; ?>" class="regular-text the-slug" type="text" placeholder="name field">
			<p class="description">The name of the field. Must be unique among fields</p>
		</div>
	</div>

	<div class="row row-group">
		<div class="two columns">
			<label for="<?php echo $name.'[fields]'; ?>"><strong>Fields</strong></label>
		</div>
		<div class="ten columns clonable-block" data-toggle="cloner">

			<?php if ( ! isset( $old['fields'] ) && empty( $old['fields'] ) ) : ?>

				<div class="clonable row row-group">
					<div class="three columns">
						<span class="regular-text-addon clonable-increment-html" type="text">Field #1</span>
					</div>
					<div class="seven columns">
						<?php
						$options = get_posts( array( 'post_type' => 'field' ) );
						$options = convert_post_to_array( $options );
						make_select($name."[fields][0][name]", null, 'clonable-increment-name', $options); ?>
					</div>
					<div class="two columns">
						<button type="button" class="button button-small button-default clonable-button-close">Remove</button>
					</div>
				</div>

			<?php else:

				foreach ( $old['fields'] as $i => $fields ) { ?>

					<div class="clonable row row-group">
						<div class="three columns">
							<span class="regular-text-addon clonable-increment-html" type="text">Field #<?php echo $i + 1; ?></span>
						</div>
						<div class="seven columns">
							<?php
							$options = get_posts( array( 'post_type' => 'field' ) );
							$options = convert_post_to_array( $options );
							make_select($name."[fields][$i][name]", $fields['name'], 'clonable-increment-name', $options); ?>
						</div>
						<div class="two columns">
							<button type="button" class="button button-small button-default clonable-button-close">Remove</button>
						</div>
					</div>

				<?php } ?>

			<?php endif; ?>

			<button class="button button-small button-default clonable-button-add" type="button">Add Field</button>

		</div>
	</div>

	<div class="row row-group">
		<h3><?php _e('Display', $global['text-domain']); ?></h3>
	</div>

	<div class="row row-group">
		<div class="two columns">
			<label for="<?php echo $name.'[display]'; ?>"><strong>Display</strong></label>
		</div>

		<div class="ten columns">
			<?php @wp_dropdown_pages(array(
				'show_option_none' => __( '--Use Shortcode--' ),
				'selected' => $old['display'],
				// 'post_type' => $name,
			)); ?>
			<p class="description">or use a shortcode</p>
			<input readonly name="<?php echo $name.'[display]'; ?>" id="<?php echo $name.'[display]'; ?>" class="regular-text the-slug" type="text" value="[<?php echo $name; ?> id='<?php echo $post->ID; ?>']">
		</div>
	</div>

</div>