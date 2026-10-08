import "./bootstrap";

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

// Page Loader
window.addEventListener("load", () => {
    const loader = document.getElementById("page-loader");
    if (loader) {
        loader.style.display = "none";
    }
});

const applySidebarState = (collapsed) => {
    const button = document.getElementById("sidebarCollapseBtn");
    const icon = document.getElementById("sidebarCollapseIcon");

    document.documentElement.classList.toggle("sidebar-collapsed", collapsed);

    if (button) {
        button.setAttribute(
            "aria-label",
            collapsed ? "Buka sidebar" : "Collapse sidebar"
        );
        button.setAttribute(
            "title",
            collapsed ? "Buka sidebar" : "Collapse sidebar"
        );
    }

    if (icon) {
        icon.classList.toggle("fa-angles-left", !collapsed);
        icon.classList.toggle("fa-angles-right", collapsed);
    }
};

document.addEventListener("DOMContentLoaded", () => {
    const button = document.getElementById("sidebarCollapseBtn");

    if (!button) return;

    const savedState = localStorage.getItem("dashboard-sidebar-collapsed");
    const collapsed = savedState === "true";

    applySidebarState(collapsed);

    button.addEventListener("click", () => {
        const nextState = !document.documentElement.classList.contains(
            "sidebar-collapsed"
        );

        localStorage.setItem(
            "dashboard-sidebar-collapsed",
            String(nextState)
        );
        applySidebarState(nextState);
    });
});

document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("[data-notification-menu]").forEach((menu) => {
        const summary = menu.querySelector("summary");
        const scope = menu.dataset.notificationScope || "default";
        const totalBadge = menu.querySelector("[data-notification-total-badge]");
        const countLabel = menu.querySelector("[data-notification-count-label]");
        const clearButton = menu.querySelector("[data-notification-clear-all]");
        const emptyMessage = menu.querySelector("[data-notification-empty]");
        const items = Array.from(
            menu.querySelectorAll("[data-notification-item]")
        );

        const groupIdentity = (group) => {
            const item = items.find(
                (notification) => notification.dataset.notificationGroup === group
            );

            return (
                item?.dataset.notificationVersion ||
                item?.dataset.notificationCount ||
                "0"
            );
        };

        const storageKey = (group) =>
            `dashboard-notifications-seen:${scope}:${group}`;
        const dismissedKey = (group) =>
            `dashboard-notifications-dismissed:${scope}:${group}`;
        const isSeen = (group) =>
            group && localStorage.getItem(storageKey(group)) === groupIdentity(group);
        const markSeen = (group) => {
            if (group) localStorage.setItem(storageKey(group), groupIdentity(group));
        };
        const isDismissed = (group) =>
            group &&
            localStorage.getItem(dismissedKey(group)) === groupIdentity(group);
        const dismiss = (group) => {
            if (group) {
                localStorage.setItem(dismissedKey(group), groupIdentity(group));
            }
        };

        const refreshNotificationList = () => {
            const visibleItems = items.filter((item) => {
                const dismissed = isDismissed(
                    item.dataset.notificationGroup || ""
                );
                item.classList.toggle("hidden", dismissed);
                return !dismissed;
            });

            if (countLabel) {
                countLabel.textContent = `${visibleItems.length} terbaru`;
                countLabel.classList.toggle("hidden", visibleItems.length === 0);
            }
            if (emptyMessage) {
                emptyMessage.classList.toggle("hidden", visibleItems.length > 0);
            }
            if (clearButton) {
                clearButton.classList.toggle("hidden", visibleItems.length === 0);
            }
        };

        const refreshBadges = () => {
            const activeItems = items.filter(
                (item) =>
                    Number(item.dataset.notificationCount || 0) > 0 &&
                    !isSeen(item.dataset.notificationGroup || "")
            ).length;

            if (totalBadge) {
                totalBadge.textContent = String(activeItems);
                totalBadge.classList.toggle("hidden", activeItems === 0);
            }
        };

        refreshNotificationList();
        refreshBadges();

        summary?.addEventListener("click", (event) => {
            event.preventDefault();
            menu.open = !menu.open;
        });

        menu.addEventListener("toggle", () => {
            summary?.setAttribute("aria-expanded", String(menu.open));
            if (!menu.open) return;

            items.forEach((item) => {
                markSeen(item.dataset.notificationGroup || "");
            });
            refreshBadges();
        });

        clearButton?.addEventListener("click", () => {
            items.forEach((item) => {
                const group = item.dataset.notificationGroup || "";
                markSeen(group);
                dismiss(group);
            });
            refreshNotificationList();
            refreshBadges();
        });

        items.forEach((item) => {
            item.addEventListener("click", () => {
                markSeen(item.dataset.notificationGroup || "");
                refreshBadges();
            });
        });
    });
});
