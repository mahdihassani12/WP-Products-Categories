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

	public function get_script_depends() {
		// Elementor can request dependencies before widget settings are hydrated in
		// the editor. Declaring the registered handle is safe and avoids reading a
		// null settings value during editor bootstrap.
		return array( 'mim-product-categories-carousel' );
	}

	/**
	 * Return render settings with safe defaults for old and partially saved widgets.
	 *
	 * @return array
	 */
	private function get_safe_settings() {
		$settings = $this->get_settings_for_display();
		$defaults = array(
			'layout_type' => 'grid', 'number' => 18, 'hide_empty' => 'yes',
			'orderby' => 'name', 'order' => 'ASC', 'parent' => 'top',
			'selected_categories' => array(), 'show_count' => 'yes', 'count_suffix' => __( 'items', 'mim-product-categories' ),
			'show_heading' => 'yes', 'title' => __( 'Featured Categories', 'mim-product-categories' ), 'subtitle' => '',
			'link_text' => '', 'link' => array(), 'show_arrow' => 'yes', 'arrow' => array(),
			'card_shape' => 'square',
			'grid_pagination' => '', 'categories_per_page' => 15,
			'show_image' => 'yes', 'show_image_tablet' => 'yes', 'show_image_mobile' => 'yes',
			'slides_visible' => 6, 'slides_visible_tablet' => 4, 'slides_visible_mobile' => 2,
			'slides_to_scroll' => 1, 'carousel_space' => array( 'size' => 16 ),
			'carousel_space_tablet' => array( 'size' => 16 ), 'carousel_space_mobile' => array( 'size' => 16 ),
			'carousel_speed' => 500, 'autoplay' => '', 'autoplay_delay' => 3000,
			'pause_on_hover' => 'yes', 'infinite_loop' => 'yes', 'center_mode' => '',
			'allow_drag' => 'yes', 'free_mode' => '', 'auto_height' => '',
			'show_navigation' => 'yes', 'show_pagination' => 'yes',
			'carousel_direction' => 'default', 'mobile_breakpoint' => 767, 'tablet_breakpoint' => 1024,
			'previous_icon' => array(), 'next_icon' => array(),
		);

		$settings         = wp_parse_args( is_array( $settings ) ? $settings : array(), $defaults );
		$settings['link'] = is_array( $settings['link'] ) ? $settings['link'] : array();
		foreach ( array( 'arrow', 'previous_icon', 'next_icon', 'carousel_space', 'carousel_space_tablet', 'carousel_space_mobile' ) as $array_setting ) {
			$settings[ $array_setting ] = is_array( $settings[ $array_setting ] ) ? $settings[ $array_setting ] : $defaults[ $array_setting ];
		}

		return $settings;
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

		$this->add_control( 'show_heading', array(
			'label' => esc_html__( 'Show Heading', 'mim-product-categories' ),
			'type' => \Elementor\Controls_Manager::SWITCHER,
			'label_on' => esc_html__( 'Show', 'mim-product-categories' ),
			'label_off' => esc_html__( 'Hide', 'mim-product-categories' ),
			'default' => 'yes',
		) );
		$this->add_control( 'title', array(
			'label' => esc_html__( 'Title', 'mim-product-categories' ),
			'type' => \Elementor\Controls_Manager::TEXT,
			'default' => esc_html__( 'Featured Categories', 'mim-product-categories' ),
			'label_block' => true,
			'condition' => array( 'show_heading' => 'yes' ),
		) );
		$this->add_control( 'subtitle', array(
			'label' => esc_html__( 'Subtitle', 'mim-product-categories' ),
			'type' => \Elementor\Controls_Manager::TEXTAREA,
			'default' => esc_html__( 'Choose your necessary products from these featured categories.', 'mim-product-categories' ),
			'rows' => 2,
			'condition' => array( 'show_heading' => 'yes' ),
		) );
		$this->add_control( 'link_text', array(
			'label' => esc_html__( 'Link Text', 'mim-product-categories' ),
			'type' => \Elementor\Controls_Manager::TEXT,
			'default' => esc_html__( 'View All Categories', 'mim-product-categories' ),
			'condition' => array( 'show_heading' => 'yes' ),
		) );
		$this->add_control( 'link', array(
			'label' => esc_html__( 'Link', 'mim-product-categories' ),
			'type' => \Elementor\Controls_Manager::URL,
			'placeholder' => 'https://example.com/shop/',
			'dynamic' => array( 'active' => true ),
			'condition' => array( 'show_heading' => 'yes' ),
		) );
		$this->add_control( 'show_arrow', array(
			'label' => esc_html__( 'Show Arrow', 'mim-product-categories' ),
			'type' => \Elementor\Controls_Manager::SWITCHER,
			'label_on' => esc_html__( 'Show', 'mim-product-categories' ),
			'label_off' => esc_html__( 'Hide', 'mim-product-categories' ),
			'default' => 'yes',
			'condition' => array( 'show_heading' => 'yes' ),
		) );
		$this->add_control( 'arrow', array(
			'label' => esc_html__( 'Arrow Icon', 'mim-product-categories' ),
			'type' => \Elementor\Controls_Manager::ICONS,
			'default' => array( 'value' => 'fas fa-arrow-right', 'library' => 'fa-solid' ),
			'condition' => array( 'show_heading' => 'yes', 'show_arrow' => 'yes' ),
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
		$this->add_control( 'layout_type', array(
			'label' => esc_html__( 'Layout Type', 'mim-product-categories' ),
			'type' => \Elementor\Controls_Manager::SELECT,
			'default' => 'grid',
			'options' => array(
				'grid' => esc_html__( 'Grid', 'mim-product-categories' ),
				'carousel' => esc_html__( 'Carousel', 'mim-product-categories' ),
			),
		) );
		$this->add_responsive_control( 'columns', array(
			'label' => esc_html__( 'Columns', 'mim-product-categories' ),
			'type' => \Elementor\Controls_Manager::SELECT,
			'desktop_default' => '9',
			'tablet_default' => '4',
			'mobile_default' => '2',
			'options' => array( '1'=>'1', '2'=>'2', '3'=>'3', '4'=>'4', '5'=>'5', '6'=>'6', '7'=>'7', '8'=>'8', '9'=>'9', '10'=>'10', '11'=>'11', '12'=>'12' ),
			'selectors' => array( '{{WRAPPER}} .mim-pc-grid' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));' ),
			'condition' => array( 'layout_type' => 'grid' ),
		) );
		$this->add_responsive_control( 'column_gap', array(
			'label' => esc_html__( 'Column Gap', 'mim-product-categories' ),
			'type' => \Elementor\Controls_Manager::SLIDER,
			'range' => array( 'px' => array( 'min' => 0, 'max' => 60 ) ),
			'default' => array( 'size' => 16, 'unit' => 'px' ),
			'selectors' => array( '{{WRAPPER}} .mim-pc-grid' => 'column-gap: {{SIZE}}{{UNIT}};' ),
			'condition' => array( 'layout_type' => 'grid' ),
		) );
		$this->add_responsive_control( 'row_gap', array(
			'label' => esc_html__( 'Row Gap', 'mim-product-categories' ),
			'type' => \Elementor\Controls_Manager::SLIDER,
			'range' => array( 'px' => array( 'min' => 0, 'max' => 60 ) ),
			'default' => array( 'size' => 10, 'unit' => 'px' ),
			'selectors' => array( '{{WRAPPER}} .mim-pc-grid' => 'row-gap: {{SIZE}}{{UNIT}};' ),
			'condition' => array( 'layout_type' => 'grid' ),
		) );
		$this->add_control( 'grid_pagination', array(
			'label' => esc_html__( 'Enable Pagination', 'mim-product-categories' ),
			'type' => \Elementor\Controls_Manager::SWITCHER,
			'default' => '',
			'condition' => array( 'layout_type' => 'grid' ),
		) );
		$this->add_control( 'categories_per_page', array(
			'label' => esc_html__( 'Categories Per Page', 'mim-product-categories' ),
			'type' => \Elementor\Controls_Manager::NUMBER,
			'default' => 15,
			'min' => 1,
			'max' => 100,
			'condition' => array( 'layout_type' => 'grid', 'grid_pagination' => 'yes' ),
		) );
		$this->end_controls_section();

		$this->register_carousel_controls();
		$this->register_carousel_styles();
		$this->register_grid_pagination_styles();

		$this->register_header_styles();
		$this->register_card_styles();
	}

	private function register_grid_pagination_styles() {
		$condition = array( 'layout_type' => 'grid', 'grid_pagination' => 'yes' );
		$this->start_controls_section( 'grid_pagination_style', array( 'label' => esc_html__( 'Grid Pagination', 'mim-product-categories' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE, 'condition' => $condition ) );
		$this->add_control( 'grid_page_color', array( 'label' => esc_html__( 'Text Color', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .mim-pc-grid-pagination a' => 'color:{{VALUE}};' ) ) );
		$this->add_control( 'grid_page_active_color', array( 'label' => esc_html__( 'Active Page Color', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .mim-pc-grid-pagination .current' => 'color:{{VALUE}};' ) ) );
		$this->add_control( 'grid_page_background', array( 'label' => esc_html__( 'Background Color', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .mim-pc-grid-pagination .page-numbers' => 'background-color:{{VALUE}};' ) ) );
		$this->add_control( 'grid_page_active_background', array( 'label' => esc_html__( 'Active Background Color', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .mim-pc-grid-pagination .current' => 'background-color:{{VALUE}};' ) ) );
		$this->add_control( 'grid_page_border_color', array( 'label' => esc_html__( 'Border Color', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .mim-pc-grid-pagination .page-numbers' => 'border-color:{{VALUE}};' ) ) );
		$this->add_responsive_control( 'grid_page_radius', array( 'label' => esc_html__( 'Border Radius', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'size_units' => array( 'px', '%' ), 'range' => array( 'px' => array( 'min' => 0, 'max' => 50 ), '%' => array( 'min' => 0, 'max' => 50 ) ), 'selectors' => array( '{{WRAPPER}} .mim-pc-grid-pagination .page-numbers' => 'border-radius:{{SIZE}}{{UNIT}};' ) ) );
		$this->add_responsive_control( 'grid_page_font_size', array( 'label' => esc_html__( 'Font Size', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => array( 'px' => array( 'min' => 8, 'max' => 40 ) ), 'selectors' => array( '{{WRAPPER}} .mim-pc-grid-pagination' => 'font-size:{{SIZE}}{{UNIT}};' ) ) );
		$this->add_responsive_control( 'grid_page_gap', array( 'label' => esc_html__( 'Item Spacing', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => array( 'px' => array( 'min' => 0, 'max' => 40 ) ), 'selectors' => array( '{{WRAPPER}} .mim-pc-grid-pagination ul' => 'gap:{{SIZE}}{{UNIT}};' ) ) );
		$this->add_responsive_control( 'grid_page_alignment', array( 'label' => esc_html__( 'Alignment', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::CHOOSE, 'options' => array( 'flex-start' => array( 'title' => esc_html__( 'Left', 'mim-product-categories' ), 'icon' => 'eicon-text-align-left' ), 'center' => array( 'title' => esc_html__( 'Center', 'mim-product-categories' ), 'icon' => 'eicon-text-align-center' ), 'flex-end' => array( 'title' => esc_html__( 'Right', 'mim-product-categories' ), 'icon' => 'eicon-text-align-right' ) ), 'default' => 'center', 'selectors' => array( '{{WRAPPER}} .mim-pc-grid-pagination' => 'justify-content:{{VALUE}};' ) ) );
		$this->end_controls_section();
	}

	private function register_carousel_controls() {
		$condition = array( 'layout_type' => 'carousel' );
		$this->start_controls_section( 'carousel_section', array( 'label' => esc_html__( 'Carousel', 'mim-product-categories' ), 'condition' => $condition ) );
		$this->add_responsive_control( 'slides_visible', array(
			'label' => esc_html__( 'Slides Visible', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::NUMBER,
			'desktop_default' => 6, 'tablet_default' => 4, 'mobile_default' => 2, 'min' => 1, 'max' => 12,
		) );
		$this->add_control( 'slides_to_scroll', array( 'label' => esc_html__( 'Slides to Scroll', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::NUMBER, 'default' => 1, 'min' => 1, 'max' => 12 ) );
		$this->add_responsive_control( 'carousel_space', array(
			'label' => esc_html__( 'Space Between Slides', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::SLIDER,
			'range' => array( 'px' => array( 'min' => 0, 'max' => 100 ) ), 'default' => array( 'size' => 16, 'unit' => 'px' ),
		) );
		$this->add_control( 'carousel_speed', array( 'label' => esc_html__( 'Carousel Speed (ms)', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::NUMBER, 'default' => 500, 'min' => 100, 'max' => 5000, 'step' => 50 ) );
		$this->add_control( 'autoplay', array( 'label' => esc_html__( 'Autoplay', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => '' ) );
		$this->add_control( 'autoplay_delay', array( 'label' => esc_html__( 'Autoplay Delay (ms)', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::NUMBER, 'default' => 3000, 'min' => 500, 'max' => 20000, 'step' => 100, 'condition' => array( 'layout_type' => 'carousel', 'autoplay' => 'yes' ) ) );
		$this->add_control( 'pause_on_hover', array( 'label' => esc_html__( 'Pause Autoplay on Hover', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => 'yes', 'condition' => array( 'layout_type' => 'carousel', 'autoplay' => 'yes' ) ) );
		$switchers = array(
			'infinite_loop' => array( 'Infinite Loop', 'yes' ), 'center_mode' => array( 'Center Mode', '' ),
			'show_navigation' => array( 'Navigation Arrows', 'yes' ), 'show_pagination' => array( 'Pagination Dots', 'yes' ),
			'allow_drag' => array( 'Touch and Mouse Dragging', 'yes' ), 'free_mode' => array( 'Free Mode', '' ),
			'auto_height' => array( 'Auto Height', '' ),
		);
		foreach ( $switchers as $name => $control ) {
			$this->add_control( $name, array( 'label' => esc_html__( $control[0], 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => $control[1] ) );
		}
		$this->add_control( 'carousel_direction', array(
			'label' => esc_html__( 'Direction', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::SELECT, 'default' => 'default',
			'options' => array( 'default' => esc_html__( 'Site Default', 'mim-product-categories' ), 'ltr' => esc_html__( 'Left to Right', 'mim-product-categories' ), 'rtl' => esc_html__( 'Right to Left', 'mim-product-categories' ) ),
		) );
		$this->add_control( 'tablet_breakpoint', array( 'label' => esc_html__( 'Tablet Breakpoint (px)', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::NUMBER, 'default' => 1024, 'min' => 481, 'max' => 1600 ) );
		$this->add_control( 'mobile_breakpoint', array( 'label' => esc_html__( 'Mobile Breakpoint (px)', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::NUMBER, 'default' => 767, 'min' => 320, 'max' => 1200 ) );
		$this->end_controls_section();
	}

	private function register_carousel_styles() {
		$this->start_controls_section( 'navigation_style', array( 'label' => esc_html__( 'Carousel Navigation', 'mim-product-categories' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE, 'condition' => array( 'layout_type' => 'carousel', 'show_navigation' => 'yes' ) ) );
		$this->add_control( 'previous_icon', array( 'label' => esc_html__( 'Previous Arrow Icon', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::ICONS, 'default' => array( 'value' => 'fas fa-chevron-left', 'library' => 'fa-solid' ) ) );
		$this->add_control( 'next_icon', array( 'label' => esc_html__( 'Next Arrow Icon', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::ICONS, 'default' => array( 'value' => 'fas fa-chevron-right', 'library' => 'fa-solid' ) ) );
		$this->add_responsive_control( 'nav_size', array( 'label' => esc_html__( 'Arrow Size', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => array( 'px' => array( 'min' => 8, 'max' => 60 ) ), 'default' => array( 'size' => 16, 'unit' => 'px' ), 'selectors' => array( '{{WRAPPER}} .mim-pc-nav' => 'font-size:{{SIZE}}{{UNIT}};' ) ) );
		$this->add_control( 'nav_color', array( 'label' => esc_html__( 'Arrow Color', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#3f5b92', 'selectors' => array( '{{WRAPPER}} .mim-pc-nav' => 'color:{{VALUE}};' ) ) );
		$this->add_control( 'nav_hover_color', array( 'label' => esc_html__( 'Arrow Hover Color', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .mim-pc-nav:hover' => 'color:{{VALUE}};' ) ) );
		$this->add_control( 'nav_background', array( 'label' => esc_html__( 'Arrow Background', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#ffffff', 'selectors' => array( '{{WRAPPER}} .mim-pc-nav' => 'background-color:{{VALUE}};' ) ) );
		$this->add_control( 'nav_hover_background', array( 'label' => esc_html__( 'Arrow Hover Background', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .mim-pc-nav:hover' => 'background-color:{{VALUE}};' ) ) );
		$this->add_group_control( \Elementor\Group_Control_Border::get_type(), array( 'name' => 'nav_border', 'selector' => '{{WRAPPER}} .mim-pc-nav' ) );
		$this->add_control( 'nav_radius', array( 'label' => esc_html__( 'Arrow Border Radius', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::DIMENSIONS, 'size_units' => array( 'px', '%' ), 'selectors' => array( '{{WRAPPER}} .mim-pc-nav' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ) ) );
		foreach ( array( 'nav_width' => 'Arrow Width', 'nav_height' => 'Arrow Height' ) as $name => $label ) {
			$property = 'nav_width' === $name ? 'width' : 'height';
			$this->add_responsive_control( $name, array( 'label' => esc_html__( $label, 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => array( 'px' => array( 'min' => 20, 'max' => 100 ) ), 'default' => array( 'size' => 40, 'unit' => 'px' ), 'selectors' => array( '{{WRAPPER}} .mim-pc-nav' => $property . ':{{SIZE}}{{UNIT}};' ) ) );
		}
		$this->add_responsive_control( 'nav_horizontal', array( 'label' => esc_html__( 'Arrow Horizontal Position', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => array( 'px' => array( 'min' => -100, 'max' => 100 ) ), 'default' => array( 'size' => 8, 'unit' => 'px' ), 'selectors' => array( '{{WRAPPER}} .mim-pc-prev' => 'inset-inline-start:{{SIZE}}{{UNIT}};', '{{WRAPPER}} .mim-pc-next' => 'inset-inline-end:{{SIZE}}{{UNIT}};' ) ) );
		$this->add_responsive_control( 'nav_vertical', array( 'label' => esc_html__( 'Arrow Vertical Position', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => array( '%' => array( 'min' => 0, 'max' => 100 ) ), 'default' => array( 'size' => 50, 'unit' => '%' ), 'selectors' => array( '{{WRAPPER}} .mim-pc-nav' => 'top:{{SIZE}}{{UNIT}};' ) ) );
		$this->end_controls_section();

		$this->start_controls_section( 'pagination_style', array( 'label' => esc_html__( 'Carousel Pagination', 'mim-product-categories' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE, 'condition' => array( 'layout_type' => 'carousel', 'show_pagination' => 'yes' ) ) );
		$this->add_responsive_control( 'dot_size', array( 'label' => esc_html__( 'Dot Size', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => array( 'px' => array( 'min' => 4, 'max' => 30 ) ), 'default' => array( 'size' => 8, 'unit' => 'px' ), 'selectors' => array( '{{WRAPPER}} .mim-pc-pagination .swiper-pagination-bullet' => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};' ) ) );
		$this->add_responsive_control( 'dot_gap', array( 'label' => esc_html__( 'Space Between Dots', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => array( 'px' => array( 'min' => 0, 'max' => 30 ) ), 'default' => array( 'size' => 4, 'unit' => 'px' ), 'selectors' => array( '{{WRAPPER}} .mim-pc-pagination .swiper-pagination-bullet' => 'margin-inline:{{SIZE}}{{UNIT}};' ) ) );
		$this->add_control( 'dot_color', array( 'label' => esc_html__( 'Dot Color', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#d6dce6', 'selectors' => array( '{{WRAPPER}} .mim-pc-pagination .swiper-pagination-bullet' => 'background-color:{{VALUE}};' ) ) );
		$this->add_control( 'dot_active_color', array( 'label' => esc_html__( 'Active Dot Color', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#3f5b92', 'selectors' => array( '{{WRAPPER}} .mim-pc-pagination .swiper-pagination-bullet-active' => 'background-color:{{VALUE}};' ) ) );
		$this->add_control( 'dot_radius', array( 'label' => esc_html__( 'Dot Border Radius', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'size_units' => array( 'px', '%' ), 'range' => array( 'px' => array( 'min' => 0, 'max' => 30 ), '%' => array( 'min' => 0, 'max' => 50 ) ), 'default' => array( 'size' => 50, 'unit' => '%' ), 'selectors' => array( '{{WRAPPER}} .mim-pc-pagination .swiper-pagination-bullet' => 'border-radius:{{SIZE}}{{UNIT}};' ) ) );
		$this->add_responsive_control( 'pagination_spacing', array( 'label' => esc_html__( 'Pagination Spacing', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => array( 'px' => array( 'min' => 0, 'max' => 100 ) ), 'default' => array( 'size' => 20, 'unit' => 'px' ), 'selectors' => array( '{{WRAPPER}} .mim-pc-pagination' => 'margin-top:{{SIZE}}{{UNIT}};' ) ) );
		$this->add_responsive_control( 'pagination_position', array( 'label' => esc_html__( 'Pagination Position', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::CHOOSE, 'options' => array( 'start' => array( 'title' => esc_html__( 'Start', 'mim-product-categories' ), 'icon' => 'eicon-text-align-left' ), 'center' => array( 'title' => esc_html__( 'Center', 'mim-product-categories' ), 'icon' => 'eicon-text-align-center' ), 'end' => array( 'title' => esc_html__( 'End', 'mim-product-categories' ), 'icon' => 'eicon-text-align-right' ) ), 'default' => 'center', 'selectors' => array( '{{WRAPPER}} .mim-pc-pagination' => 'text-align:{{VALUE}};' ) ) );
		$this->end_controls_section();
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
		$this->add_control( 'card_shape', array(
			'label' => esc_html__( 'Card Shape', 'mim-product-categories' ),
			'type' => \Elementor\Controls_Manager::CHOOSE,
			'options' => array(
				'square' => array( 'title' => esc_html__( 'Square', 'mim-product-categories' ), 'icon' => 'eicon-square' ),
				'circle' => array( 'title' => esc_html__( 'Circle', 'mim-product-categories' ), 'icon' => 'eicon-circle-o' ),
			),
			'default' => 'square',
			'toggle' => false,
		) );
		$this->add_control( 'card_background', array( 'label' => esc_html__( 'Background', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#ffffff', 'selectors' => array( '{{WRAPPER}} .mim-pc-card' => 'background-color: {{VALUE}};' ) ) );
		$this->add_control( 'card_border_color', array( 'label' => esc_html__( 'Border Color', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#d6dce6', 'selectors' => array( '{{WRAPPER}} .mim-pc-card' => 'border-color: {{VALUE}};' ) ) );
		$this->add_control( 'card_hover_border', array( 'label' => esc_html__( 'Hover Border Color', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#3f5b92', 'selectors' => array( '{{WRAPPER}} .mim-pc-card:hover' => 'border-color: {{VALUE}};' ) ) );
		$this->add_responsive_control( 'card_padding', array( 'label' => esc_html__( 'Padding', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::DIMENSIONS, 'size_units' => array( 'px', 'em' ), 'default' => array( 'top'=>12, 'right'=>8, 'bottom'=>12, 'left'=>8, 'unit'=>'px', 'isLinked'=>false ), 'selectors' => array( '{{WRAPPER}} .mim-pc-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ) ) );
		$this->add_control( 'card_radius', array( 'label' => esc_html__( 'Border Radius', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::DIMENSIONS, 'size_units' => array( 'px', '%' ), 'default' => array( 'top'=>4, 'right'=>4, 'bottom'=>4, 'left'=>4, 'unit'=>'px', 'isLinked'=>true ), 'selectors' => array( '{{WRAPPER}} .mim-pc-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ) ) );
		$this->add_responsive_control( 'image_size', array( 'label' => esc_html__( 'Image Size', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => array( 'px' => array( 'min'=>30, 'max'=>200 ) ), 'default' => array( 'size'=>78, 'unit'=>'px' ), 'selectors' => array( '{{WRAPPER}} .mim-pc-image-wrap' => 'height: {{SIZE}}{{UNIT}};', '{{WRAPPER}} .mim-pc-image' => 'max-height: {{SIZE}}{{UNIT}};' ) ) );
		$this->add_responsive_control( 'show_image', array( 'label' => esc_html__( 'Show Image', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => 'yes', 'desktop_default' => 'yes', 'tablet_default' => 'yes', 'mobile_default' => 'yes', 'selectors_dictionary' => array( 'yes' => 'flex', '' => 'none' ), 'selectors' => array( '{{WRAPPER}} .mim-pc-image-wrap' => 'display:{{VALUE}};' ) ) );
		$this->add_responsive_control( 'image_width', array( 'label' => esc_html__( 'Image Width', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'size_units' => array( 'px', '%', 'vw' ), 'range' => array( 'px' => array( 'min' => 1, 'max' => 500 ), '%' => array( 'min' => 1, 'max' => 100 ) ), 'selectors' => array( '{{WRAPPER}} .mim-pc-image' => 'width:{{SIZE}}{{UNIT}}!important;' ) ) );
		$this->add_responsive_control( 'image_height', array( 'label' => esc_html__( 'Image Height', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'size_units' => array( 'px', 'vw' ), 'range' => array( 'px' => array( 'min' => 1, 'max' => 500 ) ), 'selectors' => array( '{{WRAPPER}} .mim-pc-image-wrap' => 'height:{{SIZE}}{{UNIT}};', '{{WRAPPER}} .mim-pc-image' => 'height:{{SIZE}}{{UNIT}};max-height:none;' ) ) );
		$this->add_responsive_control( 'image_max_width', array( 'label' => esc_html__( 'Image Max Width', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'size_units' => array( 'px', '%' ), 'range' => array( 'px' => array( 'min' => 1, 'max' => 500 ), '%' => array( 'min' => 1, 'max' => 100 ) ), 'selectors' => array( '{{WRAPPER}} .mim-pc-image' => 'max-width:{{SIZE}}{{UNIT}};' ) ) );
		$this->add_responsive_control( 'image_aspect_ratio', array( 'label' => esc_html__( 'Image Aspect Ratio', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::SELECT, 'options' => array( '' => esc_html__( 'Original', 'mim-product-categories' ), '1 / 1' => '1:1', '4 / 3' => '4:3', '3 / 2' => '3:2', '16 / 9' => '16:9' ), 'selectors' => array( '{{WRAPPER}} .mim-pc-image' => 'aspect-ratio:{{VALUE}};' ) ) );
		$this->add_responsive_control( 'image_object_fit', array( 'label' => esc_html__( 'Object Fit', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::SELECT, 'default' => 'contain', 'options' => array( 'cover' => esc_html__( 'Cover', 'mim-product-categories' ), 'contain' => esc_html__( 'Contain', 'mim-product-categories' ), 'fill' => esc_html__( 'Fill', 'mim-product-categories' ) ), 'selectors' => array( '{{WRAPPER}} .mim-pc-image' => 'object-fit:{{VALUE}};' ) ) );
		$this->add_responsive_control( 'image_radius', array( 'label' => esc_html__( 'Image Border Radius', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::DIMENSIONS, 'size_units' => array( 'px', '%' ), 'selectors' => array( '{{WRAPPER}} .mim-pc-image' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ) ) );
		$this->add_responsive_control( 'image_spacing', array( 'label' => esc_html__( 'Image Spacing / Margin', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::DIMENSIONS, 'size_units' => array( 'px', 'em', '%' ), 'selectors' => array( '{{WRAPPER}} .mim-pc-image-wrap' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ) ) );
		$this->add_control( 'category_color', array( 'label' => esc_html__( 'Category Name Color', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#3f5b92', 'selectors' => array( '{{WRAPPER}} .mim-pc-name' => 'color: {{VALUE}};' ) ) );
		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array( 'name' => 'category_typography', 'selector' => '{{WRAPPER}} .mim-pc-name' ) );
		$this->add_control( 'count_color', array( 'label' => esc_html__( 'Count Color', 'mim-product-categories' ), 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#8098c9', 'selectors' => array( '{{WRAPPER}} .mim-pc-count' => 'color: {{VALUE}};' ) ) );
		$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array( 'name' => 'count_typography', 'selector' => '{{WRAPPER}} .mim-pc-count' ) );
		$this->end_controls_section();
	}

	private function render_category_card( $category, $settings ) {
		$link = get_term_link( $category );
		if ( is_wp_error( $link ) ) {
			return;
		}
		$thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
		$image        = $thumbnail_id ? wp_get_attachment_image( $thumbnail_id, 'woocommerce_thumbnail', false, array( 'class' => 'mim-pc-image', 'loading' => 'lazy' ) ) : wc_placeholder_img( 'woocommerce_thumbnail', array( 'class' => 'mim-pc-image' ) );
		?>
		<a class="mim-pc-card<?php echo 'circle' === $settings['card_shape'] ? ' mim-pc-card--circle' : ''; ?>" href="<?php echo esc_url( $link ); ?>">
			<span class="mim-pc-image-wrap"><?php echo wp_kses_post( $image ); ?></span>
			<span class="mim-pc-name"><?php echo esc_html( $category->name ); ?></span>
			<?php if ( 'yes' === $settings['show_count'] ) : ?><span class="mim-pc-count"><?php echo esc_html( number_format_i18n( $category->count ) . ' ' . sanitize_text_field( $settings['count_suffix'] ) ); ?></span><?php endif; ?>
		</a>
		<?php
	}

	private function render_grid_pagination( $current_page, $total_pages, $page_key ) {
		if ( $total_pages < 2 ) {
			return;
		}

		$links = paginate_links( array(
			'base' => add_query_arg( $page_key, '%#%' ),
			'format' => '',
			'current' => $current_page,
			'total' => $total_pages,
			'prev_text' => esc_html__( 'Previous', 'mim-product-categories' ),
			'next_text' => esc_html__( 'Next', 'mim-product-categories' ),
			'type' => 'list',
		) );

		if ( $links ) {
			printf( '<nav class="mim-pc-grid-pagination" aria-label="%1$s">%2$s</nav>', esc_attr__( 'Product category pages', 'mim-product-categories' ), wp_kses_post( $links ) );
		}
	}

	protected function render() {
		$settings        = $this->get_safe_settings();
		$is_carousel     = 'carousel' === $settings['layout_type'];
		$show_header     = 'yes' === $settings['show_heading'] && ( $settings['title'] || $settings['subtitle'] || ( $settings['link_text'] && ! empty( $settings['link']['url'] ) ) );
		$allowed_orderby = array( 'name', 'count', 'id', 'menu_order' );
		$orderby         = sanitize_key( $settings['orderby'] );
		$args            = array(
			'taxonomy' => 'product_cat',
			'number' => max( 1, absint( $settings['number'] ) ),
			'hide_empty' => 'yes' === $settings['hide_empty'],
			'orderby' => in_array( $orderby, $allowed_orderby, true ) ? $orderby : 'name',
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
		if ( is_wp_error( $categories ) || ! is_array( $categories ) ) {
			return;
		}

		$grid_page = 1;
		$total_grid_pages = 1;
		$grid_page_key = 'mim_pc_page_' . sanitize_key( $this->get_id() );
		if ( ! $is_carousel && 'yes' === $settings['grid_pagination'] ) {
			$per_page = max( 1, absint( $settings['categories_per_page'] ) );
			$total_grid_pages = (int) ceil( count( $categories ) / $per_page );
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Public, read-only pagination state.
			$requested_page = isset( $_GET[ $grid_page_key ] ) ? absint( wp_unslash( $_GET[ $grid_page_key ] ) ) : 1;
			$grid_page = min( max( 1, $requested_page ), max( 1, $total_grid_pages ) );
			$categories = array_slice( $categories, ( $grid_page - 1 ) * $per_page, $per_page );
		}

		$this->add_render_attribute( 'link', 'class', 'mim-pc-all-link' );
		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes( 'link', $settings['link'] );
		}
		?>
		<section class="mim-pc-wrap<?php echo $is_carousel ? ' mim-pc-is-carousel' : ''; ?>">
			<?php if ( $show_header ) : ?>
			<div class="mim-pc-header">
				<div class="mim-pc-heading">
					<?php if ( $settings['title'] ) : ?><h2 class="mim-pc-title"><?php echo esc_html( $settings['title'] ); ?></h2><?php endif; ?>
					<?php if ( $settings['subtitle'] ) : ?><div class="mim-pc-subtitle"><?php echo wp_kses_post( nl2br( $settings['subtitle'] ) ); ?></div><?php endif; ?>
				</div>
				<?php if ( $settings['link_text'] && ! empty( $settings['link']['url'] ) ) : ?>
					<a <?php echo $this->get_render_attribute_string( 'link' ); ?>>
						<span><?php echo esc_html( $settings['link_text'] ); ?></span>
						<?php if ( 'yes' === $settings['show_arrow'] && ! empty( $settings['arrow']['value'] ) ) : ?><span class="mim-pc-arrow" aria-hidden="true"><?php \Elementor\Icons_Manager::render_icon( $settings['arrow'], array( 'aria-hidden' => 'true' ) ); ?></span><?php endif; ?>
					</a>
				<?php endif; ?>
			</div>
			<?php endif; ?>
			<?php if ( $is_carousel ) :
				$mobile_breakpoint = max( 320, absint( $settings['mobile_breakpoint'] ) );
				$tablet_breakpoint = max( $mobile_breakpoint + 1, absint( $settings['tablet_breakpoint'] ) );
				$config = array(
					'slidesPerView' => max( 1, absint( $settings['slides_visible'] ) ),
					'slidesPerGroup' => max( 1, absint( $settings['slides_to_scroll'] ) ),
					'spaceBetween' => isset( $settings['carousel_space']['size'] ) ? absint( $settings['carousel_space']['size'] ) : 16,
					'speed' => max( 100, absint( $settings['carousel_speed'] ) ),
					'loop' => 'yes' === $settings['infinite_loop'], 'centeredSlides' => 'yes' === $settings['center_mode'],
					'allowTouchMove' => 'yes' === $settings['allow_drag'], 'simulateTouch' => 'yes' === $settings['allow_drag'],
					'freeMode' => 'yes' === $settings['free_mode'], 'autoHeight' => 'yes' === $settings['auto_height'],
					'autoplay' => 'yes' === $settings['autoplay'] ? array( 'delay' => max( 500, absint( $settings['autoplay_delay'] ) ), 'disableOnInteraction' => false, 'pauseOnMouseEnter' => 'yes' === $settings['pause_on_hover'] ) : false,
					'breakpoints' => array(
						0 => array( 'slidesPerView' => max( 1, absint( $settings['slides_visible_mobile'] ) ), 'spaceBetween' => isset( $settings['carousel_space_mobile']['size'] ) ? absint( $settings['carousel_space_mobile']['size'] ) : 16 ),
						$mobile_breakpoint + 1 => array( 'slidesPerView' => max( 1, absint( $settings['slides_visible_tablet'] ) ), 'spaceBetween' => isset( $settings['carousel_space_tablet']['size'] ) ? absint( $settings['carousel_space_tablet']['size'] ) : 16 ),
						$tablet_breakpoint + 1 => array( 'slidesPerView' => max( 1, absint( $settings['slides_visible'] ) ), 'spaceBetween' => isset( $settings['carousel_space']['size'] ) ? absint( $settings['carousel_space']['size'] ) : 16 ),
					),
				);
				$direction = in_array( $settings['carousel_direction'], array( 'ltr', 'rtl' ), true ) ? $settings['carousel_direction'] : ( is_rtl() ? 'rtl' : 'ltr' );
				?>
				<div class="mim-pc-carousel-shell" dir="<?php echo esc_attr( $direction ); ?>">
					<div class="mim-pc-carousel swiper" data-carousel-options="<?php echo esc_attr( wp_json_encode( $config ) ); ?>">
						<div class="swiper-wrapper">
							<?php foreach ( $categories as $category ) : ?><div class="swiper-slide"><?php $this->render_category_card( $category, $settings ); ?></div><?php endforeach; ?>
						</div>
					</div>
					<?php if ( 'yes' === $settings['show_navigation'] ) : ?>
						<button class="mim-pc-nav mim-pc-prev" type="button" aria-label="<?php echo esc_attr__( 'Previous categories', 'mim-product-categories' ); ?>"><?php if ( ! empty( $settings['previous_icon']['value'] ) ) { \Elementor\Icons_Manager::render_icon( $settings['previous_icon'], array( 'aria-hidden' => 'true' ) ); } ?></button>
						<button class="mim-pc-nav mim-pc-next" type="button" aria-label="<?php echo esc_attr__( 'Next categories', 'mim-product-categories' ); ?>"><?php if ( ! empty( $settings['next_icon']['value'] ) ) { \Elementor\Icons_Manager::render_icon( $settings['next_icon'], array( 'aria-hidden' => 'true' ) ); } ?></button>
					<?php endif; ?>
					<?php if ( 'yes' === $settings['show_pagination'] ) : ?><div class="mim-pc-pagination" aria-label="<?php echo esc_attr__( 'Carousel pagination', 'mim-product-categories' ); ?>"></div><?php endif; ?>
				</div>
			<?php else : ?>
				<div class="mim-pc-grid"><?php foreach ( $categories as $category ) { $this->render_category_card( $category, $settings ); } ?></div>
				<?php $this->render_grid_pagination( $grid_page, $total_grid_pages, $grid_page_key ); ?>
			<?php endif; ?>
		</section>
		<?php
	}
}
