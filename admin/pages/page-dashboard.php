<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$aafe_notifications     = $this->get_notifications();

/**
 * Hook 'athemes_addons_notifications_pro'
 * 
 * @since 1.0
 */
$aafe_notifications_pro = apply_filters( 'athemes_addons_notifications_pro', false );

/**
 * Hook 'athemes_addons_notification_tabs'
 * 
 * @since 1.0
 */
$aafe_notification_tabs = apply_filters( 'athemes_addons_notification_tabs', false );
$aafe_notification_read = $this->is_latest_notification_read();

$aafe_dashboard_tabs = $this->dashboard_tabs();

?>

<div class="athemes-addons-top-bar">
	<a href="https://athemes.com/?utm_source=plugin_dashboard&utm_medium=athemes_addons_dashboard&utm_campaign=aThemes_Addons" class="athemes-addons-top-bar-logo" target="_blank">
		<svg width="96" height="24" viewBox="0 0 96 24" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path fill-rule="evenodd" clip-rule="evenodd" d="M23.4693 1.32313L8.45381 14.3107L0.67962 4.82163L23.4693 1.32313Z" fill="#335EEA"/>
			<path fill-rule="evenodd" clip-rule="evenodd" d="M23.2942 1.17329L8.23868 14.112L16.0129 23.601L23.2942 1.17329Z" fill="#BECCF9"/>
			<path fill-rule="evenodd" clip-rule="evenodd" d="M54.4276 12.8764C54.4276 10.7582 52.94 9.55047 51.2709 9.55047C49.6019 9.55047 48.8399 10.8325 48.8399 10.8325V4.53369H46.6629V18.5807H48.8399V12.7835C48.8399 12.7835 49.4205 11.6315 50.5453 11.6315C51.4886 11.6315 52.2506 12.1703 52.2506 13.4338V18.5807H54.4276V12.8764ZM39.9463 18.5807V7.6924H36.4449V5.57421H45.6247V7.6924H42.1233V18.5807H39.9463ZM36.604 12.8392C36.604 10.8325 35.1527 9.55047 32.6854 9.55047C30.8894 9.55047 29.2929 10.3494 29.2929 10.3494L30.0004 12.1889C30.0004 12.1889 31.3248 11.5386 32.5766 11.5386C33.3385 11.5386 34.427 11.9102 34.427 13.0622V13.5639C34.427 13.5639 33.6107 12.9321 32.0323 12.9321C30.1637 12.9321 28.658 14.1585 28.658 15.8493C28.658 17.7259 30.1456 18.8036 31.7602 18.8036C33.7014 18.8036 34.5903 17.4658 34.5903 17.4658V18.5807H36.604V12.8392ZM34.427 15.9236C34.427 15.9236 33.7376 16.9456 32.4314 16.9456C31.7602 16.9456 30.8713 16.7412 30.8713 15.8121C30.8713 14.8645 31.7965 14.5672 32.4677 14.5672C33.6469 14.5672 34.427 15.0132 34.427 15.0132V15.9236ZM59.7836 9.55047C62.142 9.55047 64.1195 11.4271 64.1195 14.1399C64.1195 14.3071 64.1195 14.6416 64.1013 14.976H57.6791C57.8424 15.7564 58.7314 16.7598 60.092 16.7598C61.5978 16.7598 62.4504 15.8679 62.4504 15.8679L63.5389 17.5401C63.5389 17.5401 62.1783 18.8036 60.092 18.8036C57.4796 18.8036 55.4659 16.7598 55.4659 14.177C55.4659 11.5943 57.2982 9.55047 59.7836 9.55047ZM61.9425 13.3595H57.6792C57.7517 12.5791 58.3867 11.5758 59.7836 11.5758C61.2168 11.5758 61.9062 12.5977 61.9425 13.3595ZM72.3963 11.0926C72.3987 11.0875 73.1253 9.55047 75.1357 9.55047C76.8773 9.55047 78.1472 10.7954 78.1472 12.9136V18.5807H75.9702V13.4896C75.9702 12.2818 75.5167 11.6315 74.4282 11.6315C73.2852 11.6315 72.741 12.7649 72.741 12.7649V18.5807H70.564V13.4896C70.564 12.2818 70.1104 11.6315 69.0219 11.6315C67.879 11.6315 67.3347 12.7649 67.3347 12.7649V18.5807H65.1577V9.77343H67.1896V11.0555C67.1896 11.0555 67.9697 9.55047 69.7294 9.55047C71.7947 9.55047 72.3946 11.0884 72.3963 11.0926ZM87.8391 14.1399C87.8391 11.4271 85.8616 9.55047 83.5032 9.55047C81.0178 9.55047 79.1855 11.5943 79.1855 14.177C79.1855 16.7598 81.1992 18.8036 83.8116 18.8036C85.8979 18.8036 87.2585 17.5401 87.2585 17.5401L86.17 15.8679C86.17 15.8679 85.3174 16.7598 83.8116 16.7598C82.451 16.7598 81.562 15.7564 81.3988 14.976H87.8209C87.8391 14.6416 87.8391 14.3071 87.8391 14.1399ZM81.3988 13.3595H85.6621C85.6258 12.5977 84.9364 11.5758 83.5032 11.5758C82.1063 11.5758 81.4713 12.5791 81.3988 13.3595ZM89.5486 15.5892L88.3331 17.2057C88.3331 17.2057 89.6937 18.8036 92.2154 18.8036C94.4106 18.8036 95.9708 17.5959 95.9708 16.0909C95.9708 14.2699 94.7553 13.6939 93.0499 13.3223C91.5986 13.0065 91.0181 12.8764 91.0181 12.3376C91.0181 11.7987 91.7619 11.5014 92.5783 11.5014C93.7393 11.5014 94.719 12.2632 94.719 12.2632L95.8075 10.6281C95.8075 10.6281 94.5194 9.55047 92.5783 9.55047C90.2198 9.55047 88.8773 10.9254 88.8773 12.3004C88.8773 13.9727 90.365 14.7159 92.0703 15.0875C93.3765 15.3662 93.7756 15.4777 93.7756 16.0537C93.7756 16.5925 93.0318 16.8527 92.1429 16.8527C90.6915 16.8527 89.5486 15.5892 89.5486 15.5892Z" fill="#101517"/>
		</svg>
	</a>
	<div class="athemes-addons-top-bar-infos">
		<div class="athemes-addons-top-bar-info-item">
			<div class="athemes-addons-version">
				<strong><?php echo esc_html( ( defined( 'ATHEMES_AFE_PRO_VERSION' ) ? ATHEMES_AFE_PRO_VERSION : ATHEMES_AFE_VERSION ) ); ?></strong>
				<span class="athemes-addons-badge<?php echo ( defined( 'ATHEMES_AFE_PRO_VERSION' ) ) ? ' athemes-addons-badge-pro' : ''; ?>">
					<?php echo esc_html( ( ! defined( 'ATHEMES_AFE_PRO_VERSION' ) ) ? __( 'FREE', 'athemes-addons-elementor' ) : __( 'PRO', 'athemes-addons-elementor' ) ); ?>
				</span>
			</div>
		</div>
		<div class="athemes-addons-top-bar-info-item">
			<a href="#" class="athemes-addons-notifications<?php echo esc_attr( ( $aafe_notification_read ) ? ' read' : '' ); ?>" title="<?php esc_html_e( 'aThemes Addons News', 'athemes-addons-elementor' ); ?>">
				<span class="athemes-addons-notifications-count">1</span>
				<svg width="13" height="11" viewBox="0 0 10 8" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M8.86194 0.131242C8.75503 0.0584347 8.63276 0.0143876 8.50589 0.0029728C8.37902 -0.00844195 8.25143 0.0131252 8.13433 0.0657785L4.29726 1.65655C4.20642 1.69547 4.10927 1.71548 4.01119 1.71547H1.55473C1.34856 1.71547 1.15083 1.80168 1.00505 1.95514C0.859264 2.1086 0.777363 2.31674 0.777363 2.53377V2.59923H0V4.56315H0.777363V4.64825C0.782235 4.86185 0.866281 5.06498 1.01154 5.21422C1.1568 5.36346 1.35175 5.44697 1.55473 5.44691L2.48756 7.52866C2.55073 7.66885 2.65017 7.78744 2.77448 7.87081C2.89878 7.95418 3.04291 7.99896 3.1903 8H3.58209C3.78718 7.99827 3.98331 7.9113 4.12775 7.75802C4.27219 7.60475 4.35324 7.3976 4.35323 7.1817V5.52547L8.13433 7.11624C8.22733 7.1552 8.32652 7.17519 8.42662 7.17515C8.58191 7.17252 8.73314 7.12249 8.86194 7.03114C8.96423 6.95843 9.0486 6.86114 9.10808 6.7473C9.16755 6.63347 9.20043 6.50636 9.20398 6.3765V0.80552C9.20341 0.672312 9.17196 0.541263 9.11235 0.423757C9.05274 0.30625 8.96678 0.205839 8.86194 0.131242ZM3.57587 2.53377V4.64825H1.55473V2.53377H3.57587ZM3.57587 7.1817H3.18408L2.41915 5.44691H3.57587V7.1817ZM4.58333 4.74645C4.5095 4.70672 4.4325 4.67387 4.35323 4.64825V2.48794C4.43174 2.47089 4.50872 2.4468 4.58333 2.41593L8.42662 0.80552V6.35686L4.58333 4.74645ZM9.22264 2.76289V4.39949C9.42881 4.39949 9.62653 4.31327 9.77232 4.15981C9.9181 4.00635 10 3.79821 10 3.58119C10 3.36416 9.9181 3.15602 9.77232 3.00256C9.62653 2.8491 9.42881 2.76289 9.22264 2.76289Z" fill="#1E1E1E"/>
				</svg>
			</a>
		</div>
		<div class="athemes-addons-top-bar-info-item">
			<a href="https://athemes.com/athemes-addons-elementor?utm_source=plugin_dashboard&utm_medium=athemes_addons_dashboard&utm_campaign=aThemes_Addons" class="athemes-addons-website" target="_blank">
				<?php esc_html_e( 'Website', 'athemes-addons-elementor' ); ?>
				<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path fill-rule="evenodd" clip-rule="evenodd" d="M13.6 2.40002H7.20002L8.00002 4.00002H11.264L6.39202 8.88002L7.52002 10.008L12 5.53602V8.00002L13.6 8.80002V2.40002ZM9.60002 9.60002V12H4.00002V6.40002H7.20002L8.80002 4.80002H2.40002V13.6H11.2V8.00002L9.60002 9.60002Z" fill="#3858E9"/>
				</svg>
			</a>
		</div>
	</div>
