<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
:root {
    --primary-color: #2563eb;
    --secondary-color: #1e40af;
    --background-color: #f3f4f6;
    --card-background: #ffffff;
    --text-primary: #1f2937;
    --text-secondary: #6b7280;
    --border-color: #e5e7eb;
    --success-color: #059669;
    --danger-color: #dc2626;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

body {
    background-color: var(--background-color);
    color: var(--text-primary);
    line-height: 1.5;
    font-size: 16px;
}

tr {
    transition: opacity 0.3s ease-in-out;
}

.container {
    max-width: 95%;
    margin: 0 auto;
    padding: 2rem;
}

.header {
    background-color: var(--card-background);
    padding: 1.5rem 2rem;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
}

.header h1 {
    font-size: 2rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.controls {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    align-items: center;
    margin-bottom: 1.5rem;
}

.search-box {
    flex: 1;
    min-width: 250px;
    position: relative;
    margin-right: 0;
}

.search-box input {
    width: 100%;
    padding: 0.875rem 1rem 0.875rem 2.5rem;
    border: 1px solid var(--border-color);
    border-radius: 0.375rem;
    font-size: 15px;
    transition: border-color 0.2s;
}

.search-box i {
    position: absolute;
    left: 0.875rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-secondary);
}

.button {
    padding: 0.875rem 1.5rem;
    border: none;
    border-radius: 0.375rem;
    font-weight: 500;
    font-size: 15px;
    cursor: pointer;
    transition: all 0.2s ease-in-out;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.button:hover, .action-button:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.button-primary {
    background-color: var(--primary-color);
    color: white;
}

.button-primary:hover {
    background-color: var(--secondary-color);
}

.button-secondary {
    background-color: white;
    border: 1px solid var(--border-color);
    color: var(--text-primary);
}

.button-secondary:hover {
    background-color: var(--background-color);
}

.notes-button {
    background: var(--card-background);
    border: 1px solid var(--border-color);
    border-radius: 0.5rem;
    padding: 0.75rem 1rem;
    min-width: 44px;
    min-height: 44px;
    cursor: pointer;
    color: var(--primary-color);
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

.notes-button:hover {
    background: var(--primary-color);
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.table-container {
    width: 100%;
    overflow-x: auto;
    margin: 2rem 0;
    background-color: var(--card-background);
    border-radius: 0.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-collapse: separate;
    border-spacing: 0;
    table-layout: fixed; /* Add this to ensure consistent column widths */
}

table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    min-width: 1200px;
}

th {
    position: sticky;
    top: 0;
    background-color: #f8fafc;
    z-index: 10;
    font-size: 15px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--text-secondary);
    border-bottom: 1px solid var(--border-color);
    padding: 1.25rem 1rem;
    text-align: left;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

td {
    padding: 1.25rem 1rem;
    font-size: 15px;
    border-bottom: 1px solid var(--border-color);
    background-color: white;
    vertical-align: middle;
}

/* Column specific widths */
th:nth-child(1), td:nth-child(1) { width: 10%; }  /* Type */
th:nth-child(2), td:nth-child(2) { width: 15%; }  /* Model Name */
th:nth-child(3), td:nth-child(3) { width: 7%; }   /* Quantity */
th:nth-child(4), td:nth-child(4) { width: 7%; }   /* Description */
th:nth-child(5), td:nth-child(5) { width: 8%; }   /* REQ/PO Number */
th:nth-child(6), td:nth-child(6) { width: 10%; }  /* Order Date */
th:nth-child(7), td:nth-child(7) { width: 10%; }  /* Order Status */
th:nth-child(8), td:nth-child(8) { width: 8%; }   /* Tags */
th:nth-child(9), td:nth-child(9) { width: 7%; }   /* Notes */
th:nth-child(10), td:nth-child(10) { width: 18%; } /* Actions */

tr:hover td {
    background-color: #f8fafc;
    transform: none;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.sort-btn {
    background: none;
    border: none;
    padding: 0.25rem;
    cursor: pointer;
    color: var(--text-secondary);
    margin-left: 0.5rem;
}

.sort-btn:hover {
    color: var(--primary-color);
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
    min-width: 170px;
    justify-content: flex-end;
    transition: all 0.2s ease-in-out;
}

.action-button {
    padding: 0.5rem 0.75rem;
    border: none;
    border-radius: 0.375rem;
    font-size: 14px;
    cursor: pointer;
    min-width: 80px;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    justify-content: center;
}

.action-button.edit {
    background-color: var(--primary-color);
    color: white;
}

.action-button.delete {
    background-color: var(--danger-color);
    color: white;
}

.action-button.save {
    background-color: var(--success-color);
    color: white;
}

.action-button.cancel {
    background-color: var(--text-secondary);
    color: white;
}

.modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    transition: all 0.2s ease-in-out;
}

.modal.active {
    display: flex;
}

.modal-content {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: var(--card-background);
    border-radius: 0.5rem;
    padding: 2rem;
    width: 90%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.modal-header h2 {
    font-size: 1.75rem;
    font-weight: 600;
}

.close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: var(--text-secondary);
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.625rem;
    font-weight: 500;
    color: var(--text-primary);
    font-size: 15px;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 0.875rem;
    border: 1px solid var(--border-color);
    border-radius: 0.375rem;
    font-size: 15px;
    transition: border-color 0.2s;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--primary-color);
}

.loading {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.8);
    z-index: 9999;
    display: none;
    justify-content: center;
    align-items: center;
}

.loading-content {
    background-color: white;
    padding: 2rem;
    border-radius: 0.5rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    gap: 1rem;
}

.loading-spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.description-button {
   background: var(--card-background);
   border: 1px solid var(--border-color);
   border-radius: 0.5rem;
   padding: 0.75rem 1rem;
   min-width: 44px;
   min-height: 44px;
   cursor: pointer;
   color: var(--primary-color);
   transition: all 0.2s ease;
   display: flex;
   align-items: center;
   justify-content: center;
   box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

.description-button i {
   font-size: 1.25rem;
}

.description-button:hover {
   background: var(--primary-color);
   color: white;
   transform: translateY(-1px);
   box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.description-cell {
    text-align: center;
}

.tag {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    margin: 0.125rem;
    background-color: var(--primary-color);
    color: white;
    border-radius: 0.375rem;
    font-size: 0.75rem;
    transition: all 0.2s ease-in-out;
    transform-origin: left;
}

.tag:hover {
    transform: scale(1.05);
}

.tag-filter {
    margin-left: 0;
}

select#tagFilter {
    padding: 0.75rem 1.5rem;
    border: 1px solid var(--border-color);
    border-radius: 0.375rem;
    font-size: 15px;
    background-color: white;
    color: var(--text-primary);
    cursor: pointer;
    font-weight: 500;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 1em;
    min-width: 120px;
}

select#tagFilter:hover {
    background-color: var(--background-color);
}

@keyframes modalFade {
    from { opacity: 0; transform: translate(-50%, -48%); }
    to { opacity: 1; transform: translate(-50%, -50%); }
}

.modal.active .modal-content {
    animation: modalFade 0.3s ease-out forwards;
}

/* Responsive adjustments */
@media (max-width: 1600px) {
    .container {
        max-width: 98%;
    }
}

@media (max-width: 1200px) {
    .container {
        padding: 1rem;
    }
    
    .table-container {
        margin: 0 -1rem;
        width: calc(100% + 2rem);
        overflow-x: auto;
        overflow-y: hidden; /* Prevent vertical scroll in table */
        margin-right: 0; /* Prevent right margin shift */
    }
    
    th, td {
        padding: 1rem 0.75rem;
    }
}

@media (max-width: 768px) {
    .container {
        padding: 1rem;
    }

    .controls {
        flex-direction: column;
    }

    .search-box {
        width: 100%;
    }
}

/* Loading spinner animation */
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.loading-spinner {
    animation: spin 0.8s linear infinite;
}

/* Success feedback animation */
@keyframes successPulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.action-success {
    animation: successPulse 0.4s ease-in-out;
}

html {
    overflow-y: scroll; /* Always show vertical scrollbar */
    scrollbar-gutter: stable; /* Reserves space for scrollbar */
}

.status-filter {
    margin-left: 1rem;
}

select#statusFilter {
    padding: 0.75rem 1.5rem;
    border: 1px solid var(--border-color);
    border-radius: 0.375rem;
    font-size: 15px;
    background-color: white;
    color: var(--text-primary);
    cursor: pointer;
    font-weight: 500;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 1em;
    min-width: 120px;
}

