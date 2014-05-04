<?php
if (!extension_loaded('bcmath')) {
    echo "bc not found";
    exit;
} else {
    echo "bc found";
    exit;
}
?>