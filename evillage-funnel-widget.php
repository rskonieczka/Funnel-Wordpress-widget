<?php
/**
 * Plugin Name: eVillage Funnel Widget
 * Description: Widget wyświetlający sekcje TOFU/MOFU/BOFU na pojedynczym wpisie na podstawie tagów i kategorii.
 * Author: EVILLAGE / Rafał
 * Version: 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Ustal etap lejka na podstawie tagów: tofu / mofu / bofu.
 * Priorytet: bofu > mofu > tofu (najgłębszy etap wygrywa).
 */
if ( ! function_exists( 'ev_get_funnel_stage_for_post' ) ) {
	function ev_get_funnel_stage_for_post( $post_id = null ) {
		$post_id = $post_id ?: get_the_ID();
		if ( ! $post_id ) {
			return null;
		}

		if ( has_tag( 'bofu', $post_id ) ) {
			return 'bofu';
		}

		if ( has_tag( 'mofu', $post_id ) ) {
			return 'mofu';
		}

		if ( has_tag( 'tofu', $post_id ) ) {
			return 'tofu';
		}

		return null;
	}
}

/**
 * Pomocnicza funkcja: zbuduj tax_query na podstawie kategorii i tagów.
 *
 * @param array $cat_ids    Id kategorii (może być puste).
 * @param array $tag_slugs  Slugi tagów (np. ['tofu']).
 *
 * @return array            Tablica tax_query do WP_Query.
 */
if ( ! function_exists( 'ev_build_tax_query' ) ) {
	function ev_build_tax_query( array $cat_ids = [], array $tag_slugs = [] ) {
		$tax_query = [];
		$parts = [];

		if ( ! empty( $cat_ids ) ) {
			$parts[] = [
				'taxonomy' => 'category',
				'field' => 'term_id',
				'terms' => $cat_ids,
			];
		}

		if ( ! empty( $tag_slugs ) ) {
			$parts[] = [
				'taxonomy' => 'post_tag',
				'field' => 'slug',
				'terms' => $tag_slugs,
			];
		}

		if ( count( $parts ) > 1 ) {
			$tax_query = array_merge( [ 'relation' => 'AND' ], $parts );
		} elseif ( count( $parts ) === 1 ) {
			$tax_query = $parts;
		}

		return $tax_query;
	}
}

/**
 * Główna funkcja: wyrenderuj 3 sekcje:
 * 1) To już powinieneś wiedzieć (poprzedni etap)
 * 2) Więcej na ten temat (ten sam etap)
 * 3) Z tym się jeszcze zapoznaj (następny etap)
 */