select#statusFilter:hover {
    background-color: var(--background-color);
}
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Ordered Items Management System</h1>
            <div class="controls">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="filterInput" placeholder="Search by Type, Model, Tags, or REQ Number...">
                </div>
                <button class="button button-primary" onclick="filterTable()">
                    <i class="fas fa-search"></i>
                    Search
                </button>
                <div class="tag-filter">
                    <select id="tagFilter" onchange="filterByTag(this.value)">
                        <option value="">All Tags</option>
                    </select>
                </div>
                <button class="button button-secondary" onclick="showAll()">
                    <i class="fas fa-list"></i>
                    Show All
                </button>
                <button class="button button-primary" onclick="showModal()">
                    <i class="fas fa-plus"></i>
                    Add New Record
                </button>
            </div>
        </div>

        <div id="loading" class="loading">
        <div class="loading-content">
            <i class="fas fa-spinner loading-spinner"></i>
            <span>Loading...</span>
        </div>
    </div>

        <div class="table-container">
            <table id="itemOrdersTable">
            <thead>
        <tr>
            <th>
                Type
                <button class="sort-btn" onclick="toggleTypeSort()">
                    <i id="typeSortIcon" class="fas fa-sort"></i>
                </button>
            </th>
            <th>
                Model Name
                <button class="sort-btn" onclick="toggleModelSort()">
                    <i id="modelSortIcon" class="fas fa-sort"></i>
                </button>
            </th>
            <th>Quantity</th>
            <th>Description</th>
            <th>REQ/PO Number</th>
            <th>
                Order Date
                <button class="sort-btn" onclick="toggleDateSort()">
                    <i id="dateSortIcon" class="fas fa-sort"></i>
                </button>
            </th>
            <th>
                Order Status
                <button class="sort-btn" onclick="toggleStatusSort()">
                    <i id="statusSortIcon" class="fas fa-sort"></i>
                </button>
            </th>
            <th>
                Tags
                <button class="sort-btn" onclick="toggleTagSort()">
                    <i id="tagSortIcon" class="fas fa-sort"></i>
                </button>
            </th>
            <th>Notes</th>
            <th>Actions</th>
        </tr>
    </thead>
                <tbody>
                    <?php include 'connection.php'; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add New Record Modal -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add New Record</h2>
                <button class="close" onclick="closeModal()">&times;</button>
            </div>
            <form action="add_record.php" method="POST">
                <div class="form-group">
                    <label for="type">Type:</label>
                    <select name="type" id="type" required>
                        <option value="Cable">Cable</option>
                        <option value="Monitor">Monitor</option>
                        <option value="Network Switch">Network Switch</option>
                        <option value="PC Parts">PC Parts</option>
                        <option value="Other">Other</option>
                        <option value="Video Switcher">Video Switcher</option>
                        <option value="Video Distribution Amplifier">Video Distribution Amplifier</option>
                        <option value="Video Converter">Video Converter</option>
                        <option value="Audio Converter">Audio Converter</option>
                        <option value="CPU">CPU</option>
                        <option value="Drive">Drive</option>
                        <option value="GPU">GPU</option>
                        <option value="Memory">Memory</option>
                        <option value="Motherboard">Motherboard</option>
                        <option value="Power Supply">Power Supply</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="model_name">Model Name:</label>
                    <input type="text" name="model_name" id="model_name" required>
                </div>

                <div class="form-group">
                    <label for="quantity">Quantity:</label>
                    <input type="number" name="quantity" id="quantity" required>
                </div>

                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea name="description" id="description" rows="3" required></textarea>
                </div>

                <div class="form-group">
                    <label for="req_po_number">REQ/PO Number:</label>
                    <input type="text" name="req_po_number" id="req_po_number" required>
                </div>

                <div class="form-group">
                    <label for="order_date">Order Date:</label>
                    <input type="date" name="order_date" id="order_date" required>
                </div>

                <div class="form-group">
                    <label for="order_status">Order Status:</label>
                    <select name="order_status" id="order_status" required>
                        <option value="Ordered">Ordered</option>
                        <option value="Received">Received</option>
                        <option value="Closed">Closed</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="tags">Tags:</label>
                    <input type="text" name="tags" id="tags" placeholder="Enter tags (comma separated, e.g.: SQA 1, Automation 1)">
                </div>

                <div class="form-group">
                    <label for="notes">Notes:</label>
                    <textarea name="notes" id="notes" rows="3" required></textarea>
                </div>

                <button type="submit" class="button button-primary">
                    <i class="fas fa-save"></i>
                    Add Record
                </button>
            </form>
        </div>
    </div>

    <!-- Description Modal -->
    <div id="descriptionModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Description</h2>
                <button class="close" onclick="closeDescriptionModal()">&times;</button>
            </div>
            <div class="form-group">
                <textarea id="descriptionText" rows="10" placeholder="Enter description here..."></textarea>
            </div>
            <button onclick="saveDescription()" class="button button-primary">
                <i class="fas fa-save"></i>
                Save Description
            </button>
        </div>
    </div>

    <div id="notesModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Edit Notes</h2>
            <button class="close" onclick="closeNotesModal()">&times;</button>
        </div>
        <div class="form-group">
            <textarea id="notesText" rows="10" placeholder="Enter notes here..."></textarea>
        </div>
        <button onclick="saveNotes()" class="button button-primary">
            <i class="fas fa-save"></i>
            Save Notes
        </button>
    </div>
