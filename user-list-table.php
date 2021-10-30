<?php

// WP_List_Table is not loaded automatically so we need to load it in our application
if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

/**
 * Create a new table class that will extend the WP_List_Table
 */
class Users_List_Table extends WP_List_Table
{

    /**
     * Prepare the items for the table to process
     *
     * @return Void
     */
    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        $data = $this->table_data();
        usort($data, array(&$this, 'sort_data'));
        $perPage = 40;
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);
        $this->set_pagination_args(array(
            'total_items' => $totalItems,
            'per_page' => $perPage
        ));
        $data = array_slice($data, (($currentPage - 1) * $perPage), $perPage);
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
    }

    public function get_users()
    {
        $args = array('orderby' => 'id', 'order' => 'ASC');
        $all_users = new WP_User_Query($args);
        $total_users = $all_users->get_total();
        $request['length'] = 100000000000;
        $request['start'] = 0;
        $all_users->set('number', $request['length']);
        $all_users->set('offset', $request['start']);
        $all_users->prepare_query();
        $all_users->query();
        $filtered = count($all_users->get_results());

        return $all_users->get_results();
    }

    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns()
    {

        $columns = array(
            'id' => 'User ID',
            'name' => 'Name',
            'email' => 'Email',
            'balance' => 'Balance',
            'payments' => 'Umumiy Tolovlar',
            'debt' => 'Qarzdorligi'
        );
        return $columns;
    }

    /**
     * Define which columns are hidden
     *
     * @return Array
     */
    public function get_hidden_columns()
    {
        return array();
    }

    /**
     * Define the sortable columns
     *
     * @return Array
     */
    public function get_sortable_columns()
    {
        return array('name' => array('name', false), 'id' => ['id', false], 'balance' => ['balance', false]);
    }

    /**
     * Get the table data
     *
     * @return Array
     */
    private function table_data()
    {

        $data = array();
        $user_data = $this->get_users();
        foreach ($user_data as $user) {
            $admin_url = admin_url('admin.php?page=my-custom-submenu-page&user_id=' . $user->ID . '');
            $payments = (get_user_meta($user->ID, 'lms_payments', true)) ? get_user_meta($user->ID, 'lms_payments', true) : 0;

            $payments = ($payments) ? array_sum($payments['tolov']) : 0;
            $data[] = array(
                'id' => $user->ID,
                'name' => '<a href="' . $admin_url . ' " >' . $user->display_name . '</a>',
                'email' => $user->user_email,
                'balance' => (get_user_meta($user->ID, 'lms_balance', true)) ? get_user_meta($user->ID, 'lms_balance', true) : 0,
                'payments' => $payments,
                'debt' => (get_user_meta($user->ID, 'lms_debt', true)) ? get_user_meta($user->ID, 'lms_debt', true) : 0,
            );


        }
        return $data;
    }

    /**
     * Define what data to show on each column of the table
     *
     * @param Array $item Data
     * @param String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'id':
            case 'name':
            case 'email':
            case 'balance':
            case 'payments':
            case 'debt':
                return $item[$column_name];
            default:
                return print_r($item, true);
        }
    }

    /**
     * Allows you to sort the data by the variables set in the $_GET
     *
     * @return Mixed
     */
    private function sort_data($a, $b)
    {
        // Set defaults
        $orderby = 'id';
        $order = 'asc';
        // If orderby is set, use this as the sort column
        if (!empty($_GET['orderby'])) {
            $orderby = $_GET['orderby'];
        }
        // If order is set use this as the order
        if (!empty($_GET['order'])) {
            $order = $_GET['order'];
        }
        $result = strcmp($a[$orderby], $b[$orderby]);
        if ($order === 'asc') {
            return $result;
        }
        return -$result;
    }
}

?>