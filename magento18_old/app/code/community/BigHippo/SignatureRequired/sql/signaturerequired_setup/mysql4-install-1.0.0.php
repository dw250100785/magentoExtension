<?php
$installer = $this;
$installer->startSetup();
$installer->run("
		ALTER TABLE  `".$this->getTable('sales/quote')."` ADD  `signaturerequired_flag` int NULL;
	
		ALTER TABLE  `".$this->getTable('sales/order')."` ADD  `signaturerequired_amount` DECIMAL( 10, 2 ) NOT NULL;
		ALTER TABLE  `".$this->getTable('sales/order')."` ADD  `base_signaturerequired_amount` DECIMAL( 10, 2 ) NOT NULL;
		ALTER TABLE  `".$this->getTable('sales/order')."` ADD  `signaturerequired_amount_invoiced` DECIMAL( 10, 2 ) NOT NULL;
		ALTER TABLE  `".$this->getTable('sales/order')."` ADD  `base_signaturerequired_amount_invoiced` DECIMAL( 10, 2 ) NOT NULL;
		ALTER TABLE  `".$this->getTable('sales/order')."` ADD  `signaturerequired_amount_refunded` DECIMAL( 10, 2 ) NOT NULL;
		ALTER TABLE  `".$this->getTable('sales/order')."` ADD  `base_signaturerequired_amount_refunded` DECIMAL( 10, 2 ) NOT NULL;
		ALTER TABLE  `".$this->getTable('sales/order')."` ADD  `signaturerequired_flag` int NULL;
		
		ALTER TABLE  `".$this->getTable('sales/quote_address')."` ADD  `signaturerequired_amount` DECIMAL( 10, 2 ) NOT NULL;
		ALTER TABLE  `".$this->getTable('sales/quote_address')."` ADD  `base_signaturerequired_amount` DECIMAL( 10, 2 ) NOT NULL;
		
		ALTER TABLE  `".$this->getTable('sales/invoice')."` ADD  `signaturerequired_amount` DECIMAL( 10, 2 ) NOT NULL;
		ALTER TABLE  `".$this->getTable('sales/invoice')."` ADD  `base_signaturerequired_amount` DECIMAL( 10, 2 ) NOT NULL;
		
		ALTER TABLE  `".$this->getTable('sales/creditmemo')."` ADD  `signaturerequired_amount` DECIMAL( 10, 2 ) NOT NULL;
		ALTER TABLE  `".$this->getTable('sales/creditmemo')."` ADD  `base_signaturerequired_amount` DECIMAL( 10, 2 ) NOT NULL;
	");
$installer->endSetup(); 