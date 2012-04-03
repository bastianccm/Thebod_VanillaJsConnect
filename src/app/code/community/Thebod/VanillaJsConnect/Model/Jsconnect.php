<?php

class Thebod_VanillaJsConnect_Model_Jsconnect
{
    /**
     * return signature
     *
     * @param string $string
     * @return string
     */
    public function signature($string) {
        return md5($string);
    }

    /**
     * returns $data with signature and client_id
     *
     * @param array $data
     * @param string $clientId
     * @param string $clientSecret
     * @return array
     */
    public function buildSignedResult($data, $clientId, $clientSecret) {
        ksort($data);

        foreach ($data as $k => $v) {
            if(is_null($v)) {
                $data[$k] = '';
            }
        }

        $string = http_build_query($data, null, '&');
        $signature = $this->signature($string . $clientSecret);

        $data['client_id'] = $clientId;
        $data['signature'] = $signature;

        return $data;
    }

    /**
     * builds user array
     *
     * @todo return false/null when not logged in instead of an empty array
     * @return array
     */
    public function buildUserArray() {
        if(!Mage::getSingleton('customer/session')->isLoggedIn()) {
            return array();
        }

        $customer = Mage::getSingleton('customer/session')->getCustomer();
        $data = array(
            'uniqueid'  => $customer->getId(),
            'name'      => $customer->getName(),
            'email'     => $customer->getEmail(),
            'photourl'  => '',
        );

        return $data;
    }
}