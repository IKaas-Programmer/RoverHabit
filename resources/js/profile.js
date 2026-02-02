// File: resources/js/profile.js

//  LOGIKA MODAL USERNAME
window.openModal = function () {
    const modal = document.getElementById("editNameModal");
    const inputField = document.getElementById("modalInputName");

    if (modal) {
        modal.classList.remove("hidden");
        setTimeout(() => {
            if (inputField) {
                inputField.focus();
                inputField.select();
            }
        }, 100);
    }
};

window.closeModal = function () {
    const modal = document.getElementById("editNameModal");
    if (modal) {
        modal.classList.add("hidden");
    }
};

// LOGIKA CROPPER AVATAR
document.addEventListener("DOMContentLoaded", function () {
    let cropper;
    const avatarInput = document.getElementById("avatar-input");
    const cropModal = document.getElementById("cropModal");
    const imageToCrop = document.getElementById("image-to-crop");
    const avatarForm = document.getElementById("avatar-form");
    const cropBtn = document.getElementById("crop-and-save-btn");

    window.closeCropModal = function () {
        if (cropModal) {
            cropModal.classList.add("hidden");
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
        }
        if (avatarInput) avatarInput.value = "";
    };

    if (avatarInput) {
        avatarInput.addEventListener("change", function (e) {
            const files = e.target.files;
            if (files && files.length > 0) {
                const file = files[0];
                if (!file.type.startsWith("image/")) {
                    alert("Please select an image file.");
                    return;
                }
                const reader = new FileReader();
                reader.onload = function (event) {
                    imageToCrop.src = event.target.result;
                    cropModal.classList.remove("hidden");
                    if (cropper) cropper.destroy();

                    cropper = new Cropper(imageToCrop, {
                        aspectRatio: 1 / 1,
                        viewMode: 1,
                        dragMode: "move",
                        autoCropArea: 0.8,
                        guides: true,
                        center: true,
                        highlight: false,
                        cropBoxMovable: false,
                        cropBoxResizable: false,
                        toggleDragModeOnDblclick: false,
                    });
                };
                reader.readAsDataURL(file);
            }
        });

        if (cropBtn) {
            cropBtn.addEventListener("click", function () {
                if (!cropper) return;

                // Indikator Loading
                const originalText = cropBtn.innerText;
                cropBtn.innerText = "Uploading...";
                cropBtn.disabled = true;

                cropper
                    .getCroppedCanvas({
                        width: 400,
                        height: 400,
                        imageSmoothingQuality: "high",
                    })
                    .toBlob(
                        (blob) => {
                            // MENGGUNAKAN FORMDATA (Lebih Stabil daripada submit manual)
                            const formData = new FormData();
                            formData.append("avatar", blob, "avatar.jpg");

                            // Ambil token CSRF dan Method PATCH dari form
                            const csrfToken = avatarForm.querySelector(
                                'input[name="_token"]',
                            ).value;
                            const method = avatarForm.querySelector(
                                'input[name="_method"]',
                            ).value;

                            formData.append("_token", csrfToken);
                            formData.append("_method", method);

                            // Kirim via Fetch
                            fetch(avatarForm.action, {
                                method: "POST", // Laravel membaca _method PATCH di dalam body
                                body: formData,
                                headers: {
                                    "X-Requested-With": "XMLHttpRequest",
                                },
                            })
                                .then((response) => {
                                    if (response.ok) {
                                        window.location.reload(); // Refresh jika berhasil
                                    } else {
                                        alert(
                                            "Gagal mengunggah gambar. Pastikan ukuran file tidak terlalu besar.",
                                        );
                                        cropBtn.innerText = originalText;
                                        cropBtn.disabled = false;
                                    }
                                })
                                .catch((error) => {
                                    console.error("Error:", error);
                                    alert("Terjadi kesalahan koneksi.");
                                    cropBtn.innerText = originalText;
                                    cropBtn.disabled = false;
                                });
                        },
                        "image/jpeg",
                        0.9,
                    );
            });
        }
    }
});

// Listener tombol ESC
document.addEventListener("keydown", function (event) {
    if (event.key === "Escape") {
        if (typeof closeModal === "function") closeModal();
        if (typeof closeCropModal === "function") closeCropModal();
    }
});

// LOGIKA MODAL INVENTORY
window.openInventoryModal = function () {
    const modal = document.getElementById("inventoryModal");
    if (modal) modal.classList.remove("hidden");
};

window.closeInventoryModal = function () {
    const modal = document.getElementById("inventoryModal");
    if (modal) modal.classList.add("hidden");
};
