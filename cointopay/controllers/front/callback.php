<?php

require_once(_PS_MODULE_DIR_ . '/cointopay/vendor/cointopay/init.php');
require_once(_PS_MODULE_DIR_ . '/cointopay/vendor/version.php');

class CointopayCallbackModuleFrontController extends ModuleFrontController
{
    public $ssl = true;

    public function postProcess()
    {
        $cart_id = (int)Tools::getValue('CustomerReferenceNr');
        $order_id = Order::getOrderByCartId($cart_id);
        $order = new Order($order_id);
        try {
            if (!$order) {
                $error_message = 'Cointopay Order #' . Tools::getValue('order_id') . ' does not exists';

                $this->logError($error_message, $cart_id);
                throw new Exception($error_message);
            }

            $ctp_order_status = Tools::getValue('status');
            $notenough = Tools::getValue('notenough');
            $data = [
                'merchantId' => Configuration::get('COINTOPAY_MERCHANT_ID'),
                'TransactionID' => Tools::getValue('TransactionID'),
                'ConfirmCode' => Tools::getValue('ConfirmCode'),
                'Status' => $ctp_order_status,
            ];
            $response  = $this->validateResponse($data);

            if(!$response) {
                $error_message = 'Data deprecated ! Response data doesn\'t match to Cointopay';
                $this->logError($error_message, $cart_id);
                throw new Exception($error_message);

            }elseif ($ctp_order_status == 'paid' && $notenough == 0 ) {
                $order_status = 'PS_OS_PAYMENT';
            }
            elseif ($ctp_order_status == 'paid' && $notenough == 1) {
                $order_status = 'COINTOPAY_PNOTENOUGH';
            }
            elseif ($ctp_order_status == 'failed') {
                $order_status = 'COINTOPAY_FAILED';
                $this->logError('PS Orders is failed', $cart_id);
            }
            elseif ($ctp_order_status == 'canceled') {
                $order_status = 'PS_OS_CANCELED';
            }
            elseif ($ctp_order_status == 'refunded') {
                $order_status = 'PS_OS_REFUND';
            }
            else {
                $order_status = false;
            }

            if ($order_status !== false && $order_status == 'PS_OS_PAYMENT') {
                $history = new OrderHistory();
                $history->id_order = $order->id;
                $history->changeIdOrderState((int)Configuration::get($order_status), $order->id);
                $history->addWithemail(true, array(
                    'order_name' => Tools::getValue('order_id'),
                ));

                // Success URL
                $link = new Link();
                $success_url = $link->getPageLink('order-confirmation', null, null, array(
                    'id_cart' => $order->id_cart,
                    'id_module' => $this->module->id,
                    'key' => $order->secure_key
                ));

                Tools::redirect($success_url);

            }
            elseif ($order_status == 'COINTOPAY_FAILED' || $order_status == 'PS_OS_CANCELED'
                || $order_status == 'COINTOPAY_PNOTENOUGH' || $order_status == 'PS_OS_REFUND' )
            {
                $history = new OrderHistory();
                $history->id_order = $order->id;
                $history->changeIdOrderState((int)Configuration::get($order_status), $order->id);
                $history->addWithemail(true, array(
                    'order_name' => Tools::getValue('order_id'),
                ));

                Tools::redirect($this->context->link->getModuleLink('cointopay', 'cancel'));
            } else {
                $this->context->smarty->assign(array(
                    'text' => 'Order Status ' . $ctp_order_status . ' not implemented'
                ));
            }
        } catch (Exception $e) {
            $this->context->smarty->assign(array(
                'text' => get_class($e) . ': ' . $e->getMessage()
            ));
        }
        if (_PS_VERSION_ >= '1.7') {
            $this->setTemplate('module:cointopay/views/templates/front/payment_callback.tpl');
        } else {
            $this->setTemplate('payment_callback.tpl');
        }
    }

    public function validateResponse($response) {
        $validate = true;
        $merchant_id = $response['merchantId'];
        $transaction_id = $response['TransactionID'];
        $confirm_code = $response['ConfirmCode'];
        $url = "https://app.cointopay.com/v2REAPI?MerchantID=$merchant_id&Call=QA&APIKey=_&output=json&TransactionID=$transaction_id&ConfirmCode=$confirm_code";
        $curl = curl_init($url);
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYPEER => 0
        ));
        $result = curl_exec($curl);
        $result = json_decode($result, true);
        if(!$result || !is_array($result)) {
            $validate = false;
        }else{
            if($response['Status'] != $result['Status']) {
                $validate = false;
            }
        }
        return $validate;
    }

    private function logError($message, $cart_id)
    {
        PrestaShopLogger::addLog($message, 3, null, 'Cart', $cart_id, true);
    }
}