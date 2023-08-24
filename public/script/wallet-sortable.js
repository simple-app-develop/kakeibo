document.addEventListener("DOMContentLoaded", () => {
    const isSortableDisabled = !window.canCreatePermission;

    const updateWalletOrder = (elementId, reorderUrl) => {
        const walletList = document.getElementById(elementId);
        if (!walletList) return;

        Sortable.create(walletList, {
            animation: 150,
            disabled: isSortableDisabled,
            onEnd: function () {
                const order = [];
                walletList
                    .querySelectorAll("[data-id]")
                    .forEach(function (item) {
                        order.push(item.dataset.id);
                    });

                // サーバーに新しい順序を送信
                fetch(reorderUrl, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                    },
                    body: JSON.stringify({
                        order: order,
                    }),
                })
                    .then((response) => response.json())
                    .then((data) => {
                        console.log("Order updated on backend");
                    })
                    .catch((error) => {
                        console.error(
                            "Error updating order on backend:",
                            error
                        );
                        alert("Error updating order on backend");
                    });
            },
        });
    };

    updateWalletOrder("walletList", "/wallets/reorder");
});
