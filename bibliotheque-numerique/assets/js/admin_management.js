document.addEventListener('DOMContentLoaded', function() {
    // Load admin list
    loadAdminList();
    
    // Handle form submission
    const addAdminForm = document.getElementById('addAdminForm');
    if (addAdminForm) {
        addAdminForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Créer un objet FormData à partir du formulaire
            const formData = new FormData(this);
            formData.append('action', 'add');
            
            // Envoyer les données
            fetch('../assets/php/ajouter_un_admin.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload(); // Recharger la page pour afficher le nouvel admin
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Une erreur est survenue lors de l\'ajout de l\'administrateur');
            });
        });
    }
});

// Function to load admin list
function loadAdminList() {
    fetch('../assets/php/get_admins.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const adminList = document.getElementById('adminList');
                if (adminList) {
                    adminList.innerHTML = '';
                    
                    data.data.forEach(admin => {
                        const row = document.createElement('tr');
                        
                        // Format role for display
                        let roleDisplay = admin.role;
                        if (roleDisplay === 'admin') roleDisplay = 'Administrateur';
                        else if (roleDisplay === 'bibliothecaire') roleDisplay = 'Bibliothécaire';
                        else if (roleDisplay === 'prof') roleDisplay = 'Professeur';
                        
                        row.innerHTML = `
                            <td>${admin.id}</td>
                            <td>${admin.name}</td>
                            <td>${roleDisplay}</td>
                            <td>
                                <button class="action-btn btn-edit" onclick="editAdmin(${admin.id})">Modifier</button>
                                <button class="action-btn btn-delete" onclick="deleteAdmin(${admin.id})">Supprimer</button>
                            </td>
                        `;
                        
                        adminList.appendChild(row);
                    });
                }
            } else {
                showMessage(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error loading admin list:', error);
            showMessage('An error occurred while loading the administrator list', 'error');
        });
}

// Function to edit admin
function editAdmin(id) {
    // Fetch admin data
    fetch(`../assets/php/get_admin.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const admin = data.admin;
                
                // Create and show modal
                const modal = document.createElement('div');
                modal.className = 'modal';
                modal.id = 'editModal';
                
                // Format role for display
                let roleDisplay = admin.role;
                if (roleDisplay === 'admin') roleDisplay = 'Administrateur';
                else if (roleDisplay === 'bibliothecaire') roleDisplay = 'Bibliothécaire';
                else if (roleDisplay === 'prof') roleDisplay = 'Professeur';
                
                modal.innerHTML = `
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <h2>Modifier l'administrateur</h2>
                        <form id="editAdminForm">
                            <input type="hidden" id="editAdminId" value="${admin.id}">
                            <div class="form-group">
                                <label for="editUsername">Nom d'utilisateur</label>
                                <input type="text" id="editUsername" name="username" value="${admin.name}" required>
                            </div>
                            <div class="form-group">
                                <label for="editPassword">Nouveau mot de passe (laisser vide pour ne pas changer)</label>
                                <input type="password" id="editPassword" name="password">
                            </div>
                            <div class="form-group">
                                <label for="editRole">Rôle</label>
                                <select id="editRole" name="role" required>
                                    <option value="admin" ${admin.role === 'admin' ? 'selected' : ''}>Administrateur</option>
                                    <option value="bibliothecaire" ${admin.role === 'bibliothecaire' ? 'selected' : ''}>Bibliothécaire</option>
                                    <option value="prof" ${admin.role === 'prof' ? 'selected' : ''}>Professeur</option>
                                </select>
                            </div>
                            <button type="submit">Enregistrer</button>
                        </form>
                    </div>
                `;
                
                document.body.appendChild(modal);
                
                // Close modal when clicking the X
                const closeBtn = modal.querySelector('.close');
                closeBtn.onclick = function() {
                    modal.remove();
                };
                
                // Close modal when clicking outside
                window.onclick = function(event) {
                    if (event.target === modal) {
                        modal.remove();
                    }
                };
                
                // Handle form submission
                const editForm = document.getElementById('editAdminForm');
                editForm.onsubmit = function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData();
                    formData.append('admin_id', document.getElementById('editAdminId').value);
                    formData.append('username', document.getElementById('editUsername').value);
                    formData.append('password', document.getElementById('editPassword').value);
                    formData.append('role', document.getElementById('editRole').value);
                    
                    fetch('../assets/php/update_admin.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showMessage(data.message, 'success');
                            modal.remove();
                            // Refresh the page after successful update
                            setTimeout(() => {
                                window.location.reload();
                            }, 800);
                        } else {
                            showMessage(data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showMessage('An error occurred while updating the administrator', 'error');
                    });
                };
            } else {
                showMessage(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('An error occurred while fetching administrator data', 'error');
        });
}

// Function to delete admin
function deleteAdmin(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet administrateur ?')) {
        // Show loading message
        showMessage('Suppression en cours...', 'info');
        
        // Create form data
        const formData = new FormData();
        formData.append('admin_id', id);
        
        // Disable all delete buttons to prevent multiple clicks
        const deleteButtons = document.querySelectorAll('.btn-delete');
        deleteButtons.forEach(button => {
            button.disabled = true;
        });
        
        fetch('../assets/php/delete_admin.php', {
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
            if (data.success) {
                showMessage(data.message, 'success');
                // Refresh the page after successful deletion
                setTimeout(() => {
                    window.location.reload();
                }, 600);
            } else {
                showMessage(data.message, 'error');
                // Re-enable delete buttons
                deleteButtons.forEach(button => {
                    button.disabled = false;
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('An error occurred while deleting the administrator', 'error');
            // Re-enable delete buttons
            deleteButtons.forEach(button => {
                button.disabled = false;
            });
        });
    }
}

// Function to show messages
function showMessage(message, type) {
    // Remove any existing messages first
    const existingMessages = document.querySelectorAll('.message');
    existingMessages.forEach(msg => msg.remove());
    
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${type}-message`;
    messageDiv.textContent = message;
    
    // Add the message at the top of the page
    const container = document.querySelector('.container');
    if (container) {
        container.insertBefore(messageDiv, container.firstChild);
    } else {
        document.body.insertBefore(messageDiv, document.body.firstChild);
    }
    
    // Remove message after 3 seconds
    setTimeout(() => {
        messageDiv.remove();
    }, 3000);
} 