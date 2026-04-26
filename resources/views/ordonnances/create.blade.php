@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow border-0">
        <div class="card-header bg-success text-white py-3">
            <h4 class="mb-0"><i class="bi bi-prescription2 me-2"></i>Nouvelle Ordonnance</h4>
        </div>
        <div class="card-body p-4">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5 class="text-muted small text-uppercase fw-bold">Patient</h5>
                    <p class="fs-5 mb-0">{{ $patient->nom }} {{ $patient->prenom }} ({{ $patient->age }} ans)</p>
                </div>
                <div class="col-md-6 text-end">
                    <h5 class="text-danger small text-uppercase fw-bold"><i class="bi bi-exclamation-triangle me-1"></i> Allergies connues</h5>
                    <p class="fs-5 mb-0 text-danger fw-bold" id="patient-allergies">{{ $patient->allergies ?: 'Aucune allergie signalée' }}</p>
                </div>
            </div>

            <div id="allergy-alert" class="alert alert-danger d-none animate__animated animate__shakeX" role="alert">
                <h5 class="alert-heading"><i class="bi bi-shield-exclamation me-2"></i>ALERTE MÉDICALE : Risque d'Allergie !</h5>
                <p class="mb-0">Le médicament que vous saisissez semble correspondre à une allergie signalée pour ce patient : <strong id="conflicting-drug"></strong></p>
            </div>

            <form action="{{ route('ordonnances.store') }}" method="POST" id="prescription-form">
                @csrf
                <input type="hidden" name="patient_id" value="{{ $patient->id }}">

                <div class="mb-4">
                    <label class="form-label fw-bold">Médicaments et Instructions de prescription</label>
                    <textarea name="contenu" id="prescription-content" class="form-control" rows="10" 
                              style="font-family: 'Courier New', Courier, monospace; font-size: 1.1rem; line-height: 1.6;"
                              placeholder="Ex: PARACÉTAMOL 500mg - 1 comprimé 3 fois par jour pendant 5 jours..."></textarea>
                    <div class="form-text mt-2 text-muted">
                        <i class="bi bi-info-circle me-1"></i> Saisissez un médicament par ligne pour une meilleure lisibilité.
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                    <a href="{{ route('patients.show', $patient->id) }}" class="btn btn-outline-secondary px-4">
                        <i class="bi bi-arrow-left me-2"></i>Annuler
                    </a>
                    <button type="submit" class="btn btn-primary px-5 py-2 fw-bold" id="submit-btn">
                        <i class="bi bi-check2-circle me-2"></i>Enregistrer l'Ordonnance
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('prescription-content');
    const allergiesText = document.getElementById('patient-allergies').innerText.toLowerCase();
    const antecedentsText = @json(strtolower($patient->antecedents ?? ''));
    const alertDiv = document.getElementById('allergy-alert');
    const conflictingDrugSpan = document.getElementById('conflicting-drug');
    const submitBtn = document.getElementById('submit-btn');
    const form = document.getElementById('prescription-form');

    // Récupérer le mapping des réactions croisées et contre-indications depuis le PHP
    const safetyMap = @json(\App\Services\MedicationSafetyService::getCrossReactionsMap());
    const crossReactions = safetyMap.crossReactions;
    const contraindications = safetyMap.contraindications;

    // Liste des allergies sous forme de mots
    const allergiesList = allergiesText.split(/[,\s]+/).filter(word => word.length > 3);

    function checkConflicts() {
        const content = textarea.value.toLowerCase();
        let foundConflict = false;
        let conflictMessage = '';

        if (content.length < 3) {
            hideAlert();
            return;
        }

        // 1. VÉRIFICATION DES ALLERGIES
        if (allergiesText !== 'aucune allergie signalée') {
            for (let allergy of allergiesList) {
                if (content.includes(allergy)) {
                    foundConflict = true;
                    conflictMessage = allergy.toUpperCase() + " (Direct)";
                    break;
                }
            }

            if (!foundConflict) {
                for (let famille in crossReactions) {
                    const patientHasFamilyAllergy = allergiesList.some(a => famille.includes(a) || a.includes(famille));
                    if (patientHasFamilyAllergy) {
                        for (let membre of crossReactions[famille]) {
                            if (content.includes(membre)) {
                                foundConflict = true;
                                conflictMessage = membre.toUpperCase() + " (Famille des " + famille.toUpperCase() + ")";
                                break;
                            }
                        }
                    }
                    if (foundConflict) break;

                    const patientHasMemberAllergy = crossReactions[famille].some(m => allergiesList.includes(m));
                    if (patientHasMemberAllergy) {
                        for (let membre of crossReactions[famille]) {
                            if (content.includes(membre)) {
                                foundConflict = true;
                                conflictMessage = membre.toUpperCase() + " (Même famille que l'allergie signalée)";
                                break;
                            }
                        }
                    }
                    if (foundConflict) break;
                }
            }
        }

        // 2. VÉRIFICATION DES CONTRE-INDICATIONS (Antécédents)
        if (!foundConflict && antecedentsText) {
            for (let pathologie in contraindications) {
                if (antecedentsText.includes(pathologie)) {
                    for (let interdit of contraindications[pathologie]) {
                        if (content.includes(interdit)) {
                            foundConflict = true;
                            conflictMessage = interdit.toUpperCase() + " (Contre-indiqué en cas de " + pathologie.toUpperCase() + ")";
                            break;
                        }
                    }
                }
                if (foundConflict) break;
            }
        }

        if (foundConflict) {
            showAlert(conflictMessage);
        } else {
            hideAlert();
        }
    }

    function showAlert(message) {
        alertDiv.classList.remove('d-none');
        conflictingDrugSpan.innerText = message;
        textarea.classList.add('is-invalid');
        submitBtn.classList.remove('btn-primary');
        submitBtn.classList.add('btn-danger');
    }

    function hideAlert() {
        alertDiv.classList.add('d-none');
        textarea.classList.remove('is-invalid');
        submitBtn.classList.add('btn-primary');
        submitBtn.classList.remove('btn-danger');
    }

    textarea.addEventListener('input', checkConflicts);

    form.addEventListener('submit', function(e) {
        if (!alertDiv.classList.contains('d-none')) {
            if (!confirm('ATTENTION : Un risque d\'allergie a été détecté. Êtes-vous sûr de vouloir maintenir cette prescription ?')) {
                e.preventDefault();
            }
        }
    });
});
</script>
@endsection