</div>

    <script>
        
        
        function openDescriptionModal(id, description) {
            currentRowId = id;
            // Decode any escaped characters
            const decodedDescription = description
                .replace(/\\n/g, '\n')
                .replace(/\\"/g, '"')
                .replace(/\\\\/g, '\\');
            document.getElementById('descriptionText').value = decodedDescription;
            document.getElementById('descriptionModal').style.display = "block";
        }

        function closeDescriptionModal() {
            document.getElementById('descriptionModal').style.display = "none";
            currentRowId = null;
        }

        function openNotesModal(id, notes) {
    currentRowId = id;
    document.getElementById('notesText').value = notes;
    document.getElementById('notesModal').style.display = "flex";
    setTimeout(() => document.getElementById('notesModal').classList.add('active'), 10);
}

function closeNotesModal() {
    const modal = document.getElementById('notesModal');
    modal.classList.remove('active');
    modal.style.display = "none";
    currentRowId = null;
}

function saveNotes() {
    const updatedNotes = document.getElementById('notesText').value;
    
    if (!currentRowId) return;
    
    fetch('update_notes.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            id: currentRowId,
            notes: updatedNotes
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            const row = document.querySelector(`[data-id='${currentRowId}']`);
            if (row) {
                row.querySelector('td:nth-child(9)').innerHTML = `
                    <button onclick="openNotesModal('${currentRowId}', '${updatedNotes}')" class='notes-button'>
                        <i class='fas fa-sticky-note'></i>
                    </button>`;
            }
            closeNotesModal();
        } else if (data.error) {
            alert(data.error);
        }
    })
    .catch(error => {
        console.error(error);
        alert("Failed to update notes");
    });
}

        function saveDescription() {
    const updatedDescription = document.getElementById('descriptionText').value;
    
    // Log the raw input
    console.log('Raw description:', updatedDescription);

    if (!currentRowId) {
        console.error("No row ID available for updating description.");
        return;
    }
    
    // Create the payload
    const payload = {
        id: currentRowId,
        description: updatedDescription
    };
    
    // Log the payload
    console.log('Sending payload:', payload);
    
    fetch('update_description.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload)
    })
    .then(response => {
        // Log the raw response
        response.clone().text().then(text => {
            console.log('Raw response:', text);
        });
        
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log('Parsed response:', data);
        
        if (data.message) {
            const row = document.querySelector(`[data-id='${currentRowId}']`);
            if (row) {
                row.querySelector('td:nth-child(4)').innerHTML = `
                    <button onclick="openDescriptionModal('${currentRowId}', '${updatedDescription}')" class='description-button'>
                        <i class='fas fa-file-alt'></i>
                    </button>`;
            }
            closeDescriptionModal();
        } else if (data.error) {
            throw new Error(data.error);
        }
    })
    .catch(error => {
        console.error("Error:", error);
        alert(`Failed to update description: ${error.message}`);
    });
}

