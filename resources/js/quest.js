/**
 * Quest Logic - Module based (Vite)
 */

window.openCreateQuestModal = function () {
    const modal = document.getElementById("createQuestModal");
    if (modal) {
        modal.classList.remove("hidden");
        document.body.style.overflow = "hidden";
    }
};

window.closeCreateQuestModal = function () {
    const modal = document.getElementById("createQuestModal");
    if (modal) {
        modal.classList.add("hidden");
        document.body.style.overflow = "auto";
    }
};

// Menangani penutupan modal dengan ESC
window.addEventListener("keydown", (e) => {
    if (e.key === "Escape") closeCreateQuestModal();
});
