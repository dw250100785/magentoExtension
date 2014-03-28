<?php
/**
 * BigHippo from unexpected it
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.unexpectedit.com/LICENSE-M1.txt
 *
 * @category   iCloset
 * @package    BigHippo_iCloset
 * @copyright  Copyright (c) 2003-2016 unexpected it Co. (http://www.unexpectedit.com)
 * @license    http://www.unexpectedit.com/LICENSE-M1.txt
 */ 
class BigHippo_Icloset_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
		header('Cache-Control: no-cache, must-revalidate');

		$this->loadLayout();
        $this->renderLayout();
        return $this;    	
    }
	
	public function saveAction() {
		header('Cache-Control: no-cache, must-revalidate');

		$session = Mage::getSingleton("core/session",  array("name"=>"frontend"));
		$session->setData("selection", $this->getRequest()->getParam("selection"));
		$session->setData("zIndex", $this->getRequest()->getParam("zIndex"));
		$session->setData("scope", $this->getRequest()->getParam("scope"));
		$session->setData("skus", $this->getRequest()->getParam("skus"));
		exit;
	}
	
	public function loadAction() {
		$session = Mage::getSingleton("core/session",  array("name"=>"frontend"));

		$json["selection"] = $session->getData("selection");
		$json["zIndex"] = $session->getData("zIndex");
		$json["scope"] = $session->getData("scope");
		
		header('Cache-Control: no-cache, must-revalidate');
		header('Content-type: application/json');
		header('Access-Control-Allow-Origin: *');    	
		echo json_encode($json);	

		exit;
	}
	
	public function cleanAction() {
		$session = Mage::getSingleton("core/session",  array("name"=>"frontend"));

		$session->setData("selection", null);
		$session->setData("zIndex", 0);
		$session->setData("zIndex", null);

		exit;
	}
	
}