document.addEventListener('DOMContentLoaded', function() {
    // Initialisation
    loadDocumentTypes();
    loadDocuments();
    setupEventListeners();
});

// Chargement des types de documents
function loadDocumentTypes() {
    fetch('../assets/php/get_document_types.php')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.types) {
                const typeSelect = document.getElementById('typeSelect');
                const documentType = document.getElementById('documentType');
                
                // Clear existing options
                typeSelect.innerHTML = '<option value="">Tous</option>';
                documentType.innerHTML = '';
                
                data.types.forEach(type => {
                    const option = new Option(type.name, type.id);
                    typeSelect.add(option.cloneNode(true));
                    documentType.add(option);
                });
            } else {
                showNotification(data.message || 'Erreur lors du chargement des types', 'error');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showNotification('Erreur lors du chargement des types', 'error');
        });
}

// Chargement des documents
function loadDocuments(filters = {}) {
    const queryParams = new URLSearchParams(filters);
    fetch(`../assets/php/get_documents.php?${queryParams}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && Array.isArray(data.documents)) {
                const tbody = document.querySelector('#documentsTable tbody');
                tbody.innerHTML = '';
                
                if (data.documents.length === 0) {
                    const tr = document.createElement('tr');
                    tr.innerHTML = '<td colspan="4" class="text-center">Aucun document trouvé</td>';
                    tbody.appendChild(tr);
                    return;
                }
                
                data.documents.forEach(doc => {
                    const tr = document.createElement('tr');
                    const fileName = doc.document_path.split('/').pop();
                    const typeFolder = doc.type_name ? doc.type_name.toLowerCase() : 'default';
                    // Use relative path from the current page
                    const pdfUrl = `../uploads/documents/${typeFolder}/${fileName}`;
                    console.log('PDF URL:', pdfUrl); // Debug log
                    tr.innerHTML = `
                        <td>${doc.type_name || 'Non spécifié'}</td>
                        <td>
                            <a href="#" onclick="viewPdf('${pdfUrl}'); return false;" class="document-link">
                                ${fileName || 'Sans titre'}
                            </a>
                        </td>
                        <td>${formatDate(doc.publication_date)}</td>
                        <td>
                            <button onclick="editDocument(${doc.id})" class="btn-edit">Modifier</button>
                            <button onclick="deleteDocument(${doc.id})" class="btn-delete">Supprimer</button>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
            } else {
                showNotification(data.message || 'Erreur lors du chargement des documents', 'error');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showNotification('Erreur lors du chargement des documents', 'error');
        });
}

// Configuration des écouteurs d'événements
function setupEventListeners() {
    // Filtres
    document.getElementById('typeSelect').addEventListener('change', applyFilters);
    document.getElementById('dateFrom').addEventListener('change', applyFilters);
    document.getElementById('dateTo').addEventListener('change', applyFilters);
    document.getElementById('searchInput').addEventListener('input', debounce(applyFilters, 300));
    
    // Formulaire de modification
    document.getElementById('editDocumentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = {};
        formData.forEach((value, key) => data[key] = value);
        
        fetch('../assets/php/update_document.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Document mis à jour avec succès', 'success');
                closeModal();
                loadDocuments(); // Recharger la liste
            } else {
                showNotification(data.message || 'Erreur lors de la mise à jour', 'error');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showNotification('Erreur lors de la mise à jour', 'error');
        });
    });
    
    // Fermeture du modal
    document.querySelector('.close').addEventListener('click', closeModal);
}

// Application des filtres
function applyFilters() {
    const filters = {
        type: document.getElementById('typeSelect').value,
        dateFrom: document.getElementById('dateFrom').value,
        dateTo: document.getElementById('dateTo').value,
        search: document.getElementById('searchInput').value
    };
    
    loadDocuments(filters);
}