function editRow(row) {
    hasChanges = false;
    row.classList.add('edit-mode');
    let cells = row.querySelectorAll("td");
    
    // Save original values and add edit fields
    for (let i = 0; i < cells.length - 1; i++) {
        cells[i].setAttribute('data-original', cells[i].innerText);
        
        if (i === 0) {
            cells[i].innerHTML = getDropdownHTML(cells[i].innerText);
            cells[i].querySelector('select').addEventListener('change', () => hasChanges = true);
        } else if (i === 6) {
            cells[i].innerHTML = getStatusDropdownHTML(cells[i].innerText);
            cells[i].querySelector('select').addEventListener('change', () => hasChanges = true);
        } else if (i === 7) { // Tags column
            const currentTags = cells[i].innerText;
            cells[i].innerHTML = `<input type="text" value="${currentTags}" placeholder="Enter tags (comma separated)">`;
            cells[i].querySelector('input').addEventListener('input', () => hasChanges = true);
        } else if (i !== 3) { // Skip description cell
            let currentValue = cells[i].innerText;
            cells[i].innerHTML = `<input type="text" value="${currentValue}">`;
            cells[i].querySelector('input').addEventListener('input', () => hasChanges = true);
        }
    }

    // Update action buttons
    const actionsCell = cells[cells.length - 1];
    actionsCell.innerHTML = `
        <div class="action-buttons">
            <button onclick="saveRow(this.closest('tr'))" class="action-button save">
                <i class="fas fa-save"></i> Save
            </button>
            <button onclick="cancelEdit(this.closest('tr'))" class="action-button cancel">
                <i class="fas fa-times"></i> Cancel
            </button>
        </div>
    `;
}


function getStatusDropdownHTML(currentValue) {
    return `
        <select>
            <option value="Ordered" ${currentValue === "Ordered" ? "selected" : ""}>Ordered</option>
            <option value="Received" ${currentValue === "Received" ? "selected" : ""}>Received</option>
            <option value="Closed" ${currentValue === "Closed" ? "selected" : ""}>Closed</option>
        </select>
    `;
}

