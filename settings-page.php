<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}
?>
	<div class="wrap">
		<h1>
			<?php _e("RTL Settings", "better-rtl") ?>
		</h1>
		<p>برای دانلود بسته فونت ایران‌سنس می توانید به سایت 
			<a href="http://fontiran.com" rel="external nofollow" target="_blank">fontiran.com</a>
			مراجعه نمایید.
		</p>
		<form action="options.php" method="POST">
    <?php settings_fields( 'better-rtl-general' ); ?>
    <?php do_settings_sections( 'better-rtl-general' ); ?>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							<label for="iransans-license">کد لایسنس فونت ایران‌سنس</label>
						</th>
						<td>
							<input value="<?php echo esc_attr( get_option('font_iransans_license') ); ?>" type="text" name="font_iransans_license" id="iransans-lisence">
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="fonts-path">آدرس URL برای پوشه "fonts"</label>
							<p>این پوشه را می توانید در پوشه WebFonts در بسته دانلودی خود از سایت فونت ایران پیدا کنید.</p>
						</th>
						<td>
							<input value="<?php echo esc_attr( get_option('font_iransans_path') ); ?>" type="text" id="fonts-path" name="font_iransans_path" placeholder="example.com/fonts/">
						</td>
					</tr>

				</tbody>
			</table>

    		<?php submit_button(); ?>
		</form>
		
	</div>