// Fonction pour charger les champs spécifiques au type de document
function loadFields(typeId, documentId) {
    const fieldsContainer = document.getElementById('fieldsContainer');
    fieldsContainer.innerHTML = ''; // Clear existing fields
    
    fetch(`../assets/php/get_document_fields.php?type_id=${typeId}&document_id=${documentId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.fields) {
                data.fields.forEach(field => {
                    const div = document.createElement('div');
                    div.className = 'form-group';
                    
                    const label = document.createElement('label');
                    label.htmlFor = `field_${field.id}`;
                    label.textContent = field.name;
                    
                    let input;
                    if (field.type === 'textarea') {
                        input = document.createElement('textarea');
                        input.rows = 4;
                    } else {
                        input = document.createElement('input');
                        input.type = field.type || 'text';
                    }
                    
                    input.id = `field_${field.id}`;
                    input.name = `field_${field.id}`;
                    input.value = field.value || '';
                    input.className = 'form-control';
                    
                    div.appendChild(label);
                    div.appendChild(input);
                    fieldsContainer.appendChild(div);
                });
            } else {
                showNotification(data.message || 'Erreur lors du chargement des champs', 'error');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showNotification('Erreur lors du chargement des champs', 'error');
        });
}

// Fonction pour éditer un document
function editDocument(id) {
    // Récupérer les détails du document
    fetch(`../assets/php/get_document.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const doc = data.document;
                document.getElementById('documentId').value = doc.id;
                document.getElementById('documentTitle').value = doc.document_path;
                document.getElementById('publicationDate').value = doc.publication_date;
                
                // Charger les types de documents et mettre à jour le formulaire
                loadDocumentTypes();
                document.getElementById('documentType').value = doc.type_id;
                loadFields(doc.type_id, doc.id);
                
                // Afficher le modal
                document.getElementById('editModal').style.display = 'block';
            } else {
                showNotification(data.message || 'Erreur lors du chargement du document', 'error');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showNotification('Erreur lors du chargement du document', 'error');
        });
}

// Fonction pour supprimer un document
function deleteDocument(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce document ?')) {
        fetch('../assets/php/delete_document.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Document supprimé avec succès', 'success');
                loadDocuments(); // Recharger la liste
            } else {
                showNotification(data.message || 'Erreur lors de la suppression', 'error');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showNotification('Erreur lors de la suppression', 'error');
        });
    }
}

// Fonction pour fermer le modal
function closeModal() {
    document.getElementById('editModal').style.display = 'none';
}

// Fermer le modal en cliquant en dehors
window.addEventListener('click', function(event) {
    const modal = document.getElementById('editModal');
    if (event.target === modal) {
        closeModal();
    }
});

// Utilitaires
function formatDate(dateString) {
    if (!dateString) return 'Non spécifié';
    return new Date(dateString).toLocaleDateString('fr-FR');
}

function getStatusBadge(status) {
    const badges = {
        published: '<span class="badge badge-success">Publié</span>',
        draft: '<span class="badge badge-warning">Brouillon</span>',
        archived: '<span class="badge badge-secondary">Archivé</span>'
    };
    return badges[status] || status;
}

function showNotification(message, type) {
    // Implémentation de la notification (à adapter selon votre système de notification)
    alert(message);
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Fonction pour ouvrir le PDF dans une popup
function viewPdf(pdfUrl) {
    const modal = document.getElementById('pdfModal');
    const pdfFrame = document.getElementById('pdfFrame');
    
    // Use relative path
    const relativeUrl = pdfUrl;
    console.log('Opening PDF at:', relativeUrl); // Debug log
    
    // Set the PDF source
    pdfFrame.src = relativeUrl;
    
    // Show the modal
    modal.style.display = 'block';
    
    // Add a small delay to ensure the iframe is loaded
    setTimeout(() => {
        pdfFrame.focus();
    }, 100);
}

// Fonction pour fermer la popup PDF
function closePdfModal() {
    const modal = document.getElementById('pdfModal');
    const pdfFrame = document.getElementById('pdfFrame');
    pdfFrame.src = '';
    modal.style.display = 'none';
}

// Fermer la popup PDF en cliquant en dehors
window.addEventListener('click', function(event) {
    const modal = document.getElementById('pdfModal');
    if (event.target === modal) {
        closePdfModal();
    }
});


