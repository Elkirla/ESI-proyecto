<?php
require_once __DIR__ . '/../Config/database.php';
class ReporteModelo {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function listadoUniversalSimple(string $tabla, array $campos = ['*'], array $filtros = [], ?array $orden = null, ?int $limit = null, ?int $offset = null) {
    // Validación mínima de identificadores (solo letras, números y guiones bajos)
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $tabla)) {
        throw new Exception("Nombre de tabla inválido");
    }
    foreach ($campos as $c) {
        if ($c !== '*' && !preg_match('/^[a-zA-Z0-9_]+$/', $c)) {
            throw new Exception("Nombre de columna inválido: $c");
        }
    }

    $select = implode(', ', $campos);
    $sql = "SELECT $select FROM `$tabla`";

    $where = [];
    $params = [];
    foreach ($filtros as $col => $val) {
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $col)) {
            throw new Exception("Nombre de columna inválido en filtro: $col");
        }
        $param = ":f_" . $col;
        $where[] = "`$col` = $param";
        $params[$param] = $val;
    }

    if (!empty($where)) {
        $sql .= " WHERE " . implode(' AND ', $where);
    }

    if ($orden) {
        list($colOrden, $dir) = $orden;
        $dir = strtoupper($dir) === 'DESC' ? 'DESC' : 'ASC';
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $colOrden)) {
            throw new Exception("Columna de orden inválida");
        }
        $sql .= " ORDER BY `$colOrden` $dir";
    }

    if ($limit !== null) {
        $sql .= " LIMIT :_limit";
        $params[':_limit'] = (int)$limit;
        if ($offset !== null) {
            $sql .= " OFFSET :_offset";
            $params[':_offset'] = (int)$offset;
        }
    }

    $stmt = $this->db->prepare($sql);
    // bindParams con tipos correctos
    foreach ($params as $k => $v) {
        if (is_int($v)) $stmt->bindValue($k, $v, PDO::PARAM_INT);
        else $stmt->bindValue($k, $v, PDO::PARAM_STR);
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
?>