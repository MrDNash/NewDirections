<?php
    $dsn = 'pgsql:host=localhost;dbname=jobs';
    $username = 'pi';
    $password = 'Testing123!';

    try {
        $pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        //echo json_encode($e);
        exit;
    }
?>