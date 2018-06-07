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
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">
						<label for="iransans-lisence">کد لایسنس فونت ایران‌سنس</label>
					</th>
					<td>
						<input type="text" name="iransans-lisence" id="iransans-lisence">
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="fonts-path">آدرس URL برای پوشه "fonts"</label>
						<p>این پوشه را می توانید در پوشه WebFonts در بسته دانلودی خود از سایت فونت ایران پیدا کنید.</p>
					</th>
					<td>
						<input type="text" id="fonts-path" name="fonts-path" placeholder="example.com/fonts/">
					</td>
				</tr>

			</tbody>
		</table>

		<p class="submit">
			<input type="submit" name="submit" id="submit" class="button button-primary" value="ذخیره‌ی تغییرات">
		</p>

	</div>