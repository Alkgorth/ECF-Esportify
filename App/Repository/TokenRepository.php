<?php

namespace App\Repository;


use App\Entity\Token as TableToken;
use DateInterval;
use DateTime;

class TokenRepository extends MainRepository
{
    // Récupération du token en BDD
    public function findToken($tokenValue)
    {
        $query = $this->pdo->prepare('SELECT * FROM tokens WHERE token = :token AND expiration_date > NOW()');
        $query->bindValue(':token', $tokenValue, $this->pdo::PARAM_STR);
        $query->execute();
        $token = $query->fetch($this->pdo::FETCH_ASSOC);

        if ($token) {
            return TableToken::createAndHydrate($token);
        } else {
            return null;
        }
        
    }

    //Suppression du token en BDD
    public function deleteToken(string $tokenValue)
    {
        $query = $this->pdo->prepare('DELETE FROM tokens WHERE token = :token');
        $query->bindValue(':token', $tokenValue,$this->pdo::PARAM_STR);
        return $query->execute();
        
    }
}