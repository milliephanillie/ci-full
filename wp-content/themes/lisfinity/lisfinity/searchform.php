<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="widget-search-form">
		<span
			class="screen-reader-text"><?php esc_html_e( 'Search for:', 'lisfinity' ); ?></span>
		<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
			 x="0px" y="0px"
			 viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve"
			 class="absolute top-12 left-20 w-16 h-16 fill-grey-500"
		>
			<path d="M94.4,83.8L73.2,62.6c-1.9-1.9-4.9-1.9-6.8-0.1l-9.3-9.2c4.3-5.2,7-11.9,7-19.2c0-16.5-13.4-30-30-30s-30,13.4-30,30
			s13.4,30,30,30c7.2,0,13.9-2.6,19.1-6.9l9.3,9.2l-0.1,0.1c-1.9,1.9-1.9,5.1,0,7l21.2,21.1c1.9,1.9,5.1,1.9,7,0l3.8-3.8
			C96.3,88.8,96.3,85.7,94.4,83.8z M9.7,34.1c0-13.5,11-24.5,24.5-24.5s24.5,11,24.5,24.5s-11,24.5-24.5,24.5S9.7,47.5,9.7,34.1z"/>
		</svg>
		<input type="search" id="widget-search-form" class="search-field pl-30"
			   placeholder="<?php echo esc_attr_x( 'Type your search term...', 'placeholder', 'lisfinity' ); ?>"
			   value="<?php echo get_search_query(); ?>" name="s"/>
	</label>
</form>
