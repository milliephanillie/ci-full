<?php
/**
 * Template Name: Shortcodes | Navigation Avatar
 * Description: The file that is being used to display theme navigation
 *
 * @var $args
 * @package templates/shortcodes
 * @version 1.0.0
 *
 * @author pebas
 */
?>
<?php $page_account_id = lisfinity_get_page_id( 'page-account' ); ?>
<?php $account_permalink = get_permalink( $page_account_id ); ?>
<?php $avatar = lisfinity_get_avatar_url(); ?>
<?php if ( is_user_logged_in() ) : ?>
	<?php
	$user = get_userdata( get_current_user_id() );
	$name = !empty($user->first_name) ? $user->first_name : $user->user_login;
	?>
	<div id="avatar"><a href="<?php echo esc_url( $account_permalink ); ?>"
			class="relative flex navigation--avatar"
			title="<?php echo esc_html__( 'My Account', 'lisfinity-core' ); ?>">
			<?php if('yes' === $args['settings']['display_avatar']) : ?>
			<img
				src="<?php echo esc_url( $avatar ); ?>"
				alt="<?php echo esc_attr__( 'Agent', 'lisfinity-core' ); ?>"
				class="absolute top-0 left-0 w-full h-full object-cover"/>
			<?php endif; ?>
			<?php if('yes' === $args['settings']['display_name']) : ?>
			<span class="navigation--user-name absolute b-0 r-0"><?php echo esc_html__( $name, 'lisfinity-core'); ?></span>
			<?php endif; ?>
		</a>
	</div>
<?php else: ?>
	<div id="avatar"></div>
	<script>
		const elementAvatar = document.getElementById("avatar");
		if(elementAvatar) {
			const elementAvatarColumn = elementAvatar.closest('.elementor-column');
			elementAvatarColumn.style.display = 'none';
		}
	</script>
<?php endif; ?>

