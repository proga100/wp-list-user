<?php

class user_report_table
{

	public function __construct()
	{
		$page = (!empty($_GET['page'])) ? $_GET['page'] : '';
		if ($page == 'user-report-page') {
			add_action('admin_enqueue_scripts', [$this, 'user_list_script']);
		}
		add_action('admin_action_wpse10500', [$this, 'wpse10500_admin_action']);
		add_action('admin_menu', [$this, 'wpdocs_register_my_custom_submenu_page']);

	}


	public function user_list_script()
	{
		wp_enqueue_style('boostrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css', NULL, STM_THEME_VERSION, 'all');
		wp_enqueue_style('user-report-list', STM_WP_PAYMENT_URL . '/assets/css/user-report-list.css', array(), wp_get_theme()->get('Version'), 'all');
		wp_enqueue_script('bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array('jquery'), STM_THEME_VERSION, TRUE);
		wp_enqueue_script('user-report-list', STM_WP_PAYMENT_URL . '/assets/js/user-report-list.js', ['jquery'], time());
	}

	public function wpse10500_admin_action()
	{
		// Do your stuff here
		wp_redirect($_SERVER['HTTP_REFERER']);
		exit();
	}

	public function report_do_page()
	{

		$user_id = (!empty($_REQUEST['user_id'])) ? sanitize_text_field($_REQUEST['user_id']) : null;
		$lastname = get_user_meta($user_id, 'last_name', true);
		$firstname = get_user_meta($user_id, 'first_name', true);
		$dirs = (!empty(get_user_meta($user_id, 'reports', true))) ? get_user_meta($user_id, 'reports', true) : [];
		?>
        <div class="container user-list-form">
            <div class="row">
                <div class="col-lg-10">
                    <div class="panel panel-default ">
                        <div class="panel-heading">
                            <h2> <?php echo "$lastname $firstname (User ID: $user_id)  Xisobotlari" ?></</h2></div>
                        <div class="panel-body">

							<?php if (!empty($dirs)):
								foreach ($dirs as $dir) : ?>
                                    <div class="row user-report">
                                        <div class="col-md-8">
                                            <a class="form-control"
                                               href="<?php echo $dir['path'] ?>"><?php echo basename($dir['path']) ?></a>
                                        </div>
                                        <div class="col-md-4">Sana: <?php
											$date = date_create($dir['date']);
											echo date_format($date, "d-m-Y"); ?>
                                        </div>
                                    </div>
								<?php endforeach;
							else:?>
                                <h2> Xisobotlar yuklanmagan </h2>
							<?php
							endif;
							?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<?php
	}

	public function wpdocs_register_my_custom_submenu_page()
	{
		add_submenu_page(
			null,
			'Talaba Xisobot Sahifasi',
			'Talaba Xisobot Sahifasi',
			'manage_options',
			'user-report-page',
			[$this, 'report_do_page']
		);
	}
}

new user_report_table();