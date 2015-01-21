<?php require_once 'functions.php'; ?>

<!DOCTYPE html>
<html>
    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex, nofollow">
        <link rel="shortcut icon" href="img/font-awesome-rss-black.png">

        <title>sitefeed</title>

    </head>
    <body>

        <?php if (!is_writable('cache/')) : ?>
            <p>!!! Error: cache is not writable</p>
        <?php endif; ?>

        <p>Bookmarklet: <a href="javascript:location.href='<?php echo base_url('rss.php'); ?>?url='+encodeURIComponent(location.href);">sitefeed</a></p>

    </body>
</html>
