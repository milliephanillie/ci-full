<?php
/**
 * Template: Admin / Ads custom fields
 * Description: Template for creating custom taxonomies for ad post type
 *
 * @author pebas
 * @package ager-core/admin
 * @version 1.0.0
 *
 * @var $options - @see includes/Admin/Admin_Taxonomies.php
 * @var $options_list - @see includes/Admin/Admin_Taxonomies.php
 */
?>

<!-- Ads CF / Wrapper -->
<div class="ads-custom-fields">
    <!-- Ads CF / Content -->
    <div class="ads-cf--content">
        <!-- Ads CF / Table -->
        <table class="wp-list-table widefat ads-cf--table">
            <!-- Ads CF / Table Head -->
            <thead>
            <tr>
                <th><?php esc_html_e( 'Singular', 'lisfinity-core' ); ?></th>
                <th><?php esc_html_e( 'Plural', 'lisfinity-core' ); ?></th>
                <th><?php esc_html_e( 'Slug', 'lisfinity-core' ); ?></th>
                <th><?php esc_html_e( 'Numeric', 'lisfinity-core' ); ?></th>
                <th><?php esc_html_e( 'Manage', 'lisfinity-core' ); ?></th>
                <th><?php esc_html_e( 'Edit', 'lisfinity-core' ); ?></th>
            </tr>
            </thead>
            <!-- Ads CF / Table Body -->
            <tbody>
			<?php if ( ! empty( $options ) ): ?>
				<?php foreach ( $options as $option_key => $option ): ?>
                    <tr class="ads-cf--head" data-tr="<?php echo $option_key ?>">
                        <td class="highlighted"><?php printf( esc_html__( '%s', 'lisfinity-core' ),
								$option['single_name'] ); ?></td>
                        <td><?php printf( esc_html__( '%s', 'lisfinity-core' ),
								$option['plural_name'] ); ?></td>
                        <td><?php printf( esc_html__( '%s', 'lisfinity-core' ), $option['slug'] ); ?></td>
                        <td><?php isset( $option['numeric'] ) ? esc_html_e( 'Yes',
								'lisfinity-core' ) : esc_html_e( 'No', 'lisfinity-core' ); ?></td>
                        <td class="manage"><a
                                    href="<?php echo get_site_url() . "/wp-admin/edit-tags.php?taxonomy=" . esc_attr( $option['slug'] ) . "&post_type=product"; ?>"><i
                                        class="material-icons"><?php echo esc_html( 'format_list_bulleted' ); ?></i></a>
                        </td>
                        <td><i class="material-icons"><?php echo esc_html( 'edit' ); ?></i></td>
                    </tr>
                    <tr class="ads-cf--options" data-tr="<?php echo $option_key ?>">
                        <td colspan="7">
                            <form action="" method="post">
                                <div class="ads-cf--option-meta">
                                    <div class="ads-cf--row-options">
                                        <div>
                                            <div class="inner">
                                                <input name="ads-cf--row-position" type="hidden"
                                                       value="<?php echo $option_key; ?>"/>
												<?php if ( $options_list ) : ?>
													<?php foreach ( $options_list as $option_name => $option_settings ): ?>
														<?php $this->show_field( $option_name, $option_settings,
															$option ); ?>
													<?php endforeach; ?>
												<?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ads-cf--actions">
                                        <a href="#save"
                                           class="button button-primary button-large"><?php esc_html_e( 'Save',
												'lisfinity-core' ); ?></a>

                                        <a href="#cancel"
                                           class="button button-secondary button-large"><?php esc_html_e( 'Cancel',
												'lisfinity-core' ); ?></a>
                                        <a href="#delete" class="button button-secondary button-large">
                                            <i class="fa fa-trash-o"></i>
											<?php esc_html_e( 'Delete', 'lisfinity-core' ); ?>
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </td>
                    </tr>
				<?php endforeach; ?>
			<?php endif; ?>

            </tbody>
        </table>

        <!-- Ads CF / Add New Template -->
        <div class="ads-cf--add-new">
            <div class="ads-cf--add-new-taxonomy">
                <i class="fa fa-plus"></i><?php esc_html_e( 'Add new', 'lisfinity-core' ); ?>
            </div>
            <table class="wp-list-table widefat ads-cf--table">
                <tbody>
                <tr class="ads-cf--options">
                    <td colspan="7">
                        <form action="" method="post">
                            <div class="ads-cf--option-meta">
                                <div class="ads-cf--option-row">
                                    <div>
                                        <div class="inner">
											<?php if ( $options_list ) : ?>
												<?php foreach ( $options_list as $option_name => $option_settings ): ?>
													<?php $this->show_field( $option_name, $option_settings, array() ); ?>
												<?php endforeach; ?>
											<?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="ads-cf--actions">
                                    <a href="#add_new"
                                       class="button button-primary button-large"><?php esc_html_e( 'Save',
											'lisfinity-core' ); ?></a>

                                    <a href="#delete" class="button button-secondary button-large">
                                        <i class="fa fa-trash-o"></i>
										<?php esc_html_e( 'Delete', 'lisfinity-core' ); ?>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>
