<?php
include_once("Config.php");

class Horaire {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // ==================== DEPARTEMENT ====================
    public function createDepartement(string $nom): bool {
        $sql = "INSERT INTO departement (nom) VALUES (:nom)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':nom' => $nom]);
    }

    public function getDepartements(): array {
        $sql = "SELECT * FROM departement ORDER BY nom";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDepartementById(int $id): ?array {
        $sql = "SELECT * FROM departement WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function updateDepartement(int $id, string $nom): bool {
        $sql = "UPDATE departement SET nom = :nom WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id, ':nom' => $nom]);
    }

    public function deleteDepartement(int $id): bool {
        $sql = "DELETE FROM departement WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // ==================== SECTION ====================
    public function createSection(string $nom, int $id_departement): bool {
        $sql = "INSERT INTO section (nom, id_departement) VALUES (:nom, :id_departement)";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([
            ':nom' => $nom,
            ':id_departement' => $id_departement
        ]);
        
        if (!$result) {
            error_log("Erreur création section: " . implode(", ", $stmt->errorInfo()));
            return false;
        }
        
        return $stmt->rowCount() > 0;
    }

    public function getSections(): array {
        $sql = "SELECT s.*, d.nom as departement_nom 
                FROM section s 
                JOIN departement d ON s.id_departement = d.id
                ORDER BY s.nom";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSectionById(int $id): ?array {
        $sql = "SELECT s.*, d.nom as departement_nom 
                FROM section s
                JOIN departement d ON s.id_departement = d.id
                WHERE s.id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function updateSection(int $id, string $nom, int $id_departement): bool {
        $sql = "UPDATE section SET nom = :nom, id_departement = :id_departement WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id, ':nom' => $nom, ':id_departement' => $id_departement]);
    }

    public function deleteSection(int $id): bool {
        // Vérifier d'abord les contraintes de clé étrangère
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM promotion WHERE id_section = :id");
        $stmt->execute([':id' => $id]);
        
        if ($stmt->fetchColumn() > 0) {
            throw new Exception("Impossible de supprimer : des promotions sont liées à cette section");
        }

        // Si pas de dépendances, procéder à la suppression
        $sql = "DELETE FROM section WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        return $stmt->rowCount() > 0;
    }

    // ==================== PROMOTION ====================
    public function createPromotion(string $nom, int $id_section, string $annee_academique): bool {
        $sql = "INSERT INTO promotion (nom, id_section, annee_academique) VALUES (:nom, :id_section, :annee_academique)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':nom' => $nom, ':id_section' => $id_section, ':annee_academique' => $annee_academique]);
    }

    public function getPromotions(): array {
        $sql = "SELECT p.*, s.nom as section_nom, d.nom as departement_nom
                FROM promotion p 
                JOIN section s ON p.id_section = s.id
                JOIN departement d ON s.id_departement = d.id
                ORDER BY p.nom";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPromotionById(int $id): ?array {
        $sql = "SELECT p.*, s.nom as section_nom, d.nom as departement_nom
                FROM promotion p
                JOIN section s ON p.id_section = s.id
                JOIN departement d ON s.id_departement = d.id
                WHERE p.id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function updatePromotion(int $id, string $nom, int $id_section): bool {
        $sql = "UPDATE promotion SET nom = :nom, id_section = :id_section WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id, ':nom' => $nom, ':id_section' => $id_section]);
    }

    public function deletePromotion(int $id): bool {
        $sql = "DELETE FROM promotion WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // ==================== ENSEIGNANT ====================
 public function createEnseignant(string $nom, string $prenom, string $email, string $mot_de_passe, string $telephone, string $statut, string $specialite): bool {
    $sql = "INSERT INTO enseignant (nom, prenom, email, mot_de_passe, telephone, statut, specialite) 
            VALUES (:nom, :prenom, :email, :mot_de_passe, :telephone, :statut, :specialite)";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([
        ':nom' => $nom,
        ':prenom' => $prenom,
        ':email' => $email,
        ':mot_de_passe' => password_hash($mot_de_passe, PASSWORD_DEFAULT),
        ':telephone' => $telephone,
        ':statut' => $statut,
        ':specialite' => $specialite
    ]);
}

    public function getEnseignants(): array {
        $sql = "SELECT * FROM enseignant ORDER BY nom, prenom";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEnseignantById(int $id): ?array {
        $sql = "SELECT * FROM enseignant WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function updateEnseignant(
    int $id,
    string $nom,
    string $prenom,
    string $email,
    ?string $mot_de_passe,
    string $telephone,
    string $statut,
    string $specialite
): bool {
    $sql = "UPDATE enseignant SET 
            nom = :nom, 
            prenom = :prenom, 
            email = :email, " . 
            ($mot_de_passe !== null ? "mot_de_passe = :mot_de_passe, " : "") .
            "telephone = :telephone, 
            statut = :statut, 
            specialite = :specialite 
            WHERE id = :id";
    
    $params = [
        ':id' => $id,
        ':nom' => $nom,
        ':prenom' => $prenom,
        ':email' => $email,
        ':telephone' => $telephone,
        ':statut' => $statut,
        ':specialite' => $specialite
    ];
    
    if ($mot_de_passe !== null) {
        $params[':mot_de_passe'] = password_hash($mot_de_passe, PASSWORD_DEFAULT);
    }
    
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute($params);
}

public function deleteEnseignant(int $id): bool {
    $sql = "DELETE FROM enseignant WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([':id' => $id]);
}
    // ==================== SALLE ====================
public function createSalle(string $designation, string $description_salle): bool {
    $sql = "INSERT INTO salle (designation, description_salle) VALUES (:designation, :description_salle)";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([
        ':designation' => $designation,
        ':description_salle' => $description_salle
    ]);
}

public function getSalles(): array {
    $sql = "SELECT * FROM salle ORDER BY designation";
    return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

public function getSalleById(int $id): ?array {
    $sql = "SELECT * FROM salle WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

public function updateSalle(int $id, string $designation, string $description_salle): bool {
    $sql = "UPDATE salle SET 
            designation = :designation, 
            description_salle = :description_salle 
            WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([
        ':id' => $id,
        ':designation' => $designation,
        ':description_salle' => $description_salle
    ]);
}

public function deleteSalle(int $id): bool {
    $sql = "DELETE FROM salle WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([':id' => $id]);
}

// ==================== TYPE COURS ====================
public function createTypeCours(string $nom, ?string $description = null, ?string $duree_par_seance = null): bool {
    $sql = "INSERT INTO typecours (nom, Description, duree_par_seance) 
            VALUES (:nom, :description, :duree)";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([
        ':nom' => $nom,
        ':description' => $description,
        ':duree' => $duree_par_seance
    ]);
}

public function getTypesCours(): array {
    $sql = "SELECT id, nom, Description, duree_par_seance FROM typecours ORDER BY nom";
    return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

public function getTypeCoursById(int $id): ?array {
    $sql = "SELECT id, nom, Description, duree_par_seance FROM typecours WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

public function updateTypeCours(int $id, string $nom, ?string $description = null, ?string $duree_par_seance = null): bool {
    $sql = "UPDATE typecours 
            SET nom = :nom, 
                Description = :description, 
                duree_par_seance = :duree
            WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([
        ':id' => $id,
        ':nom' => $nom,
        ':description' => $description,
        ':duree' => $duree_par_seance
    ]);
}

public function deleteTypeCours(int $id): bool {
    $sql = "DELETE FROM typecours WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([':id' => $id]);
}

// ==================== COURS ====================
public function createCours(
    string $titre, 
    string $Description, 
    int $id_enseignant, 
    int $id_promotion, 
    int $id_typeCours, 
    ?int $id_salle = null, 
    string $volume_horaire, 
    ?float $Coefficient = null, 
    ?int $Credit = null
): bool {
    $sql = "INSERT INTO cours (
                titre, 
                Description, 
                id_enseignant, 
                id_promotion, 
                id_typeCours, 
                id_salle, 
                volume_horaire, 
                Coefficient, 
                Credit
            ) VALUES (
                :titre, 
                :description, 
                :id_enseignant, 
                :id_promotion, 
                :id_typeCours, 
                :id_salle, 
                :volume_horaire, 
                :coefficient, 
                :credit
            )";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([
        ':titre' => $titre,
        ':description' => $Description,
        ':id_enseignant' => $id_enseignant,
        ':id_promotion' => $id_promotion,
        ':id_typeCours' => $id_typeCours,
        ':id_salle' => $id_salle,
        ':volume_horaire' => $volume_horaire,
        ':coefficient' => $Coefficient,
        ':credit' => $Credit
    ]);
}

public function getCoursWithDetails(): array {
    $sql = "SELECT 
                c.id, 
                c.titre, 
                c.Description, 
                c.volume_horaire, 
                c.Coefficient, 
                c.Credit,
                e.nom AS enseignant_nom,
                p.nom AS promotion_nom,
                tc.nom AS type_cours_nom,
                s.designation AS salle_designation
            FROM cours c
            LEFT JOIN enseignant e ON c.id_enseignant = e.id
            LEFT JOIN promotion p ON c.id_promotion = p.id
            LEFT JOIN typecours tc ON c.id_typeCours = tc.id
            LEFT JOIN salle s ON c.id_salle = s.id
            ORDER BY c.titre";
    return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

public function getCoursById(int $id): ?array {
    $sql = "SELECT 
                c.*,
                e.nom AS enseignant_nom,
                p.nom AS promotion_nom,
                tc.nom AS type_cours_nom,
                s.designation AS salle_designation
            FROM cours c
            LEFT JOIN enseignant e ON c.id_enseignant = e.id
            LEFT JOIN promotion p ON c.id_promotion = p.id
            LEFT JOIN typecours tc ON c.id_typeCours = tc.id
            LEFT JOIN salle s ON c.id_salle = s.id
            WHERE c.id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

public function updateCours(
    int $id, 
    string $titre, 
    string $Description, 
    int $id_enseignant, 
    int $id_promotion, 
    int $id_typeCours, 
    ?int $id_salle, 
    string $volume_horaire, 
    ?float $Coefficient, 
    ?int $Credit
): bool {
    $sql = "UPDATE cours SET 
                titre = :titre, 
                Description = :description, 
                id_enseignant = :id_enseignant, 
                id_promotion = :id_promotion, 
                id_typeCours = :id_typeCours, 
                id_salle = :id_salle, 
                volume_horaire = :volume_horaire, 
                Coefficient = :coefficient, 
                Credit = :credit
            WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([
        ':id' => $id,
        ':titre' => $titre,
        ':description' => $Description,
        ':id_enseignant' => $id_enseignant,
        ':id_promotion' => $id_promotion,
        ':id_typeCours' => $id_typeCours,
        ':id_salle' => $id_salle,
        ':volume_horaire' => $volume_horaire,
        ':coefficient' => $Coefficient,
        ':credit' => $Credit
    ]);
}

public function deleteCours(int $id): bool {
    $sql = "DELETE FROM cours WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([':id' => $id]);
}


     public function createDisponibiliteEnseignant(string $jour, string $heure_debut, string $heure_fin, int $id_enseignant): bool {
        $sql = "INSERT INTO disponibiliteenseignant (jour, heure_debut, heure_fin, id_enseignant) 
                VALUES (:jour, :heure_debut, :heure_fin, :id_enseignant)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':jour' => $jour,
            ':heure_debut' => $heure_debut,
            ':heure_fin' => $heure_fin,
            ':id_enseignant' => $id_enseignant
        ]);
    }

    public function getDisponibilitesByEnseignant(int $id_enseignant): array {
    $sql = "SELECT * FROM disponibiliteenseignant WHERE id_enseignant = :id_enseignant ORDER BY jour, heure_debut";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id_enseignant' => $id_enseignant]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function updateDisponibilite(int $id, string $jour, string $heure_debut, string $heure_fin): bool {
    $sql = "UPDATE disponibiliteenseignant 
            SET jour = :jour, heure_debut = :heure_debut, heure_fin = :heure_fin 
            WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([
        ':id' => $id,
        ':jour' => $jour,
        ':heure_debut' => $heure_debut,
        ':heure_fin' => $heure_fin
    ]);
}

public function deleteDisponibilite(int $id): bool {
    $sql = "DELETE FROM disponibiliteenseignant WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([':id' => $id]);
}

public function checkDisponibilite(int $id_enseignant, string $jour, string $heure): bool {
    $sql = "SELECT COUNT(*) FROM disponibiliteenseignant 
            WHERE id_enseignant = :id_enseignant 
            AND jour = :jour 
            AND heure_debut <= :heure 
            AND heure_fin >= :heure";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([
        ':id_enseignant' => $id_enseignant,
        ':jour' => $jour,
        ':heure' => $heure
    ]);
    return $stmt->fetchColumn() > 0;
}

// ==================== ETUDIANT ====================
public function createEtudiant(
    string $nom, 
    string $prenom, 
    string $adresseEmail, 
    string $matricule, 
    string $telephone, 
    string $sexe, 
    int $id_promotion
): bool {
    $sql = "INSERT INTO etudiant (nom, prenom, Adressemail, matricule, telephone, sexe, id_promotion) 
            VALUES (:nom, :prenom, :email, :matricule, :telephone, :sexe, :id_promotion)";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([
        ':nom' => $nom,
        ':prenom' => $prenom,
        ':email' => $adresseEmail,
        ':matricule' => $matricule,
        ':telephone' => $telephone,
        ':sexe' => $sexe,
        ':id_promotion' => $id_promotion
    ]);
}

