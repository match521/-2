<?php
require 'db.php';

// 读取传入的 JSON 数据
$data = json_decode(file_get_contents('php://input'), true);
$domains = $data['domains'] ?? [];

if (empty($domains)) {
    echo json_encode(['message' => '没有提供任何域名']);
    exit;
}

// 将域名添加到数据库
try {
    $stmt = $pdo->prepare("INSERT INTO domain_pool (domain, status, is_current) VALUES (?, ?, ?)");
    foreach ($domains as $domain) {
        $stmt->execute([$domain, 'valid', 0]); // 将 is_current 设置为 0（未使用）
    }
    echo json_encode(['message' => '域名添加成功']);
} catch (PDOException $e) {
    echo json_encode(['message' => '添加域名时发生错误：' . $e->getMessage()]);
}
