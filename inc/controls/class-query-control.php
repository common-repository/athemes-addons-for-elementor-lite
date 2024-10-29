<?php
namespace aThemes_Addons\Controls;

use \Elementor\Base_Data_Control;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor custom query control
 *
 * @since 1.0.0
 */
class Query_Control extends Base_Data_Control {
	public function get_type() {
		return 'aafe-query';
	}

	protected function get_default_settings() {
        return [
            'multiple' => false,
            'source_name' => 'post_type',
            'source_type' => 'post',
        ];
    }

	public function content_template() {
        $control_uid = $this->get_control_uid();
        ?>
        <# var controlUID = '<?php echo esc_html( $control_uid ); ?>'; #>
        <# var currentID = elementor.panel.currentView.currentPageView.model.attributes.settings.attributes[data.name]; #>
        <div class="elementor-control-field">
            <# if ( data.label ) { #>
            <label for="<?php echo esc_attr( $control_uid ); ?>" class="elementor-control-title">{{{data.label }}}</label>
            <# } #>
            <div class="elementor-control-input-wrapper elementor-control-unit-5">
                <# var multiple = ( data.multiple ) ? 'multiple' : ''; #>
                <select id="<?php echo esc_attr( $control_uid ); ?>" {{ multiple }} class="ea-select2" data-setting="{{ data.name }}"></select>
            </div>
        </div>
        <#
        ( function( $ ) {
        $( document.body ).trigger( 'aafe_query_control_init',{currentID:data.controlValue,data:data,controlUID:controlUID,multiple:data.multiple} );
        }( jQuery ) );
        #>
        <?php
    }
}