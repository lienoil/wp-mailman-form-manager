<?php
# Check if empty
if ( empty( $posts_array ) ) { ?>
    <div class="no-email">
        <div class="icon dashicons-before dashicons-email-alt"></div>
        <p class="message"><?php _e('No new emails today.', 'mail-manager') ?></p>
        <p class="mail-man-footer">
            <span class="configure-settings"><a href="<?php echo get_admin_url() . 'edit.php?post_type=email&page=mail-manager-settings' ?>"><i class="dashicons dashicons-admin-settings">&nbsp;</i><?php _e("Configure settings", 'mail-manager'); ?></a></span>
            |
            <span class="all-emails"><a href="<?php echo get_admin_url() . 'edit.php?post_type=email' ?>"><?php _e("All", 'mail-manager'); ?></a></span>
            |
            <span class="read-emails"><a href="<?php echo get_admin_url() . 'edit.php?post_status=read&post_type=email' ?>"><?php _e('Read', 'mail-manager'); ?></a>&nbsp;<?php echo '(' . wp_count_posts('email')->read . ')'; ?></span>
            |
            <span class="trashed-emails"><a href="<?php echo get_admin_url() . 'edit.php?post_status=trash&post_type=email' ?>"><?php _e('Trash', 'mail-manager'); ?></a>&nbsp;<?php echo '(' . wp_count_posts('email')->trash . ')'; ?></span>
        </p>
    </div><?php
} else { ?>
    <div class="unread-emails">
        <?php $unread = wp_count_posts('email')->unread; ?>
        <p class="message"><a href="<?php echo get_admin_url() . 'edit.php?post_status=unread&post_type=email' ?>"><?php echo sprintf("%u Unread Email%s", $unread, ($unread <= 1)?'':'s'); ?></a></p>
        <?php $getEmails = (get_option('mail_manager_field_emailFrom')) ? get_option('mail_manager_field_emailFrom') : get_option('admin_email'); ?>
        <ul class="emails email-list">
            <?php foreach ($posts_array as $post => $options) {
                echo "<li class='email-entry'>";
                echo    "<a href='" . get_admin_url() . "post.php?post=$options->ID&action=edit" . "'>";
                echo        "<span class='email-subject'><i class='dashicons-before dashicons-email-alt'>&nbsp;</i>" . $options->post_title . "</span>";
                echo    "</a>";
                echo    "<div class='email-details'>";
                            $date = new DateTime($options->post_date);
                            $date = ($date->format('F d, Y') == date('F d, Y') ? 'Today, ' . $date->format('g:i A') : $date->format('F d, Y, g:i A'));
                echo        "<span class='send-date'>" . $date . "</span>";
                echo    "</div>";
                echo "</li>";
            } ?>
        </ul>
        <p class="mail-man-footer">
            <span class="configure-settings"><a href="<?php echo get_admin_url() . 'edit.php?post_type=email&page=mail-manager-settings' ?>"><i class="dashicons dashicons-admin-settings">&nbsp;</i><?php _e("Configure settings", 'mail-manager'); ?></a></span>
            |
            <span class="all-emails"><a href="<?php echo get_admin_url() . 'edit.php?post_type=email' ?>"><?php _e("All", 'mail-manager'); ?></a></span>
            |
            <span class="read-emails"><a href="<?php echo get_admin_url() . 'edit.php?post_status=read&post_type=email' ?>"><?php _e('Read', 'mail-manager'); ?></a>&nbsp;<?php echo '(' . wp_count_posts('email')->read . ')'; ?></span>
            |
            <span class="trashed-emails"><a href="<?php echo get_admin_url() . 'edit.php?post_status=trash&post_type=email' ?>"><?php _e('Trash', 'mail-manager'); ?></a>&nbsp;<?php echo '(' . wp_count_posts('email')->trash . ')'; ?></span>
        </p>
    </div><?php

}
 ?>