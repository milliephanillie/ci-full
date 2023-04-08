<?php


namespace Lisfinity\REST_API\Tips;

use Lisfinity\Abstracts\Route as Route;
use Lisfinity\Models\Tips\TipsModel;

class TipsRoute extends Route {

	/**
	 * Register Products Routes
	 * ------------------------
	 *
	 * @var array
	 */
	protected $routes = [
		'get_tips' => [
			'path'                => '/tips/get',
			'callback'            => 'get_tips',
			'permission_callback' => 'allow_access',
			'methods'             => 'GET',
		],
	];

	public function get_tips() {
		$result  = [];
		$page_id = lisfinity_get_page_id( 'page-tips' );

		$page['title']   = get_the_title( $page_id );
		$page['content'] = get_the_content( $page_id );

		$tips_args = [
			'post_type'      => TipsModel::$type,
			'posts_per_page' => - 1,
			'fields'         => 'ids',
		];
		$tips_ids  = get_posts( $tips_args );
		$tips      = [];

		if ( ! empty( $tips_ids ) ) {
			$count = 1;
			foreach ( $tips_ids as $tip ) {
				/*				$title = carbon_get_post_meta( $tip, 'tips-category' );
								if ( 'all' === $title ) {
									$old_count = $count;
									$count     = 0;
								}*/
				$title                      = get_the_title( $tip );
				$tips[ $count ]['ID']       = $tip;
				$tips[ $count ]['category'] = $title;
				$tips[ $count ]['name']     = $title;
				/*				$tips[ $count ]['name']     = 'all' === $title ? __( 'General Tips', 'lisfinity-core' ) : str_replace( [
									'-',
									'_'
								], [ ' ', ' ' ], ucwords( $title ) );*/
				$tips[ $count ]['tips'] = array_column( carbon_get_post_meta( $tip, 'tips' ), 'tip' );
				/*				if ( 'all' === $title ) {
									$count = $old_count - 1;
								}*/
				$count += 1;
			}
		}

		$page['tips'] = $tips;

		$result['page'] = $page;

		return $result;
	}

}
