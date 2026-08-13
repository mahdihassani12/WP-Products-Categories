<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Mim_Product_Categories_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'mim-product-categories';
	}

	public function get_title() {
		return esc_html__( 'Mim Product Categories', 'mim-product-categories' );
	}

	public function get_icon() {
		return 'eicon-product-categories';
	}

	public function get_categories() {
		return array( 'general' );
	}

	public function get_keywords() {
		return array( 'woocommerce', 'product', 'categories', 'shop', 'mim' );
	}

	public function get_style_depends() {
		return array( 'mim-product-categories' );
	}

	private function get_product_category_options() {
		$options = array();
		$terms   = get_terms( array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => false,
			'orderby'    => 'name',
			'order'      => 'ASC',
		) );

		if ( is_wp_error( $terms ) ) {
			return $options;
		}

		foreach ( $terms as $term ) {
			$options[ $term->term_id ] = $term->name;
		}

		return $options;
	}

	protected function register_controls() {
		$this->start_controls_section( 'content_section', array( 'label' => esc_html__( 'Content', 'mim-product-categories' ) ) );

		$this->add_control( 'title', array(
			'label' => esc_html__( 'Title', 'mim-product-categories' ),
			'type' => \Elementor\Controls_Manager::TEXT,
			'default' => esc_html__( 'Featured Categories', 'mim-product-categories' ),
			'label_block' => true,
		) );
		$this->add_control( 'subtitle', array(
			'label' => esc_html__( 'Subtitle', 'mim-product-categories' ),
			'type' => \Elementor\Controls_Manager::TEXTAREA,
			'default' => esc_html__( 'Choose your necessary products from these featured categories.', 'mim-product-categories' ),
			'rows' => 2,
		) );
		$this->add_control( 'link_text', array(
			'label' => esc_html__( 'Link Text', 'mim-product-categories' ),
			'type' => \Elementor\Controls_Manager::TEXT,
			'default' => esc_html__( 'View All Categories', 'mim-product-categories' ),
		) );
		$this->add_control( 'link', array(
			'label' => esc_html__( 'Link', 'mim-product-categories' ),
			'type' => \Elementor\Controls_Manager::URL,
			'placeholder' => 'https://example.com/shop/',
			'dynamic' => array( 'active' => true ),
		) );
		$this->add_control( 'show_arrow', array(
			'label' => esc_html__( 'Show Arrow', 'mim-product-categories' ),
			'type' => \Elementor\Controls_Manager::SWITCHER,
			'label_on' => esc_html__( 'Show', 'mim-product-categories' ),
			'label_off' => esc_html__( 'Hide', 'mim-product-categories' ),
			'default' => 'yes',
		) );
		$this->add_control( 'arrow', array(
			'label' => esc_html__( 'Arrow Icon', 'mim-product-categories' ),
			'type' => \Elementor\Controls_Manager::ICONS,
			'default' => array( 'value' => 'fas fa-arrow-right', 'library' => 'fa-solid' ),
			'condition' => array( 'show_arrow' => 'yes' ),
		) );
		$this->end_controls_section();

		$this->start_controls_section( 'query_section', array( 'label' => esc_html__( 'Categories Query', 'mim-product-categories' ) ) );
		$this->add_control( 'selected_categories', array(
			'label' => esc_html__( 'Choose Categories', 'mim-product-categories' ),
			'type' => \Elementor\Controls_Manager::SELECT2,
			'options' => $this->get_product_category_options(),
			'multiple' => true,
			'label_block' => true,
			'description' => esc_html__( 'Search by category name and select one or more categories. Leave empty to display categories automatically.', 'mim-product-categories' ),
		) );
		$this->add_control( 'number', array(
			'label' => esc_html__( 'Number of Categories', 'mim-product-categories' ),
			'type' => \Elementor\Controls_Manager::NUMBER,
			'default' => 18,
			'min' => 1,
			'max' => 100,
		) );
		$this->add_control( 'parent', array(
			'label' => esc_html__( 'Category Level', 'mim-product-categories' ),
			'type' => \Elementor\Controls_Manager::SELECT,
			'default' => 'top',
			'options' => array(
				'top' => esc_html__( 'Top-level only', 'mim-product-categories' ),
				'all' => esc_html__( 'All categories', 'mim-product-categories' ),
			),
		) );
		$this->add_control( 'hide_empty', array(
			'label' => esc_html__( 'Hide Empty Categories', 'mim-product-categories' ),
			'type' => \Elementor\Controls_Manager::SWITCHER,
			'default' => 'yes',
		) );
		$this->add_control( 'orderby', array(
			'label' => esc_html__( 'Order By', 'mim-product-categories' ),
			'type' => \Elementor\Controls_Manager::SELECT,
			'default' => 'name',
			'options' => array(
				'name' => esc_html__( 'Name', 'mim-product-categories' ),
				'count' => esc_html__( 'Product Count', 'mim-product-categories' ),
				'id' => esc_html__( 'Category ID', 'mim-product-categories' ),
				'menu_order' => esc_html__( 'Menu Order', 'mim-product-categories' ),
			),
		) );
		$this->add_control( 'order', array(
			'label' => esc_html__( 'Order', 'mim-product-categories' ),
			'type' => \Elementor\Controls_Manager::SELECT,
			'default' => 'ASC',
			'options' => array( 'ASC' => 'ASC', 'DESC' => 'DESC' ),
		) );
		$this->add_control( 'show_count', array(
			'label' => esc_html__( 'Show Product Count', 'mim-product-categories' ),
			'type' => \Elementor\Controls_Manager::SWITCHER,
			'default' => 'yes',
		) );
		$this->add_control( 'count_suffix', array(
			'label' => esc_html__( 'Count Suffix', 'mim-product-categories' ),
			'type' => \Elementor\Controls_Manager::TEXT,
			'default' => esc_html__( 'items', 'mim-product-categories' ),
			'condition' => array( 'show_count' => 'yes' ),
		) );
		$this->end_controls_section();

		$this->start_controls_section( 'layout_section', array( 'label' => esc_html__( 'Layout', 'mim-product-categories' ) ) );
		$this->add_responsive_control( 'columns', array(
			'label' => esc_html__( 'Columns', 'mim-product-categories' ),
			'type' => \Elementor\Controls_Manager::SELECT,
			'desktop_default' => '9',
			'tablet_default' => '4',
			'mobile_default' => '2',
			'options' => array( '1'=>'1', '2'=>'2', '3'=>'3', '4'=>'4', '5'=>'5', '6'=>'6', '7'=>'7', '8'=>'8', '9'=>'9', '10'=>'10', '11'=>'11', '12'=>'12' ),
			'selectors' => array( '{{WRAPPER}} .mim-pc-grid' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));' ),
		) );
		$this->add_responsive_control( 'column_gap', array(
			'label' => esc_html__( 'Column Gap', 'mim-product-categories' ),
			'type' => \Elementor\Controls_Manager::SLIDER,
			'range' => array( 'px' => array( 'min' => 0, 'max' => 60 ) ),
			'default' => array( 'size' => 16, 'unit' => 'px' ),
			'selectors' => array( '{{WRAPPER}} .mim-pc-grid' => 'column-gap: {{SIZE}}{{UNIT}};' ),
		) );
		$this->add_responsive_control( 'row_gap', array(
			'label' => esc_html__( 'Row Gap', 'mim-product-categories' ),
			'type' => \Elementor\Controls_Manager::SLIDER,
			'range' => array( 'px' => array( 'min' => 0, 'max' => 60 ) ),
			'default' => array( 'size' => 10, 'unit' => 'px' ),
			'selectors' => array( '{{WRAPPER}} .mim-pc-grid' => 'row-gap: {{SIZE}}{{UNIT}};' ),
		) );
		$this->end_controls_section();

		$this->register_header_styles();
		$this->register_card_styles();
	}

	private function register_header_styles() {
		$this->start_controls_section( 'header_style', array( 'label' => esc_html__( 'Header', 'mim-product-categories' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE ) );
		$this->add_responsive_control( 'header_gap', array(
			'label' => esc_html__( 'Header Bottom Spacing', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::SLIDER,
			'range' => array( 'px' => array( 'min' => 0, 'max' => 100 ) ), 'default' => array( 'size' => 30, 'unit' => 'px' ),
			'selectors' => array( '{{WRAPPER}} .mim-pc-header' => 'padding-bottom: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}};' ),
		) );
		$this->add_control( 'divider_color', array( 'label' => esc_html__( 'Divider Color', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#d6dce6', 'selectors' => array( '{{WRAPPER}} .mim-pc-header' => 'border-color: {{VALUE}};' ) ) );
		$this->add_control( 'title_color', array( 'label' => esc_html__( 'Title Color', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#3f5b92', 'selectors' => array( '{{WRAPPER}} .mim-pc-title' => 'color: {{VALUE}};' ) ) );
		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array( 'name' => 'title_typography', 'selector' => '{{WRAPPER}} .mim-pc-title' ) );
		$this->add_control( 'subtitle_color', array( 'label' => esc_html__( 'Subtitle Color', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#3f5b92', 'selectors' => array( '{{WRAPPER}} .mim-pc-subtitle' => 'color: {{VALUE}};' ) ) );
		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array( 'name' => 'subtitle_typography', 'selector' => '{{WRAPPER}} .mim-pc-subtitle' ) );
		$this->add_control( 'link_color', array( 'label' => esc_html__( 'Link Color', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#3f5b92', 'selectors' => array( '{{WRAPPER}} .mim-pc-all-link' => 'color: {{VALUE}};' ) ) );
		$this->add_control( 'link_hover_color', array( 'label' => esc_html__( 'Link Hover Color', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#1f3765', 'selectors' => array( '{{WRAPPER}} .mim-pc-all-link:hover' => 'color: {{VALUE}};' ) ) );
		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array( 'name' => 'link_typography', 'selector' => '{{WRAPPER}} .mim-pc-all-link' ) );
		$this->end_controls_section();
	}

	private function register_card_styles() {
		$this->start_controls_section( 'card_style', array( 'label' => esc_html__( 'Category Cards', 'mim-product-categories' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE ) );
		$this->add_control( 'card_background', array( 'label' => esc_html__( 'Background', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#ffffff', 'selectors' => array( '{{WRAPPER}} .mim-pc-card' => 'background-color: {{VALUE}};' ) ) );
		$this->add_control( 'card_border_color', array( 'label' => esc_html__( 'Border Color', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#d6dce6', 'selectors' => array( '{{WRAPPER}} .mim-pc-card' => 'border-color: {{VALUE}};' ) ) );
		$this->add_control( 'card_hover_border', array( 'label' => esc_html__( 'Hover Border Color', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#3f5b92', 'selectors' => array( '{{WRAPPER}} .mim-pc-card:hover' => 'border-color: {{VALUE}};' ) ) );
		$this->add_responsive_control( 'card_padding', array( 'label' => esc_html__( 'Padding', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::DIMENSIONS, 'size_units' => array( 'px', 'em' ), 'default' => array( 'top'=>12, 'right'=>8, 'bottom'=>12, 'left'=>8, 'unit'=>'px', 'isLinked'=>false ), 'selectors' => array( '{{WRAPPER}} .mim-pc-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ) ) );
		$this->add_control( 'card_radius', array( 'label' => esc_html__( 'Border Radius', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::DIMENSIONS, 'size_units' => array( 'px', '%' ), 'default' => array( 'top'=>4, 'right'=>4, 'bottom'=>4, 'left'=>4, 'unit'=>'px', 'isLinked'=>true ), 'selectors' => array( '{{WRAPPER}} .mim-pc-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ) ) );
		$this->add_responsive_control( 'image_size', array( 'label' => esc_html__( 'Image Size', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => array( 'px' => array( 'min'=>30, 'max'=>200 ) ), 'default' => array( 'size'=>78, 'unit'=>'px' ), 'selectors' => array( '{{WRAPPER}} .mim-pc-image-wrap' => 'height: {{SIZE}}{{UNIT}};', '{{WRAPPER}} .mim-pc-image' => 'max-height: {{SIZE}}{{UNIT}};' ) ) );
		$this->add_control( 'category_color', array( 'label' => esc_html__( 'Category Name Color', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#3f5b92', 'selectors' => array( '{{WRAPPER}} .mim-pc-name' => 'color: {{VALUE}};' ) ) );
		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array( 'name' => 'category_typography', 'selector' => '{{WRAPPER}} .mim-pc-name' ) );
		$this->add_control( 'count_color', array( 'label' => esc_html__( 'Count Color', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#8098c9', 'selectors' => array( '{{WRAPPER}} .mim-pc-count' => 'color: {{VALUE}};' ) ) );
		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array( 'name' => 'count_typography', 'selector' => '{{WRAPPER}} .mim-pc-count' ) );
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$args = array(
			'taxonomy' => 'product_cat',
			'number' => max( 1, absint( $settings['number'] ) ),
			'hide_empty' => 'yes' === $settings['hide_empty'],
			'orderby' => sanitize_key( $settings['orderby'] ),
			'order' => 'DESC' === $settings['order'] ? 'DESC' : 'ASC',
		);
		$selected_categories = ! empty( $settings['selected_categories'] ) && is_array( $settings['selected_categories'] )
			? array_filter( array_map( 'absint', $settings['selected_categories'] ) )
			: array();
		if ( $selected_categories ) {
			$args['include'] = $selected_categories;
			$args['orderby'] = 'include';
		} elseif ( 'top' === $settings['parent'] ) {
			$args['parent'] = 0;
		}
		$categories = get_terms( $args );
		if ( is_wp_error( $categories ) ) {
			return;
		}

		$this->add_render_attribute( 'link', 'class', 'mim-pc-all-link' );
		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes( 'link', $settings['link'] );
		}
		?>
		<section class="mim-pc-wrap">
			<div class="mim-pc-header">
				<div class="mim-pc-heading">
					<?php if ( $settings['title'] ) : ?><h2 class="mim-pc-title"><?php echo esc_html( $settings['title'] ); ?></h2><?php endif; ?>
					<?php if ( $settings['subtitle'] ) : ?><div class="mim-pc-subtitle"><?php echo wp_kses_post( nl2br( $settings['subtitle'] ) ); ?></div><?php endif; ?>
				</div>
				<?php if ( $settings['link_text'] && ! empty( $settings['link']['url'] ) ) : ?>
					<a <?php echo $this->get_render_attribute_string( 'link' ); ?>>
						<span><?php echo esc_html( $settings['link_text'] ); ?></span>
						<?php if ( 'yes' === $settings['show_arrow'] ) : ?><span class="mim-pc-arrow" aria-hidden="true"><?php \Elementor\Icons_Manager::render_icon( $settings['arrow'], array( 'aria-hidden' => 'true' ) ); ?></span><?php endif; ?>
					</a>
				<?php endif; ?>
			</div>
			<div class="mim-pc-grid">
				<?php foreach ( $categories as $category ) :
					$link = get_term_link( $category );
					if ( is_wp_error( $link ) ) { continue; }
					$thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
					$image = $thumbnail_id ? wp_get_attachment_image( $thumbnail_id, 'woocommerce_thumbnail', false, array( 'class'=>'mim-pc-image', 'loading'=>'lazy' ) ) : wc_placeholder_img( 'woocommerce_thumbnail', array( 'class'=>'mim-pc-image' ) );
					?>
					<a class="mim-pc-card" href="<?php echo esc_url( $link ); ?>">
						<span class="mim-pc-image-wrap"><?php echo wp_kses_post( $image ); ?></span>
						<span class="mim-pc-name"><?php echo esc_html( $category->name ); ?></span>
						<?php if ( 'yes' === $settings['show_count'] ) : ?><span class="mim-pc-count"><?php echo esc_html( number_format_i18n( $category->count ) . ' ' . $settings['count_suffix'] ); ?></span><?php endif; ?>
					</a>
				<?php endforeach; ?>
			</div>
		</section>
		<?php
	}
}
