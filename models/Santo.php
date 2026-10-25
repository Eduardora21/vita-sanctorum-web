<?php
class Santo {
    private ?PDO $conn;
    private string $table = 'santos';

    public function __construct(?PDO $db) {
        $this->conn = $db;
    }

    /**
     * Obtener santos destacados para el catálogo de inicio
     */
    public function getDestacados(): array {
        if (!$this->conn) return [];

        $query = "SELECT id, slug, nombre, titulo, festividad, patronazgo, biografia, imagen 
                  FROM {$this->table} 
                  WHERE destacado = 1 
                  ORDER BY id ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Obtener un santo por su slug único
     */
    public function getBySlug(string $slug): ?array {
        if (!$this->conn) return null;

        $query = "SELECT * FROM {$this->table} WHERE slug = :slug LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':slug', $slug, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Obtener festividades por mes litúrgico
     */
    public function getByMesLiturgico(int $mes): array {
        if (!$this->conn) return [];

        $query = "SELECT slug, nombre, titulo, dia_liturgico, grado_liturgico 
                  FROM {$this->table} 
                  WHERE mes_liturgico = :mes 
                  ORDER BY dia_liturgico ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':mes', $mes, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Listar todos los santos para el panel de administración
     */
    public function getAllAdmin(): array {
        if (!$this->conn) return [];

        $query = "SELECT id, slug, nombre, festividad, siglo, patronazgo, destacado 
                  FROM {$this->table} 
                  ORDER BY id DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Crear un nuevo santo
     */
    public function create(array $data): bool {
        if (!$this->conn) return false;

        $query = "INSERT INTO {$this->table} 
                  (slug, nombre, titulo, festividad, mes_liturgico, dia_liturgico, grado_liturgico, siglo, lugar_origen, patronazgo, atributos, frase_celebre, biografia, santidad, oracion, imagen, destacado) 
                  VALUES 
                  (:slug, :nombre, :titulo, :festividad, :mes_liturgico, :dia_liturgico, :grado_liturgico, :siglo, :lugar_origen, :patronazgo, :atributos, :frase_celebre, :biografia, :santidad, :oracion, :imagen, :destacado)";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':slug'            => $data['slug'],
            ':nombre'          => $data['nombre'],
            ':titulo'          => $data['titulo'],
            ':festividad'      => $data['festividad'],
            ':mes_liturgico'   => (int)$data['mes_liturgico'],
            ':dia_liturgico'   => $data['dia_liturgico'],
            ':grado_liturgico' => $data['grado_liturgico'] ?? 'Memoria obligatoria',
            ':siglo'           => $data['siglo'] ?? null,
            ':lugar_origen'    => $data['lugar_origen'] ?? null,
            ':patronazgo'      => $data['patronazgo'] ?? null,
            ':atributos'       => $data['atributos'] ?? null,
            ':frase_celebre'   => $data['frase_celebre'] ?? null,
            ':biografia'       => $data['biografia'],
            ':santidad'        => $data['santidad'] ?? null,
            ':oracion'         => $data['oracion'] ?? null,
            ':imagen'          => $data['imagen'] ?? 'public/img/san-francisco.jpg',
            ':destacado'       => isset($data['destacado']) ? (int)$data['destacado'] : 1
        ]);
    }

    /**
     * Eliminar un santo por ID
     */
    public function delete(int $id): bool {
        if (!$this->conn) return false;

        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Actualizar la información de un santo existente
     */
    public function update(int $id, array $data): bool {
        if (!$this->conn) return false;

        $query = "UPDATE {$this->table} SET 
                    slug = :slug,
                    nombre = :nombre,
                    titulo = :titulo,
                    festividad = :festividad,
                    mes_liturgico = :mes_liturgico,
                    dia_liturgico = :dia_liturgico,
                    siglo = :siglo,
                    lugar_origen = :lugar_origen,
                    patronazgo = :patronazgo,
                    atributos = :atributos,
                    frase_celebre = :frase_celebre,
                    biografia = :biografia,
                    santidad = :santidad,
                    oracion = :oracion" .
                    (!empty($data['imagen']) ? ", imagen = :imagen" : "") .
                 " WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $params = [
            ':id'             => $id,
            ':slug'           => $data['slug'],
            ':nombre'         => $data['nombre'],
            ':titulo'         => $data['titulo'],
            ':festividad'     => $data['festividad'],
            ':mes_liturgico'  => (int)$data['mes_liturgico'],
            ':dia_liturgico'  => $data['dia_liturgico'],
            ':siglo'          => $data['siglo'] ?? null,
            ':lugar_origen'   => $data['lugar_origen'] ?? null,
            ':patronazgo'     => $data['patronazgo'] ?? null,
            ':atributos'      => $data['atributos'] ?? null,
            ':frase_celebre'  => $data['frase_celebre'] ?? null,
            ':biografia'      => $data['biografia'],
            ':santidad'       => $data['santidad'] ?? null,
            ':oracion'        => $data['oracion'] ?? null
        ];

        if (!empty($data['imagen'])) {
            $params[':imagen'] = $data['imagen'];
        }

        return $stmt->execute($params);
    }
}