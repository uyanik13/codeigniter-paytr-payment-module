<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class paytr extends MY_Controller {

    public $tb_package_manager = "sp_package_manager";

    public function __construct(){
        parent::__construct();
        $this->load->model(get_class($this).'_model', 'model');
        $this->load->model('payment/payment_model', 'payment_model');

        //
        $this->module_name = get_module_config( $this, 'name' );
        $this->module_icon = get_module_config( $this, 'icon' );
        //

        if( ! get_option("paytr_status", 1) ){
            redirect( get_url() );
        }


    }

    public function index($ids = "", $plan = 1)
    {
        if(!_s("uid")){
            redirect( get_url("login?redirect=".urlencode( get_url("payment/index/".$ids."/".$plan) )) );
        }

        if(_gd("is_subscription", 0)){
            $error = __("You are using the monthly payment plan. Cancel it if you want to change the package or change your payment method.");
            redirect( get_url( "profile/index/package?error=".urlencode($error) ) );
        }

        $package = $this->payment_model->get_package($ids, $plan);

        $merchant_id = get_option('paytr_merchant_id', '');
        $merchant_key = get_option('paytr_merchant_key', '');
        $merchant_salt = get_option('paytr_merchant_salt', '');
        $test_mode = get_option('paytr_test_mode', '');



        $email = _u("email");

        $payment_amount = $package->amount * 100;

        $merchant_oid = strtotime(now());
       // $merchant_oid = date("dmY") . substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);

        $user_name = _u("name") ? _u("name") : 'Osman' . ' ' . _u("surname") ? _u("surname") : 'Cansız' ;


        $user_address = _u("adress") ? _u("adress") : "Istanbul / Bahçelievler";

        $user_phone = _u("phone_number") ? _u("phone_number") : "555 555 55 55";

        $merchant_ok_url = get_url("paytr/complete/".$ids."/".$plan."/".$merchant_oid);

        $merchant_fail_url = "payment_unsuccessfully";

        $user_basket = base64_encode(json_encode(array(
            array("Site Bakiyesi", "1", $payment_amount),
        )));


        if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        } elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else {
            $ip = $_SERVER["REMOTE_ADDR"];
        }


        $user_ip = $ip;
        $timeout_limit = "30";
        $debug_on = 1;
        $no_installment = 0;
        $max_installment = 0;
        $currency = "TL";
        $hash_str = $merchant_id . $user_ip . $merchant_oid . $email . $payment_amount . $user_basket . $no_installment . $max_installment . $currency . $test_mode;
        $paytr_token = base64_encode(hash_hmac('sha256', $hash_str . $merchant_salt, $merchant_key, true));

        $post_vals = array(
            'merchant_id' => $merchant_id,
            'user_ip' => $user_ip,
            'merchant_oid' => $merchant_oid,
            'email' => $email,
            'payment_amount' => $payment_amount,
            'paytr_token' => $paytr_token,
            'user_basket' => $user_basket,
            'debug_on' => $debug_on,
            'no_installment' => $no_installment,
            'max_installment' => $max_installment,
            'user_name' => $user_name,
            'user_address' => $user_address,
            'user_phone' => $user_phone,
            'merchant_ok_url' => $merchant_ok_url,
            'merchant_fail_url' => $merchant_fail_url,
            'timeout_limit' => $timeout_limit,
            'currency' => $currency,
            'test_mode' => $test_mode
        );

    print_r($post_vals);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.paytr.com/odeme/api/get-token");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_vals);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        $result = @curl_exec($ch);

        if (curl_errno($ch))
            die("PAYTR IFRAME connection error. err:" . curl_error($ch));

                 curl_close($ch);
               $result = json_decode($result, 1);

              if ($result['status'] == 'success') {

        try {
             _ss("paytr_check", true);
                $data = array(
                    'oid' => $merchant_oid,
                    'user_id' => _u("id"),
                    'amount' => $payment_amount / 100,
                    'package' => $ids,
                    'plan' => $plan,
                    'type' => 'PayTr',
                    'status' => 1,
                );
                $this->db->insert('sp_payment_history_paytr', $data);
                redirect('https://www.paytr.com/odeme/guvenli/' . $result['token']);

            } catch (Exception $e) {
                echo $e->getMessage();
                exit(0);
            }
         }
  }

    public function complete($ids = "", $plan = "", $merchant_oid = ""){

        try {
            if(!_s("paytr_check")) redirect( get_module_url("index/".$ids."/".$plan) );

            $package = $this->payment_model->get_package($ids, $plan);
            _us("paytr_check");


          $query = $this->db->get_where( 'sp_payment_history_paytr', array('oid' => $merchant_oid))->row();

                $query = json_encode($query);
                $query = json_decode($query, true);
                $oid = $query['oid'];
                 if($query){
                        $data = [
                            'type' => 'PayTr',
                            'package' => $package->id,
                            'transaction_id' => $query['oid'],
                            'amount' => $query['amount'],
                            'plan' => $query['plan'],
                        ];
                        $this->sendEmail((int)$oid);
                        $this->payment_model->save($data);
                    }
                        else{ redirect( get_url("payment/unsuccess") );

                  }

        } catch (Exception $e) {
            echo $e->getMessage();
            exit(0);
        }

    }

    public function sendEmail($transactionId) {

        include __DIR__.'/../libraries/vendor/autoload.php';
        $CI = &get_instance();
        $this->load->library('parser');
        try {
            $mail = new PHPMailer(true);

            $mail->CharSet = 'UTF-8';

            if(get_option("email_protocol", 1) == 2){
                //Server settings

                $mail->isSMTP();
                $mail->Host       = get_option('email_smtp_server', '');
                $mail->SMTPAuth   = true;
                $mail->Username   = get_option('email_smtp_username', '');
                $mail->Password   = get_option('email_smtp_password', '');
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = get_option('email_smtp_port', '');
            }

            //Recipients
            $mail->setFrom('admin@cksosyal.com');
            $mail->addAddress('muhasebe@cksosyal.com');
            $mail->addCC('info@cksosyal.com');


            $dataHelper = get_invoice($transactionId);


            $data = [
                'fullname' => $dataHelper['user']->fullname,
                'adress' => $dataHelper['user']->adress,
                'phone_number' => $dataHelper['user']->phone_number,
                'firma_ismi' => $dataHelper['user']->firma_ismi,
                'vergi_dairesi' => $dataHelper['user']->vergi_dairesi,
                'vergi_numarasi' => $dataHelper['user']->vergi_numarasi,
                'email' => $dataHelper['user']->email,
                'package' => $dataHelper['invoice']->package,
                'transaction_id' => $dataHelper['invoice']->oid,
                'amount' => $dataHelper['invoice']->amount,
                'plan' => $dataHelper['invoice']->plan,
                'type' => $dataHelper['invoice']->type,
                'date' => $dataHelper['invoice']->created,
                'id' => $transactionId
            ];



            $mail->Subject = 'Paket Faturası';
            $mail->isHTML(true);
            $body = $this->parser->parse('emailTemplate',$data,TRUE);
            $mail->Body    = $body;

            $mail->send();

            return [
                "status" => "success",
                "message" => __("Success")
            ];
        } catch (Exception $e) {
            return [
                "status" => "error",
                "message" => __("Message could not be sent. Mailer Error: {$mail->ErrorInfo}")
            ];
        }


    }
}
