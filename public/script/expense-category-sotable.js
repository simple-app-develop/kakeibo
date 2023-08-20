document.addEventListener("DOMContentLoaded", () => {
    const updateCategoryOrder = (elementId, reorderUrl) => {
        const categoryList = document.getElementById(elementId);
        if (!categoryList) return;

        Sortable.create(categoryList, {
            animation: 150,
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
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.message === "Order updated successfully") {
                            console.log("Order updated on backend");
                        } else {
                            alert(
                                "Error updating order on backend:" +
                                    data.message
                            );
                        }
                    });
            },
        });
    };

    updateCategoryOrder("expenseCategoryList", "/expense-category/reorder");
    updateCategoryOrder("incomeCategoryList", "/expense-category/reorder");
});