function getDropdownHTML(currentValue) {
    return `
        <select>
            <option value="Cable" ${currentValue === "Cable" ? "selected" : ""}>Cable</option>
            <option value="Monitor" ${currentValue === "Monitor" ? "selected" : ""}>Monitor</option>
            <option value="Network Switch" ${currentValue === "Network Switch" ? "selected" : ""}>Network Switch</option>
            <option value="PC Parts" ${currentValue === "PC Parts" ? "selected" : ""}>PC Parts</option>
            <option value="Other" ${currentValue === "Other" ? "selected" : ""}>Other</option>
            <option value="Video Switcher" ${currentValue === "Video Switcher" ? "selected" : ""}>Video Switcher</option>
            <option value="Video Distribution Amplifier" ${currentValue === "Video Distribution Amplifier" ? "selected" : ""}>Video Distribution Amplifier</option>
            <option value="Video Converter" ${currentValue === "Video Converter" ? "selected" : ""}>Video Converter</option>
            <option value="Audio Converter" ${currentValue === "Audio Converter" ? "selected" : ""}>Audio Converter</option>
            <option value="CPU" ${currentValue === "CPU" ? "selected" : ""}>CPU</option>
            <option value="Drive" ${currentValue === "Drive" ? "selected" : ""}>Drive</option>
            <option value="GPU" ${currentValue === "GPU" ? "selected" : ""}>GPU</option>
            <option value="Memory" ${currentValue === "Memory" ? "selected" : ""}>Memory</option>
            <option value="Motherboard" ${currentValue === "Motherboard" ? "selected" : ""}>Motherboard</option>
            <option value="Power Supply" ${currentValue === "Power Supply" ? "selected" : ""}>Power Supply</option>
        </select>
    `;
}

function saveRow(row) {
    let id = row.getAttribute('data-id');
    if (!id) {
        console.error("ID not found in the row data-id attribute.");
        return;
    }

    let cells = row.querySelectorAll("td");
    let rowData = {
        id: id,
        type: cells[0].querySelector("select") ? cells[0].querySelector("select").value : cells[0].innerText,
        model_name: cells[1].querySelector("input") ? cells[1].querySelector("input").value : cells[1].innerText,
        quantity: cells[2].querySelector("input") ? cells[2].querySelector("input").value : cells[2].innerText,
        description: cells[3].querySelector(".fa-file-alt") ? 
            cells[3].getAttribute('data-description') || '' : 
            cells[3].innerText,
        req_po_number: cells[4].querySelector("input") ? cells[4].querySelector("input").value : cells[4].innerText,
        order_date: cells[5].querySelector("input") ? cells[5].querySelector("input").value : cells[5].innerText,
        order_status: cells[6].querySelector("select") ? cells[6].querySelector("select").value : cells[6].innerText,
        tags: cells[7].querySelector("input") ? cells[7].querySelector("input").value : cells[7].innerText,
        notes: cells[8].querySelector(".fa-sticky-note") ? 
            cells[8].getAttribute('data-notes') || '' : 
            cells[8].innerText,
        from_closed: window.location.href.includes('closed_records.php')
    };

    // Debug log
    console.log('Sending data:', rowData);

    // Show loading indicator
    document.getElementById('loading').style.display = "flex";

    fetch('update_table.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(rowData)
    })
    .then(response => {
        // Debug log
        console.log('Response status:', response.status);
        return response.text().then(text => {
            console.log('Raw response:', text);
            try {
                return JSON.parse(text);
            } catch (e) {
                throw new Error(`Invalid JSON response: ${text}`);
            }
        });
    })
    .then(data => {
        document.getElementById('loading').style.display = "none";
        
        if (data.error) {
            throw new Error(data.error);
        }

        if (data.message) {
            if (data.message.includes("moved to closed items") || 
                data.message.includes("restored to active items")) {
                row.style.opacity = '0';
                setTimeout(() => {
                    row.remove();
                    alert(data.message);
                }, 300);
            } else {
                restoreRowToViewMode(row, rowData);
                // Show success message
                alert(data.message);
            }
        }
    })
    .catch(error => {
        document.getElementById('loading').style.display = "none";
        console.error("Error:", error);
        alert("Error saving changes: " + error.message);
    });
}

function cancelEdit(row) {
   let cells = row.querySelectorAll("td");
   
   for (let i = 0; i < cells.length - 1; i++) {
       const originalValue = cells[i].getAttribute('data-original');
       if (i === 3) {
           cells[i].innerHTML = `
               <button onclick="openDescriptionModal('${row.getAttribute('data-id')}', '${originalValue}')" class='description-button'>
                   <i class='fas fa-file-alt'></i>
               </button>`;
       } else if (i === 8) {
           cells[i].innerHTML = `
               <button onclick="openNotesModal('${row.getAttribute('data-id')}', '${originalValue}')" class='notes-button'>
                   <i class='fas fa-sticky-note'></i>
               </button>`;
       } else {
           cells[i].innerHTML = originalValue;
       }
   }

   const actionsCell = cells[cells.length - 1];
   actionsCell.innerHTML = `
       <div class='action-buttons'>
           <button onclick="editRow(this.closest('tr'))" class="action-button edit">
               <i class="fas fa-edit"></i> Edit
           </button>
           <button onclick="deleteRow(this.closest('tr'))" class="action-button delete">
               <i class="fas fa-trash"></i> Delete
           </button>
       </div>
   `;
}