if ( ! function_exists( 'ev_render_funnel_sections' ) ) {
	function ev_render_funnel_sections( $post_id = null ) {
		if ( ! is_singular( 'post' ) ) {
			return;
		}

		$post_id = $post_id ?: get_the_ID();
		if ( ! $post_id ) {
			return;
		}

		$stage = ev_get_funnel_stage_for_post( $post_id );
		if ( ! $stage ) {
			// Brak tagów tofu/mofu/bofu – nic nie pokazujemy.
			return;
		}

		$stages = [ 'tofu', 'mofu', 'bofu' ];
		$stage_index = array_search( $stage, $stages, true );

		if ( $stage_index === false ) {
			return;
		}

		$prev_stage = $stage_index > 0 ? $stages[ $stage_index - 1 ] : null;
		$next_stage = $stage_index < count( $stages ) - 1 ? $stages[ $stage_index + 1 ] : null;

		$cat_ids = wp_get_post_categories( $post_id );
		$cat_ids = array_map( 'intval', $cat_ids );

		// Pobierz nazwę pierwszej kategorii dla wyświetlenia w nagłówkach
		$cat_name = '';
		if ( ! empty( $cat_ids ) ) {
			$cat_name = get_cat_name( $cat_ids[0] );
		}

		echo '<div class="ev-funnel-sections">';

		/*
		 * 1. TO JUŻ POWINIENEŚ WIEDZIEĆ – poprzedni etap lejka
		 */
		if ( $prev_stage ) {
			$tax_query = ev_build_tax_query( $cat_ids, [ $prev_stage ] );

			$args_prev = [
				'post_type' => 'post',
				'posts_per_page' => 3,
				'post__not_in' => [ $post_id ],
				'tax_query' => $tax_query,
				'orderby' => 'date',
				'order' => 'ASC',
			];

			$q_prev = new WP_Query( $args_prev );

			if ( $q_prev->have_posts() ) {
				echo '<section class="ev-funnel-section ev-funnel-prev-stage">';
				$heading = 'To już powinieneś wiedzieć';
				if ( $cat_name ) {
					$heading .= ' na temat ' . esc_html( $cat_name );
				}
				echo '<h2 class="ev-funnel-heading">' . $heading . '</h2>';
				echo '<p class="ev-funnel-intro">Jeśli pojęcia w tym wpisie są dla Ciebie nowe, zacznij od tych treści:</p>';
				echo '<ul class="ev-funnel-list ev-funnel-list-prev">';

				while ( $q_prev->have_posts() ) {
					$q_prev->the_post();
					echo '<li class="ev-funnel-item">';
					echo '<a href="' . esc_url( get_permalink() ) . '" class="ev-funnel-link">';
					echo esc_html( get_the_title() );
					echo '</a>';
					echo '<p class="ev-funnel-excerpt">' . esc_html( wp_trim_words( get_the_excerpt(), 22 ) ) . '</p>';
					echo '</li>';
				}

				echo '</ul>';
				echo '</section>';
			}

			wp_reset_postdata();
		}

		/*
		 * 2. WIĘCEJ NA TEN TEMAT – ten sam etap lejka
		 */
		$tax_query_same = ev_build_tax_query( $cat_ids, [ $stage ] );

		$args_same = [
			'post_type' => 'post',
			'posts_per_page' => 4,
			'post__not_in' => [ $post_id ],
			'tax_query' => $tax_query_same,
			'orderby' => 'date',
			'order' => 'DESC',
		];

		$q_same = new WP_Query( $args_same );

		if ( $q_same->have_posts() ) {
			echo '<section class="ev-funnel-section ev-funnel-same-stage">';
			$heading_same = 'Więcej na ten temat';
			if ( $cat_name ) {
				$heading_same .= ' ' . esc_html( $cat_name );
			}
			echo '<h2 class="ev-funnel-heading">' . $heading_same . '</h2>';
			echo '<p class="ev-funnel-intro">Jeśli chcesz zgłębić ten temat, zobacz też:</p>';
			echo '<ul class="ev-funnel-list ev-funnel-list-same">';

			while ( $q_same->have_posts() ) {
				$q_same->the_post();
				echo '<li class="ev-funnel-item">';
				echo '<a href="' . esc_url( get_permalink() ) . '" class="ev-funnel-link">';
				echo esc_html( get_the_title() );
				echo '</a>';
				echo '<p class="ev-funnel-excerpt">' . esc_html( wp_trim_words( get_the_excerpt(), 22 ) ) . '</p>';
				echo '</li>';
			}

			echo '</ul>';
			echo '</section>';
		}

		wp_reset_postdata();

		/*
		 * 3. Z TYM SIĘ JESZCZE ZAPOZNAJ – następny etap lejka
		 */
		if ( $next_stage ) {
			$tax_query_next = ev_build_tax_query( $cat_ids, [ $next_stage ] );

			$args_next = [
				'post_type' => 'post',
				'posts_per_page' => 3,
				'post__not_in' => [ $post_id ],
				'tax_query' => $tax_query_next,
				'orderby' => 'date',
				'order' => 'DESC',
			];

			$q_next = new WP_Query( $args_next );

			if ( $q_next->have_posts() ) {
				echo '<section class="ev-funnel-section ev-funnel-next-stage">';
				$heading_next = 'Z tym się jeszcze zapoznaj';
				if ( $cat_name ) {
					$heading_next .= ' na temat ' . esc_html( $cat_name );
				}
				echo '<h2 class="ev-funnel-heading">' . $heading_next . '</h2>';
				echo '<p class="ev-funnel-intro">To są kolejne wpisy, które pomogą Ci dowiedzieć się więcej:</p>';
				echo '<ul class="ev-funnel-list ev-funnel-list-next">';

				while ( $q_next->have_posts() ) {
					$q_next->the_post();
					echo '<li class="ev-funnel-item">';
					echo '<a href="' . esc_url( get_permalink() ) . '" class="ev-funnel-link">';
					echo esc_html( get_the_title() );
					echo '</a>';
					echo '<p class="ev-funnel-excerpt">' . esc_html( wp_trim_words( get_the_excerpt(), 22 ) ) . '</p>';
					echo '</li>';
				}

				echo '</ul>';
				echo '</section>';
			}

			wp_reset_postdata();
		}

		echo '</div><!-- .ev-funnel-sections -->';
	}
}

