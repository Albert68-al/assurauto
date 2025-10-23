<?php
$db = new PDO('sqlite:' . __DIR__ . '/../database/database.sqlite');
$stmt = $db->query("SELECT id, nom, code, devise, tva_par_defaut, actif FROM pays ORDER BY nom");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $r) {
    echo sprintf("%d | %s | %s | %s | %s | %s\n", $r['id'], $r['nom'], $r['code'], $r['devise'], $r['tva_par_defaut'], $r['actif']);
}
if (count($rows) === 0) echo "(no rows)\n";
