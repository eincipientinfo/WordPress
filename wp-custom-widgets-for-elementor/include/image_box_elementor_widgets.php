<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Image_Box_WP_Elementor_widget extends Widget_Base {

    public function get_name() {
        return 'wp-custom-image-box';
    }
    
    public function get_title() {
        return __( 'WP: Custom Image Box With Hover Image', 'wp-custom' );
    }

    public function get_icon() {
        return 'eicon-image-box';
    }

    public function get_categories() {
        return [ 'wp-custom' ];
    }

    public function get_style_depends() {
        return [ 'wp-custom-style' ];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'section_image',
            [
                'label' => __( 'Custom Image Box', 'wp-custom' ),
            ]
        );

        $this->add_control(
            'image',
            [
                'label' => __( 'Choose Image', 'wp-custom' ),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'hover_image',
            [
                'label' => __( 'Choose Hover Image', 'wp-custom' ),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
                'default' => 'full',
                'separator' => 'none',
            ]
        );

        $this->add_control(
            'title_text',
            [
                'label' => __( 'Title & Description', 'wp-custom' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => __( 'This is the heading', 'wp-custom' ),
                'placeholder' => __( 'Enter your title', 'wp-custom' ),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'description_text',
            [
                'label' => __( 'Content', 'wp-custom' ),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => __( 'Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'wp-custom' ),
                'placeholder' => __( 'Enter your description', 'wp-custom' ),
                'separator' => 'none',
                'rows' => 10,
                'show_label' => false,
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => __( 'Link', 'wp-custom' ),
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => __( 'https://your-link.com', 'wp-custom' ),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'position',
            [
                'label' => __( 'Image Position', 'wp-custom' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'top',
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'wp-custom' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'top' => [
                        'title' => __( 'Top', 'wp-custom' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'wp-custom' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'prefix_class' => 'elementor-position-',
                'toggle' => false,
            ]
        );

        $this->add_control(
            'title_size',
            [
                'label' => __( 'Title HTML Tag', 'wp-custom' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                    'p' => 'p',
                ],
                'default' => 'h3',
            ]
        );

        $this->add_control(
            'view',
            [
                'label' => __( 'View', 'wp-custom' ),
                'type' => Controls_Manager::HIDDEN,
                'default' => 'traditional',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_image',
            [
                'label' => __( 'Image', 'wp-custom' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_space',
            [
                'label' => __( 'Spacing', 'wp-custom' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 15,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}.elementor-position-right .elementor-wp-custom-image-box-img' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.elementor-position-left .elementor-wp-custom-image-box-img' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.elementor-position-top .elementor-wp-custom-image-box-img' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '(mobile){{WRAPPER}} .elementor-wp-custom-image-box-img' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_size',
            [
                'label' => __( 'Width', 'wp-custom' ) . ' (%)',
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 30,
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'size_units' => [ '%' ],
                'range' => [
                    '%' => [
                        'min' => 5,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-wp-custom-image-box-wrapper .elementor-wp-custom-image-box-img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'hover_animation',
            [
                'label' => __( 'Hover Animation', 'wp-custom' ),
                'type' => Controls_Manager::HOVER_ANIMATION,
            ]
        );

        $this->start_controls_tabs( 'image_effects' );

        $this->start_controls_tab( 'normal',
            [
                'label' => __( 'Normal', 'wp-custom' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'css_filters',
                'selector' => '{{WRAPPER}} .elementor-wp-custom-image-box-img img',
            ]
        );

        $this->add_control(
            'image_opacity',
            [
                'label' => __( 'Opacity', 'wp-custom' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-wp-custom-image-box-img img' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_control(
            'background_hover_transition',
            [
                'label' => __( 'Transition Duration', 'wp-custom' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0.3,
                ],
                'range' => [
                    'px' => [
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-wp-custom-image-box-img img' => 'transition-duration: {{SIZE}}s',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 'hover',
            [
                'label' => __( 'Hover', 'wp-custom' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'css_filters_hover',
                'selector' => '{{WRAPPER}}:hover .elementor-wp-custom-image-box-img img',
            ]
        );

        $this->add_control(
            'image_opacity_hover',
            [
                'label' => __( 'Opacity', 'wp-custom' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}:hover .elementor-wp-custom-image-box-img img' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_content',
            [
                'label' => __( 'Content', 'wp-custom' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'text_align',
            [
                'label' => __( 'Alignment', 'wp-custom' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'wp-custom' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'wp-custom' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'wp-custom' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => __( 'Justified', 'wp-custom' ),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-wp-custom-image-box-wrapper' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'content_vertical_alignment',
            [
                'label' => __( 'Vertical Alignment', 'wp-custom' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'top' => __( 'Top', 'wp-custom' ),
                    'middle' => __( 'Middle', 'wp-custom' ),
                    'bottom' => __( 'Bottom', 'wp-custom' ),
                ],
                'default' => 'top',
                'prefix_class' => 'elementor-vertical-align-',
            ]
        );

        $this->add_control(
            'heading_title',
            [
                'label' => __( 'Title', 'wp-custom' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'title_bottom_space',
            [
                'label' => __( 'Spacing', 'wp-custom' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-image-box-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Color', 'wp-custom' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-wp-custom-image-box-content .elementor-image-box-title' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
            ]
        );

        $this->add_control(
            'title_hover_color',
            [
                'label' => __( 'Hover Color', 'wp-custom' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-wp-custom-image-box-wrapper:hover .elementor-wp-custom-image-box-content .elementor-image-box-title' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .elementor-wp-custom-image-box-content .elementor-image-box-title',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
            ]
        );

        $this->add_control(
            'heading_description',
            [
                'label' => __( 'Description', 'wp-custom' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => __( 'Color', 'wp-custom' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-wp-custom-image-box-content .elementor-image-box-description' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_3,
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .elementor-wp-custom-image-box-content .elementor-image-box-description',
                'scheme' => Scheme_Typography::TYPOGRAPHY_3,
            ]
        );

        $this->end_controls_section();    

    }

    protected function render() {

        $settings = $this->get_settings_for_display();

        $has_content = ! empty( $settings['title_text'] ) || ! empty( $settings['description_text'] );

        $html = '<div class="elementor-wp-custom-image-box-wrapper">';

        if ( ! empty( $settings['link']['url'] ) ) {
            $this->add_render_attribute( 'link', 'href', $settings['link']['url'] );

            if ( $settings['link']['is_external'] ) {
                $this->add_render_attribute( 'link', 'target', '_blank' );
            }

            if ( ! empty( $settings['link']['nofollow'] ) ) {
                $this->add_render_attribute( 'link', 'rel', 'nofollow' );
            }
        }

        if ( ! empty( $settings['image']['url'] ) ) {
            $this->add_render_attribute( 'image', 'src', $settings['image']['url'] );
            $this->add_render_attribute( 'image', 'alt', Control_Media::get_image_alt( $settings['image'] ) );
            $this->add_render_attribute( 'image', 'title', Control_Media::get_image_title( $settings['image'] ) );

            if ( $settings['hover_animation'] ) {
                $this->add_render_attribute( 'image', 'class', 'elementor-animation-' . $settings['hover_animation'] );
            }

            $image_html = Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'image' );

            if ( ! empty( $settings['link']['url'] ) ) {
                $image_html = '<a ' . $this->get_render_attribute_string( 'link' ) . '>' . $image_html . '</a>';
            }

            

            // Image After Hover
            if ( ! empty( $settings['hover_image']['url'] ) ) {
                $this->add_render_attribute( 'hover_image', 'src', $settings['hover_image']['url'] );
                $this->add_render_attribute( 'hover_image', 'alt', Control_Media::get_image_alt( $settings['hover_image'] ) );
                $this->add_render_attribute( 'hover_image', 'title', Control_Media::get_image_title( $settings['hover_image'] ) );


                if ( $settings['hover_animation'] ) {
                    $this->add_render_attribute( 'hover_image', 'class', 'elementor-animation-' . $settings['hover_animation'] );
                }

                $hover_image_html = Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'hover_image' );

                if ( ! empty( $settings['link']['url'] ) ) {
                    $hover_image_html = '<a class="image_after_hover"' . $this->get_render_attribute_string( 'link' ) . '>' . $hover_image_html . '</a>';
                }

                $hover_image_html = '<span class="image_after_hover">' . $hover_image_html . '</span>';

                //$html .= '<figure class="elementor-wp-custom-image-box-img image_after_hover" style="display:none;">' . $hover_image_html . '</figure>';
            }

            if( !empty($hover_image_html) ){
                $image_html = '<span class="image_before_hover">' . $image_html . '</span>';
                $html .= '<figure class="elementor-wp-custom-image-box-img">' . $image_html . $hover_image_html . '</figure>';
            }else{
                $html .= '<figure class="elementor-wp-custom-image-box-img">' . $image_html . '</figure>';
            }


            
        }

        /**/

        if ( $has_content ) {
            $html .= '<div class="elementor-wp-custom-image-box-content">';

            if ( ! empty( $settings['title_text'] ) ) {
                $this->add_render_attribute( 'title_text', 'class', 'elementor-image-box-title' );

                $this->add_inline_editing_attributes( 'title_text', 'none' );

                $title_html = $settings['title_text'];

                if ( ! empty( $settings['link']['url'] ) ) {
                    $title_html = '<a ' . $this->get_render_attribute_string( 'link' ) . '>' . $title_html . '</a>';
                }

                $html .= sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['title_size'], $this->get_render_attribute_string( 'title_text' ), $title_html );
            }

            if ( ! empty( $settings['description_text'] ) ) {
                $this->add_render_attribute( 'description_text', 'class', 'elementor-image-box-description' );

                $this->add_inline_editing_attributes( 'description_text' );

                $html .= sprintf( '<p %1$s>%2$s</p>', $this->get_render_attribute_string( 'description_text' ), $settings['description_text'] );
            }

            $html .= '</div>';
        }

        $html .= '</div>';

        echo $html;
    }

    /**
     * Render Custom image box widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _content_template() {
        ?>
        <#
        var html = '<div class="elementor-wp-custom-image-box-wrapper">';

        if ( settings.image.url ) {
            var image = {
                id: settings.image.id,
                url: settings.image.url,
                size: settings.thumbnail_size,
                dimension: settings.thumbnail_custom_dimension,
                model: view.getEditModel()
            };

            var hover_image = {
                id: settings.hover_image.id,
                url: settings.hover_image.url,
                size: settings.thumbnail_size,
                dimension: settings.thumbnail_custom_dimension,
                model: view.getEditModel()
            };

            var image_url = elementor.imagesManager.getImageUrl( image );
            var hover_image_url = elementor.imagesManager.getImageUrl( hover_image );

            var imageHtml = '<img src="' + image_url + '" class="elementor-animation-' + settings.hover_animation + '" />';
            var hoverimageHtml = '<img src="' + hover_image_url + '" class="elementor-animation-' + settings.hover_animation + '" />';

            if ( settings.link.url ) {
                imageHtml = '<a href="' + settings.link.url + '">' + imageHtml + '</a>';
            }

            html += '<figure class="elementor-wp-custom-image-box-img">' + imageHtml + '</figure>';
        }

        var hasContent = !! ( settings.title_text || settings.description_text );

        if ( hasContent ) {
            html += '<div class="elementor-wp-custom-image-box-content">';

            if ( settings.title_text ) {
                var title_html = settings.title_text;

                if ( settings.link.url ) {
                    title_html = '<a href="' + settings.link.url + '">' + title_html + '</a>';
                }

                view.addRenderAttribute( 'title_text', 'class', 'elementor-image-box-title' );

                view.addInlineEditingAttributes( 'title_text', 'none' );

                html += '<' + settings.title_size  + ' ' + view.getRenderAttributeString( 'title_text' ) + '>' + title_html + '</' + settings.title_size  + '>';
            }

            if ( settings.description_text ) {
                view.addRenderAttribute( 'description_text', 'class', 'elementor-image-box-description' );

                view.addInlineEditingAttributes( 'description_text' );

                html += '<p ' + view.getRenderAttributeString( 'description_text' ) + '>' + settings.description_text + '</p>';
            }

            html += '</div>';
        }

        html += '</div>';

        print( html );
        #>
        <?php
    }

}
Plugin::instance()->widgets_manager->register_widget_type( new Image_Box_WP_Elementor_widget() );