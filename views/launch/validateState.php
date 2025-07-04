<html>

<head>
    <title>LTI Launch: postMessage</title>
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

        window.addEventListener('message', function(event) {
            // This isn't a message we're expecting
            if (typeof event.data !== "object") {
                return;
            }

            // Validate it's the response type you expect
            if (event.data.subject !== "lti.get_data.response") {
                return;
            }

            // Validate the message id matches the id you sent
            if (event.data.message_id !== messageId) {
                // this is not the response you're looking for
                return;
            }

            // Validate that the event's origin is the same as the derived platform origin
            if (event.origin !== platformOrigin) {
                return;
            }

            // handle errors
            if (event.data.error) {
                // handle errors
                console.error(`Error ${event.data.error.code} ${event.data.error.message}`);
                return;
            }

            // It's the response we expected
            // The state and nonce values were successfully fetched, validate them
            document.getElementById('launch').submit();
        });

        targetFrame.postMessage({
            "subject": "lti.get_data",
            "message_id": messageId,
            "key": `state_<?= $state ?>`,
        }, platformOrigin)
    </script>
</body>

</html>