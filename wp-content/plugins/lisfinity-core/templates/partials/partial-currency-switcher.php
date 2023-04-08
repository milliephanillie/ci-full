<?php
/**
 * Template Name: Partial | Currency Switcher
 * Description: Partials that is loading currency switcher template
 *
 * @author pebas
 * @package templates/partials
 * @version 1.0.0
 */
?>
<?php $currencies = carbon_get_theme_option( 'currencies' ); ?>
<?php $default_currency = get_option( 'woocommerce_currency' ); ?>
<?php $all_currencies = get_woocommerce_currencies(); ?>
<?php $chosen_currency = lisfinity_get_chosen_currency(); ?>

<?php if ( ! empty ( $currencies ) ) : ?>
	<div class="currency-switcher">
		<div class="currency-switcher--header hidden"><?php esc_html_e( 'Select Currency', 'lisfinity-core' ); ?></div>
		<div class="currency flex <?php echo $chosen_currency === $default_currency ? esc_attr( 'active' ) : ''; ?>">
			<?php if ( $chosen_currency === $default_currency ) : ?>
				<p class="flex items-center">
					<?php echo esc_html( $default_currency ); ?>
					<span class="currency--full"><?php echo esc_html( $all_currencies[ $default_currency ] ); ?></span>
				</p>
			<?php else: ?>
				<button type="button" class="flex items-center currency--trigger" data-currency="<?php echo esc_attr( $default_currency ); ?>">
					<?php echo esc_html( $default_currency ); ?>
					<span class="currency--full"><?php echo esc_html( $all_currencies[ $default_currency ] ); ?></span>
				</button>
			<?php endif; ?>
		</div>
		<?php foreach ( $currencies as $currency ) : ?>
			<div class="currency flex <?php echo $chosen_currency === $currency['country'] ? esc_attr( 'active' ) : ''; ?>">
				<?php if ( $chosen_currency === $currency['country'] ) : ?>
					<p class="flex items-center">
						<?php echo esc_html( $currency['country'] ); ?>
						<span class="currency--full"><?php echo esc_html( $all_currencies[ $currency['country'] ] ); ?></span>
					</p>
				<?php else: ?>
					<button type="button" class="flex items-center currency--trigger" data-currency="<?php echo esc_attr( $currency['country'] ); ?>">
						<?php echo esc_html( $currency['country'] ); ?>
						<span class="currency--full"><?php echo esc_html( $all_currencies[ $currency['country'] ] ); ?></span>
					</button>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
<?php endif; ?>