function restoreRowToViewMode(row, data) {
    let cells = row.querySelectorAll("td");
    
    cells[0].innerHTML = data.type;
    cells[1].innerHTML = data.model_name;
    cells[2].innerHTML = data.quantity;
    cells[3].innerHTML = `
        <button onclick="openDescriptionModal('${data.id}', '${data.description}')" class='description-button'>
            <i class='fas fa-file-alt'></i>
        </button>`;
    cells[4].innerHTML = data.req_po_number;
    cells[5].innerHTML = data.order_date;
    cells[6].innerHTML = data.order_status;
    cells[7].innerHTML = formatTagsHTML(data.tags);
    cells[8].innerHTML = `
        <button onclick="openNotesModal('${data.id}', '${data.notes}')" class='notes-button'>
            <i class='fas fa-sticky-note'></i>
        </button>`;

    const actionsCell = cells[cells.length - 1];
    actionsCell.innerHTML = `
        <div class='action-buttons'>
            <button onclick="editRow(this.closest('tr'))" class="action-button edit">
                <i class="fas fa-edit"></i> Edit
            </button>
            <button onclick="deleteRow(this.closest('tr'))" class="action-button delete">
                <i class="fas fa-trash"></i> Delete
            </button>
        </div>
    `;
}

        function updateButtonState(row, buttonText, buttonClass, onClickFunction) {
            let button = row.querySelector(".save-btn, .edit-btn");
            button.innerText = buttonText;
            button.className = buttonClass;
            button.setAttribute("onclick", onClickFunction);
        }

        function reusableFilterLogic(rows, filterValue) {
            for (let i = 1; i < rows.length; i++) {
                const cells = rows[i].getElementsByTagName("td");
                if (cells.length > 0) {
                    const reqCell = cells[4];
                    const reqText = reqCell.textContent.trim().toLowerCase();
                    if (filterValue === "" || reqText.includes(filterValue)) {
                        rows[i].style.display = "";
                    } else {
                        rows[i].style.display = "none";
                    }
                }
            }
        }

        function filterTable() {
            const input = document.getElementById('filterInput');
            const filter = input.value.toLowerCase();
            const table = document.getElementById('itemOrdersTable');
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) {
                const row = rows[i];
                const type = row.cells[0].textContent.toLowerCase();
                const modelName = row.cells[1].textContent.toLowerCase();
                const reqNumber = row.cells[4].textContent.toLowerCase();
                const tags = Array.from(row.cells[7].querySelectorAll('.tag'))
                    .map(tag => tag.textContent.toLowerCase())
                    .join(' ');

                if (type.includes(filter) || 
                    modelName.includes(filter) || 
                    reqNumber.includes(filter) || 
                    tags.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        }

        // Add event listener for real-time search
        document.getElementById('filterInput').addEventListener('keyup', filterTable);

        function showAll() {
            document.getElementById('filterInput').value = '';
            document.getElementById('tagFilter').value = '';
            
            const table = document.getElementById("itemOrdersTable");
            const rows = table.getElementsByTagName("tr");
            for (let i = 1; i < rows.length; i++) {
                rows[i].style.display = "";
            }
        }

        function showModal() {
            document.getElementById("addModal").style.display = "block";
        }

        function closeModal() {
            document.getElementById("addModal").style.display = "none";
        }

        window.onclick = function(event) {
            let modal = document.getElementById("addModal");
            if (event.target === modal) {
                modal.style.display = "none";
            }
        }

        function deleteRow(row) {
            let id = row.getAttribute('data-id');

            if (confirm("Are you sure you want to delete this record?")) {
                document.getElementById('loading').style.display = "flex";
                
                // Create FormData object
                const formData = new FormData();
                formData.append('id', id);

                fetch('delete_record.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    document.getElementById('loading').style.display = "none";
                    if (data.success) {
                        row.style.opacity = '0';
                        setTimeout(() => {
                            row.remove();
                        }, 300);
                    } else {
                        alert("Error: " + (data.error || "Failed to delete record"));
                    }
                })
                .catch(error => {
                    document.getElementById('loading').style.display = "none";
                    console.error("Error:", error);
                    alert("Failed to delete record. Please try again later.");
                });
            }
        }

        let currentDateSort = 'none'; // Possible values: 'none', 'asc', 'desc'

function toggleDateSort() {
    const icon = document.getElementById('dateSortIcon');
    
    if (currentDateSort === 'none') {
        currentDateSort = 'asc';
        icon.className = 'fas fa-sort-up';
        loadSortedData();
    } else if (currentDateSort === 'asc') {
        currentDateSort = 'desc';
        icon.className = 'fas fa-sort-down';
        loadSortedData();
    } else {
        currentDateSort = 'none';
        icon.className = 'fas fa-sort';
        restoreOriginalOrder();
    }
}

