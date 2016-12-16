<?php

class FormBuilder extends WP_Mailman_Form_Manager
{
	protected static $regex_loop = '#<loop[^>]*>(.*?)</loop>#s';
	protected static $regex_empty_paragraph = '#<p>(\s|&nbsp;|</?\s?br\s?/?>)*</?p>#';
	protected static $pattern_start_loop = '<loop>';
	protected static $pattern_end_loop = '</loop>';
	protected static $pattern_label = '%label%';
	protected static $pattern_field = '%field%';
	protected static $pattern_error = '%error%';
	protected static $pattern_submit = '%submit-button%';

	public static function make_field( $type = "text", $attr = array(), $value = null, $old_value = null )
	{
		$attr_arr = array();
		foreach ( $attr as $name => $val ) {
			$attr_arr[] = "$name='$val'";
		}
		$attr_html = implode( " ", $attr_arr );

		$html = "";

		switch ( $type ) {
			case 'select':
				$value = explode( "|", $value );
				$value_arr = array();

				foreach ( $value as $v ) {
					$selected = strpos( $v, '*' ) !== false ? 'selected="selected"' : "";

					$v = str_replace( "*", '', $v );

					if ( null !== $old_value ) $selected = $v == $old_value ? 'selected="selected"' : "";

					$value_arr[] = "<option value='$v' $selected>$v</option>";
				}
				$value_html = implode( "", $value_arr );
				$html = "<select $attr_html autocomplete='off'>$value_html</select>";
				break;

			case 'textarea':
				if ( null !== $old_value ) $value = trim( $old_value );
				$html = "<textarea $attr_html>$value</textarea>";
				break;

			case 'text':
			case 'email':
			default:
				if ( null !== $old_value ) $value = trim( $old_value );
				$html = "<input type='$type' $attr_html value='$value'>";
				break;
		}

		return $html;
	}

	public static function make_select( $name, $value = null, $class = "", $options = array() )
	{
		?>
		<select name="<?php echo $name; ?>" class="regular-select <?php echo $class; ?>">
			<option value="">--Select Options--</option>
			<?php foreach ($options as $code => $name) {
				$selected = ($value == $code) ? 'selected="selected"' : ""; ?>
				<option value="<?php echo $code; ?>" <?php echo $selected; ?>><?php echo $name; ?></option>
			<?php } ?>
		</select>
		<?php
	}

	/**
	 * Gets the pattern out the $content.
	 *
	 * @param  String $content
	 * @return String
	 */
	public static function get_loop( $content, $include_parent = true )
	{
		$pattern = self::$regex_loop;
		$subject = $content;

		preg_match_all( $pattern, $subject, $matches );

		if ( $include_parent ) return $matches[0][0];

		return $matches[1][0];
	}

	public static function make_content( $replacement, $subject )
	{
		return preg_replace( self::$regex_loop, $replacement, $subject );
	}

	public static function remove_empty_paragraphs( $subject )
	{
		return preg_replace( self::$regex_empty_paragraph, '', $subject );
	}

	public static function check_if_checked( $new_value='', $old_value = '', $checked = "checked", $else_checked = "" ) {
		return $new_value == $old_value ? $checked : $else_checked;
	}
}