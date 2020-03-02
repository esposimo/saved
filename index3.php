<?php

header('Content-type: text/html;charset=utf8');
$string = 'abcd';
echo '<table>';
for ($i = 0; $i < 65536; $i++) {
    echo '<td>';
    echo dechex($i) . ' ' . mb_chr($i, 'utf-8');
    echo '</td>';
    if (($i % 32) == 0) {
        echo '<tr></tr>';
    }
}
echo '</table>';

