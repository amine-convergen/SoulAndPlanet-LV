<?php
/**
 * Frontend Reset Password - Reset Complete
 * 
 * @version	1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div id="password-lost-form-wrap">

		<div>
			<fieldset>
				<legend><?php echo $form_title; ?></legend>

				<p>
					<?php printf(
						__( 'Votre mot de passe a été réinitialisé. Vous pouvez maintenant <a href="%s">vous connecter</a>.', 'frontend-reset-password' ),
						som_get_login_url()
					); ?>
				</p>

			</fieldset>
		</div>

</div>