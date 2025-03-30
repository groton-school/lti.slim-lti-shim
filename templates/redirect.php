<!DOCTYPE html>
<html lang="en">
    <head>
        <title>LTI Redirect</title>
    </head>
    <body onload="window.location.href = '<?= $redirect ?>';">
        <p>If you are not redirected, <a href="<?= $redirect ?>">please click here.</a></p>
    </body>
</html>