public function getEtudiants(): array {
    $sql = "SELECT e.*, p.nom as promotion_nom 
            FROM etudiant e
            LEFT JOIN promotion p ON e.id_promotion = p.id
            ORDER BY e.nom, e.prenom";
    return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

public function getEtudiantById(int $id): ?array {
    $sql = "SELECT e.*, p.nom as promotion_nom 
            FROM etudiant e
            LEFT JOIN promotion p ON e.id_promotion = p.id
            WHERE e.matricule = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

public function getEtudiantsByPromotion(int $id_promotion): array {
    $sql = "SELECT e.* FROM etudiant e WHERE e.id_promotion = :id_promotion ORDER BY e.nom, e.prenom";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id_promotion' => $id_promotion]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function updateEtudiant(
    string $matricule, 
    string $nom, 
    string $prenom, 
    string $adresseEmail, 
    string $telephone, 
    string $sexe, 
    int $id_promotion
): bool {
    $sql = "UPDATE etudiant SET 
            nom = :nom, 
            prenom = :prenom, 
            Adressemail = :email, 
            telephone = :telephone, 
            sexe = :sexe, 
            id_promotion = :id_promotion
            WHERE matricule = :matricule";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([
        ':matricule' => $matricule,
        ':nom' => $nom,
        ':prenom' => $prenom,
        ':email' => $adresseEmail,
        ':telephone' => $telephone,
        ':sexe' => $sexe,
        ':id_promotion' => $id_promotion
    ]);
}
public function deleteEtudiant(string $matricule): bool {
    $sql = "DELETE FROM etudiant WHERE matricule = :matricule";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([':matricule' => $matricule]);
}

