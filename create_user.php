<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// CORES PARA O TERMINAL
$green = "\033[32m";
$red = "\033[31m";
$reset = "\033[0m";

echo "--------------------------------------------------\n";
echo "  🏢 CRIAR NOVO USUÁRIO (CRM)  \n";
echo "--------------------------------------------------\n";

if (php_sapi_name() !== 'cli') {
    die("Apenas via terminal!");
}

// Perguntar Nome
echo "Nome: ";
$name = trim(fgets(STDIN));

// Perguntar Email
echo "Email: ";
$email = trim(fgets(STDIN));

// Perguntar Senha
echo "Senha: ";
$password = trim(fgets(STDIN));

// Perguntar Cargo (opcional)
echo "É Gerente? (s/n): ";
$isManager = trim(fgets(STDIN));
$role = (strtolower($isManager) === 's') ? 'manager' : 'salesperson';

// Validação simples
if (empty($name) || empty($email) || empty($password)) {
    echo $red . "ERRO: Todos os campos são obrigatórios.\n" . $reset;
    exit;
}

if (User::where('email', $email)->exists()) {
    echo $red . "ERRO: Este email já está cadastrado.\n" . $reset;
    exit;
}

try {
    $user = User::create([
        'name' => $name,
        'email' => $email,
        'password' => Hash::make($password),
        'role' => $role,
    ]);

    echo "\n" . $green . "SUCESSO! Usuário criado." . $reset . "\n";
    echo "Nome: {$user->name}\n";
    echo "Email: {$user->email}\n";
    echo "Cargo: {$user->role}\n";
    echo "--------------------------------------------------\n";

} catch (\Exception $e) {
    echo $red . "ERRO ao criar usuário: " . $e->getMessage() . "\n" . $reset;
}
