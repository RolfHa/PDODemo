<?php

class Person
{
    private int $id;
    private string $vorname;
    private string $nachname;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getVorname(): string
    {
        return $this->vorname;
    }

    /**
     * @return string
     */
    public function getNachname(): string
    {
        return $this->nachname;
    }
// Brauche standard-Konstruktor für
// Erstellen eines Arrays mit allen Objekten( getAll()).
// $stmt->fetchAll(PDO::FETCH_CLASS, self::class)
    /**
     * @param int $id
     * @param string $vorname
     * @param string $nachname
     */
    public function __construct(int $id = null, string $vorname = null, string $nachname = null)
    {
        if (isset($id) && isset($vorname) && isset($nachname)) {
            $this->id = $id;
            $this->vorname = $vorname;
            $this->nachname = $nachname;
        }

    }


    /**
     * @return Person[]
     */
    public static function getAll(): array
    {
        $dbh = Db::getConnection();
        $stmt = $dbh->prepare("SELECT * FROM person");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    /**
     * @param int $pk
     * @return Person
     */
    public static function getById(int $pk): Person
    {
        $dbh = Db::getConnection();
        $stmt = $dbh->prepare("SELECT * FROM person WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $id = $pk;
        $stmt->execute();

        return $stmt->fetchObject(self::class);
    }

    /**
     * @param string $vorname
     * @param string $nachname
     * @return Person
     * Eintrag in db
     */
    public static function create(string $vorname, string $nachname): Person
    {
        // Aufgabe: Insert-statement schreiben, ausführen, die erzeughte id auslesen und mit GetById()
        // das neue Objekt zurückgeben
        $dbh = Db::getConnection();
        $stmt = $dbh->prepare("INSERT INTO person (vorname, nachname) VALUES (:vorname, :nachname)");
        // Speichernplatz der Variable $vorname wird an das
        // prepared statement gebunden
        $stmt->bindParam(':vorname', $vorname);
        $stmt->bindParam(':nachname', $nachname);
        // das unten Stehende kann mehrfach ausgeführt werden:
        $stmt->execute();
        $id = $dbh->lastInsertId();
        return self::getById($id);
    }

    /**
     * @return void
     */
    public function delete(): void
    {
        $dbh = Db::getConnection();
        $stmt = $dbh->prepare("DELETE FROM person
                             WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $id = $this->id;
        $stmt->execute();
    }

    /**
     * @param string $vorname
     */
    public function setVorname(string $vorname): void
    {
        $this->vorname = $vorname;
    }

    /**
     * @param string $nachname
     */
    public function setNachname(string $nachname): void
    {
        $this->nachname = $nachname;
    }

    /**
     * @return void
     * speichert geänderte Attribute in db
     */
    public function update(): void
    {
        $dbh = Db::getConnection();
        $stmt = $dbh->prepare("UPDATE person 
                                    SET vorname = :vorname,nachname = :nachname 
                                    WHERE id=:id");
        $stmt->bindParam(':vorname', $this->vorname, PDO::PARAM_STR);
        $stmt->bindParam(':nachname', $this->nachname, PDO::PARAM_STR);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->execute();
    }
}