public function authenticateEtudiant(string $email, string $password): ?array {
    $sql = "SELECT * FROM etudiant WHERE Adressemail = :email";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':email' => $email]);
    $etudiant = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($etudiant && password_verify($password, $etudiant['mot_de_passe'])) {
        unset($etudiant['mot_de_passe']);
        return $etudiant;
    }
    
    return null;
}

// ==================== SECRETAIRE ====================
public function createSecretaire(
    string $nom,
    string $prenom,
    string $sexe,
    string $adresseEmail,
    string $telephone,
    string $motDePasse,
    string $adresse,
    string $grade,
    int $id_section
): bool {
    $sql = "INSERT INTO secretairesection 
            (nom, prenom, sexe, adressmail, telephone, mot_de_passe, Adresse, grade, id_section) 
            VALUES (:nom, :prenom, :sexe, :email, :telephone, :mot_de_passe, :adresse, :grade, :id_section)";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([
        ':nom' => $nom,
        ':prenom' => $prenom,
        ':sexe' => $sexe,
        ':email' => $adresseEmail,
        ':telephone' => $telephone,
        ':mot_de_passe' => password_hash($motDePasse, PASSWORD_DEFAULT),
        ':adresse' => $adresse,
        ':grade' => $grade,
        ':id_section' => $id_section
    ]);
}

