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
			<label for="<?php echo $name.'[show_label]'; ?>"><strong>Label</strong></label>
		</div>
		<div class="ten columns">
			<input name="<?php echo $name.'[label]'; ?>" id="<?php echo $name.'[label]'; ?>" value="<?php echo @$old['label']; ?>" class="regular-text" type="text" placeholder="Label">
			<br>
			<label for="<?php echo $name.'[show_label]'; ?>" class="description"><input name="<?php echo $name.'[show_label]'; ?>" id="<?php echo $name.'[show_label]'; ?>" <?php echo @$old['show_label'] ? 'checked="checked"' : ''; ?> value="1" class="regular-checkbox" type="checkbox"> Toggle <code>Label</code> visibility</label>
		</div>
	</div>

	<div class="row row-group">
		<div class="two columns">
			<label for="<?php echo $name.'[attributes]'; ?>"><strong>Attributes</strong></label>
		</div>
		<div class="ten columns clonable-block" data-toggle="cloner">

			<?php if ( ! isset( $old['attributes'] ) && empty( $old['attributes'] ) ) : ?>

				<div class="clonable row row-group">
					<div class="three columns">
						<span class="regular-text-addon clonable-increment-html" type="text">Attribute Name #1</span>
					</div>
					<div class="three columns">
						<input name="<?php echo $name.'[attributes][0][name]'; ?>" class="regular-text-fluid clonable-increment-name" type="text" placeholder="key">
					</div>
					<div class="four columns">
						<input name="<?php echo $name.'[attributes][0][value]'; ?>" class="regular-text-fluid clonable-increment-name" type="text" placeholder="value">
					</div>
					<div class="two columns">
						<button type="button" class="button button-small button-default clonable-button-close">Remove</button>
					</div>
				</div>

			<?php else:

				foreach ( $old['attributes'] as $i => $attributes ) { ?>

					<div class="clonable row row-group">
						<div class="three columns">
							<span class="regular-text-addon clonable-increment-html" type="text">Attribute Name #<?php echo $i+1 ?></span>
						</div>
						<div class="three columns">
							<input name="<?php echo $name."[attributes][$i][name]"; ?>" value="<?php echo @$attributes['name']; ?>" class="regular-text-fluid" type="text" placeholder="key">
						</div>
						<div class="four columns">
							<input name="<?php echo $name."[attributes][$i][value]"; ?>" value="<?php echo @$attributes['value']; ?>" class="regular-text-fluid" type="text" placeholder="value">
						</div>
						<div class="two columns">
							<button type="button" class="button button-small button-default clonable-button-close">Remove</button>
						</div>
					</div>

				<?php } ?>

			<?php endif; ?>

			<button class="button button-small button-default clonable-button-add" type="button">Add Attribute</button>
		</div>
	</div>

	<div class="row row-group">
		<h3><?php _e('Validations', $global['text-domain']); ?></h3>
	</div>

	<div class="row row-group">
		<div class="two columns">
			<label for="<?php echo $name.'[required]'; ?>"><strong>Required</strong></label>
		</div>
		<div class="ten columns">
			<label for="<?php echo $name.'[required]'; ?>" class="description"><input name="<?php echo $name.'[required]'; ?>" id="<?php echo $name.'[required]'; ?>" <?php echo @$old['required'] ? 'checked="checked"' : ''; ?> value="1" class="regular-checkbox" type="checkbox"> is required</label>
		</div>
	</div>

	<div class="row row-group">
		<div class="two columns">
			<label for="<?php echo $name.'[rules]'; ?>"><strong>Rules</strong></label>
		</div>
		<div class="ten columns">
			<div class="ten columns clonable-block" data-toggle="cloner">

				<?php if ( ! isset( $old['rules'] ) && empty( $old['rules'] ) ) : ?>

					<div class="clonable row row-group">
						<div class="two columns">
							<span class="regular-text-addon clonable-increment-html" type="text"><?php _e('Rule #1', $global['text-domain']) ?></span>
						</div>
						<div class="three columns">
							<?php make_select( $name.'[rules][0][name]', null, 'clonable-increment-name', $validations ); ?>
						</div>
						<div class="five columns">
							<input name="<?php echo $name.'[rules][0][value]'; ?>" value="" class="regular-text-fluid clonable-increment-name" type="text" placeholder="value">
						</div>
						<div class="two columns">
							<button type="button" class="button button-small button-default clonable-button-close">Remove</button>
						</div>
					</div>

				<?php else:

					foreach ( $old['rules'] as $i => $rules ) { ?>

						<div class="clonable row row-group">
							<div class="two columns">
								<span class="regular-text-addon clonable-increment-html" type="text">Rule #<?php echo $i+1 ?></span>
							</div>
							<div class="three columns">
								<?php make_select( $name."[rules][$i][name]", @$rules['name'], 'clonable-increment-name' ); ?>
							</div>
							<div class="five columns">
								<input name="<?php echo $name."[rules][$i][value]"; ?>" value="<?php echo @$rules['value']; ?>" class="regular-text-fluid clonable-increment-name" type="text" placeholder="value">
							</div>
							<div class="two columns">
								<button type="button" class="button button-small button-default clonable-button-close">Remove</button>
							</div>
						</div>

					<?php } ?>

				<?php endif; ?>

				<button class="button button-small button-default clonable-button-add" type="button">Add Rule</button>
			</div>
		</div>
	</div>
</div>