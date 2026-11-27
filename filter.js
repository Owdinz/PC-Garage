
document.addEventListener("DOMContentLoaded", function () {

    // ==============================
    // PRICE RANGE DISPLAY
    // ==============================

    const priceRange = document.getElementById("priceRange");
    const priceVal = document.getElementById("priceVal");

    if (priceRange && priceVal) {

        function updatePrice() {
            priceVal.textContent = Number(priceRange.value)
                .toLocaleString("en-PH");
        }

        priceRange.addEventListener("input", updatePrice);

        updatePrice();
    }


    // ==============================
    // SORTING FUNCTION
    // ==============================

    const sortSelect = document.getElementById("sortSelect");

    if (sortSelect) {

        sortSelect.addEventListener("change", function () {

            const url = new URL(window.location.href);

            url.searchParams.set("sort", this.value);

            window.location.href = url.toString();

        });

    }

});
