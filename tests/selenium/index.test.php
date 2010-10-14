<?php
$this->Selenium->open('/selenium');
$this->Selenium->assertTextPresent('CakePHP Test Suite 1.3');
$this->Selenium->assertTextPresent('App');
$this->Selenium->assertTextPresent('Plugins');
?>