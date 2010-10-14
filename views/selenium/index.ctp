<?php $selenium->suiteTitle('CakePHP Test Suite 1.3'); ?>
<?php $indent = '&nbsp;&nbsp;&nbsp;' ?>
<?php $skipSubtitle = 'javascript:window.top.skipSubtitle();//' ?>
<tr>
	<td>
		<a href="<?php echo $skipSubtitle ?>"></a><strong>App</strong>
	</td>
</tr>
<?php if (!empty($testcases['app'])): ?>
	<?php echo $this->element('testcases', array('cases' => $testcases['app'], 'indent' => $indent)) ?>
<?php endif ?>
<tr>
	<td>
		<a href="<?php echo $skipSubtitle ?>"></a><strong>Plugins</strong>
	</td>
</tr>
<?php if (!empty($testcases['plugins'])): ?>
	<?php foreach ($testcases['plugins'] as $plugin => $pluginCases): ?>
		<tr>
			<td>
				<a href="<?php echo $skipSubtitle ?>"></a><strong><?php echo $indent ?><?php echo Inflector::camelize($plugin) ?></strong>
			</td>
		</tr>
		<?php echo $this->element('testcases', array('pluginName' => $plugin, 'cases' => $pluginCases, 'indent' => $indent . $indent)) ?>
	<?php endforeach ?>
<?php endif ?>

<script type="text/javascript" charset="utf-8">
window.top.skipSubtitle = function() {
	var htmlTestRunner = window.top.htmlTestRunner;
	var testSuite = htmlTestRunner.getTestSuite();
	testSuite.getCurrentRow().markDone();

	if (htmlTestRunner.runAllTests) {
		htmlTestRunner.runNextTest();
	} else {
		testSuite.suiteRows[testSuite.currentRowInSuite + 1].loadTestCase();
	}
}
</script>