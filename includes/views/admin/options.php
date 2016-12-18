<div class="wrap">
	<h1>Form Settings</h1>
	<p class="description"><?php _e( 'Make sure to <strong>triple check</strong> your emails.', $globals['text-domain'] ) ?></p>
	<?php settings_errors(); ?>
	<form action="options.php" method="POST" autocomplete="off" class="mailman-form-settings">

		<?php
		settings_fields( $this->pluginname );
        do_settings_sections( $this->pluginname ); ?>

        <div class="row row-group m-t-2">
			<div class="two columns">
				<label for="<?php echo $name.'[sending][user]'; ?>"><strong>Notifications</strong></label>
			</div>
			<div class="ten columns">
				<div class="form-group">
					<label for="<?php echo $name.'[sending][user]'; ?>">
						<input
							type="checkbox"
							class="regular-checkbox"
							name="<?php echo $name.'[sending][user]'; ?>"
							id="<?php echo $name.'[sending][user]'; ?>"
							value="true"
							<?php echo FormBuilder::check_if_checked(true, @$old['sending']['user']); ?>> <?php _e( '<strong>Send to User</strong>. Send a response email to the user upon form submission.', $globals['text-domain'] ) ?>
						<span class="description"><?php _e('You may specify the Message Template per form.', $globals['text-domain']) ?></span>
					</label>
				</div>
				<div class="form-group">
					<label for="<?php echo $name.'[sending][admin]'; ?>">
						<input
							type="checkbox"
							class="regular-checkbox"
							name="<?php echo $name.'[sending][admin]'; ?>"
							id="<?php echo $name.'[sending][admin]'; ?>"
							value="true"
							<?php echo FormBuilder::check_if_checked(true, @$old['sending']['admin']); ?>> <?php _e( '<strong>Send to Admin</strong>. Send a notification email to the admin on every new form submission.', $globals['text-domain'] ) ?>
					</label>
				</div>
			</div>
		</div>

		<div class="row row-group m-t-2">
			<div class="two columns">
				<label for="<?php echo $name.'[save_to_db]'; ?>"><strong>Saving</strong></label>
			</div>
			<div class="ten columns">
				<label for="<?php echo $name.'[save_to_db]'; ?>" class="description">
					<input
						name="<?php echo $name.'[save_to_db]'; ?>"
						id="<?php echo $name.'[save_to_db]'; ?>"
						<?php echo isset( $old['save_to_db'] ) ? $old['save_to_db'] : 'checked'; ?>
						<?php echo FormBuilder::check_if_checked(true, @$old['save_to_db']); ?>
						value="true" class="regular-checkbox" type="checkbox"> Save all Forms submissions.
				</label>
				<p class="description">Note: all previous submissions while this option is unchecked are not saved and are unrecoverable from the database. View <a href="<?php echo admin_url('edit.php?post_type=message'); ?>">Messages</a>.</p>
			</div>
		</div>

		<div class="row row-group m-t-2">
			<div class="two columns">
				<label for="<?php echo $name.'[mail][from][email]'; ?>"><strong>Emails</strong></label>
			</div>
			<div class="ten columns">

				<div class="form-group">
					<div class="row">
						<div class="five columns">
							<label><strong>From Email</strong></label>
						</div>
						<div class="five columns">
							<label><strong>From Name</strong></label>
						</div>
					</div>
					<div class="row row-group">
						<div class="five columns">
							<input name="<?php echo $name.'[mail][from][email]'; ?>" id="<?php echo $name.'[mail][from][email]'; ?>" class="regular-text" type="text" value="<?php echo isset( $old['mail']['from']['email'] ) ? $old['mail']['from']['email'] : get_bloginfo('admin_email'); ?>">
							<p class="description"><?php _e('defaults to', $globals['text-domain']) ?> <span class="badge"><?php bloginfo('admin_email'); ?></span></p>
						</div>
						<div class="five columns">
							<input name="<?php echo $name.'[mail][from][name]'; ?>" id="<?php echo $name.'[mail][from][name]'; ?>" class="regular-text" type="text" value="<?php echo isset( $old['mail']['from']['name'] ) ? $old['mail']['from']['name'] : get_bloginfo('name'); ?>">
							<p class="description"><?php _e('defaults to', $globals['text-domain']) ?> <span class="badge"><?php bloginfo('name'); ?></span></p>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="row">
						<label for="<?php echo $name.'[mail][to][0]'; ?>"><strong>Recipients</strong></label>
					</div>
					<div class="clonable-block" data-toggle="cloner">
						<?php if ( ! isset( $old['mail']['to'] ) && empty( $old['mail']['to'] ) ) : ?>

							<div class="row row-group clonable">
								<div class="five columns">

									<input name="<?php echo $name.'[mail][to][0]'; ?>" id="<?php echo $name.'[mail][to][0]'; ?>" class="regular-text clonable-increment-name clonable-increment-id" type="email">
									<p class="description"><?php _e('defaults to', $globals['text-domain']) ?> <span class="badge"><?php bloginfo('admin_email'); ?></span></p>

								</div>
								<div class="two columns">
									<button class="button button-default button-small clonable-button-close" type="button">Remove</button>
								</div>
							</div>

						<?php else : ?>

							<?php foreach ( $old['mail']['to'] as $i => $to ) : ?>

								<div class="row row-group clonable">
									<div class="five columns">
										<input name="<?php echo $name."[mail][to][$i]"; ?>" id="<?php echo $name."[mail][to][$i]"; ?>" class="regular-text clonable-increment-name clonable-increment-id" type="email" value="<?php echo @$to; ?>">
									</div>
									<div class="two columns">
										<button class="button button-default button-small clonable-button-close" type="button">Remove</button>
									</div>
								</div>

							<?php endforeach; ?>

						<?php endif; ?>

						<button class="button button-default button-small clonable-button-add" type="button">Add Recipient</button>
					</div>
				</div>

				<div class="form-group">
					<div class="row">
						<label for="<?php echo $name.'[mail][cc][0]'; ?>"><strong>Cc</strong></label>
					</div>
					<div class="clonable-block" data-toggle="cloner">
						<?php if ( ! isset( $old['mail']['cc'] ) && empty( $old['mail']['cc'] ) ) : ?>

							<div class="row row-group clonable">
								<div class="five columns">

									<input name="<?php echo $name.'[mail][cc][0]'; ?>" id="<?php echo $name.'[mail][cc][0]'; ?>" class="regular-text clonable-increment-name clonable-increment-id" type="email">

								</div>
								<div class="two columns">
									<button class="button button-default button-small clonable-button-close" type="button">Remove</button>
								</div>
							</div>

						<?php else : ?>

							<?php foreach ($old['mail']['cc'] as $i => $cc) : ?>

								<div class="row row-group clonable">
									<div class="five columns">
										<input name="<?php echo $name."[mail][cc][$i]"; ?>" id="<?php echo $name."[mail][cc][$i]"; ?>" class="regular-text clonable-increment-name clonable-increment-id" type="email" value="<?php echo @$cc; ?>">
									</div>
									<div class="two columns">
										<button class="button button-default button-small clonable-button-close" type="button">Remove</button>
									</div>
								</div>

							<?php endforeach; ?>

						<?php endif; ?>

						<button class="button button-default button-small clonable-button-add" type="button">Add Cc</button>
					</div>
				</div>

				<div class="form-group">
					<div class="row">
						<label for="<?php echo $name.'[mail][bcc][0]'; ?>"><strong>Bcc</strong></label>
					</div>
					<div class="clonable-block" data-toggle="cloner">
						<?php if ( ! isset( $old['mail']['bcc'] ) && empty( $old['mail']['bcc'] ) ) : ?>

							<div class="row row-group clonable">
								<div class="five columns">

									<input name="<?php echo $name.'[mail][bcc][0]'; ?>" id="<?php echo $name.'[mail][bcc][0]'; ?>" class="regular-text clonable-increment-name clonable-increment-id" type="email">

								</div>
								<div class="two columns">
									<button class="button button-default button-small clonable-button-close" type="button">Remove</button>
								</div>
							</div>

						<?php else : ?>

							<?php foreach ($old['mail']['bcc'] as $i => $bcc) : ?>

								<div class="row row-group clonable">
									<div class="five columns">
										<input name="<?php echo $name."[mail][bcc][$i]"; ?>" id="<?php echo $name."[mail][bcc][$i]"; ?>" class="regular-text clonable-increment-name clonable-increment-id" type="email" value="<?php echo @$bcc; ?>">
									</div>
									<div class="two columns">
										<button class="button button-default button-small clonable-button-close" type="button">Remove</button>
									</div>
								</div>

							<?php endforeach; ?>

						<?php endif; ?>

						<button class="button button-default button-small clonable-button-add" type="button">Add Bcc</button>
					</div>
				</div>

			</div>
		</div>

		<div class="row row-group m-t-2">
			<div class="two columns">
				<label for="<?php echo $name.'[protocol]'; ?>"><strong>Protocol</strong></label>
			</div>
			<div class="ten columns">
				<div class="radio-group">
					<label for="<?php echo $name.'[protocol]'; ?>-smtp-only">
		                <input
		                	id="<?php echo $name.'[protocol]'; ?>-smtp-only"
		                	name="<?php echo $name.'[protocol]'; ?>"
		                	<?php echo ( isset($old['protocol']) ? $old['protocol'] : 'checked' ); ?>
		                	<?php echo FormBuilder::check_if_checked('smtp_fallback', @$old['protocol']); ?>
		                	type="radio"
		                	value="smtp_fallback">
		                <span>Use <span class="badge">PHP Mail</span> and <span class="badge">SMTP</span> as fallback (recommended)</span>
		            </label>
				</div>

				<div class="radio-group">
					<label for="<?php echo $name.'[protocol]'; ?>-smtp-fallback">
		                <input
		                	id="<?php echo $name.'[protocol]'; ?>-smtp-fallback"
		                	name="<?php echo $name.'[protocol]'; ?>"
		                	<?php echo FormBuilder::check_if_checked('smtp_only', @$old['protocol']); ?>
		                	type="radio"
		                	value="smtp_only">
		                <span>Use <span class="badge">SMTP</span> Only</span>
		            </label>
				</div>

				<div class="radio-group">
					<label for="<?php echo $name.'[protocol]'; ?>-mail-only">
		                <input
		                	id="<?php echo $name.'[protocol]'; ?>-mail-only"
		                	name="<?php echo $name.'[protocol]'; ?>"
		                	<?php echo FormBuilder::check_if_checked('mail_only', @$old['protocol']); ?>
		                	type="radio"
		                	value="mail_only">
		                <span>Use <span class="badge">PHP Mail</span> only</span>
		            </label>
				</div>
			</div>
		</div>

		<div class="row row-group m-t-2">
			<div class="two columns">
				<label for="<?php echo $name.'[type]'; ?>"><strong>Message Type</strong></label>
			</div>
			<div class="ten columns">
				<div class="radio-group">
					<label for="<?php echo $name.'[type]'; ?>-html">
		                <input
		                	id="<?php echo $name.'[type]'; ?>-html"
		                	name="<?php echo $name.'[type]'; ?>"
		                	<?php echo ( isset($old['type']) ? $old['type'] : 'checked' ); ?>
		                	<?php echo FormBuilder::check_if_checked('html', @$old['type']); ?>
		                	type="radio"
		                	value="html">
		                <span><code>HTML</code> | Send message as HTML</span>
		            </label>
				</div>

				<div class="radio-group">
					<label for="<?php echo $name.'[type]'; ?>-plain-text">
		                <input
		                	id="<?php echo $name.'[type]'; ?>-plain-text"
		                	name="<?php echo $name.'[type]'; ?>"
		                	<?php echo FormBuilder::check_if_checked('plain-text', @$old['type']); ?>
		                	type="radio"
		                	value="plain-text">
		                <span><code>Plain Text</code> | Send message as Plain text (Message Template will be stripped out of html entities)</span>
		            </label>
				</div>

			</div>
		</div>

		<div class="row row-group m-t-2">
			<div class="two columns">
				<label for="<?php echo $name.'[smtp_details][host]'; ?>"><strong>SMTP Details</strong></label>
			</div>
			<div class="ten columns">
				<div class="form-group">
					<label for="<?php echo $name.'[smtp_details][host]'; ?>"><strong>Host</strong></label>
					<input type="text" class="regular-text" id="<?php echo $name.'[smtp_details][host]'; ?>" name="<?php echo $name.'[smtp_details][host]'; ?>" value="<?php echo @$old['smtp_details']['host']; ?>">
				</div>

				<div class="form-group">
					<label for="<?php echo $name.'[smtp_details][port]'; ?>"><strong>Port</strong></label>
					<input type="text" class="regular-text" id="<?php echo $name.'[smtp_details][port]'; ?>" name="<?php echo $name.'[smtp_details][port]'; ?>" value="<?php echo @$old['smtp_details']['port']; ?>">
				</div>

				<div class="form-group">
					<label for="<?php echo $name.'[smtp_details][encryption]'; ?>"><strong>Encryption</strong></label>
					<select type="text" class="regular-select-box regular-text" id="<?php echo $name.'[smtp_details][encryption]'; ?>" name="<?php echo $name.'[smtp_details][encryption]'; ?>">
						<option value="">None</option>
						<option value="ssl" <?php echo @FormBuilder::check_if_checked('ssl', $old['smtp_details']['encryption'], 'selected="selected"'); ?>>SSL</option>
						<option value="tls" <?php echo @FormBuilder::check_if_checked('tls', $old['smtp_details']['encryption'], 'selected="selected"'); ?>>TLS</option>
					</select>
				</div>
			</div>
		</div>

		<div class="row row-group m-t-2">
			<div class="two columns">
				<label for="<?php echo $name.'[smtp_details][authenticate]'; ?>"><strong>Authenticate</strong></label>
			</div>
			<div class="ten columns">
				<div class="form-group">
					<label for="<?php echo $name.'[smtp_details][authenticate]'; ?>">
						<input
							type="checkbox"
							class="regular-checkbox"
							id="<?php echo $name.'[smtp_details][authenticate]'; ?>"
							name="<?php echo $name.'[smtp_details][authenticate]'; ?>"
							<?php echo @FormBuilder::check_if_checked(true, $old['smtp_details']['authenticate']); ?>
							value="true">
						<span><?php _e('Whether to authenticate the SMTP Account (Requires a Username and Password).', $globals['text-domain']) ?></span>
					</label>
				</div>
				<div class="row row-group">
					<div class="two columns">
						<label for="<?php echo $name.'[smtp_details][username]'; ?>"><strong>SMTP Account</strong></label>
					</div>
					<div class="ten columns">
						<div class="form-group">
							<label for="<?php echo $name.'[smtp_details][username]'; ?>"><strong><?php _e('Username', $globals['text-domain']) ?></strong></label>
							<input
								type="email"
								class="regular-text"
								id="<?php echo $name.'[smtp_details][username]'; ?>"
								name="<?php echo $name.'[smtp_details][username]'; ?>"
								value="<?php echo @$old['smtp_details']['username']; ?>"
								placeholder="your@email.com">
						</div>
						<div class="form-group">
							<label for="<?php echo $name.'[smtp_details][password]'; ?>"><strong><?php _e('Password', $globals['text-domain']) ?></strong> <span class="text-muted">| <em>Password will be encrypted</em></span></label>
							<input
								autocomplete="off"
								type="password"
								class="regular-text"
								id="<?php echo $name.'[smtp_details][password]'; ?>"
								name="<?php echo $name.'[smtp_details][password]'; ?>"
								value="<?php echo @$old['smtp_details']['password']; ?>">
							<p class="description"><?php _e("You haven't provided a password yet.", $globals['text-domain']) ?></p>

						</div>
					</div>
				</div>
			</div>
		</div>

		<?php
        # The submit button
        submit_button(); ?>

    </form>

</div>