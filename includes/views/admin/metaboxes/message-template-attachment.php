<div class="wp-mailman-form-manager">
	<div class="clonable-block" data-toggle="cloner">
		<?php if ( ! isset( $old['attachments'] ) && ! empty( isset( $old['attachments'] ) ) ) : ?>
			<div class="clonable">
				<div class="row form-group">
					<div class="nine columns">
						<div class="file-control regular-text-addon">
							<input
								type="file"
								id="<?php echo $name."[attachments][0]"; ?>"
								name="<?php echo $name."[attachments][0]"; ?>"
								class="clonable-increment-id clonable-increment-name regular-file" title="Browse File...">
							<label role="button" for="<?php echo $name."[attachments][0]"; ?>" class="clonable-increment-for button button-default">Browse...</label>
							<input class="text-muted file-name" readonly disabled type="text">
						</div>
					</div>
					<div class="three columns">
						<a role="button" class="clonable-button-close">Remove</a>
						<!-- <button type="button" class="button button-link clonable-button-close">Remove</button> -->
					</div>
				</div>
			</div>
		<?php else : ?>
			<?php
				$i = 0;
				foreach ( $old['attachments'] as $attachment ) : ?>
				<div class="clonable">
					<div class="row form-group">
						<div class="nine columns">
							<div class="file-control regular-text-addon">
								<input
									type="file"
									id="<?php echo $name."[attachments][$i]"; ?>"
									name="<?php echo $name."[attachments][$i]"; ?>"
									value="<?php echo @$attachment; ?>"
									class="clonable-increment-id clonable-increment-name regular-file" title="Browse File...">
								<label role="button" for="<?php echo $name."[attachments][$i]"; ?>" class="clonable-increment-for button button-default">Browse...</label>
								<input class="text-muted file-name" readonly disabled type="text" value="<?php echo @$attachment; ?>">
							</div>
						</div>
						<div class="three columns">
							<a role="button" class="clonable-button-close">Remove</a>
							<!-- <button type="button" class="button button-link clonable-button-close">Remove</button> -->
						</div>
					</div>
				</div>
			<?php $i++; endforeach; ?>
		<?php endif; ?>
		<div class="m-t-2 clearfix">
			<a role="button" class="text-warning clonable-button-close-all pull-left">Remove all</a>
			<button type="button" class="button button-default clonable-button-add pull-right">Add Attachment</button>
		</div>
	</div>
</div>