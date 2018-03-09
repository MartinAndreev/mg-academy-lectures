<?php

session_unset();
session_destroy();

redirect('index.php?action=login');