<?php
$this->Selenium->open('/selenium/selenium/testcase?case=index.test.php&plugin=selenium');
$this->Selenium->assertTextPresent('open');
$this->Selenium->assertTextPresent('/selenium');
?>