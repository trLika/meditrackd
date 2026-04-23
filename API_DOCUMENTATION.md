# Documentation API SGIDM

## Authentification

### Login
```http
POST /api/login
Content-Type: application/json

{
    "email": "user@example.com",
    "password": "password"
}
```

**Réponse:**
```json
{
    "user": {
        "id": 1,
        "name": "Dr. Dupont",
        "email": "dr.dupont@hopital.fr",
        "roles": ["medecin"],
        "services": [
            {
                "id": 1,
                "name": "Cardiologie"
            }
        ]
    },
    "token": "1|abc123...",
    "token_type": "Bearer"
}
```

### Logout
```http
POST /api/logout
Authorization: Bearer {token}
```

## Patients

### Lister les patients
```http
GET /api/patients
Authorization: Bearer {token}
```

### Créer un patient
```http
POST /api/patients
Authorization: Bearer {token}
Content-Type: application/json

{
    "nom": "Martin",
    "prenom": "Jean",
    "sexe": "M",
    "date_naissance": "1980-05-15",
    "telephone": "0612345678",
    "adresse": "123 rue de la Paix",
    "groupe_sanguin": "A+",
    "antecedents": "Hypertension",
    "allergies": "Pénicilline",
    "service_id": 1,
    "is_critique": false
}
```

### Voir un patient
```http
GET /api/patients/{id}
Authorization: Bearer {token}
```

### Modifier un patient
```http
PUT /api/patients/{id}
Authorization: Bearer {token}
Content-Type: application/json
```

### Supprimer un patient
```http
DELETE /api/patients/{id}
Authorization: Bearer {token}
```

## Consultations

### Consultations d'un patient
```http
GET /api/patients/{patient_id}/consultations
Authorization: Bearer {token}
```

### Créer une consultation
```http
POST /api/patients/{patient_id}/consultations
Authorization: Bearer {token}
Content-Type: application/json

{
    "date_consultation": "2024-01-15",
    "symptomes": "Douleur thoracique",
    "diagnostic": "Angine de poitrine",
    "traitement": "Trinitrine 0.5mg",
    "poids": 75.5,
    "tension": "130/80"
}
```

## Ordonnances

### Ordonnances d'un patient
```http
GET /api/patients/{patient_id}/ordonnances
Authorization: Bearer {token}
```

### Créer une ordonnance
```http
POST /api/patients/{patient_id}/ordonnances
Authorization: Bearer {token}
Content-Type: application/json

{
    "medicaments": "Amoxicilline 500mg",
    "posologie": "1 comprimé 3 fois par jour",
    "duree": "7 jours",
    "instructions": "Pendant les repas"
}
```

## Services (Admin seulement)

### Lister les services
```http
GET /api/services
Authorization: Bearer {token}
```

### Assigner un médecin à un service
```http
POST /api/services/{service_id}/assign-medecin
Authorization: Bearer {token}
Content-Type: application/json

{
    "medecin_id": 5
}
```

### Retirer un médecin d'un service
```http
DELETE /api/services/{service_id}/remove-medecin/{medecin_id}
Authorization: Bearer {token}
```

## Utilisateurs (Admin seulement)

### Lister les utilisateurs
```http
GET /api/users
Authorization: Bearer {token}
```

### Créer un utilisateur
```http
POST /api/users
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "Dr. Martin",
    "email": "dr.martin@hopital.fr",
    "password": "password123",
    "role": "medecin"
}
```

## Routes Spéciales par Rôle

### Pour les médecins
```http
GET /api/mes-patients
GET /api/mon-service/patients
```

### Pour les patients
```http
GET /api/mon-dossier
GET /api/mes-consultations
GET /api/mes-ordonnances
```

## Permissions

| Rôle | Accès Patients | Création | Modification | Suppression |
|------|----------------|----------|---------------|-------------|
| Administrateur | Tous | Oui | Oui | Oui |
| Médecin | Service seulement | Oui | Oui | Non |
| Patient | Soi-même | Non | Non | Non |
| Stagiaire | Service seulement | Non | Non | Non |

## Erreurs

**401 Non autorisé:**
```json
{
    "message": "Non autorisé"
}
```

**403 Accès refusé:**
```json
{
    "message": "Ce patient n'est pas dans votre service"
}
```

**422 Validation:**
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": ["The email field is required."]
    }
}
```

## Tests d'API

### Test avec curl
```bash
# Login
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@hopital.fr","password":"password"}'

# Utiliser le token
curl -X GET http://localhost:8000/api/patients \
  -H "Authorization: Bearer VOTRE_TOKEN"
```
