<?php

if(!function_exists("get_invoice")){
    function get_invoice($transcationId)  {
        $CI = &get_instance();
        $invoice = $CI->main_model->get("*", "sp_payment_history_paytr", "oid = '".$transcationId."'");
        $user = $CI->main_model->get("*", "sp_users", "id = '".$invoice->user_id."'");
        $data = [
            'user' => $user,
            'invoice' => $invoice
        ];
        return $data;
    }
}