</div>

<div class="athemes-addons-notifications-sidebar">
	<a href="#" class="athemes-addons-notifications-sidebar-close" title="<?php echo esc_attr__( 'Close the sidebar', 'athemes-addons-elementor' ); ?>">
		<svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M13.4584 4.54038L12.4597 3.54163L8.50008 7.50121L4.5405 3.54163L3.54175 4.54038L7.50133 8.49996L3.54175 12.4595L4.5405 13.4583L8.50008 9.49871L12.4597 13.4583L13.4584 12.4595L9.49883 8.49996L13.4584 4.54038Z" fill="black"/>
		</svg>
	</a>
	<div class="athemes-addons-notifications-sidebar-inner">
		<div class="athemes-addons-notifications-sidebar-header">
			<div class="athemes-addons-notifications-sidebar-header-icon">
				<svg width="26" height="25" viewBox="0 0 26 25" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M11.9441 20.5818C12.3228 20.8497 12.7752 20.9936 13.2391 20.9937L11.9441 20.5818ZM11.9441 20.5818C11.6044 20.3416 11.3391 20.0122 11.1764 19.6313M11.9441 20.5818L11.1764 19.6313M11.1764 19.6313H15.3018C15.1392 20.0122 14.8738 20.3416 14.5341 20.5818C14.1554 20.8497 13.703 20.9936 13.2391 20.9937L11.1764 19.6313ZM5.42653 19.6313H5.4266H9.33118C9.5281 20.5037 10.0116 21.2861 10.7057 21.8526C11.4209 22.4365 12.3158 22.7554 13.2391 22.7554C14.1624 22.7554 15.0573 22.4365 15.7725 21.8526C16.4666 21.2861 16.9501 20.5037 17.147 19.6313H21.0516H21.0517C21.344 19.6309 21.631 19.5534 21.8838 19.4068C22.1366 19.2601 22.3462 19.0494 22.4916 18.7959C22.637 18.5424 22.713 18.255 22.712 17.9628C22.7109 17.6705 22.6329 17.3837 22.4856 17.1313C21.9553 16.2176 21.1516 13.5951 21.1516 10.1562C21.1516 8.05772 20.318 6.04515 18.8341 4.56127C17.3502 3.07739 15.3376 2.24375 13.2391 2.24375C11.1406 2.24375 9.128 3.07739 7.64412 4.56127C6.16024 6.04515 5.3266 8.05772 5.3266 10.1562C5.3266 13.5963 4.52185 16.2179 3.99149 17.1314L4.07797 17.1816L3.99158 17.1313C3.84432 17.3838 3.76625 17.6707 3.76524 17.963C3.76424 18.2554 3.84034 18.5428 3.98587 18.7964C4.1314 19.0499 4.34121 19.2606 4.59414 19.4072C4.84708 19.5537 5.13419 19.631 5.42653 19.6313ZM5.59668 17.8687C6.33498 16.4852 7.0891 13.5615 7.0891 10.1562C7.0891 8.52517 7.73705 6.96089 8.8904 5.80754C10.0437 4.65419 11.608 4.00625 13.2391 4.00625C14.8702 4.00625 16.4345 4.65419 17.5878 5.80754C18.7412 6.96089 19.3891 8.52517 19.3891 10.1562C19.3891 13.5589 20.1415 16.4827 20.8815 17.8687H5.59668Z" fill="#3858E9" stroke="#3858E9" stroke-width="0.2"/>
				</svg>
			</div>
			<div class="athemes-addons-notifications-sidebar-header-content">
				<h3>
					<?php
					if ( ! empty( $aafe_notification_read ) ) {
						esc_html_e( 'Latest News', 'athemes-addons-elementor' );
					} else {
						esc_html_e( 'New Update', 'athemes-addons-elementor' );
					}
					?>
				</h3>
				<p><?php echo esc_html__( 'Check the latest news from aThemes Addons for Elementor', 'athemes-addons-elementor' ); ?></p>
			</div>
		</div>
		<?php if ( $aafe_notification_tabs ) : ?>
			<div class="athemes-addons-notifications-sidebar-tabs">
				<nav class="athemes-addons-tabs-nav athemes-addons-tabs-nav-no-negative-margin" data-tab-wrapper-id="notifications-sidebar">
					<ul>
						<li class="athemes-addons-tabs-nav-item active">
							<a href="#" class="athemes-addons-tabs-nav-link" data-tab-to="notifications-sidebar-aafe">
								<?php echo esc_html__( 'aThemes Addons', 'athemes-addons-elementor' ); ?>
							</a>
						</li>
						<li class="athemes-addons-tabs-nav-item">
							<a href="#" class="athemes-addons-tabs-nav-link" data-tab-to="notifications-sidebar-athemes-addons-pro">
								<?php echo esc_html__( 'aThemes Addons Pro', 'athemes-addons-elementor' ); ?>
							</a>
						</li>
					</ul>
				</nav>
			</div>
		<?php endif; ?>
		<div class="athemes-addons-notifications-sidebar-body athemes-addons-tab-content-wrapper" data-tab-wrapper-id="notifications-sidebar">
			<div class="athemes-addons-tab-content active" data-tab-content-id="notifications-sidebar-aafe">
				<?php if ( ! empty( $aafe_notifications ) ) : ?>
					<?php $aafe_display_version = false; ?>
					<?php foreach ( $aafe_notifications as $aafe_notification ) : ?>
						<?php $aafe_date = isset( $aafe_notification->post_date ) ? $aafe_notification->post_date : false; ?>
						<?php $aafe_version = isset( $aafe_notification->post_title ) ? $aafe_notification->post_title : false; ?>
						<?php $aafe_content = isset( $aafe_notification->post_content ) ? $aafe_notification->post_content : false; ?>
						<div class="athemes-addons-notification">
							<?php if ( $aafe_date ) : ?>
								<span class="athemes-addons-notification-date" data-raw-date="<?php echo esc_attr( $aafe_date ); ?>">
									<?php echo esc_html( date_format( date_create( $aafe_date ), 'F j, Y' ) ); ?>
									<?php if ( $aafe_display_version ) : ?>
										<span class="athemes-addons-notification-version">(<?php echo esc_html( $aafe_version ); ?>)</span>
									<?php endif; ?>
								</span>
							<?php endif; ?>
							<?php if ( $aafe_content ) : ?>
								<div class="athemes-addons-notification-content">
									<?php echo wp_kses_post( $aafe_content ); ?>
								</div>
							<?php endif; ?>
						</div>
						<?php $aafe_display_version = true; ?>
					<?php endforeach; ?>
				<?php else : ?>
					<div class="athemes-addons-notification">
						<div class="athemes-addons-notification-content">
							<p class="changelog-description"><?php echo esc_html__( 'No notifications found', 'athemes-addons-elementor' ); ?></p>
						</div>
					</div>
				<?php endif; ?>
			</div>

			<?php if ( $aafe_notification_tabs ) : ?>
			<div class="athemes-addons-tab-content" data-tab-content-id="notifications-sidebar-athemes-addons-pro">
				<?php if ( ! empty( $aafe_notifications_pro ) ) : ?>
					<?php $aafe_display_version = false; ?>
					<?php foreach ( $aafe_notifications_pro as $aafe_notification ) : ?>
						<?php $aafe_date = isset( $aafe_notification->post_date ) ? $aafe_notification->post_date : false; ?>
						<?php $aafe_version = isset( $aafe_notification->post_title ) ? $aafe_notification->post_title : false; ?>
						<?php $aafe_content = isset( $aafe_notification->post_content ) ? $aafe_notification->post_content : false; ?>
						<div class="athemes-addons-notification">
							<?php if ( $aafe_date ) : ?>
								<span class="athemes-addons-notification-date" data-raw-date="<?php echo esc_attr( $aafe_date ); ?>">
									<?php echo esc_html( date_format( date_create( $aafe_date ), 'F j, Y' ) ); ?>
									<?php if ( $aafe_display_version ) : ?>
										<span class="athemes-addons-notification-version">(<?php echo esc_html( $aafe_version ); ?>)</span>
									<?php endif; ?>
								</span>
							<?php endif; ?>
							<?php if ( $aafe_content ) : ?>
								<div class="athemes-addons-notification-content">
									<?php echo wp_kses_post( $aafe_content ); ?>
								</div>
							<?php endif; ?>
						</div>
						<?php $aafe_display_version = true; ?>
					<?php endforeach; ?>
				<?php else : ?>
					<div class="athemes-addons-notification">
						<div class="athemes-addons-notification-content">
							<p class="changelog-description"><?php echo esc_html__( 'No notifications found', 'athemes-addons-elementor' ); ?></p>
						</div>
					</div>
				<?php endif; ?>
			</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<div class="wrap athemes-addons-wrap">

	<?php if ( ! empty( $_GET['section'] ) && 'settings' === $_GET['section'] ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>

		<?php require ATHEMES_AFE_DIR . 'admin/pages/page-settings.php'; ?>

	<?php else : ?>

		<?php 
		$aafe_widgets = athemes_addons_get_widgets();
	
		$aafe_active_modules = 0;
		$aafe_option_modules = get_option( 'athemes-addons-modules', array() );

		if ( ! empty( $aafe_widgets ) ) {
			foreach ( $aafe_widgets as $aafe_data ) {
				if ( ! empty( $aafe_data['modules'] ) ) {
					foreach ( $aafe_data['modules'] as $aafe_module_id => $aafe_module ) {
						if ( ! empty( $aafe_option_modules[ $aafe_module_id ] ) ) {
							$aafe_active_modules++;
						}
					}
				}
			}
		}

		?>

		<div class="athemes-addons-modules-header">

			<div class="athemes-addons-modules-header-left">

				<div class="athemes-addons-modules-header-heading"><?php esc_html_e( 'Welcome To aThemes Addons for Elementor', 'athemes-addons-elementor' ); ?> <?php esc_html_e('👋🏻', 'athemes-addons-elementor'); ?></div>
				
				<div class="athemes-addons-modules-header-subheading"><?php esc_html_e( 'We’re glad to see you :)', 'athemes-addons-elementor' ); ?></div>
			</div>

			<div class="athemes-addons-modules-header-right">

				<ul class="athemes-addons-modules-header-shortlinks">

					<li class="athemes-addons-modules-header-shortlinks-get-help">
						<a href="https://athemes.com/support/" target="_blank">

							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M11.96 16.8C12.24 16.8 12.4768 16.7032 12.6704 16.5096C12.8635 16.3165 12.96 16.08 12.96 15.8C12.96 15.52 12.8635 15.2835 12.6704 15.0904C12.4768 14.8968 12.24 14.8 11.96 14.8C11.68 14.8 11.4432 14.8968 11.2496 15.0904C11.0565 15.2835 10.96 15.52 10.96 15.8C10.96 16.08 11.0565 16.3165 11.2496 16.5096C11.4432 16.7032 11.68 16.8 11.96 16.8ZM12.08 8.56C12.4533 8.56 12.7533 8.6632 12.98 8.8696C13.2067 9.07653 13.32 9.34667 13.32 9.68C13.32 9.90667 13.2435 10.1365 13.0904 10.3696C12.9368 10.6032 12.72 10.8467 12.44 11.1C12.04 11.4467 11.7467 11.78 11.56 12.1C11.3733 12.42 11.28 12.74 11.28 13.06C11.28 13.2467 11.3501 13.4032 11.4904 13.5296C11.6301 13.6565 11.7933 13.72 11.98 13.72C12.1667 13.72 12.3333 13.6533 12.48 13.52C12.6267 13.3867 12.72 13.22 12.76 13.02C12.8 12.7933 12.8901 12.5835 13.0304 12.3904C13.1701 12.1968 13.4 11.9467 13.72 11.64C14.1333 11.2533 14.4235 10.9 14.5904 10.58C14.7568 10.26 14.84 9.90667 14.84 9.52C14.84 8.84 14.5835 8.2832 14.0704 7.8496C13.5568 7.41653 12.8933 7.2 12.08 7.2C11.52 7.2 11.0235 7.30667 10.5904 7.52C10.1568 7.73333 9.82 8.06 9.58 8.5C9.48667 8.67333 9.45333 8.8432 9.48 9.0096C9.50667 9.17653 9.6 9.31333 9.76 9.42C9.93333 9.52667 10.1235 9.56 10.3304 9.52C10.5368 9.48 10.7067 9.36667 10.84 9.18C10.9867 8.98 11.1635 8.82667 11.3704 8.72C11.5768 8.61333 11.8133 8.56 12.08 8.56ZM12 20C10.9067 20 9.87333 19.7899 8.9 19.3696C7.92667 18.9499 7.0768 18.38 6.3504 17.66C5.62347 16.94 5.05013 16.0933 4.6304 15.12C4.21013 14.1467 4 13.1067 4 12C4 10.8933 4.21013 9.85333 4.6304 8.88C5.05013 7.90667 5.62347 7.06 6.3504 6.34C7.0768 5.62 7.92667 5.04987 8.9 4.6296C9.87333 4.20987 10.9067 4 12 4C13.12 4 14.1667 4.20987 15.14 4.6296C16.1133 5.04987 16.96 5.62 17.68 6.34C18.4 7.06 18.9667 7.90667 19.38 8.88C19.7933 9.85333 20 10.8933 20 12C20 13.1067 19.7933 14.1467 19.38 15.12C18.9667 16.0933 18.4 16.94 17.68 17.66C16.96 18.38 16.1133 18.9499 15.14 19.3696C14.1667 19.7899 13.12 20 12 20ZM12 18.4C13.7867 18.4 15.3 17.7768 16.54 16.5304C17.78 15.2835 18.4 13.7733 18.4 12C18.4 10.2267 17.78 8.71653 16.54 7.4696C15.3 6.2232 13.7867 5.6 12 5.6C10.2533 5.6 8.74987 6.2232 7.4896 7.4696C6.22987 8.71653 5.6 10.2267 5.6 12C5.6 13.7733 6.22987 15.2835 7.4896 16.5304C8.74987 17.7768 10.2533 18.4 12 18.4Z" fill="#787C82"/>
							</svg>

							<span><?php esc_html_e( 'Get', 'athemes-addons-elementor' ); ?> <strong><?php esc_html_e( 'help and support', 'athemes-addons-elementor' ); ?></strong></span>

							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M10.8626 8.04102L14.2809 12.0291L10.8626 16.0172L9.72363 15.041L12.3053 12.0291L9.72363 9.01721L10.8626 8.04102Z" fill="#757575"/>
							</svg>

						</a>
					</li>

					<li class="athemes-addons-modules-header-shortlinks-leave-review">
						<a href="https://wordpress.org/support/plugin/athemes-addons-for-elementor-lite/reviews/?filter=5#new-post">

							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M12 7L13.2747 9.35645L16 9.73445L14 11.4545L14.6667 14L12 12.5682L9.33333 14L10 11.4545L8 9.73445L10.8 9.35645L12 7Z" fill="#787C82"/>
								<path d="M13.116 21L12 20.3846L14.5714 16.0769H18.4286C18.5975 16.0772 18.7648 16.0455 18.9209 15.9837C19.077 15.922 19.2188 15.8313 19.3383 15.717C19.4577 15.6026 19.5524 15.4669 19.6169 15.3174C19.6815 15.168 19.7145 15.0078 19.7143 14.8462V7.46154C19.7145 7.29984 19.6815 7.13969 19.6169 6.99026C19.5524 6.84082 19.4577 6.70505 19.3383 6.59071C19.2188 6.47638 19.077 6.38573 18.9209 6.32396C18.7648 6.2622 18.5975 6.23053 18.4286 6.23077H5.57143C5.40251 6.23053 5.23521 6.2622 5.07911 6.32396C4.923 6.38573 4.78117 6.47638 4.66173 6.59071C4.54228 6.70505 4.44759 6.84082 4.38307 6.99026C4.31854 7.13969 4.28546 7.29984 4.28571 7.46154V14.8462C4.28546 15.0078 4.31854 15.168 4.38307 15.3174C4.44759 15.4669 4.54228 15.6026 4.66173 15.717C4.78117 15.8313 4.923 15.922 5.07911 15.9837C5.23521 16.0455 5.40251 16.0772 5.57143 16.0769H11.3571V17.3077H5.57143C4.88944 17.3077 4.23539 17.0484 3.75315 16.5867C3.27092 16.1251 3 15.499 3 14.8462V7.46154C2.99992 7.13826 3.06637 6.81814 3.19557 6.51945C3.32476 6.22077 3.51417 5.94938 3.75297 5.72079C3.99176 5.4922 4.27527 5.31088 4.58729 5.18721C4.8993 5.06353 5.23372 4.99992 5.57143 5H18.4286C18.7663 4.99992 19.1007 5.06353 19.4127 5.18721C19.7247 5.31088 20.0082 5.4922 20.247 5.72079C20.4858 5.94938 20.6752 6.22077 20.8044 6.51945C20.9336 6.81814 21.0001 7.13826 21 7.46154V14.8462C21 15.499 20.7291 16.1251 20.2468 16.5867C19.7646 17.0484 19.1106 17.3077 18.4286 17.3077H15.3204L13.116 21Z" fill="#787C82"/>
							</svg>

							<span><?php esc_html_e( 'Leave a', 'athemes-addons-elementor' ); ?> <strong><?php esc_html_e( 'review', 'athemes-addons-elementor' ); ?></strong></span>

							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M10.8626 8.04102L14.2809 12.0291L10.8626 16.0172L9.72363 15.041L12.3053 12.0291L9.72363 9.01721L10.8626 8.04102Z" fill="#757575"/>
							</svg>

						</a>
					</li>

					<li class="athemes-addons-modules-header-shortlinks-feedback">
						<a href="https://athemes.com/feature-request/" target="_blank">

							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M6.09407 4L5.18893 4.8922L6.53571 6.22098L7.43957 5.32878L6.09343 4H6.09407ZM17.9066 4L16.5598 5.32878L17.4643 6.22098L18.8104 4.89283L17.9066 4ZM12 4.39597C11.7879 4.39851 11.5731 4.41056 11.3571 4.43658C11.3507 4.43658 11.3443 4.43531 11.3379 4.43658C8.73043 4.73165 6.65529 6.81112 6.29464 9.3735C6.00664 11.4358 6.86807 13.3059 8.30357 14.5103C8.89076 15.005 9.28793 15.6838 9.42857 16.433V20.2404H10.8943C11.118 20.6193 11.5262 20.875 12 20.875C12.4738 20.875 12.882 20.6193 13.1057 20.2404H14.5714V17.7022H14.6319V16.9483C14.6319 16.018 15.1217 15.0801 15.9176 14.351C16.9821 13.2989 17.7857 11.8045 17.7857 10.088C17.7857 6.95327 15.1719 4.36678 12 4.39597ZM12 5.6651C14.4846 5.63083 16.5 7.6386 16.5 10.088C16.5 11.4168 15.8764 12.5869 15.0131 13.4385L15.0336 13.4588C14.1757 14.2398 13.6209 15.292 13.4651 16.4337H10.6532C10.5118 15.346 10.0393 14.2933 9.14636 13.5382C8.01043 12.5863 7.3335 11.1522 7.55979 9.53278C7.84071 7.5339 9.48386 5.92654 11.4973 5.70635C11.6635 5.68347 11.8309 5.66991 11.9987 5.66573L12 5.6651ZM3 10.088V11.3572H4.92857V10.088H3ZM19.0714 10.088V11.3572H21V10.088H19.0714ZM6.53571 15.2242L5.18957 16.5523L6.09407 17.4452L7.43893 16.1164L6.53571 15.2242ZM17.4643 15.2242L16.5604 16.1164L17.9059 17.4452L18.8104 16.5523L17.4643 15.2242ZM10.7143 17.7028H13.2857V18.9719H10.7143V17.7028Z" fill="#787C82"/>
							</svg>

							<span><?php esc_html_e( 'Have an', 'athemes-addons-elementor' ); ?> <strong><?php esc_html_e( 'idea or feedback?', 'athemes-addons-elementor' ); ?></strong></span>

							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M10.8626 8.04102L14.2809 12.0291L10.8626 16.0172L9.72363 15.041L12.3053 12.0291L9.72363 9.01721L10.8626 8.04102Z" fill="#757575"/>
							</svg>

						</a>
					</li>

				</ul>

			</div>

		</div>

		<div class="athemes-addons-modules-panel">		
			<div class="athemes-addons-dashboard-tabs-nav">
			<?php foreach ( $aafe_dashboard_tabs as $aafe_tab_id => $aafe_tab ) : ?>
				<a href="<?php echo esc_html( $aafe_tab['link'] ); ?>" data-tab="<?php echo esc_attr( $aafe_tab_id ); ?>" class="athemes-addons-tab-nav-item <?php echo ( 'widgets' === $aafe_tab_id ) ? 'active' : ''; ?>">
					<?php echo esc_html( $aafe_tab['title'] ); ?>
				</a>
			<?php endforeach; ?>
			</div>

			<?php foreach ( $aafe_dashboard_tabs as $aafe_tab_id => $aafe_tab ) : ?>
				<div id="<?php echo esc_attr( $aafe_tab_id ); ?>" class="athemes-addons-dashboard-tab-page <?php echo ( 'widgets' === $aafe_tab_id ) ? 'active' : ''; ?>">
					<?php require_once ATHEMES_AFE_DIR . 'admin/pages/page-' . $aafe_tab_id . '.php'; ?>
				</div>
			<?php endforeach; ?>
		</div>

	<?php endif; ?>

</div>