public function getSecretaires(): array {
    $sql = "SELECT s.*, sec.nom as section_nom 
            FROM secretairesection s
            LEFT JOIN section sec ON s.id_section = sec.id
            ORDER BY s.nom, s.prenom";
    return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

public function getSecretaireById(int $id): ?array {
    $sql = "SELECT s.*, sec.nom as section_nom 
            FROM secretairesection s
            LEFT JOIN section sec ON s.id_section = sec.id
            WHERE s.id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

public function getSecretairesBySection(int $id_section): array {
    $sql = "SELECT s.* FROM secretairesection s WHERE s.id_section = :id_section ORDER BY s.nom, s.prenom";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id_section' => $id_section]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function updateSecretaire(
    int $id,
    string $nom,
    string $prenom,
    string $sexe,
    string $adresseEmail,
    string $telephone,
    ?string $motDePasse,
    string $adresse,
    string $grade,
    int $id_section
): bool {
    $params = [
        ':id' => $id,
        ':nom' => $nom,
        ':prenom' => $prenom,
        ':sexe' => $sexe,
        ':email' => $adresseEmail,
        ':telephone' => $telephone,
        ':adresse' => $adresse,
        ':grade' => $grade,
        ':id_section' => $id_section
    ];
    
    $sql = "UPDATE secretairesection SET 
            nom = :nom, 
            prenom = :prenom, 
            sexe = :sexe, 
            adressmail = :email, 
            telephone = :telephone, 
            Adresse = :adresse, 
            grade = :grade, 
            id_section = :id_section";
    
    if ($motDePasse !== null) {
        $sql .= ", mot_de_passe = :mot_de_passe";
        $params[':mot_de_passe'] = password_hash($motDePasse, PASSWORD_DEFAULT);
    }
    
    $sql .= " WHERE id = :id";
    
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute($params);
}

public function deleteSecretaire(int $id): bool {
    $sql = "DELETE FROM secretairesection WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([':id' => $id]);
}

public function authenticateSecretaire(string $email, string $password): ?array {
    $sql = "SELECT * FROM secretairesection WHERE adressmail = :email";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':email' => $email]);
    $secretaire = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($secretaire && password_verify($password, $secretaire['mot_de_passe'])) {
        unset($secretaire['mot_de_passe']);
        return $secretaire;
    }
    
    return null;
}

