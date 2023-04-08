<?php
/**
 * Model for our custom Reports Admin functionality
 *
 * @author pebas
 * @package lisfinity-reports
 * @version 1.0.0
 */

namespace Lisfinity\Models\Reports;

class ReportAdmin {

	/**
	 * Add Report Listings admin menu
	 */
	public function report_admin_menu() {
		$report     = ReportModel::$type;
		$report_obj = get_post_type_object( $report );

		// add submenu page under listings
		add_submenu_page(
			$parent_slug = 'edit.php?post_type=product',
			$page_title = $report_obj->labels->name,
			$menu_title = $report_obj->labels->menu_name,
			$capability = 'edit_posts',
			$menu_slug = "edit.php?post_type={$report}"
		);
	}

	function admin_menu_parent_file( $parent_file ) {
		global $current_screen;
		if ( in_array( $current_screen->base, array(
				'post',
				'edit'
			) ) && ReportModel::$type == $current_screen->post_type ) {
			$parent_file = 'edit.php?post_type=product';
		}

		return $parent_file;
	}

	function admin_menu_submenu_file( $submenu_file ) {
		global $current_screen;
		$post_type = ReportModel::$type;
		if ( in_array( $current_screen->base, array(
				'post',
				'edit'
			) ) && ReportModel::$type == $current_screen->post_type ) {
			$submenu_file = "edit.php?post_type={$post_type}";
		}

		return $submenu_file;
	}

	/**
	 * Add Reported Listing column statuses
	 *
	 * @param $columns
	 *
	 * @return array
	 */
	public function manage_columns( $columns ) {

		$old_columns = $columns;
		$columns     = array(
			'cb'      => $old_columns['cb'],
			'status'  => __( 'Status', 'lisfinity-core' ),
			'reason'  => __( 'Reason', 'lisfinity-core' ),
			'title'   => __( 'Reported', 'lisfinity-core' ),
			'listing' => __( 'Listing', 'lisfinity-core' ),
			'author'  => __( 'Reported By', 'lisfinity-core' ),
			'date'    => $old_columns['date'],
		);

		return $columns;
	}

	/**
	 * Manage Custom Report Listings columns
	 *
	 * @param $column
	 * @param $post_id
	 *
	 * @return mixed
	 */
	public function manage_custom_column( $column, $post_id ) {
		switch ( $column ) {
			case 'status' :
				$status = carbon_get_post_meta( $post_id, 'report-status' );
				?>
				<span class='status status-<?php echo sanitize_html_class( strtolower( $status ) ); ?>'>
					<?php echo isset( $status ) ? ucfirst( esc_html( $status ) ) : esc_html__( 'Unknown', 'lisfinity-core' ); ?>
				</span>
				<?php
				break;
			case 'reason' :
				$status = carbon_get_post_meta( $post_id, 'report-reason' );
				?>
				<span class='status status-<?php echo sanitize_html_class( strtolower( $status ) ); ?>'>
					<?php echo isset( $status ) ? ucfirst( esc_html( str_replace( '-', ' ', ucfirst( $status ) ) ) ) : esc_html__( 'General', 'lisfinity-core' ); ?>
				</span>
				<?php
				break;
			case 'listing':
				$listing = carbon_get_post_meta( $post_id, 'report-product' );
				$listing_link = admin_url() . "/post.php?post={$listing}&action=edit";
				?>
				<strong><a class="row-title"
				           href="<?php echo esc_url( $listing_link ); ?>"><?php echo esc_html( get_the_title( $listing ) ); ?></a></strong>
				<?php
				break;
			default :
				break;
		}

		return $column;
	}

	/**
	 * Add Report Listing statuses in dropdown
	 *
	 * @param $post_type
	 */
	public function manage_column_filter_status_dropdown( $post_type ) {

		if ( ReportModel::$type !== $post_type ) {
			return;
		}

		/* Vars */
		$statuses = array( 'pending', 'stashed' );
		$request  = stripslashes_deep( $_GET );
		?>
		<select name='report_status' id='dropdown_report_status'>
			<option value=''><?php _e( 'All report statuses', 'lisfinity-core' ); ?></option>

			<?php foreach ( $statuses as $key => $status ) { ?>

				<option
					value='<?php echo esc_attr( $key ); ?>' <?php selected( isset( $request['report_status'] ) ? $request['report_status'] : '', $key ); ?>><?php echo esc_html( $status ); ?></option>

			<?php } ?>

		</select><!-- #dropdown_report_status -->
		<?php
	}

	/**
	 * Allow sorting Report Listing columns by status
	 *
	 * @param $query
	 */
	public function manage_column_status_filter( $query ) {

		/* Vars */
		global $hook_suffix, $post_type;
		$request  = stripslashes_deep( $_GET );
		$statuses = array( 'pending', 'stashed' );

		/* Only in Admin Edit Column Screen */
		if ( is_admin() && 'edit.php' == $hook_suffix && ReportModel::$type == $post_type && $query->is_main_query() && isset( $request['report_status'] ) && array_key_exists( $request['report_status'], $statuses ) ) {

			/* Set simple meta query */
			$query->query_vars['meta_key']   = '_report_status';
			$query->query_vars['meta_value'] = esc_attr( $request['report_status'] );
		}
	}

	/**
	 * Listing report updated messages
	 *
	 * @param $messages
	 *
	 * @return mixed
	 */
	public function post_updated_messages( $messages ) {
		$post      = get_post();
		$post_type = ReportModel::$type;

		$messages[ $post_type ] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => __( 'Report updated.', 'lisfinity-core' ),
			2  => __( 'Custom field updated.', 'lisfinity-core' ),
			3  => __( 'Custom field deleted.', 'lisfinity-core' ),
			4  => __( 'Report updated.', 'lisfinity-core' ),
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Report restored to revision from %s', 'lisfinity-core' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6  => __( 'Report published.', 'lisfinity-core' ),
			7  => __( 'Report saved.', 'lisfinity-core' ),
			8  => __( 'Report submitted.', 'lisfinity-core' ),
			9  => sprintf(
				__( 'Report scheduled for: <strong>%1$s</strong>.', 'lisfinity-core' ),
				date_i18n( __( 'M j, Y @ G:i', 'lisfinity-core' ), strtotime( $post->post_date ) )
			),
			10 => __( 'Report draft updated.', 'lisfinity-core' ),
		);

		$return_to_reports_link = sprintf( ' <a href="%s">%s</a>', esc_url( admin_url( "edit.php?post_type={$post_type}" ) ), __( 'Return to reports', 'lisfinity-core' ) );

		$messages[ $post_type ][1]  .= $return_to_reports_link;
		$messages[ $post_type ][6]  .= $return_to_reports_link;
		$messages[ $post_type ][9]  .= $return_to_reports_link;
		$messages[ $post_type ][8]  .= $return_to_reports_link;
		$messages[ $post_type ][10] .= $return_to_reports_link;

		return $messages;
	}

}
