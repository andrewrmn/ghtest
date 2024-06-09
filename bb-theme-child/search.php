<?php
$search = $_GET['s'] ?? '';
wp_redirect('/job-search/?_keyword_search=' . $search );
exit;
