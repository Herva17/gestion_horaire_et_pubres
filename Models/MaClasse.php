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
    public function createEnseignant(string $nom, string $prenom, string $email, string $telephone, string $statut, string $specialite): bool {
        $sql = "INSERT INTO enseignant (nom, prenom, email, telephone, statut, specialite) 
                VALUES (:nom, :prenom, :email, :telephone, :statut, :specialite)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':email' => $email,
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

    public function updateEnseignant(int $id, string $nom, string $prenom, string $email, string $telephone, string $statut, string $specialite): bool {
        $sql = "UPDATE enseignant SET 
                nom = :nom, 
                prenom = :prenom, 
                email = :email, 
                telephone = :telephone, 
                statut = :statut, 
                specialite = :specialite 
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':email' => $email,
            ':telephone' => $telephone,
            ':statut' => $statut,
            ':specialite' => $specialite
        ]);
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
    public function createTypeCours(string $nom): bool {
        $sql = "INSERT INTO type_cours (nom) VALUES (:nom)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':nom' => $nom]);
    }

    public function getTypesCours(): array {
        $sql = "SELECT * FROM type_cours ORDER BY nom";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTypeCoursById(int $id): ?array {
        $sql = "SELECT * FROM type_cours WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function updateTypeCours(int $id, string $nom): bool {
        $sql = "UPDATE type_cours SET nom = :nom WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id, ':nom' => $nom]);
    }

    public function deleteTypeCours(int $id): bool {
        $sql = "DELETE FROM type_cours WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // ==================== COURS ====================
    public function createCours(string $nom, string $code, int $id_type_cours, int $credit, int $volume_horaire): bool {
        $sql = "INSERT INTO cours (nom, code, id_type_cours, credit, volume_horaire) 
                VALUES (:nom, :code, :id_type_cours, :credit, :volume_horaire)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nom' => $nom,
            ':code' => $code,
            ':id_type_cours' => $id_type_cours,
            ':credit' => $credit,
            ':volume_horaire' => $volume_horaire
        ]);
    }

    public function getCours(): array {
        $sql = "SELECT c.*, tc.nom as type_cours_nom 
                FROM cours c
                JOIN type_cours tc ON c.id_type_cours = tc.id
                ORDER BY c.nom";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCoursById(int $id): ?array {
        $sql = "SELECT c.*, tc.nom as type_cours_nom 
                FROM cours c
                JOIN type_cours tc ON c.id_type_cours = tc.id
                WHERE c.id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function updateCours(int $id, string $nom, string $code, int $id_type_cours, int $credit, int $volume_horaire): bool {
        $sql = "UPDATE cours SET 
                nom = :nom, 
                code = :code, 
                id_type_cours = :id_type_cours, 
                credit = :credit, 
                volume_horaire = :volume_horaire 
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':nom' => $nom,
            ':code' => $code,
            ':id_type_cours' => $id_type_cours,
            ':credit' => $credit,
            ':volume_horaire' => $volume_horaire
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