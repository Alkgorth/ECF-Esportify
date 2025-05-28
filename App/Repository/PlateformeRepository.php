<?php

namespace App\Repository;

class PlateformeRepository extends MainRepository
{
    public function findPlateforme(int $id_plateforme): ?array
    {
        $stmtPlateforme = $this->pdo->prepare("SELECT id_plateforme FROM plateforme WHERE id_plateforme = :id_plateforme LIMIT 1");
        $stmtPlateforme->bindParam(':id_plateforme', $id_plateforme, $this->pdo::PARAM_INT);
        $stmtPlateforme->execute();
        $plateformeData = $stmtPlateforme->fetch($this->pdo::FETCH_ASSOC);

        if ($plateformeData) {
            return $plateformeData;
        } else {
            return null;
        }
    }
}
