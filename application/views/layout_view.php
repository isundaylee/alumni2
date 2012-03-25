<?

if (!isset($extra_css)) $extra_css = array(); 

?>

<!DOCTYPE html>

<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title><?= $title ?></title>
		<link rel="stylesheet" type="text/css" href="<?= $css ?>" />
		<? foreach ($extra_css as $sheet): ?>
		<link rel="stylesheet" type="text/css" href="<?= $css_base . $sheet . '.css'; ?>" />
		<? endforeach; ?>
	</head>
	
	<body>
		<?= $header ?>
		<div id="panel"><?= $content ?></div>
		<?= $footer ?>
	</body>
</html>