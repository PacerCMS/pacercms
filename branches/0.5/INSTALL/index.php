<?php

// This file checks to see where you are in the install 

if (file_exists('../includes/config.php'))
{
    header("Location: cm-install.php");
    exit;
} else {
    header("Location: cm-config.php");
}
