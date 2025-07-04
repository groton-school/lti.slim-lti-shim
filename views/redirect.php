<!DOCTYPE html>
<html lang="en">

<head>
    <title>LTI Redirect</title>
</head>

<body>
    <p>Loading…</p>
    <script>
        const platformOIDCLoginURL = '<?= $redirect ?>';
        const url = new URL(platformOIDCLoginURL);
        const platformOrigin = url.origin;
        const state = url.searchParams.get('state');
        const nonce = url.searchParams.get('nonce');
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
                    // This isn't a message we're expecting
                    if (typeof event.data !== "object") {
                        return;
                    }

                    // Validate it's the response type you expect
                    if (event.data.subject !== "lti.put_data.response") {
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
                        console.log(event.data.error.code)
                        console.log(event.data.error.message)
                        return;
                    }

                    // It's the response we expected
                    // The state and nonce values were successfully stored, redirect to Platform
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