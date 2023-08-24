document.addEventListener("DOMContentLoaded", () => {
    const isSortableDisabled = !window.canCreatePermission;

    const updateCategoryOrder = (elementId, reorderUrl) => {
        const categoryList = document.getElementById(elementId);
        if (!categoryList) return;

        Sortable.create(categoryList, {
            animation: 150,
            disabled: isSortableDisabled,
            onEnd: function () {
                const order = [];
                categoryList
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
                    .then((response) => {
                        if (!response.ok) {
                            // エラーレスポンスが返された場合、エラーメッセージをJSONとして解析します
                            return response
                                .json()
                                .then((err) => Promise.reject(err));
                        }
                        return response.json();
                    })
                    .then((data) => {
                        console.log("Order updated on backend");
                    })
                    .catch((error) => {
                        console.error("Error updating order on backend:");
                        alert("Error updating order on backend");
                    });
            },
        });
    };

    updateCategoryOrder("expenseCategoryList", "/expense-category/reorder");
    updateCategoryOrder("incomeCategoryList", "/expense-category/reorder");
});
