<?php
class BigHippo_SignatureRequired_AjaxController extends Mage_Core_Controller_Front_Action {
	
	/**
     * Standard preDispatch
     *
     * @param void
     * @return object
     */
	public function preDispatch()
    {
	    parent::preDispatch();
        $this->_preDispatchValidateCustomer();

        return $this;
    }
	
	/**
     * Sets the excellenc fee boolean in the checkout session
     *
     * @param void
     * @return void
     */
    
    public function signatureAction() {
		$message = "error";
		$postData = Mage::app()->getRequest()->getPost();
		if (isset($postData['signature_required'])){
			$flag = $postData['signature_required'] === 'true' ? true: false;
			Mage::getSingleton('checkout/session')->setSignaturerequiredFlag($flag);
			$message = "success";
		}
		$data = array(
			'message' => $message,
			'session' => array("post" => $postData, "quote_id" => Mage::getSingleton('checkout/session')->getQuote()->getId(), "signaturerequired_flag" => Mage::getSingleton('checkout/session')->get("signaturerequired_flag"))
		);
		echo json_encode($data);
		exit;
	}
	
	/**
     * Make sure customer is valid, if logged in
     * By default will add error messages and redirect to customer edit form
     *
     * @param bool $redirect - stop dispatch and redirect?
     * @param bool $addErrors - add error messages?
     * @return bool
     */
    protected function _preDispatchValidateCustomer($redirect = true, $addErrors = true)
    {
		$customer = Mage::getSingleton('customer/session')->getCustomer();
        if ($customer && $customer->getId()) {
            $validationResult = $customer->validate();
            if ((true !== $validationResult) && is_array($validationResult)) {
                if ($addErrors) {
                    foreach ($validationResult as $error) {
                        Mage::getSingleton('customer/session')->addError($error);
                    }
                }
                if ($redirect) {
                    $this->_redirect('customer/account/edit');
                    $this->setFlag('', self::FLAG_NO_DISPATCH, true);
                }
                return false;
            }
        }
        return true;
    }
}
