<?php

class Thebod_VanillaJsConnect_Block_Jscontrol extends Mage_Core_Block_Template
{
    const _TEMPLATE_LOGIN = 'vanillajsconnect/jscontrol_login.phtml';
    const _TEMPLATE_LOGOUT = 'vanillajsconnect/jscontrol_logout.phtml';

    public function __construct() {
        /**
         * @var Mage_Customer_Model_Session $customer
         */
        $customer = Mage::getSingleton('customer/session');

        if ($customer->isLoggedIn()) {
            $this->setTemplate(self::_TEMPLATE_LOGIN);
        } else {
            $this->setTemplate(self::_TEMPLATE_LOGOUT);
        }
    }
}