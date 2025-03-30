<!DOCTYPE html>
<html lang="en">

<head>
    <title>LTI Tool Configuration</title>
</head>

<body>
    <pre lang="json"><?= $config ?></pre>
    <form method="post" action="/lti/complete-regisration">
        <button type="submit">Complete Configuration</button>
    </form>
</body>

</html>