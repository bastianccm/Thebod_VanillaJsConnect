<?php
/**
 * Thebod VanillaJsConnect
 *
 * adds jsconnect support for magento so you can login on vanilla with your magento account
 *
 * @author Bastian Ike <thebod@thebod.>
 * @license http://creativecommons.org/licenses/by/3.0/ CC-BY 3.0
 */
class Thebod_VanillaJsConnect_IndexController extends Mage_Core_Controller_Front_Action
{
    /**
     * index action, checks for parameters and then returns json-encoded data
     */
    public function indexAction() {
        $clientId = Mage::getStoreConfig('customer/vanillajsconnect/client_id');
        $clientSecret = Mage::getStoreConfig('customer/vanillajsconnect/client_secret');

        $result = array();

        // checking input parameters
        /**
         * @todo inplement input validation model?
         */

        if (!strlen($clientId) || !strlen($clientSecret)) {
            $result = array(
                'error' => 'invalid_request',
                'message' => 'Please set up VanillaJsConnect before using it!'
            );
        } else if (!$this->getRequest()->getParam('client_id')) {
            //no client_id
            $result = array(
                'error' => 'invalid_request',
                'message' => 'The client_id parameter is missing.'
            );
        } else if ($this->getRequest()->getParam('client_id') != $clientId) {
            // wrong client_id
            $result = array(
                'error' => 'invalid_request',
                'message' => 'unknown client_id'
            );
        } else if ((!$this->getRequest()->getParam('timestamp') || !is_numeric($this->getRequest()->getParam('timestamp'))) && 0){
            // no timestamp
            $result = array(
                'error' => 'invalid_request',
                'message' => 'The timestamp parameter is missing or invalid.'
            );
        } else if (!$this->getRequest('signature')) {
            // no signature
            $result = array(
                'error' => 'invalid_request',
                'message' => 'Missing  signature parameter.'
            );
        } else if (abs($this->getRequest()->getParam('timestamp') - time()) > (24 * 60) && 0) {
            // old timestamp
            $result = array(
                'error' => 'invalid_request',
                'message' => 'The timestamp is invalid.'
            );
        } else if (($this->_signature($this->getRequest()->getParam('timestamp') . $clientSecret) != $this->getRequest()->getParam('signature')) && 0) {
            // wrong signature
            $result = array(
                'error' => 'access_denied',
                'message' => 'Signature invalid.'
            );
        }

        if(!isset($result['error'])) {
            $user = $this->_buildUserArray();
            if(count($user)) {
                $result = $this->_buildSignedResult($user, $clientId, $clientSecret);
            } else {
                $result = array('name' => '', 'photourl' => '');
            }
        }

        $result = json_encode($result);

        if ($this->getRequest()->getParam('callback')) {
            // put into callback call
            $result = $this->getRequest()->getParam('callback') . '(' . $result . ');';
        }

        $this->getResponse()->setBody($result);
    }

    /**
     * return signature
     *
     * @param string $string
     * @return string
     */
    protected function _signature($string) {
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
    protected function _buildSignedResult($data, $clientId, $clientSecret) {
        ksort($data);

        foreach ($data as $k => $v) {
            if(is_null($v)) {
                $data[$k] = '';
            }
        }

        $string = http_build_query($data, null, '&');
        $signature = $this->_signature($string . $clientSecret);

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
    protected function _buildUserArray() {
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
