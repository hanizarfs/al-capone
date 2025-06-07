// Set default theme based on localStorage
function applyTheme() {
    const savedTheme = localStorage.getItem("theme") || "auto";
    const theme =
        savedTheme === "auto"
            ? window.matchMedia &&
              window.matchMedia("(prefers-color-scheme: dark)").matches
                ? "dark"
                : "light"
            : savedTheme;

    document.documentElement.setAttribute("data-bs-theme", theme);

    // Dynamically update the icon based on the selected theme
    updateIcon(theme);

    // Set the dropdown active state based on saved theme
    const activeButton = document.querySelector(
        `[data-bs-theme-value="${theme}"]`
    );
    if (activeButton) {
        activeButton.classList.add("active");
    }
}

// Update theme icon based on current theme
function updateIcon(theme) {
    const icon = document.getElementById("theme-icon");
    if (theme === "dark") {
        icon.classList.remove("bi-sun-fill");
        icon.classList.add("bi-moon-stars");
    } else {
        icon.classList.remove("bi-moon-stars");
        icon.classList.add("bi-sun-fill");
    }
}

// Apply theme when the page loads
applyTheme();

// Event listener for theme selection in the dropdown
document.querySelectorAll(".dropdown-item").forEach((item) => {
    item.addEventListener("click", function () {
        const theme = this.getAttribute("data-bs-theme-value");
        document.documentElement.setAttribute("data-bs-theme", theme);
        localStorage.setItem("theme", theme);

        // Update the icon after theme selection
        updateIcon(theme);

        // Set the active state in the dropdown
        document
            .querySelectorAll(".dropdown-item")
            .forEach((btn) => btn.classList.remove("active"));
        this.classList.add("active");
    });
});
