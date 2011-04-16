<html>
<head>
<title>404 Page Not Found: "<?= ($_SERVER['SERVER_PORT'] == 80 ? "http" : "https") . "://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . ($_SERVER['QUERY_STRING'] != "" ? "?" . ($_SERVER['QUERY_STRING']) : "") ?>"</title>
</head>
<body style="text-align: center;margin:0;">
<div style="margin: 0;padding: 0;">
<h1>404 Page Not Found: "<?= ($_SERVER['SERVER_PORT'] == 80 ? "http" : "https") . "://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . ($_SERVER['QUERY_STRING'] != "" ? "?" . ($_SERVER['QUERY_STRING']) : "") ?>"</h1>
<p>
Page <?php
$uri = explode('/', $_SERVER['REQUEST_URI']);
echo $uri[count($uri)-1];
?> was not found.
<br />
Either go back to the previous page or go to a known page.
</p>
</div>
</body>
</html>
