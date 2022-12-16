<?php
require __DIR__ . '/../vendor/autoload.php';

use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Checagem para verificar se autenticação foi recebida

if (!preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
    // Se entrar aqui, o Token não foi enviado e algum tratamento deve ser realizado...
    http_response_code(400);
    echo json_encode(["status" => "Erro", "msg" => "Bearer não enviado"]);
    exit;
}

// Verifica se o valor de autorização tem o formato esperado...
$jwt = $matches[1];
if (!$jwt) {
    // Não foi encontrado um token do tipo esperado
    http_response_code(400);
    echo json_encode([
        "status" => "Erro",
        "msg" => "Bearer não encontrado com formato correto"
    ]);
    exit;
}

// Esta DEVE ser a mesma chave secreta usada na criação (login) do JWT...
$secretKey = "chaveSecretaLojaPetshop";

// Nesse ponto, temos a certeza que '$jwt' é um token JWT. Mas temos que verificar as informações e ver se ele ainda é válido!
$token = null;
try {
    $token = JWT::decode($jwt, new Key($secretKey, "HS512"));
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        "status" => "Erro",
        "msg" => "Bearer inválido"
    ]);
    exit;
}

$now = new DateTimeImmutable();
if ($token->iss !== "localhost" or $token->nbf > $now->getTimestamp() or $token->exp < $now->getTimestamp()) {
    http_response_code(401);
    echo json_encode([
        "status" => "Erro",
        "msg" => "Bearer inválido ou expirado"
    ]);
    exit;
}

// Obtendo o identificador único que veito do JWT (muito importante!!!)
$idUsuario = $token->sub;
$email = $token->data->email;
$tipo = $token->data->tipo;

// Pronto, agora todas as checagens foram feitas. Hora de fazer o que você quer :)
// http_response_code(200);
// echo json_encode([
//     "idUsuario" => $idUsuario,
//     "email" => $email,
//     "tipo" => $tipo
// ]);
//exit;
