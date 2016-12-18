<?php
$validations = require $this->dir . '/includes/config/fields-validation.php';

 ?>
<div class="container-fluid u-full-width after-title">

	<div class="row row-group">
		<div class="two columns">
			<label for="<?php echo $name.'[name]'; ?>"><strong>Name</strong></label>
		</div>
		<div class="ten columns">
			<input name="<?php echo $name.'[name]'; ?>" id="<?php echo $name.'[name]'; ?>" value="<?php echo @$old['name']; ?>" class="regular-text-fluid the-slug" type="text" placeholder="name field">
			<p class="description">The name of the field. Must be unique among fields</p>
		</div>
	</div>

	<div class="row row-group">
		<div class="two columns">
			<label for="<?php echo $name.'[show_label]'; ?>"><strong>Label</strong></label>
		</div>
		<div class="ten columns">
			<input name="<?php echo $name.'[label]'; ?>" id="<?php echo $name.'[label]'; ?>" value="<?php echo @$old['label']; ?>" class="regular-text-fluid the-label" type="text" placeholder="Label">
			<br>
			<label for="<?php echo $name.'[show_label]'; ?>" class="description"><input name="<?php echo $name.'[show_label]'; ?>" id="<?php echo $name.'[show_label]'; ?>" <?php echo @$old['show_label'] ? 'checked="checked"' : ''; ?> value="1" class="regular-checkbox" type="checkbox"> Toggle <code>Label</code> visibility</label>
		</div>
	</div>

	<div class="row row-group">
		<div class="two columns">
			<label for="<?php echo $name.'[type]'; ?>"><strong>Type</strong></label>
		</div>
		<div class="ten columns">
			<?php
			$field_types = array(
				'text' => 'Text Field',
				'textarea' => 'Textarea Field',
				'email' => 'Email Field',
				'select' => 'Select Field',
				'radio' => 'Radio Field',
				'checkbox' => 'Checkbox Field',
				'color' => 'Color Field',
				'date' => 'Date Field',
				'datetime' => 'DateTime Field',
				'datetime-local' => 'DateTime (Local) Field',
				'month' => 'Month Field',
				'number' => 'Number Field',
				'range' => 'Range Field',
				'search' => 'Search Field',
				'tel' => 'Telephone Field',
				'time' => 'Time Field',
				'url' => 'URL Field',
				'week' => 'Week Field',
				'submit' => 'Submit Button Field',
				'reset' => 'Reset Button Field',
				'button' => 'Button Field',
			);
			FormBuilder::make_select( $name.'[type]', @$old['type'], 'regular-select-fluid regular-select-box', $field_types ); ?>
			<p class="description"><?php _e( 'Some Field Types have specific attributes.', $globals['text-domain'] ); ?></p>
		</div>
	</div>

	<div class="row row-group">
		<div class="two columns">
			<label for="<?php echo $name.'[value]'; ?>"><strong>Value</strong></label>
		</div>
		<div class="ten columns">
			<input name="<?php echo $name.'[value]'; ?>" id="<?php echo $name.'[value]'; ?>" value="<?php echo @$old['value']; ?>" class="regular-text-fluid" type="text" placeholder="Default value">
			<div class="accordion">
				<p class="description"><?php _e( 'The default value, if any.', $globals['text-domain'] ); ?> <a href="#">More Details</a></p>
				<div class="help help-block">
					<p><strong>Special Cases</strong></p>
					<ol>
						<li>
							<h4>Textarea Fields</h4>
							<p>Usually for comment box / long texts.</p>
						</li>
						<li>
							<h4>Select Fields</h4>
							<p>When the field type is <code>Select Field</code>, the value is converted as the options for the select field. Separate each entry using <code>|</code> (pipe), and choose a default using <code>*</code> (asterisk).</p>
							<p><em>E.g.</em> Dog|Cat|Cow|Horse|Lion*|Tiger</p>
						</li>
						<li>
							<h4>Radio Fields</h4>
							<p>Radio fields are unselected by default. If you want the radio field selected by default, append <code>*</code> (asterisk) on the value.</p>
							<p><em>E.g.</em> I choose Charizard*</p>
						</li>
						<li>
							<h4>Checkbox Fields</h4>
							<p>Checkbox fields are unselected by default. If you want the checkbox field selected by default, append <code>*</code> (asterisk) on the value.</p>
							<p><em>E.g.</em> Yes, I have read the Terms and Conditions*</p>
						</li>
					</ol>
				</div>
			</div>
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
							<input name="<?php echo $name."[attributes][$i][name]"; ?>" value="<?php echo @$attributes['name']; ?>" class="regular-text-fluid clonable-increment-name" type="text" placeholder="key">
						</div>
						<div class="four columns">
							<input name="<?php echo $name."[attributes][$i][value]"; ?>" value="<?php echo @$attributes['value']; ?>" class="regular-text-fluid clonable-increment-name" type="text" placeholder="value">
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

	<div class="row row-group m-t-2">
		<h3><?php _e('Validations', $globals['text-domain']); ?></h3>
	</div>

	<div class="row row-group">
		<div class="two columns">
			<label for="<?php echo $name.'[unique]'; ?>"><strong>Unique</strong></label>
		</div>
		<div class="ten columns">
			<label for="<?php echo $name.'[unique]'; ?>" class="description"><input name="<?php echo $name.'[unique]'; ?>" id="<?php echo $name.'[unique]'; ?>" <?php echo @$old['unique'] ? 'checked="checked"' : ''; ?> value="1" class="regular-checkbox" type="checkbox"> Toggle if field value should be unique.</label>
			<p class="description">This validation only runs if the containing form can save to database.</p>
		</div>
	</div>

	<div class="row row-group">
		<div class="two columns">
			<label for="<?php echo $name.'[rules]'; ?>"><strong>Rules</strong></label>
		</div>
		<div class="ten columns">
			<div class="clonable-block sortables" data-toggle="cloner">

				<?php if ( ! isset( $old['rules'] ) && empty( $old['rules'] ) ) : ?>

					<div class="clonable">
						<div class="row row-group">
							<div class="twelve columns">
								<span class="regular-text-addon" type="text"><?php _e('Rule ', $globals['text-domain']) ?><span class="clonable-increment-html">1</span></span>
							</div>
						</div>
						<div class="row">
							<div class="three columns">
								<?php FormBuilder::make_select( $name.'[rules][0][name]', null, 'regular-select-fluid regular-select-box clonable-increment-name', $validations ); ?>
							</div>
							<div class="two columns">
								<input name="<?php echo $name.'[rules][0][value]'; ?>" value="" class="regular-text-fluid clonable-increment-name" type="text" placeholder="value">
							</div>
							<div class="five columns">
								<input name="<?php echo $name.'[rules][0][message]'; ?>" value="" class="regular-text-fluid clonable-increment-name" type="text" placeholder="Error message">
								<p class="description">The error message to display to user if the <code>value</code> is not met.</p>
							</div>
							<div class="two columns">
								<button type="button" class="button button-small button-default clonable-button-close">Remove</button>
							</div>
						</div>
					</div>

				<?php else:

					foreach ( $old['rules'] as $i => $rules ) { ?>

						<div class="clonable">
							<div class="row row-group">
								<div class="twelve columns">
									<span class="regular-text-addon" type="text">Rule <span id="span" class="clonable-increment-html"><?php echo $i+1 ?></span></span>
								</div>
							</div>
							<div class="row">
								<div class="three columns">
									<?php FormBuilder::make_select( $name."[rules][$i][name]", @$rules['name'], 'regular-select-fluid regular-select-box clonable-increment-name', $validations ); ?>
								</div>
								<div class="two columns">
									<input name="<?php echo $name."[rules][$i][value]"; ?>" value="<?php echo @$rules['value']; ?>" class="regular-text-fluid clonable-increment-name" type="text" placeholder="value">
								</div>
								<div class="five columns">
									<input name="<?php echo $name."[rules][$i][message]"; ?>" value="<?php echo @$rules['message']; ?>" class="regular-text-fluid clonable-increment-name" type="text" placeholder="Error message">
									<p class="description">The error message to display to user if the <code>value</code> is not met.</p>
								</div>
								<div class="two columns">
									<button type="button" class="button button-small button-default clonable-button-close">Remove</button>
								</div>
							</div>
						</div>

					<?php } ?>

				<?php endif; ?>

				<button class="button button-small button-default clonable-button-add" type="button">Add Rule</button>
			</div>
		</div>
	</div>
</div>