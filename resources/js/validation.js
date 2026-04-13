// Validation JavaScript pour les formulaires MediTrackD

document.addEventListener('DOMContentLoaded', function() {
    
    // Validation du formulaire patient
    const patientForm = document.getElementById('patientForm');
    if (patientForm) {
        initPatientValidation();
    }
    
    // Validation du formulaire consultation
    const consultationForm = document.getElementById('consultationForm');
    if (consultationForm) {
        initConsultationValidation();
    }
    
    // Animation du heart beat sur la page welcome
    const heartIcon = document.querySelector('.heartBeat-active');
    if (heartIcon) {
        initHeartBeatAnimation();
    }
});

function initPatientValidation() {
    const form = document.getElementById('patientForm');
    const phoneInput = document.getElementById('telephone');
    
    // Validation en temps réel du téléphone
    phoneInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        
        // Formatage automatique
        if (value.length >= 2 && value.length <= 4) {
            value = value.slice(0, 2) + ' ' + value.slice(2);
        } else if (value.length >= 5 && value.length <= 6) {
            value = value.slice(0, 2) + ' ' + value.slice(2, 4) + ' ' + value.slice(4);
        } else if (value.length >= 7 && value.length <= 8) {
            value = value.slice(0, 2) + ' ' + value.slice(2, 4) + ' ' + value.slice(4, 6) + ' ' + value.slice(6);
        }
        
        e.target.value = value;
        
        // Validation en temps réel
        validateField(phoneInput, value.replace(/\s/g, '').length === 8 && /^[67]/.test(value.replace(/\s/g, '')), 
                    'Le numéro doit commencer par 6 ou 7 et contenir 8 chiffres');
    });
    
    // Validation des noms et prénoms
    const nameInputs = ['input[name="nom"]', 'input[name="prenom"]'];
    nameInputs.forEach(selector => {
        const input = document.querySelector(selector);
        if (input) {
            input.addEventListener('input', function(e) {
                const value = e.target.value.trim();
                const isValid = /^[a-zA-Z\s\-\']+$/.test(value) && value.length >= 2;
                validateField(input, isValid, 'Ne doit contenir que des lettres, espaces, tirets et apostrophes (min. 2 caractères)');
            });
        }
    });
    
    // Validation de la date de naissance
    const dateInput = document.getElementById('date_naissance');
    if (dateInput) {
        dateInput.addEventListener('change', function(e) {
            const selectedDate = new Date(e.target.value);
            const today = new Date();
            const minDate = new Date('1900-01-01');
            
            const isValid = selectedDate < today && selectedDate > minDate;
            validateField(dateInput, isValid, 'La date doit être antérieure à aujourd\'hui et postérieure au 01/01/1900');
        });
    }
    
    // Validation du sexe
    const sexeSelect = document.querySelector('select[name="sexe"]');
    if (sexeSelect) {
        sexeSelect.addEventListener('change', function(e) {
            const isValid = e.target.value !== '';
            validateField(sexeSelect, isValid, 'Veuillez choisir un sexe');
        });
    }
    
    // Validation des longueurs de texte
    const textareas = ['textarea[name="antecedents"]', 'textarea[name="allergies"]'];
    const limits = {'textarea[name="antecedents"]': 1000, 'textarea[name="allergies"]': 500};
    
    textareas.forEach(selector => {
        const textarea = document.querySelector(selector);
        if (textarea) {
            textarea.addEventListener('input', function(e) {
                const value = e.target.value;
                const limit = limits[selector];
                const isValid = value.length <= limit;
                validateField(textarea, isValid, `Maximum ${limit} caractères autorisés (${value.length}/${limit})`);
            });
        }
    });
    
    // Validation avant soumission
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Valider tous les champs requis
        const requiredFields = form.querySelectorAll('[required]');
        requiredFields.forEach(field => {
            if (field.value.trim() === '') {
                field.classList.add('is-invalid');
                
                // Ajouter message d'erreur si nécessaire
                if (!field.parentNode.querySelector('.invalid-feedback')) {
                    const feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback';
                    feedback.textContent = 'Ce champ est obligatoire';
                    field.parentNode.appendChild(feedback);
                }
                
                isValid = false;
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            
            // Afficher une alerte générale
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-danger alert-dismissible fade show mt-3';
            alertDiv.innerHTML = `
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Veuillez corriger les erreurs dans le formulaire</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            // Insérer l'alerte après le formulaire
            form.parentNode.insertBefore(alertDiv, form.nextSibling);
            
            // Faire défiler vers le premier champ invalide
            const firstInvalid = form.querySelector('.is-invalid');
            if (firstInvalid) {
                firstInvalid.focus();
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });
}

function initConsultationValidation() {
    const form = document.getElementById('consultationForm');
    
    // Validation en temps réel pour les textareas
    const textareas = [
        { selector: 'textarea[name="symptomes"]', min: 0, max: 1000 },
        { selector: 'textarea[name="diagnostic"]', min: 10, max: 2000 },
        { selector: 'textarea[name="traitement"]', min: 10, max: 2000 }
    ];
    
    textareas.forEach(({ selector, min, max }) => {
        const textarea = document.querySelector(selector);
        if (textarea) {
            const counter = textarea.parentElement.querySelector('.character-count');
            
            textarea.addEventListener('input', function(e) {
                const value = e.target.value;
                const length = value.length;
                
                // Mettre à jour le compteur
                if (counter) {
                    counter.textContent = `${length}/${max}`;
                    counter.className = length > max ? 'text-danger' : 'text-muted';
                }
                
                // Validation
                const isValid = length >= min && length <= max;
                const message = length < min 
                    ? `Minimum ${min} caractères requis (${length}/${max})`
                    : length > max 
                    ? `Maximum ${max} caractères autorisés (${length}/${max})`
                    : '';
                
                validateField(textarea, isValid, message);
            });
        }
    });
    
    // Validation du poids
    const poidsInput = document.querySelector('input[name="poids"]');
    if (poidsInput) {
        poidsInput.addEventListener('input', function(e) {
            const value = parseFloat(e.target.value);
            const isValid = !isNaN(value) && value >= 0.5 && value <= 300;
            validateField(poidsInput, isValid, 'Le poids doit être entre 0.5 et 300 kg');
        });
    }
    
    // Validation de la tension
    const tensionInput = document.querySelector('input[name="tension"]');
    if (tensionInput) {
        tensionInput.addEventListener('input', function(e) {
            const value = e.target.value.trim();
            const isValid = /^[0-9]{1,3}\/[0-9]{1,3}$/.test(value);
            validateField(tensionInput, isValid, 'Format tension invalide (ex: 12/8)');
        });
    }
    
    // Validation de la date
    const dateInput = document.querySelector('input[name="date_consultation"]');
    if (dateInput) {
        dateInput.addEventListener('change', function(e) {
            const selectedDate = new Date(e.target.value);
            const today = new Date();
            
            const isValid = selectedDate <= today;
            validateField(dateInput, isValid, 'La date ne peut pas être dans le futur');
        });
    }
    
    // Validation avant soumission
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Vérifier les champs requis
        const requiredFields = form.querySelectorAll('[required]');
        requiredFields.forEach(field => {
            if (field.value.trim() === '') {
                field.classList.add('is-invalid');
                
                if (!field.parentNode.querySelector('.invalid-feedback')) {
                    const feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback';
                    feedback.textContent = 'Ce champ est obligatoire';
                    field.parentNode.appendChild(feedback);
                }
                
                isValid = false;
            }
        });
        
        // Vérifier les longueurs minimales
        textareas.forEach(({ selector, min }) => {
            const textarea = document.querySelector(selector);
            if (textarea && textarea.hasAttribute('required') && textarea.value.length < min) {
                textarea.classList.add('is-invalid');
                isValid = false;
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            
            // Afficher l'alerte
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-danger alert-dismissible fade show mt-3';
            alertDiv.innerHTML = `
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Veuillez corriger les erreurs dans le formulaire</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            form.parentNode.insertBefore(alertDiv, form.nextSibling);
            
            // Faire défiler vers le premier champ invalide
            const firstInvalid = form.querySelector('.is-invalid');
            if (firstInvalid) {
                firstInvalid.focus();
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });
}

function initHeartBeatAnimation() {
    const heartIcon = document.querySelector('.heartBeat-active');
    if (heartIcon) {
        // Ajouter les styles pour l'animation
        heartIcon.style.fontSize = '15rem';
        heartIcon.style.color = '#dc3545';
        heartIcon.style.animation = 'heartBeat 1.5s ease-in-out infinite';
        
        // Créer les keyframes si elles n'existent pas
        if (!document.querySelector('#heartBeatStyles')) {
            const style = document.createElement('style');
            style.id = 'heartBeatStyles';
            style.textContent = `
                @keyframes heartBeat {
                    0% { transform: scale(1); }
                    14% { transform: scale(1.3); }
                    28% { transform: scale(1); }
                    42% { transform: scale(1.3); }
                    70% { transform: scale(1); }
                }
            `;
            document.head.appendChild(style);
        }
    }
}

// Fonction utilitaire de validation
function validateField(field, isValid, errorMessage) {
    // Supprimer les messages d'erreur précédents
    const existingFeedback = field.parentNode.querySelector('.invalid-feedback');
    if (existingFeedback) {
        existingFeedback.remove();
    }
    
    // Mettre à jour les classes de validation
    field.classList.remove('is-valid', 'is-invalid');
    
    if (field.value.trim() === '') {
        // Champ vide - pas de validation
        return;
    }
    
    if (isValid) {
        field.classList.add('is-valid');
    } else {
        field.classList.add('is-invalid');
        
        // Ajouter le message d'erreur
        const feedback = document.createElement('div');
        feedback.className = 'invalid-feedback';
        feedback.textContent = errorMessage;
        field.parentNode.appendChild(feedback);
    }
}
