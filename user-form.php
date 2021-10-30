<?php

class user_payment_form
{

	public function __construct()
	{
		$page = (!empty($_GET['page'])) ? $_GET['page'] : '';
		if ($page == 'my-custom-submenu-page') {
			add_action('init', [$this, 'update_metas']);
			add_action('admin_enqueue_scripts', [$this, 'user_list_script']);
		}
		add_action('admin_action_wpse10500', [$this, 'wpse10500_admin_action']);
		add_action('admin_menu', [$this, 'wpdocs_register_my_custom_submenu_page']);

	}

	public function update_metas()
	{
		$action_hs = (!empty($_POST['action_s'])) ? $_POST['action_s'] : '';
		if ($action_hs == 'update_tolov') {
			$user_id = (!empty($_REQUEST['user_id'])) ? sanitize_text_field($_REQUEST['user_id']) : null;

			$balance = (!empty($_REQUEST['lms_balance'])) ? sanitize_text_field($_REQUEST['lms_balance']) : '';
			$payments = (!empty($_REQUEST['lms_payments'])) ? $_REQUEST['lms_payments'] : ['0' => ''];


			$debt = (!empty($_REQUEST['lms_debt'])) ? sanitize_text_field($_REQUEST['lms_debt']) : '';
			$datas = [
				'lms_balance' => $balance,
				'lms_payments' => $payments,
				'lms_debt' => $debt
			];
			// echo '<pre>';            print_r($datas);            echo '</pre>';
			if ($user_id) {
				foreach ($datas as $k => $v) {
					update_user_meta((int)$user_id, $k, $v);
				}

				$this->wpse10500_admin_action();
			}
		}

	}

	public function user_list_script()
	{
		wp_enqueue_style('boostrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css', NULL, STM_THEME_VERSION, 'all');
		wp_enqueue_style('user-form-list', STM_WP_PAYMENT_URL . '/assets/css/user-form-list.css', array(), wp_get_theme()->get('Version'), 'all');
		wp_enqueue_script('bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array('jquery'), STM_THEME_VERSION, TRUE);
		wp_enqueue_script('user-form-list', STM_WP_PAYMENT_URL . '/assets/js/user-form-list.js', ['jquery'], time());
	}

	public function wpse10500_admin_action()
	{
		// Do your stuff here
		wp_redirect($_SERVER['HTTP_REFERER']);
		exit();
	}

	public function wpse10500_do_page()
	{

		$user_id = (!empty($_REQUEST['user_id'])) ? sanitize_text_field($_REQUEST['user_id']) : null;
		$balance = (get_user_meta($user_id, 'lms_balance', true)) ? get_user_meta($user_id, 'lms_balance', true) : '';
		$payments = (get_user_meta($user_id, 'lms_payments', true)) ? get_user_meta($user_id, 'lms_payments', true) : ['0' => ''];
		$debt = (get_user_meta($user_id, 'lms_debt', true)) ? get_user_meta($user_id, 'lms_debt', true) : '';
		$lastname = get_user_meta($user_id, 'last_name', true);
		$firstname = get_user_meta($user_id, 'first_name', true);
		?>

        <div class="container user-list-form">
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default ">
                        <div class="panel-heading"><h2> <?php echo "$lastname $firstname  to'lovlari" ?></</h2></div>
                        <div class="panel-body">

                            <form method="POST" action="">
                                <div class="input-group control-group ">
                                    <label>Qarzdorlik</label>
                                    <input class="form-control" type="text" name="lms_debt" placeholder="Qarzdorlik"
                                           value="<?php echo $debt ?>"/>
                                </div>
                                <div class="input-group control-group ">
                                    <label>Balance</label>
                                    <input class="form-control" type="text" name="lms_balance" placeholder="Balance"
                                           value="<?php echo $balance ?>"/>
                                </div>

                                <h3>To'lovlar</h3>
								<?php
								if (!empty($payments['tolov'])):
									foreach ($payments['tolov'] as $k => $payment):
										if (empty($payment)) continue;
										?>
                                        <div class="input-group control-group ">
                                            <input class="form-control" type="text" name="lms_payments[tolov][]"
                                                   placeholder="To'lov"
                                                   value="<?php echo $payment ?>"/>
                                            <input class="form-control lms-date" type="date"
                                                   name="lms_payments[tolov_date][]"
                                                   placeholder="To'lov kuni"
                                                   value="<?php echo $payments['tolov_date'][$k] ?>"/>
                                            <div class="input-group-btn">
                                                <button data-id="" class="btn btn-danger remove" type="button">Remove
                                                </button>
                                            </div>
                                        </div>
									<?php endforeach;
								endif;
								?>

                                <input type="hidden" name="action_s" value="update_tolov"/>
                                <input type="hidden" name="user_id" value="<?php echo $user_id ?>"/>

                                <div class="input-group control-group after-add-more">
                                    <input type="text" name="lms_payments[tolov][]" class="form-control"
                                           placeholder=" To'lov">
                                    <input class="form-control lms-date" type="date" name="lms_payments[tolov_date][]"
                                           placeholder="To'lov kuni"
                                           value=""/>
                                    <div class="input-group-btn">
                                        <button data-id="<?php echo $i + 1 ?>" class="btn btn-success add-more"
                                                type="button"> Add
                                        </button>
                                    </div>
                                </div>
                                <div class="input-group control-group ">
                                    <input type="submit" class="btn btn-success" value="submit"/>
                                </div>
                            </form>

                            <!-- Copy Fields -->
                            <div class="copy hide">
                                <div class="control-group input-group" style="margin-top:10px">
                                    <input type="text" name="lms_payments[tolov][]" class="form-control"
                                           placeholder=". To'lov">
                                    <input class="form-control lms-date" type="date" name="lms_payments[tolov_date][]"
                                           placeholder="To'lov kuni"
                                           value=""/>
                                    <div class="input-group-btn">
                                        <button data-id="" class="btn btn-danger remove" type="button">Remove
                                        </button>
                                    </div>
                                </div>
                            </div>

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
			'Talaba tolov formasi',
			'Talaba tolov formasi',
			'manage_options',
			'my-custom-submenu-page',
			[$this, 'wpse10500_do_page']
		);
	}
}

new user_payment_form();