/**
 * Widget: wyświetla ev_render_funnel_sections() w obszarach widgetów.
 */
class EV_Funnel_Sections_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'ev_funnel_sections_widget',
			__( 'Lejek treści (TOFU/MOFU/BOFU)', 'ev-funnel' ),
			[
				'description' => __( 'Sekcje „To już powinieneś wiedzieć / Więcej na ten temat / Z tym się jeszcze zapoznaj” dla pojedynczego wpisu.', 'ev-funnel' ),
			]
		);
	}

	public function widget( $args, $instance ) {
		if ( ! is_singular( 'post' ) ) {
			return;
		}

		echo $args['before_widget'];

		// Tytuł widgetu – opcjonalny, możesz zostawić puste w panelu.
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}

		if ( function_exists( 'ev_render_funnel_sections' ) ) {
			ev_render_funnel_sections();
		}

		echo $args['after_widget'];
	}

	public function form( $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : '';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
				<?php esc_html_e( 'Tytuł widgetu (opcjonalnie):', 'ev-funnel' ); ?>
			</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
				value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<em><?php esc_html_e( 'Sam widget pokaże się tylko na pojedynczych wpisach z tagami tofu/mofu/bofu.', 'ev-funnel' ); ?></em>
		</p> <?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = [];
		$instance['title'] = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';

		return $instance;
	}
}

/**
 * Rejestracja widgetu.
 */
function ev_register_funnel_sections_widget() {
	register_widget( 'EV_Funnel_Sections_Widget' );
}
add_action( 'widgets_init', 'ev_register_funnel_sections_widget' );


/**
 * Shortcode: [ev_funnel_sections]
 * Umożliwia użycie lejka treści np. w Blocksy Content Block (Hook).
 */
function ev_funnel_sections_shortcode() {
	if ( ! function_exists( 'ev_render_funnel_sections' ) ) {
		return '';
	}

	// Tylko na pojedynczych wpisach.
	if ( ! is_singular( 'post' ) ) {
		return '';
	}

	ob_start();
	ev_render_funnel_sections();
	return ob_get_clean();
}
add_shortcode( 'ev_funnel_sections', 'ev_funnel_sections_shortcode' );


/**
 * Ładuje style CSS dla widgetu lejka treści.
 */
function ev_funnel_sections_styles() {
	// Tylko na pojedynczych wpisach
	if ( ! is_singular( 'post' ) ) {
		return;
	}

	wp_enqueue_style(
		'evillage-funnel-widget',
		plugin_dir_url( __FILE__ ) . 'evillage-funnel-widget.css',
		array(),
		'1.0.0'
	);
}
add_action( 'wp_enqueue_scripts', 'ev_funnel_sections_styles' );