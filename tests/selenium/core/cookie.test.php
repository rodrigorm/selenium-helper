<?php
$this->Selenium->open('/selenium/selenium/cookie');
$this->Selenium->assertCookieByName('selenium', 'selenium');

$this->Selenium->open('/selenium/selenium/cookie/stc12345');
$this->Selenium->assertCookieByName('selenium', 'stc12345');
?>