<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LTI Launch</title>
    <style type="text/css">
        body {
            margin-top: 20%;
            margin-left: 40%;
        }
    </style>
</head>

<body>
    <progress style="width: 20%; height: 1em;"></progress>
    <form id="launch" method="post" action="<?= $action ?>">
        <?php foreach ($post as $key => $value) { ?>
            <input type="hidden" name="<?= $key ?>" value="<?= $value ?>" />
        <?php } ?>
        <input type="hidden" name="<?= $nonce_param ?>" value="<?= $nonce ?>" />
    </form>
    <script type="text/javascript">
        const authLoginUrl = '<?= $authLoginUrl ?>';
        const platformOrigin = new URL(authLoginUrl).origin;
        const frameName = '<?= $lti_storage_target ?>';
        const parent = window.parent || window.opener;
        const targetFrame = frameName === "_parent" ? parent : parent.frames[frameName];
        const messageId = crypto.randomUUID();
        const state = '<?= $state ?>';

        window.addEventListener('message', function(event) {
            if (
                typeof event.data !== "object" ||
                event.data.subject !== "lti.get_data.response" ||
                event.data.message_id !== messageId ||
                event.origin !== platformOrigin
            ) {
                return;
            }

            if (event.data.error) {
                console.error(`Error ${event.data.error.code} ${event.data.error.message}`);
                return;
            }
            document.getElementById('launch').submit();
        });

        targetFrame.postMessage({
            "subject": "lti.get_data",
            "message_id": messageId,
            "key": `state_${state}`,
        }, platformOrigin)
    </script>
</body>

</html>