import './bootstrap';

document.addEventListener("DOMContentLoaded", function () {
    Livewire.on("subscriptionSuccess", function () {
        document.querySelector("#subscriptionModal")?.classList.remove("hidden");
    });
});
