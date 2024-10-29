<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<?php if ( ! empty( $aafe_widgets ) ) : ?>

<div class="athemes-addons-modules-box">

		<div class="athemes-addons-module-filter">
			<div class="filter-wrapper">
				<div class="module-filter-group">
					<?php $categories = athemes_addons_get_widget_categories( $aafe_widgets ); ?>
					<select class="athemes-addons-modules-filter-button-category">
						<option value="all"><?php esc_html_e( 'All Categories', 'athemes-addons-elementor' ); ?></option>
						<?php foreach ( $categories as $category ) : ?>
							<option value="<?php echo esc_attr( $category ); ?>"><?php echo esc_html( ucfirst( str_replace( '-', ' ', $category ) ) ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>			
				<div class="module-filter-group athemes-addons-module-page-setting-field-radio">
					<div>
						<label for="all-modules-status" data-filter="all">
							<input type="radio" id="all-modules" name="athemes-addons-status-filter" value="all" checked>
							<span><?php esc_html_e( 'All', 'athemes-addons-elementor' ); ?></span>
						</label>
						<label for="active-modules" data-filter="active">
							<input type="radio" id="active-modules" name="athemes-addons-status-filter" value="active">
							<span><?php esc_html_e( 'Active', 'athemes-addons-elementor' ); ?></span>
						</label>
						<label for="inactive-modules" data-filter="inactive">
							<input type="radio" id="inactive-modules" name="athemes-addons-status-filter" value="inactive">
							<span><?php esc_html_e( 'Inactive', 'athemes-addons-elementor' ); ?></span>
						</label>
					</div>
				</div>
				<div class="module-filter-group athemes-addons-module-page-setting-field-radio">
					<div>
						<label for="all-modules-freepro" data-filter="all">
							<input type="radio" id="all-modules" name="athemes-addons-free-pro-filter" value="all" checked>
							<span><?php esc_html_e( 'All', 'athemes-addons-elementor' ); ?></span>
						</label>				
						<label for="free-modules" data-filter="free">
							<input type="radio" id="free-modules" name="athemes-addons-free-pro-filter" value="free">
							<span><?php esc_html_e( 'Free', 'athemes-addons-elementor' ); ?></span>
						</label>
						<label for="pro-modules" data-filter="pro">
							<input type="radio" id="pro-modules" name="athemes-addons-free-pro-filter" value="pro">
							<span><?php esc_html_e( 'Pro', 'athemes-addons-elementor' ); ?></span>
						</label>
					</div>
				</div>
			</div>
			<div class="module-filter-group module-filter-search">
				<input type="search" placeholder="<?php esc_attr_e( 'Search modules', 'athemes-addons-elementor' ); ?>" class="athemes-addons-modules-search" />
			</div>
		</div>

		<div class="athemes-addons-modules-list">

			<?php 
			foreach ( $aafe_widgets as $aafe_module_id => $aafe_module ) : 

				$is_pro_module  = isset( $aafe_module['pro'] ) && true === $aafe_module['pro'];
				$is_upsell      = ! defined( 'ATHEMES_AFE_PRO_VERSION' ) && $is_pro_module;

				/**
				 * Hook 'athemes_addons_admin_module_{module_id}_list_item_class'
				 * 
				 * @since 1.0
				 */
				$module_list_item_class   = apply_filters( "athemes_addons_admin_module_{$aafe_module_id}_list_item_class", 'athemes-addons-modules-list-item' );                               
				?>

				<div class="<?php echo esc_attr( $module_list_item_class ) ?>" 
					data-title="<?php echo esc_attr( $aafe_module['title'] ); ?>"
					data-type="<?php echo ( $aafe_module['pro'] ) ? 'pro' : 'free'; ?>"
					data-category="<?php echo esc_attr( $aafe_module['category'] ); ?>"
					data-status="<?php echo ( aThemes_Addons_Modules::is_module_active($aafe_module_id) ) ? 'active' : 'inactive'; ?>"
					data-keywords="<?php echo esc_attr( isset( $aafe_module['keywords'] ) ? implode( ',', $aafe_module['keywords'] ) : '' ); ?>"
				>
					<?php if( $is_upsell ) : ?>
						<div class="athemes-addons-modules-list-item-badge-wrapper athemes-addons-modules-list-item-upsell">
							<span class="athemes-addons-pro-badge athemes-addons-pro-tooltip" data-tooltip-message="<?php echo esc_attr__( 'This option is only available on aThemes Addons Pro', 'athemes-addons-elementor' ); ?>">
								<svg width="28" height="16" viewBox="0 0 28 16" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M7.41309 8.90723H5.58203V7.85254H7.41309C7.71257 7.85254 7.95508 7.80371 8.14062 7.70605C8.32943 7.60514 8.46777 7.46842 8.55566 7.2959C8.64355 7.12012 8.6875 6.91992 8.6875 6.69531C8.6875 6.47721 8.64355 6.27376 8.55566 6.08496C8.46777 5.89616 8.32943 5.74316 8.14062 5.62598C7.95508 5.50879 7.71257 5.4502 7.41309 5.4502H6.02148V11.5H4.67871V4.39062H7.41309C7.96647 4.39062 8.43848 4.48991 8.8291 4.68848C9.22298 4.88379 9.52246 5.1556 9.72754 5.50391C9.93587 5.84896 10.04 6.24284 10.04 6.68555C10.04 7.14453 9.93587 7.54004 9.72754 7.87207C9.52246 8.2041 9.22298 8.45964 8.8291 8.63867C8.43848 8.81771 7.96647 8.90723 7.41309 8.90723ZM11.0947 4.39062H13.6777C14.2181 4.39062 14.682 4.47201 15.0693 4.63477C15.4567 4.79753 15.7546 5.03841 15.9629 5.35742C16.1712 5.67643 16.2754 6.06868 16.2754 6.53418C16.2754 6.90202 16.2103 7.22103 16.0801 7.49121C15.9499 7.76139 15.766 7.98763 15.5283 8.16992C15.2939 8.35221 15.0173 8.49544 14.6982 8.59961L14.2783 8.81445H11.998L11.9883 7.75488H13.6924C13.9691 7.75488 14.1986 7.70605 14.3809 7.6084C14.5632 7.51074 14.6999 7.37565 14.791 7.20312C14.8854 7.0306 14.9326 6.83366 14.9326 6.6123C14.9326 6.37467 14.887 6.1696 14.7959 5.99707C14.7048 5.82129 14.5664 5.6862 14.3809 5.5918C14.1953 5.4974 13.9609 5.4502 13.6777 5.4502H12.4375V11.5H11.0947V4.39062ZM15.1084 11.5L13.4629 8.31641L14.8838 8.31152L16.5488 11.4316V11.5H15.1084ZM23.209 7.76465V8.13086C23.209 8.66797 23.1374 9.15137 22.9941 9.58105C22.8509 10.0075 22.6475 10.3704 22.3838 10.6699C22.1201 10.9694 21.806 11.1989 21.4414 11.3584C21.0768 11.5179 20.6715 11.5977 20.2256 11.5977C19.7861 11.5977 19.3825 11.5179 19.0146 11.3584C18.6501 11.1989 18.3343 10.9694 18.0674 10.6699C17.8005 10.3704 17.5938 10.0075 17.4473 9.58105C17.3008 9.15137 17.2275 8.66797 17.2275 8.13086V7.76465C17.2275 7.22428 17.3008 6.74089 17.4473 6.31445C17.5938 5.88802 17.7988 5.52507 18.0625 5.22559C18.3262 4.92285 18.6403 4.69173 19.0049 4.53223C19.3727 4.37272 19.7764 4.29297 20.2158 4.29297C20.6618 4.29297 21.0671 4.37272 21.4316 4.53223C21.7962 4.69173 22.1104 4.92285 22.374 5.22559C22.641 5.52507 22.846 5.88802 22.9893 6.31445C23.1357 6.74089 23.209 7.22428 23.209 7.76465ZM21.8516 8.13086V7.75488C21.8516 7.36751 21.8158 7.02734 21.7441 6.73438C21.6725 6.43815 21.5667 6.18913 21.4268 5.9873C21.2868 5.78548 21.1143 5.63411 20.9092 5.5332C20.7041 5.42904 20.473 5.37695 20.2158 5.37695C19.9554 5.37695 19.7243 5.42904 19.5225 5.5332C19.3239 5.63411 19.1546 5.78548 19.0146 5.9873C18.8747 6.18913 18.7673 6.43815 18.6924 6.73438C18.6208 7.02734 18.585 7.36751 18.585 7.75488V8.13086C18.585 8.51497 18.6208 8.85514 18.6924 9.15137C18.7673 9.44759 18.8747 9.69824 19.0146 9.90332C19.1579 10.1051 19.3304 10.2581 19.5322 10.3623C19.734 10.4665 19.9652 10.5186 20.2256 10.5186C20.486 10.5186 20.7171 10.4665 20.9189 10.3623C21.1208 10.2581 21.29 10.1051 21.4268 9.90332C21.5667 9.69824 21.6725 9.44759 21.7441 9.15137C21.8158 8.85514 21.8516 8.51497 21.8516 8.13086Z" fill="#3858E9"/>
									<rect x="0.5" y="1" width="27" height="14" rx="1.5" stroke="#3858E9"/>
								</svg>
							</span>
						</div>
					<?php endif; ?>

					<div class="athemes-addons-modules-list-item-content">
						<div class="athemes-addons-modules-list-item-title">
							<?php echo esc_html( $aafe_module['title'] ); ?>
						</div>

						<?php if ( ! empty( $aafe_module['desc'] ) ) : ?>
							<div class="athemes-addons-modules-list-item-desc"><?php echo esc_html( $aafe_module['desc']  ); ?></div>
						<?php endif; ?>

						<div class="athemes-addons-module-actions">
							<div>
								<?php echo isset( $aafe_module['tutorial_url'] ) ? athemes_addons_module_help_icon( $aafe_module['tutorial_url'] ) : false; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<?php echo isset( $aafe_module['preview_url'] ) ? athemes_addons_module_preview_icon( $aafe_module['preview_url'] ) : false; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</div>

							<?php $aafe_status = ( aThemes_Addons_Modules::is_module_active($aafe_module_id) ) ? 1 : 0; ?>

							<?php 
							if ( !$is_pro_module || ( $is_pro_module && defined( 'ATHEMES_AFE_PRO_VERSION' ) ) ) {
								echo athemes_addons_module_activation_switcher( $aafe_module_id, $aafe_status ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							}
							?>							
						</div>
					</div>

				</div>

			<?php endforeach; ?>

		</div>

</div>

<?php endif; ?>