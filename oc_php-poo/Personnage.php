<?php
class Personnage
{
  private $_id;
  private $_nom;
  private $_forcePerso;
  private $_degats;
  private $_niveau;
  private $_experience;

  // Hydratation

  // un tableau de données doit être passé à la fonction (d'où le préfixe " array")
  public function hydrate(array $donnees)
  {
    foreach ($donnees as $key => $value) {
      $method = 'set'.ucfirst($key);

      if (method_exists($this, $method)) {
        // On appelle le setter
        $this->$method($value);
      }
    }
  }

  // Liste des getters

  public function id() { return $this->_id; }
  public function nom() { return $this->_nom; }
  public function forcePerso() { return $this->_forcePerso; }
  public function degats() { return $this->_degats; }
  public function niveau() { return $this->_niveau; }
  public function experience() { return $this->_experience; }

  // Liste des settes

  public function setId($id)
  {
    // L'identifiant du personnage sera, quoi qu'il arrive, un nombre entier
    $this->_id = (int) $id;
  }

  public function setNom($nom)
  {
    // On vérifie qu'il s'agit bien d'une chaïne de caractères
    // Dont la longueur est inférieure à 30 caractères
    if (is_string($nom) && strlen($nom) <= 30 ) {
      $this->_nom = $nom;
    }
  }

  public function setForcePerso($forcePerso)
  {
    $forcePerso = (int) $forcePerso;

    // On vérifie que la force pass"e est comprise entre 0 et 100
    if ($forcePerso >= 0 && $forcePerso <= 100) {
      $this->_forcePerso = $forcePerso;
    }
  }

  public function setDegats($degats)
  {
    $degats = (int) $degats;

    if ($degats >= 0 && $degats <= 100) {
      $this->_degats = $degats;
    }
  }

  public function setNiveau($niveau)
  {
    $niveau = (int) $niveau;

    // On vérifie que le niveau n'est pas négatif
    if ($niveau >= 0) {
      $this->_niveau = $niveau;
    }
  }

  public function setExperience($experience)
  {
    $experience = (int) $experience;

    if ($experience >= 0 && $experience <= 100) {
      $this->_experience = $experience;
    }
  }
}
