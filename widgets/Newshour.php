<?php
// PBS Newshour
// Dont forget to change the function name and title

add_action( 'widgets_init', 'rssleech_newshour_register' );
function rssleech_newshour_register() {
	register_widget( "RSS_Leech_Newshour" );
}

class RSS_Leech_Newshour extends WP_Widget {
	/**
	 * Register widget with WordPress.
	 */
	function __construct() {

		parent::__construct(
			'pbs_leech_newshour', // Base ID
			__( "Leech PBS Newshour", 'rss_leech' ), // Name
			array( 'description' => __( "Grab PBS Newshour feed.", 'rss_leech' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		}

		$rss = new RSSParser();
		$rss->load('http://www.pbs.org/newshour/topic/nation/feed/');
		$limit = 5;
		$items = $rss->getItems();

		for($x=0; $x < $limit; $x++) {
			echo '<ul class="rss-leech-list">';
			echo '<a class="rss-leech-link" target="_blank" href="' . $items[$x]->getLink() .'"><li>';
			echo '<img class="rss-leech-img" src="' . $items[$x]->getImage() . '" />';
			echo '<span class="rss-leech-headline">' . $items[$x]->getTitle() . '</span>';
			echo '</li></a>';
			echo '</ul>';
		}

		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'PBS Newshour', 'rss_leech' );
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}

}