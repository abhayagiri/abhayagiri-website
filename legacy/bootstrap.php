<?php

// Setup global variables
foreach (App\Legacy::$GLOBAL_VARIABLES as $key) {
    $$key = '';
}
