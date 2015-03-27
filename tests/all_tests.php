<?php
$files = array();
$ignore_list = array();
$dir = dir(dirname(__FILE__));
while ($file = $dir->read()) {
	if (stripos($file, 'test_') === 0) {
		if (!in_array($file, $ignore_list)) {
			//echo "<b style='color:darkgreen'>$file</b><br/>";
			//$this->addFile($file);
			$files[] = $file;
		} else {
			//echo "ignore:$file<br/>";
		}
	}
}
$dir->close();
echo '<script>var files=' . json_encode($files) . ';</script>';
?>
<script src="jq.js"></script>
<div id="output"></div>
<script>
	$(function () {
		function doTest() {
			var $url = files.pop();
			$('#testing').remove();
			if ($url) {
				$('#output').prepend('<div id="testing">testing ' + $url + '</div>');
				$.get($url, function (data) {
					$('#output').prepend(data);
					doTest();
				});
			} else {
				$('#output').prepend('<div style="font-weight:bold;font-size:2em;color:green;">testing done</div>');

			}
		}
		doTest();
	});
</script>