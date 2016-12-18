<div class="container-fluid u-full-width after-title wp-mailman-form-manager">

	<div class="row row-group">
		<h3 class="group-title"><?php _e( 'Form', $globals['text-domain'] ); ?></h3>
	</div>

	<div class="row row-group">
		<div class="two columns">
			<label for="<?php echo $name.'[method]'; ?>"><strong>Method</strong></label>
		</div>

		<div class="ten columns">
			<select name="<?php echo $name.'[method]'; ?>" id="<?php echo $name.'[method]'; ?>" class="regular-select-fluid selectizable">
				<option value="POST">POST</option>
				<option value="GET">GET</option>
			</select>
			<p class="description">Usually <code>POST</code>.</p>
		</div>
	</div>

	<div class="row row-group">
		<div class="two columns">
			<label for="<?php echo $name.'[fields]'; ?>"><strong>Fields</strong></label>
		</div>
		<div class="ten columns clonable-block sortables" data-toggle="cloner">

			<?php if ( ! isset( $old['fields'] ) && empty( $old['fields'] ) ) : ?>

				<div class="panelbox clonable row row-group sortable">
					<div class="row row-group">
						<p class="regular-text-addon" type="text">Field <span class="clonable-increment-html">1</span></p>
					</div>
					<div class="row">
						<div class="ten columns">
							<div class="form-group">
								<?php
								@wp_dropdown_pages(array(
									'name' => $name."[fields][0][name]",
									'show_option_none' => __( '--Select Fields--' ),
									'class' => 'regular-select-fluid clonable-increment-name regular-select-box',
									'post_type' => 'field',
								)); ?>
							</div>
						</div>
						<div class="two columns">
							<button type="button" class="button button-small button-default clonable-button-close">Remove</button>
						</div>
					</div>
				</div>

			<?php else:
				$_i = 0;
				foreach ( $old['fields'] as $i => $fields ) { ?>

					<div class="panelbox clonable row row-group sortable">
						<div class="row row-group">
							<p class="regular-text-addon" type="text">Field <span class="clonable-increment-html"><?php echo ++$_i ?></span> <span class="text-muted">| <input type="text" class="text-muted" readonly disabled value="<?php echo get_post( $fields['name'] )->post_title ?>"></span></p>
						</div>
						<div class="row">
							<div class="ten columns">
								<div class="form-group">
									<?php
									@wp_dropdown_pages(array(
										'name' => $name."[fields][$i][name]",
										'show_option_none' => __( '--Select Fields--' ),
										'selected' => $fields['name'],
										'class' => 'regular-select-fluid clonable-increment-name regular-select-box',
										'post_type' => 'field',
									)); ?>
								</div>
							</div>
							<div class="two columns">
								<button type="button" class="button button-small button-default clonable-button-close">Remove</button>
							</div>
						</div>
					</div>

				<?php } ?>

			<?php endif; ?>

			<div class="clonable-footer">
				<button class="button button-small button-default clonable-button-add" type="button">Add Field</button>
			</div>

		</div>
	</div>

	<div class="row row-group">
		<div class="two columns">
			<label for="<?php echo $name.'[submit_button][label]'; ?>"><strong>Submit Button</strong></label>
		</div>

		<div class="panelbox ten columns">
			<div class="form-group">
				<div class="row">
					<div class="ten columns">
						<label for="<?php echo $name.'[submit_button][label]'; ?>"><strong><?php _e( 'Label', $globals['text-domain'] ) ?></strong></label>
						<input name="<?php echo $name.'[submit_button][label]'; ?>" id="<?php echo $name.'[submit_button][label]'; ?>" class="regular-text-fluid" type="text" value="<?php echo @$old['submit_button']['label'] ?>">
					</div>
				</div>
			</div>
			<div class="form-group clonable-block" data-toggle="cloner">
				<div class="row">
					<div class="five columns">
						<label for="<?php echo $name.'[submit_button][attributes][0][name]' ?>"><strong><?php _e( 'Attribute Name', $globals['text-domain'] ); ?></strong></label>
					</div>
					<div class="five columns">
						<label for="<?php echo $name.'[submit_button][attributes][0][value]' ?>"><strong><?php _e( 'Attribute Value', $globals['text-domain'] ); ?></strong></label>
					</div>
				</div>

				<?php if ( ! isset( $old['submit_button']['attributes'] ) && ! empty( $old['submit_button']['attributes'] ) ) : ?>
					<div class="row row-group clonable">
						<div class="five columns">
							<input name="<?php echo $name.'[submit_button][attributes][0][name]'; ?>" id="<?php echo $name.'[submit_button][attributes][0][name]'; ?>" class="regular-text-fluid clonable-increment-name clonable-increment-id" type="text">
						</div>
						<div class="five columns">
							<input name="<?php echo $name.'[submit_button][attributes][0][value]'; ?>" id="<?php echo $name.'[submit_button][attributes][0][value]'; ?>" class="regular-text-fluid clonable-increment-name clonable-increment-id" type="text">
						</div>
						<div class="two columns">
							<button class="button button-default pull-right clonable-button-close" type="button"><?php _e( 'Remove', $globals['text-domain'] ); ?></button>
						</div>
					</div>
				<?php else : ?>
					<?php $i = 0; foreach ( $old['submit_button']['attributes'] as $i => $attribute ) : ?>
						<div class="row row-group clonable">
							<div class="five columns">
								<input name="<?php echo $name."[submit_button][attributes][$i][name]"; ?>" id="<?php echo $name."[submit_button][attributes][$i][name]"; ?>" class="regular-text-fluid clonable-increment-name clonable-increment-id" type="text" value="<?php echo @$attribute['name'] ?>">
							</div>
							<div class="five columns">
								<input name="<?php echo $name."[submit_button][attributes][$i][value]"; ?>" id="<?php echo $name."[submit_button][attributes][$i][value]"; ?>" class="regular-text-fluid clonable-increment-name clonable-increment-id" type="text" value="<?php echo @$attribute['value'] ?>">
							</div>
							<div class="two columns">
								<button class="button button-default pull-right clonable-button-close" type="button"><?php _e( 'Remove', $globals['text-domain'] ); ?></button>
							</div>
						</div>
					<?php $i++; endforeach; ?>
				<?php endif; ?>

				<div class="row row-group">
					<div class="twelve columns">
						<button type="button" class="button button-default clonable-button-add"><?php _e( 'Add Attribute', $globals['text-domain'] ) ?></button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row row-group">
		<div class="two columns">
			<label for="<?php echo $name.'[display]'; ?>"><strong>Extra Buttons</strong></label>
		</div>

		<div class="ten columns">
			<p class="description"><?php _e( 'Used for other types of buttons, e.g. Reset, Download, Help, etc.', $globals['text-domain'] ) ?></p>

			<div class="clonable-block sortables" data-toggle="cloner">
				<div class="row row-group clonable panelbox sortable">
					<div class="ten columns">
						<div class="row row-group">
							<div class="twelve columns">
								<label for=""><strong><?php _e( 'Button 1', $globals['text-domain'] ); ?></strong></label>
							</div>
						</div>

						<div class="row">
							<div class="twelve columns">
								<label for=""><strong><?php _e( 'Button Type', $globals['text-domain'] ); ?></strong></label>
							</div>
						</div>
						<div class="row row-group">
							<div class="twelve columns">
								<select name="" id="" class="regular-select-fluid regular-select-box">
								<option value="reset">Reset Button</option>
									<option value="link">Link Button</option>
									<option value="button">Button Field</option>
									<option value="input">Input Button</option>
								</select>
							</div>
						</div>

						<div class="row">
							<div class="five columns">
								<label for=""><?php _e( 'Label', $globals['text-domain'] ); ?></label>
							</div>
							<div class="five columns">

							</div>
						</div>

					</div>
					<div class="two columns">
						close button
					</div>
				</div>

			</div>

		</div>
	</div>

	<div class="row row-group">
		<h3 class="group-title"><?php _e('Display', $globals['text-domain']); ?></h3>
	</div>

	<div class="row row-group">
		<div class="two columns">
			<label for="<?php echo $name.'[display]'; ?>"><strong>Page</strong></label>
		</div>

		<div class="ten columns">
			<?php @wp_dropdown_pages(array(
				'name' => $name."[display_to_page]",
				'show_option_none' => __('--Use Shortcode--'),
				'selected' => $old['display_to_page'],
				'class' => 'regular-select-fluid regular-select-box',
				// 'post_type' => $name,
			)); ?>
			<p class="description"><?php _e('or use the shortcode', $globals['text-domain']) ?> <code><strong><?php echo "[$name id='{$post->ID}']" ?></strong></code> and paste to any Page, Post, and/or any supported Custom Post Types.</p>
			<input readonly name="<?php echo $name.'[shortcode]'; ?>" id="<?php echo $name.'[shortcode]'; ?>" class="regular-text-fluid" type="text" value="<?php echo "[$name id='{$post->ID}']" ?>">
		</div>
	</div>

	<div class="row row-group">
		<div class="two columns">
			<label for="<?php echo $name.'[message][form_template]'; ?>"><strong>Form Template</strong></label>
		</div>

		<div class="ten columns">
			<?php
			@wp_dropdown_pages(array(
				'name' => $name."[display_template]",
				'show_option_none' => __('-- Use Default --'),
				'selected' => $old['display_template'],
				'class' => 'regular-text-fluid regular-select-box',
				'post_type' => 'form-template',
			)); ?>
			<p class="description">The structure of the form</p>
		</div>
	</div>

	<div class="row row-group">
		<h3 class="group-title"><?php _e('Messages & Notifications', $globals['text-domain']); ?></h3>
	</div>

	<div class="row row-group">
		<div class="two columns">
			<label for="<?php echo $name.'[message][template]'; ?>"><strong>Response</strong></label>
		</div>

		<div class="ten columns">
			<div class="form-group">
				<div class="form-group">
					<label for="<?php echo $name.'[sending][user][true]'; ?>">
						<input
							type="radio"
							class="regular-radio"
							name="<?php echo $name.'[sending][user]'; ?>"
							id="<?php echo $name.'[sending][user][true]'; ?>"
							value="true"
							<?php echo FormBuilder::check_if_checked(true, @$old['sending']['user']); ?>> <?php _e( '<strong>Send to User</strong>. Send a response email to the user upon form submission.', $globals['text-domain'] ) ?>
					</label>
					<div class="row">
						<div class="ten columns offset-by-one">
							<div class="form-group">
								<div>
									<label for="<?php echo $name.'[message][template]'; ?>"><strong>Template</strong></label>
								</div>
								<?php
								@wp_dropdown_pages(array(
									'name' => $name."[message][template]",
									'show_option_none' => __('-- Use Default --'),
									'selected' => $old['message']['template'],
									'class' => 'regular-select regular-select-box',
									'post_type' => 'message-template',
								)); ?>
								<p class="description"><?php _e('The Message Template the user will receive in response upon form submission.') ?></p>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="<?php echo $name.'[sending][user][false]'; ?>">
						<input
							type="radio"
							class="regular-radio"
							name="<?php echo $name.'[sending][user]'; ?>"
							id="<?php echo $name.'[sending][user][false]'; ?>"
							value="false"
							<?php echo FormBuilder::check_if_checked(false, @$old['sending']['user']); ?>> <?php _e( '<strong>Do not send to User</strong>. Do not send a response email to the user upon form submission.', $globals['text-domain'] ) ?>
					</label>
				</div>
			</div>

		</div>
	</div>

	<div class="row row-group">
		<div class="two columns">
			<label for="<?php echo $name.'[sending][admin][true]'; ?>"><strong>Admin Notification</strong></label>
		</div>
		<div class="ten columns">

			<div class="form-group">
				<label for="<?php echo $name.'[sending][admin][true]'; ?>">
					<input
						type="radio"
						class="regular-radio"
						name="<?php echo $name.'[sending][admin]'; ?>"
						id="<?php echo $name.'[sending][admin][true]'; ?>"
						value="true"
						<?php echo FormBuilder::check_if_checked(true, @$old['sending']['admin']); ?>> <?php _e( '<strong>Notify Admin</strong>. Send a notification email to the admin on every new form submission.', $globals['text-domain'] ) ?>
				</label>
			</div>
			<div class="form-group">
				<label for="<?php echo $name.'[sending][admin][false]'; ?>">
					<input
						type="radio"
						class="regular-radio"
						name="<?php echo $name.'[sending][admin]'; ?>"
						id="<?php echo $name.'[sending][admin][false]'; ?>"
						value="false"
						<?php echo FormBuilder::check_if_checked(false, @$old['sending']['admin']); ?>> <?php _e( '<strong>Do not notify Admin</strong>. Do not send a notification email to the admin on every new form submission.', $globals['text-domain'] ) ?>
				</label>
			</div>
		</div>
	</div>

	<div class="row row-group">
		<div class="two columns">
			<label for="<?php echo $name.'[message][success]'; ?>"><strong>Success</strong></label>
		</div>

		<div class="ten columns">
			<p class="description">The message to show on a successful submission.</p>
			<!-- <input type="text" class="regular-text-fluid" id="<?php echo $name.'[message][success]'; ?>" name="<?php echo $name.'[message][success]'; ?>"> -->
		</div>
	</div>

	<div class="row row-group">
		<h3 class="group-title"><?php _e('Global Overrides', $globals['text-domain']); ?></h3>
		<p class="description">The options below will override their counterpart option in the <a href="<?php echo admin_url("edit.php?post_type=form&page=$this->pluginname"); ?>">Settings</a> page.</p>
	</div>

	<div class="row row-group m-t-2">
		<div class="two columns">
			<label for="<?php echo $name.'[save_to_db]'; ?>"><strong>Saving</strong></label>
		</div>
		<div class="ten columns">
			<div class="form-group">
				<label for="<?php echo $name.'[save_to_db][true]'; ?>" class="description">
					<input
						name="<?php echo $name.'[save_to_db]'; ?>"
						id="<?php echo $name.'[save_to_db][true]'; ?>"
						<?php echo isset( $old['save_to_db'] ) ? $old['save_to_db'] : 'checked'; ?>
						<?php echo FormBuilder::check_if_checked(true, @$old['save_to_db']); ?>
						value="true" class="regular-radio"
						type="radio"> Save all submissions from this Form.
				</label>
			</div>
			<div class="form-group">
				<label for="<?php echo $name.'[save_to_db][false]'; ?>" class="description">
					<input
						name="<?php echo $name.'[save_to_db]'; ?>"
						id="<?php echo $name.'[save_to_db][false]'; ?>"
						<?php echo isset( $old['save_to_db'] ) ? $old['save_to_db'] : 'checked'; ?>
						<?php echo FormBuilder::check_if_checked(false, @$old['save_to_db']); ?>
						value="false" class="regular-radio"
						type="radio"> Do not save submissions from this Form.
				</label>
				<p class="description">Note: all previous submissions while this option is selected are not saved and are unrecoverable from the database. View <a href="<?php echo admin_url('edit.php?post_type=message'); ?>">Messages</a>.</p>
			</div>
		</div>
	</div>

</div>