<?php


	
	public function XMLtoJson ($string) {

		

		$fileContents = str_replace(array("\n", "\r", "\t"), '', $string);

		$fileContents = trim(str_replace('"', "'", $fileContents));

		$simpleXml = simplexml_load_string($fileContents);

		$json = json_encode($simpleXml);

		return $json;

	}


?>