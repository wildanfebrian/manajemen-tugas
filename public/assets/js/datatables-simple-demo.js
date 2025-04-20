// DataTables initialization
document.addEventListener('DOMContentLoaded', function () {
    const dataTable = document.getElementById('datatablesSimple');
    if (dataTable) {
        new simpleDatatables.DataTable(dataTable);
    }
}); 