// ==================== HORAIRE ====================
public function createHoraire($date_debut, $date_fin, $heure_debut, $heure_fin, $frequence, $id_salle, $id_promotion, $id_cours, $id_secretaire, $id_enseignant) {
    $sql = "INSERT INTO horaire (date_debut, date_fin, heure_debut, heure_fin, frequence, id_salle, id_promotion, id_cours, id_secretaire, id_enseignant) 
            VALUES (:date_debut, :date_fin, :heure_debut, :heure_fin, :frequence, :id_salle, :id_promotion, :id_cours, :id_secretaire, :id_enseignant)";
    
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([
        ':date_debut' => $date_debut,
        ':date_fin' => $date_fin,
        ':heure_debut' => $heure_debut,
        ':heure_fin' => $heure_fin,
        ':frequence' => $frequence,
        ':id_salle' => $id_salle,
        ':id_promotion' => $id_promotion,
        ':id_cours' => $id_cours,
        ':id_secretaire' => $id_secretaire,
        ':id_enseignant' => $id_enseignant
    ]);
}

public function getHoraires(): array {
    $sql = "SELECT 
        h.id,
        h.date_debut,
        h.date_fin,
        h.heure_debut,
        h.heure_fin,
        h.frequence,
        h.id_salle,
        h.id_promotion,
        h.id_cours,
        h.id_secretaire,
        h.id_enseignant,
        s.designation as salle_nom, 
        p.nom as promotion_nom, 
        sec.nom as section_nom, 
        c.titre as cours_nom, 
        IFNULL(sc.nom, '') as secretaire_nom, 
        e.nom as enseignant_nom 
    FROM 
        horaire h 
        LEFT JOIN salle s ON h.id_salle = s.id 
        LEFT JOIN promotion p ON h.id_promotion = p.id 
        LEFT JOIN section sec ON p.id_section = sec.id 
        LEFT JOIN cours c ON h.id_cours = c.id 
        LEFT JOIN secretairesection sc ON h.id_secretaire = sc.id 
        LEFT JOIN enseignant e ON h.id_enseignant = e.id 
    ORDER BY 
        sec.nom, p.nom, h.date_debut, h.heure_debut";
    
    return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

public function getHoraireById(int $id): ?array {
    $sql = "SELECT 
                h.*,
                s.designation as salle_nom,
                p.nom as promotion_nom,
                c.titre as cours_nom,
                sec.nom as secretaire_nom,
                e.nom as enseignant_nom
            FROM horaire h
            LEFT JOIN salle s ON h.id_salle = s.id
            LEFT JOIN promotion p ON h.id_promotion = p.id
            LEFT JOIN cours c ON h.id_cours = c.id
            LEFT JOIN secretairesection sec ON h.id_secretaire = sec.id
            LEFT JOIN enseignant e ON h.id_enseignant = e.id
            WHERE h.id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

public function getHorairesByPromotion(int $id_promotion): array {
    $sql = "SELECT 
                h.*,
                s.designation as salle_nom,
                p.nom as promotion_nom,
                c.titre as cours_nom,
                e.nom as enseignant_nom
            FROM horaire h
            LEFT JOIN salle s ON h.id_salle = s.id
            LEFT JOIN promotion p ON h.id_promotion = p.id
            LEFT JOIN cours c ON h.id_cours = c.id
            LEFT JOIN enseignant e ON h.id_enseignant = e.id
            WHERE h.id_promotion = :id_promotion
            ORDER BY h.jour, h.heure_debut";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id_promotion' => $id_promotion]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getHorairesByEnseignant(int $id_enseignant): array {
    $sql = "SELECT 
                h.*,
                s.designation as salle_nom,
                p.nom as promotion_nom,
                c.titre as cours_nom
            FROM horaire h
            LEFT JOIN salle s ON h.id_salle = s.id
            LEFT JOIN promotion p ON h.id_promotion = p.id
            LEFT JOIN cours c ON h.id_cours = c.id
            WHERE h.id_enseignant = :id_enseignant
            ORDER BY h.jour, h.heure_debut";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id_enseignant' => $id_enseignant]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function updateHoraire($id, $date_debut, $date_fin, $heure_debut, $heure_fin, $frequence, $id_salle, $id_promotion, $id_cours, $id_secretaire, $id_enseignant) {
    $sql = "UPDATE horaire SET 
            date_debut = :date_debut,
            date_fin = :date_fin,
            heure_debut = :heure_debut,
            heure_fin = :heure_fin,
            frequence = :frequence,
            id_salle = :id_salle,
            id_promotion = :id_promotion,
            id_cours = :id_cours,
            id_secretaire = :id_secretaire,
            id_enseignant = :id_enseignant
            WHERE id = :id";
    
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([
        ':id' => $id,
        ':date_debut' => $date_debut,
        ':date_fin' => $date_fin,
        ':heure_debut' => $heure_debut,
        ':heure_fin' => $heure_fin,
        ':frequence' => $frequence,
        ':id_salle' => $id_salle,
        ':id_promotion' => $id_promotion,
        ':id_cours' => $id_cours,
        ':id_secretaire' => $id_secretaire,
        ':id_enseignant' => $id_enseignant
    ]);
}
public function deleteHoraire(int $id): bool {
    $sql = "DELETE FROM horaire WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([':id' => $id]);
}

// Méthodes utilitaires pour vérifier les conflits
private function hasSalleConflict(
    int $id_salle, 
    string $jour, 
    string $heure_debut, 
    string $heure_fin,
    ?int $exclude_id = null
): bool {
    $sql = "SELECT COUNT(*) FROM horaire 
            WHERE id_salle = :id_salle 
            AND jour = :jour 
            AND (
                (:heure_debut BETWEEN heure_debut AND heure_fin) OR
                (:heure_fin BETWEEN heure_debut AND heure_fin) OR
                (heure_debut BETWEEN :heure_debut AND :heure_fin)
            )";
    
    if ($exclude_id !== null) {
        $sql .= " AND id != :exclude_id";
    }
    
    $stmt = $this->pdo->prepare($sql);
    $params = [
        ':id_salle' => $id_salle,
        ':jour' => $jour,
        ':heure_debut' => $heure_debut,
        ':heure_fin' => $heure_fin
    ];
    
    if ($exclude_id !== null) {
        $params[':exclude_id'] = $exclude_id;
    }
    
    $stmt->execute($params);
    return $stmt->fetchColumn() > 0;
}

private function hasEnseignantConflict(
    int $id_enseignant, 
    string $jour, 
    string $heure_debut, 
    string $heure_fin,
    ?int $exclude_id = null
): bool {
    $sql = "SELECT COUNT(*) FROM horaire 
            WHERE id_enseignant = :id_enseignant 
            AND jour = :jour 
            AND (
                (:heure_debut BETWEEN heure_debut AND heure_fin) OR
                (:heure_fin BETWEEN heure_debut AND heure_fin) OR
                (heure_debut BETWEEN :heure_debut AND :heure_fin)
            )";
    
    if ($exclude_id !== null) {
        $sql .= " AND id != :exclude_id";
    }
    
    $stmt = $this->pdo->prepare($sql);
    $params = [
        ':id_enseignant' => $id_enseignant,
        ':jour' => $jour,
        ':heure_debut' => $heure_debut,
        ':heure_fin' => $heure_fin
    ];
    
    if ($exclude_id !== null) {
        $params[':exclude_id'] = $exclude_id;
    }
    
    $stmt->execute($params);
    return $stmt->fetchColumn() > 0;
}
    // ==================== UTILITAIRES ====================
    public function beginTransaction(): bool {
        return $this->pdo->beginTransaction();
    }

    public function commit(): bool {
        return $this->pdo->commit();
    }

    public function rollBack(): bool {
        return $this->pdo->rollBack();
    }
}
?>