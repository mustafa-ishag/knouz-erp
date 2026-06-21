<?php
require __DIR__ . '/core/Database.php';
$db = Database::getInstance();
$user = $db->fetch('SELECT id, username, password FROM users WHERE username = ?', ['admin']);
echo 'User found: ' . ($user ? 'YES' : 'NO') . PHP_EOL;
if ($user) {
    echo 'Verify admin123: ' . (password_verify('admin123', $user['password']) ? 'YES' : 'NO') . PHP_EOL;
    // Update password properly
    $hash = password_hash('admin123', PASSWORD_DEFAULT);
    $db->update('users', ['password' => $hash], 'id = ?', [$user['id']]);
    echo 'Password updated!' . PHP_EOL;
    
    // Verify again
    $user2 = $db->fetch('SELECT password FROM users WHERE id = ?', [$user['id']]);
    echo 'Verify after update: ' . (password_verify('admin123', $user2['password']) ? 'YES' : 'NO') . PHP_EOL;
}
