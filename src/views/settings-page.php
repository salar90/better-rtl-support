<?php
/**
 * Appearance → RTL Settings. Included from Better_RTL_Support::settings_page().
 *
 * @package Better_RTL_Support
 *
 * @var array $settings     Current settings.
 * @var bool  $block_theme  Whether the active theme is a block theme.
 * @var bool  $theme_fixes  Whether the active theme has layout fixes in this plugin.
 * @var bool  $rtl_language Whether the current language is RTL.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$option = Better_RTL_Support::OPTION;
?>
<div class="wrap">
	<h1><?php esc_html_e( 'RTL Settings', 'better-rtl-support' ); ?></h1>

	<p><?php esc_html_e( 'Better RTL Support makes your site look right in Persian and other right-to-left languages with popular themes: the default WordPress themes, Astra, Hueman and others.', 'better-rtl-support' ); ?></p>

	<?php if ( ! $rtl_language ) : ?>
		<div class="notice notice-warning inline">
			<p><?php esc_html_e( 'Your current language is not right-to-left, so nothing is changed. The settings below apply when the site or your profile uses an RTL language such as Persian.', 'better-rtl-support' ); ?></p>
		</div>
	<?php endif; ?>

	<form method="post" action="options.php">
		<?php settings_fields( 'brtls' ); ?>
		<table class="form-table" role="presentation">
			<tr>
				<th scope="row"><label for="brtls-font"><?php esc_html_e( 'Font', 'better-rtl-support' ); ?></label></th>
				<td>
					<select id="brtls-font" name="<?php echo esc_attr( $option ); ?>[font]">
						<option value="vazirmatn" <?php selected( $settings['font'], 'vazirmatn' ); ?>><?php esc_html_e( 'Vazirmatn', 'better-rtl-support' ); ?></option>
						<option value="none" <?php selected( $settings['font'], 'none' ); ?>><?php esc_html_e( 'Theme default (do not change fonts)', 'better-rtl-support' ); ?></option>
					</select>
					<p class="description">
						<?php esc_html_e( 'Vazirmatn is a free Persian font by the late Saber Rastikerdar, bundled with the plugin (no external requests).', 'better-rtl-support' ); ?>
						<?php if ( $block_theme ) : ?>
							<?php esc_html_e( 'With your block theme, the theme’s fonts are replaced in its font presets, so font choices in the Site Editor still work.', 'better-rtl-support' ); ?>
						<?php endif; ?>
					</p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Use the font in', 'better-rtl-support' ); ?></th>
				<td>
					<fieldset>
						<legend class="screen-reader-text"><?php esc_html_e( 'Use the font in', 'better-rtl-support' ); ?></legend>
						<label><input type="checkbox" name="<?php echo esc_attr( $option ); ?>[font_site]" value="1" <?php checked( $settings['font_site'] ); ?>> <?php esc_html_e( 'The site', 'better-rtl-support' ); ?></label><br>
						<label><input type="checkbox" name="<?php echo esc_attr( $option ); ?>[font_editor]" value="1" <?php checked( $settings['font_editor'] ); ?>> <?php esc_html_e( 'The block editor (post content)', 'better-rtl-support' ); ?></label><br>
						<label><input type="checkbox" name="<?php echo esc_attr( $option ); ?>[font_admin]" value="1" <?php checked( $settings['font_admin'] ); ?>> <?php esc_html_e( 'The dashboard and the admin bar', 'better-rtl-support' ); ?></label>
					</fieldset>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Theme fixes', 'better-rtl-support' ); ?></th>
				<td>
					<label><input type="checkbox" name="<?php echo esc_attr( $option ); ?>[theme_fixes]" value="1" <?php checked( $settings['theme_fixes'] ); ?>> <?php esc_html_e( 'Fix right-to-left layout issues of supported themes', 'better-rtl-support' ); ?></label>
					<p class="description">
						<?php
						if ( $theme_fixes ) {
							esc_html_e( 'Your theme has fixes in this plugin.', 'better-rtl-support' );
						} else {
							esc_html_e( 'This plugin has no layout fixes for your theme; only the font is applied.', 'better-rtl-support' );
						}
						?>
					</p>
				</td>
			</tr>
		</table>
		<?php submit_button(); ?>
	</form>

	<h2><?php esc_html_e( 'Tested themes', 'better-rtl-support' ); ?></h2>
	<p>Astra, Hueman, Twenty Twenty-Five, Twenty Twenty-Four, Twenty Twenty-Three, Twenty Twenty-Two, Twenty Twenty-One, Twenty Twenty, Twenty Nineteen, Twenty Seventeen, Twenty Sixteen.</p>
	<p>
		<?php
		printf(
			/* translators: %s: link to the GitHub repository */
			esc_html__( 'Found a problem with your theme? Report it on %s.', 'better-rtl-support' ),
			'<a href="https://github.com/salar90/better-rtl-support/issues">GitHub</a>'
		);
		?>
	</p>
</div>
