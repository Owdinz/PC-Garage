// PC-Garage: PHP Session Authentication Guard

(async function () {
    const protectedPages = [
        "buy-sell.php",
        "cart.php",
        "profile.php",
        "profile-settings.php",
        "checkout.php",
        "deliver.php"
    ];

    const currentPage = window.location.pathname
        .split("/")
        .pop();

    if (!protectedPages.includes(currentPage)) {
        return;
    }

    try {
        const response = await fetch("session-check.php", {
            method: "GET",
            credentials: "same-origin",
            cache: "no-store"
        });

        if (!response.ok) {
            throw new Error("Session verification failed.");
        }

        const data = await response.json();

        if (!data.authenticated) {
            alert("Access Restricted: Please log in first.");
            window.location.replace("login.php");
        }

    } catch (error) {
        console.error("Authentication error:", error);

        alert("Unable to verify your login session.");
        window.location.replace("login.php");
    }
})();