function loadSortedData() {
    document.getElementById('loading').style.display = "block";
    
    // Get current filter value if any
    const filterValue = document.getElementById('filterInput').value.trim();
    
    fetch(`get_sorted_data.php?sort=${currentDateSort}&filter=${filterValue}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                throw new Error(data.error);
            }
            
            const tbody = document.querySelector('#itemOrdersTable tbody');
            tbody.innerHTML = data.rows;
            document.getElementById('loading').style.display = "none";
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('loading').style.display = "none";
            alert('Error loading data. Please try again.');
        });
}

function updateTagFilter() {
    const tagSet = new Set();
    const rows = document.querySelectorAll('#itemOrdersTable tbody tr');
    rows.forEach(row => {
        const tagSpans = row.querySelectorAll('.tag');
        tagSpans.forEach(span => tagSet.add(span.textContent.trim()));
    });

    const tagFilter = document.getElementById('tagFilter');
    const currentValue = tagFilter.value;
    tagFilter.innerHTML = '<option value="">All Tags</option>';
    
    [...tagSet].sort().forEach(tag => {
        const option = document.createElement('option');
        option.value = tag;
        option.textContent = tag;
        if (tag === currentValue) option.selected = true;
        tagFilter.appendChild(option);
    });
}

function filterByTag(tag) {
    const rows = document.querySelectorAll('#itemOrdersTable tbody tr');
    
    rows.forEach(row => {
        const tags = Array.from(row.querySelectorAll('.tag')).map(t => t.textContent);
        
        if (!tag || tags.includes(tag)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Call this after table updates
document.addEventListener('DOMContentLoaded', updateTagFilter);

function formatTagsHTML(tags) {
    if (!tags) return '';
    return tags.split(',')
        .map(tag => tag.trim())
        .filter(tag => tag.length > 0)
        .map(tag => `<span class="tag">${tag}</span>`)
        .join('');
}

// Add success feedback
function addSuccessFeedback(element) {
    element.classList.add('action-success');
    setTimeout(() => element.classList.remove('action-success'), 400);
}

// Smooth modal opening
function showModal() {
    const modal = document.getElementById("addModal");
    modal.style.display = "flex";
    setTimeout(() => modal.classList.add('active'), 10);
}

function openNotesModal(id, notes) {
    document.getElementById('descriptionText').value = notes;
    document.getElementById('notesModal').style.display = 'flex';
    currentRowId = id;
}

let currentStatusSort = 'none'; // Possible values: 'none', 'asc', 'desc'

function toggleStatusSort() {
    const icon = document.getElementById('statusSortIcon');
    
    if (currentStatusSort === 'none') {
        currentStatusSort = 'asc';
        icon.className = 'fas fa-sort-up';
        sortTableByStatus();
    } else if (currentStatusSort === 'asc') {
        currentStatusSort = 'desc';
        icon.className = 'fas fa-sort-down';
        sortTableByStatus();
    } else {
        currentStatusSort = 'none';
        icon.className = 'fas fa-sort';
        restoreOriginalOrder();
    }
}

function sortTableByStatus() {
    const tbody = document.querySelector('#itemOrdersTable tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    rows.sort((a, b) => {
        const statusA = a.querySelector('td:nth-child(7)').textContent.trim();
        const statusB = b.querySelector('td:nth-child(7)').textContent.trim();
        
        if (currentStatusSort === 'asc') {
            return statusA.localeCompare(statusB);
        } else {
            return statusB.localeCompare(statusA);
        }
    });
    
    // Clear the table body
    tbody.innerHTML = '';
    
    // Add sorted rows back
    rows.forEach(row => tbody.appendChild(row.cloneNode(true)));
}

let currentTagSort = 'none'; // Possible values: 'none', 'asc', 'desc'

function toggleTagSort() {
    const icon = document.getElementById('tagSortIcon');
    
    if (currentTagSort === 'none') {
        currentTagSort = 'asc';
        icon.className = 'fas fa-sort-up';
        sortTableByTags();
    } else if (currentTagSort === 'asc') {
        currentTagSort = 'desc';
        icon.className = 'fas fa-sort-down';
        sortTableByTags();
    } else {
        currentTagSort = 'none';
        icon.className = 'fas fa-sort';
        restoreOriginalOrder();
    }
}

function sortTableByTags() {
    const tbody = document.querySelector('#itemOrdersTable tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    rows.sort((a, b) => {
        // Get all tags from the cells and join them into a single string for comparison
        const tagsA = Array.from(a.querySelector('td:nth-child(8)').querySelectorAll('.tag'))
            .map(tag => tag.textContent.trim())
            .sort()
            .join(', ') || ''; // Handle empty tags
        
        const tagsB = Array.from(b.querySelector('td:nth-child(8)').querySelectorAll('.tag'))
            .map(tag => tag.textContent.trim())
            .sort()
            .join(', ') || ''; // Handle empty tags
        
        if (currentTagSort === 'asc') {
            return tagsA.localeCompare(tagsB);
        } else {
            return tagsB.localeCompare(tagsA);
        }
    });
    
    // Clear the table body
    tbody.innerHTML = '';
    
    // Add sorted rows back
    rows.forEach(row => tbody.appendChild(row.cloneNode(true)));
}

function filterByStatus(status) {
    const rows = document.querySelectorAll('#itemOrdersTable tbody tr');
    rows.forEach(row => {
        const statusCell = row.querySelector('td:nth-child(7)'); // 7th column is Order Status
        if (!status || statusCell.textContent.trim() === status) {
            // Only show the row if it's not already hidden by tag filter
            if (!row.style.display || row.style.display === '') {
                const tagFilter = document.getElementById('tagFilter').value;
                if (!tagFilter || Array.from(row.querySelectorAll('.tag')).some(t => t.textContent === tagFilter)) {
                    row.style.display = '';
                }
            }
        } else {
            row.style.display = 'none';
        }
    });
}

// Store original order when the page loads
let originalOrder = [];

// Store the original order when the page loads
document.addEventListener('DOMContentLoaded', () => {
    const tbody = document.querySelector('#itemOrdersTable tbody');
    originalOrder = Array.from(tbody.querySelectorAll('tr')).map(row => row.cloneNode(true));
});

// Function to restore the original order
function restoreOriginalOrder() {
    const tbody = document.querySelector('#itemOrdersTable tbody');
    tbody.innerHTML = '';
    originalOrder.forEach(row => {
        tbody.appendChild(row.cloneNode(true));
    });
}

// Update all toggle functions to properly handle the third click
function toggleTypeSort() {
    const icon = document.getElementById('typeSortIcon');
    
    if (currentTypeSort === 'none') {
        currentTypeSort = 'asc';
        icon.className = 'fas fa-sort-up';
        sortTableByType();
    } else if (currentTypeSort === 'asc') {
        currentTypeSort = 'desc';
        icon.className = 'fas fa-sort-down';
        sortTableByType();
    } else {
        currentTypeSort = 'none';
        icon.className = 'fas fa-sort';
        restoreOriginalOrder();
    }
}

function toggleModelSort() {
    const icon = document.getElementById('modelSortIcon');
    
    if (currentModelSort === 'none') {
        currentModelSort = 'asc';
        icon.className = 'fas fa-sort-up';
        sortTableByModel();
    } else if (currentModelSort === 'asc') {
        currentModelSort = 'desc';
        icon.className = 'fas fa-sort-down';
        sortTableByModel();
    } else {
        currentModelSort = 'none';
        icon.className = 'fas fa-sort';
        restoreOriginalOrder();
    }
}

function toggleDateSort() {
    const icon = document.getElementById('dateSortIcon');
    
    if (currentDateSort === 'none') {
        currentDateSort = 'asc';
        icon.className = 'fas fa-sort-up';
        loadSortedData();
    } else if (currentDateSort === 'asc') {
        currentDateSort = 'desc';
        icon.className = 'fas fa-sort-down';
        loadSortedData();
    } else {
        currentDateSort = 'none';
        icon.className = 'fas fa-sort';
        restoreOriginalOrder();
    }
}

// Update the sort functions to maintain event listeners
function sortTableByType() {
    const tbody = document.querySelector('#itemOrdersTable tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    rows.sort((a, b) => {
        const typeA = a.querySelector('td:nth-child(1)').textContent.trim();
        const typeB = b.querySelector('td:nth-child(1)').textContent.trim();
        
        if (currentTypeSort === 'asc') {
            return typeA.localeCompare(typeB);
        } else {
            return typeB.localeCompare(typeA);
        }
    });
    
    tbody.innerHTML = '';
    rows.forEach(row => tbody.appendChild(row.cloneNode(true)));
}

// Sort function for Model Name column
function sortTableByModel() {
    const tbody = document.querySelector('#itemOrdersTable tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    rows.sort((a, b) => {
        const modelA = a.querySelector('td:nth-child(2)').textContent.trim();
        const modelB = b.querySelector('td:nth-child(2)').textContent.trim();
        
        if (currentModelSort === 'asc') {
            return modelA.localeCompare(modelB);
        } else {
            return modelB.localeCompare(modelA);
        }
    });
    
    // Clear the table body
    tbody.innerHTML = '';
    
    // Add sorted rows back
    rows.forEach(row => tbody.appendChild(row.cloneNode(true)));
}

// Make sure these variables are declared at the top of your script
let currentTypeSort = 'none';
let currentModelSort = 'none';
    </script>



</body>
</html>