<?php
/**
 * Elementor BS Post Widget
 *
 * Elementor widget to show posts on any page.
 *
 * @since 1.0.0
 */
class BS_Posts_widget extends \Elementor\Widget_Base {
	public function get_name() {
		return 'bs-posts-widget';
	}
	public function get_title() {
		return __( 'BS Posts Widget', 'backstrap-elementor-addons' );
	}
	public function get_icon() {
		return 'eicon-posts-grid';
	}
	public function get_categories() {
		return [ 'backstrap' ];
	}

	public function get_wp_post_categories() {
		$categories = get_categories( ['hide_empty' => false] );
		$cat_list = [ '' => 'all'];
		foreach ($categories as $category) {
			$cat_list[$category->term_id] = $category->cat_name;
		}

		return $cat_list;
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'post_type_content',
			[
				'label' => __( 'Post Config', 'backstrap-elementor-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'select_post_type',
			[
				'label' => __( 'Select Post Type', 'backstrap-elementor-addons' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => get_post_types(['public' => true], 'names'),
				'default' => 'post'
			]
		);
		$this->add_control(
			'select_post_category',
			[
				'label' => __( 'Select Category', 'backstrap-elementor-addons' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $this->get_wp_post_categories(),
				'default' => '',
				'condition' => ['select_post_type' => 'post']
			]
		);
		$this->add_control(
			'posts_per_page',
			[
				'label' => __( 'Posts per page', 'backstrap-elementor-addons' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => -1,
				'max' => 100,
				'step' => 1,
				'default' => 3
			]
		);
		$this->add_responsive_control(
			'posts_per_row',
			[
				'label' => __( 'Posts per row', 'backstrap-elementor-addons' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => array(
					'3' => '4 Columns',
					'4' => '3 Columns',
					'6' => '2 Columns',
				),
				'default' => '4'
			]
		);
		
		$this->add_control(
			'default_image',
			[
				'label' => __( 'Post default image', 'backstrap-elementor-addons' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				]
			]
		);
		
		$this->end_controls_section();
		// End Post Config

		$this->start_controls_section(
			'post_type_options',
			[
				'label' => __( 'Post Options', 'backstrap-elementor-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'preview_type',
			[
				'label' => __( 'Layout Type', 'backstrap-elementor-addons' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => array(
					'1' => 'Layout 1',
					'2' => 'Layout 2',
					'3' => 'Layout 3',
				),
				'default' => '1'
			]
		);
		$this->add_control(
			'show_excerpt',
			[
				'label' => __( 'Show Excerpt', 'backstrap-elementor-addons' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'backstrap-elementor-addons' ),
				'label_off' => __( 'No', 'backstrap-elementor-addons' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => ['preview_type' => '2']
			]
		);

		$this->end_controls_section();





		// Style Control section start
		$this->start_controls_section(
			'post_title_style',
			[
				'label' => __( 'Title Section', 'backstrap-elementor-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Title Color', 'backstrap-elementor-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .bs-post-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'background',
				'label' => __( 'Title Background', 'backstrap-elementor-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .bs-post-title',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .bs-post-title',
			]
		);

		$this->end_controls_section();
		// Title style ends


		// Image style start
		$this->start_controls_section(
			'post_image_style',
			[
				'label' => __( 'Image Style', 'backstrap-elementor-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->end_controls_section();
		// Image style ends

		// Wrapper style starts
		$this->start_controls_section(
			'post_wrap_style',
			[
				'label' => __( 'Wrapper Style', 'backstrap-elementor-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'label' => __( 'Border', 'backstrap-elementor-addons' ),
				'selector' => '{{WRAPPER}} .bs-post-wrapper',
			]
		);
		$this->add_control(
			'border-radius',
			[
				'label' => __( 'Border Radius', 'backstrap-elementor-addons' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bs-post-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'label' => __( 'Box Shadow', 'backstrap-elementor-addons' ),
				'selector' => '{{WRAPPER}} .bs-single-post',
			]
		);
		$this->add_control(
			'box_padding',
			[
				'label' => __( 'Box Padding', 'backstrap-elementor-addons' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bs-single-post' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		global $post;
		$counter = 1;
		if($settings['posts_per_row'] == '3') {
			$post_per_row = 4;
		} elseif($settings['posts_per_row'] == '4') {
			$post_per_row = 3;
		} else {
			$post_per_row = 2;
		}

		
		$args = array(
			'numberposts' => $settings['posts_per_page'],
			'post_type'   => $settings['select_post_type'],
			'category' 	  => implode (", ", !empty($settings['select_post_category']) ? $settings['select_post_category'] : []),
		);
		 
		$latest_posts = get_posts( $args );


		/**
		 * Layout starts
		 * Layout preiview_type added
		 */
		if($settings['preview_type'] == '1') {
			echo '<div class="row bs-posts-wrapper bs-layout-1">';

			foreach($latest_posts as $post) :
				setup_postdata( $post ); ?>

				<div class="col-md-<?php echo $settings['posts_per_row']; ?>">
					<?php 
						if( has_post_thumbnail() ) {
							$thumbnail = get_the_post_thumbnail_url( get_the_ID(), 'large' );
						} else {
							$thumbnail = $settings['default_image']['url'];
						}
					?>
					<a href="<?php the_permalink(); ?>" class="bs-post-wrapper" style="background-image: url(<?php echo $thumbnail; ?>)">
						<h4 class="bs-post-title"><?php the_title(); ?></h4>
					</a>
				</div>

			<?php
				if( $counter % $post_per_row == 0 && $counter != $settings['posts_per_page'] ) {
					echo '</div><div class="row bs-posts-wrapper bs-layout-1">';
				}
				$counter++;

			endforeach;
			wp_reset_postdata();

			echo '</div>';
		} elseif($settings['preview_type'] == '2') {
			echo '<div class="row bs-posts-wrapper bs-layout-2">';

			foreach($latest_posts as $post) :
				setup_postdata( $post ); ?>

				<div class="col-md-<?php echo $settings['posts_per_row']; ?>">
					<?php 
						if( has_post_thumbnail() ) {
							$thumbnail = get_the_post_thumbnail_url( get_the_ID(), 'large' );
						} else {
							$thumbnail = $settings['default_image']['url'];
						}
					?>
					<div class="bs-single-post">
						<div class="bs-post-wrapper" style="background-image: url(<?php echo $thumbnail; ?>)"></div>
						<a href="<?php the_permalink(); ?>" class="bs-post-title"><?php the_title(); ?></a>
						<?php $settings['show_excerpt'] == 'yes' ? the_excerpt() : '';  ?>
					</div>
				</div>

			<?php
				if( $counter % $post_per_row == 0 && $counter != $settings['posts_per_page'] ) {
					echo '</div><div class="row bs-posts-wrapper bs-layout-2">';
				}
				$counter++;

			endforeach;
			wp_reset_postdata();

			echo '</div>';
		} elseif($settings['preview_type'] == '3') {
			echo '<div class="row bs-posts-wrapper bs-layout-3">';

			foreach($latest_posts as $post) :
				setup_postdata( $post ); ?>

				<div class="col-md-<?php echo $settings['posts_per_row']; ?>">
					<?php 
						if( has_post_thumbnail() ) {
							$thumbnail = get_the_post_thumbnail_url( get_the_ID(), 'large' );
						} else {
							$thumbnail = $settings['default_image']['url'];
						}
					?>
					<div class="bs-single-post">
						<div class="bs-post-img-box">
							<img src="<?php echo $thumbnail; ?>" alt="<?php the_title(); ?>">
						</div>
						<a href="<?php the_permalink(); ?>" class="bs-post-title"><?php the_title(); ?></a>
						<?php $settings['show_excerpt'] == 'yes' ? the_excerpt() : '';  ?>
					</div>
				</div>

			<?php
				if( $counter % $post_per_row == 0 && $counter != $settings['posts_per_page'] ) {
					echo '</div><div class="row bs-posts-wrapper bs-layout-2">';
				}
				$counter++;

			endforeach;
			wp_reset_postdata();

			echo '</div>';
		}
	}
}