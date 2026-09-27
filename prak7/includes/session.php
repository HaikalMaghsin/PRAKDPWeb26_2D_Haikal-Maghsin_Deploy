<?php

$sessionPath = sys_get_temp_dir();

if (session_status() === PHP_SESSION_NONE) {
    session_name('praktikum7');
    session_save_path($sessionPath);
    session_start();
}
