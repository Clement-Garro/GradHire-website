<?php

namespace app\src\model\dataObject;

use app\src\model\dataObject\AbstractDataObject;

class Avis extends AbstractDataObject{

    private int $idavis;
    private int $identreprise;
    private int $idutilisateur;
    private string $commentaire;

    public function __construct(array $dataObjectFormatTableau)
    {
        foreach ($dataObjectFormatTableau as $key => $value) {
            $this->$key = $value;
        }
    }

    public function getIdavis(): int
    {
        return $this->idavis;
    }

    public function setIdavis(int $idavis): void
    {
        $this->idavis = $idavis;
    }

    public function getIdentreprise(): int
    {
        return $this->identreprise;
    }

    public function setIdentreprise(int $identreprise): void
    {
        $this->identreprise = $identreprise;
    }

    public function getIdutilisateur(): int
    {
        return $this->idutilisateur;
    }

    public function setIdutilisateur(int $idutilisateur): void
    {
        $this->idutilisateur = $idutilisateur;
    }

    public function getCommentaire(): string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): void
    {
        $this->commentaire = $commentaire;
    }

    protected function getValueColonne(string $nomColonne): string
    {
        return strval($$nomColonne);
    }
}