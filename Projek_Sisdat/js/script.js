function setActiveTab() {
    const activeTab = document.body.getAttribute('data-active-tab');
    if (activeTab === 'user') {
        const userTab = new bootstrap.Tab(document.getElementById('user-tab'));
        userTab.show();
    }
}

// Panggil fungsi saat DOM siap
document.addEventListener('DOMContentLoaded', setActiveTab);