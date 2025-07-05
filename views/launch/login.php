<!DOCTYPE html>
<html lang="en">

<head>
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
    <script>
        const platformOIDCLoginURL = '<?= $redirect ?>';
        const url = new URL(platformOIDCLoginURL);
        const platformOrigin = url.origin;
        const state = url.searchParams.get('state');
        const messageId = crypto.randomUUID();

        function redirect_to_platform(url) {
            window.location.href = url;
        }

        document.hasStorageAccess().then(hasAccess => {
            if (!hasAccess) {
                const frameName = '<?= $lti_storage_target ?>';
                const parent = window.parent || window.opener;
                const targetFrame = frameName === "_parent" ? parent : parent.frames[frameName];

                window.addEventListener('message', function(event) {
                    if (
                        typeof event.data !== "object" ||
                        event.data.subject !== "lti.put_data.response" ||
                        event.data.message_id !== messageId ||
                        event.origin !== platformOrigin) {
                        return;
                    }

                    if (event.data.error) {
                        console.error(`Error ${event.data.error.code} ${event.data.error.message}`)
                        return;
                    }

                    redirect_to_platform(platformOIDCLoginURL);
                });

                targetFrame.postMessage({
                    "subject": "lti.put_data",
                    "message_id": messageId,
                    "key": `state_${state}`,
                    "value": state
                }, platformOrigin)
            } else {
                redirect_to_platform(platformOIDCLoginURL);
            }
        })
    </script>
</body>

</html>