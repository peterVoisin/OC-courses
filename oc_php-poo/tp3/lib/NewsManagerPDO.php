<?php
class NewsManagerPDO extends NewsManager
{
  // Attribut contenant l'instance représentant le BDD
  protected $db;

  // Constructeur chargé d'enregistrer l'instance de PDO dans l'attribut $db
  public function __construct(PDO $db)
  {
    $this->db = $db;
  }

  // NewsManager::add()
  protected function add(News $news)
  {
    $requete = $this->db->prepare('INSERT INTO news(auteur,titre,contenu,dateAjout,dateModif) VALUES(:auteur,:titre,:contenu,NOW(),NOW())');

    $requete->bindValue(':auteur', $news->auteur());
    $requete->bindValue(':titre', $news->titre());
    $requete->bindValue(':contenu', $news->contenu());

    $requete->execute();
  }

  // NewsManager::count()
  public function count()
  {
    return $this->db->query('SELECT COUNT(*) FROM news')->fetchColumn();
  }

  // NewsManager::delete()
  public function delete($id)
  {
    $this->db->exec('DELETE FROM news WHERE id = '.(int) $id);
  }

  // NewsManager::getList()
  public function getList($debut = -1,$limite = -1)
  {
    $sql = 'SELECT id,auteur,titre,contenu,dateAjout,dateModif FROM news ORDER BY id DESC';

    // Vérifier l'intégrité des paramètres fournis
    if ($debut != -1 || $limite != -1) {
      $sql .= ' LIMIT '.(int) $limite.' OFFSET '.(int) $debut;
    }

    $requete = $this->db->query($sql);
    $requete->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'News');

    $listeNews = $requete->fetchALL();

    // On parcourt notre liste de news pouvoir placer des instances de DateTime en guise de dates d'ajout et de date de modification
    foreach ($listeNews as $news) {
      $news->setDateAjout(new DateTime($news->dateAjout()));
      $news->setDateModif(new DateTime($news->dateModif()));
    }

    $requete->closeCursor();

    return $listeNews;
  }

  // NewsManager::getUnique()
  public function getunique($id)
  {
    $requete = $this->db->prepare('SELECT id,auteur,titre,contenu,dateAjout,dateModif FROM news WHERE id = :id');
    $requete->bindValue(':id', (int) $id, PDO::PARAM_INT);
    $requete->execute();

    $requete->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'News');

    $news = $requete->fetch();

    $news->setDateAjout(new DateTime($news->dateAjout()));
    $news->setDateModif(new DateTime($news->dateModif()));

    return $news;
  }

  // NewsManager::update()
  protected function update(News $news)
  {
    $requete = $this->db->prepare('UPDATE news SET auteur = :auteur, titre = :titre, contenu = :contenu, dateModif = NOW() WHERE id = :id');

    $requete->bindValue(':auteur', $news->auteur());
    $requete->bindValue(':titre', $news->titre());
    $requete->bindValue(':contenu', $news->contenu());
    $requete->bindValue(':id', $news->id());

    $requete->execute();
  }
}
