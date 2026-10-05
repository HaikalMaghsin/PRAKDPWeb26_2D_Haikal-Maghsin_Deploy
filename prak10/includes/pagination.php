<?php
$keyword = trim($_GET['q'] ?? '');
$page = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 5;
$totalPages = 1;

function paginatedRows(PDO $pdo, string $select, string $count, array $params): array {
    global $page, $perPage, $totalPages;
    $stmt = $pdo->prepare($count);
    $stmt->execute($params);
    $totalPages = max(1, (int) ceil($stmt->fetchColumn() / $perPage));
    $page = min($page, $totalPages);
    $stmt = $pdo->prepare($select . ' LIMIT :limit OFFSET :offset');
    foreach ($params as $key => $value) $stmt->bindValue($key, $value);
    $stmt->bindValue('limit', $perPage, PDO::PARAM_INT);
    $stmt->bindValue('offset', ($page - 1) * $perPage, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function showPagination(): void {
    global $totalPages, $page, $keyword;
    echo '<nav class="pagination" aria-label="Halaman daftar">';
    for ($i = 1; $i <= $totalPages; $i++) {
        $url = '?' . http_build_query(['page' => $i, 'q' => $keyword]);
        echo '<a href="' . esc($url) . '"' . ($page === $i ? ' aria-current="page"' : '') . '>' . $i . '</a>';
    }
    echo '</nav>';
}
