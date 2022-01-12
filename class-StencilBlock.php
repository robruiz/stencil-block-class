<?php

/**
 * Class for interfacing with a StencilJS web component to build a block
 *
 */
class StencilBlock
{

    public $handle, $filename, $rel_path, $esm, $main_filepath, $esm_filepath;

    function __construct($handle, $filename, $rel_path=false){
        $this->handle = $handle; $this->filename = $filename; $this->rel_path = $rel_path;
        $this->esm = $filename.'.esm';
        if(empty($this->rel_path)){
            $this->main_filepath = $this->filename.'.js';
            $this->esm_filepath = $this->esm .'.js';
        } else {
            $this->main_filepath = plugin_dir_url(__FILE__) . $this->rel_path . $this->filename.'.js';
            $this->esm_filepath = plugin_dir_url(__FILE__) . $this->rel_path . $this->esm .'.js';
        }

        $this->hooks();
    }

    function hooks(){
        add_action('init', array( $this, 'stencil_block_register_component') );
        add_filter( 'script_loader_tag', array( $this, 'enqueue_stencil_components_proper' ), 10, 2);
        add_action( 'enqueue_block_assets', array( $this, 'stencil_blocks_enqueue_component_editor' ) );
        add_action( 'init', array( $this, 'create_stencil_block_init' ) );
    }

    function stencil_block_register_component(){
        wp_register_script($this->handle, $this->main_filepath);
        wp_register_script($this->handle . '-esm', $this->esm_filepath);
    }

    function enqueue_stencil_components_proper($tag, $handle) {
        if ( $this->handle . '-esm' === $handle){
            $src = $this->esm_filepath;
            $tag = '<script type="module" src="' . esc_url( $src ) . '"></script>';
        }
        if ( $this->handle === $handle){
            $src = $this->main_filepath;
            $tag = '<script nomodule src="' . esc_url( $src ) . '"></script>';
        }
        return $tag;
    }

    function stencil_blocks_enqueue_component_editor() {
        wp_enqueue_script( $this->handle );
        wp_enqueue_script( $this->handle . '-esm' );
    }

    function create_stencil_block_init() {
        register_block_type( __DIR__, array(
            'render_callback' => function( $attribs, $content ) {
                wp_enqueue_script( $this->handle );
                wp_enqueue_script( $this->handle . '-esm' );
                return $content;
            },
        ) );
    }

}